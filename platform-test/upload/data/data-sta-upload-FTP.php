<?php
	header("Content-type: text/html; charset=utf-8");

	error_reporting(1);

	//获取当前用户信息
	$user_num = $_SESSION["number"];
	$err_num = 0;
	
	//系统配置文件
	require_once("../../sysconf.inc");
		
	//获取时间戳
	$t = time();
	
	//获取上传文件名、扩展名以及所选数据库编号
	$f_name = $_FILES["file"]["name"];
	$f_path = pathinfo($f_name);
	$f_type = $f_path[extension];
	$db_num = $_POST["database"];
	
	//连接FTP服务器
	$conn = ftp_connect($IP_ADDR);
	if(!$conn)
	{
		$err_num = 0;
	}
	
	//登录FTP
	$login = ftp_login($conn,"bio",$FTPpwd);
	if(!$login)
	{
		$err_num = 1;
	}
	
	//打开FTP被动模式
	$pasv = ftp_pasv($conn,true);
	
	//创建并打开个人文件夹
	$mkdir = ftp_mkdir($conn,$user_num);
	$chdir = ftp_chdir($conn,$user_num);
	
	//创建并打开计算文件目录
	$mkdir_d = ftp_mkdir($conn,"RAW");
	$chdir_d = ftp_chdir($conn,"RAW");
	
	//创建并打开本次上传文件夹（以时间戳命名）
	$mkdir_t = ftp_mkdir($conn,$t);
	$chdir_t = ftp_chdir($conn,$t);
	
	//上传文件
	$upload = ftp_put($conn,"A-".$user_num."-".$t.".RAW",$_FILES["file"]["tmp_name"],FTP_BINARY);
	
	//临时文件信息
	$up_err = $_FILES["file"]["error"];
	$up_size = $_FILES["file"]["size"];
	
	if(!$upload)
	{
		$err_num = 2;
		echo "<script language='javascript'>";
		echo "alert('UPLOAD FAILED!\terror:".$err_num."');";
		echo "</script>";
	}
	else
	{
		$linker=mysql_connect($DBHOST,$DBUSER,$DBPWD);			//连接数据库
		mysql_select_db($DBNAME); 		//选择数据库
		$init=mysql_query("set name utf8");
		
		//插入上传信息
		$str="insert into raw values(NULL,'$user_num','$f_name','$t','$db_num','0','0')";
		$result=mysql_query($str, $linker); //执行查询
		
		//数据库使用次数加一
		$str2="update datab set used=used+1 where num='$db_num'";
		$result2=mysql_query($str2,$linker);
				
		$str3="update users set rnum=rnum+1 where number ='$number'";
		$result3=mysql_query($str3,$linker);
		mysql_close($linker);
		
		
		echo "<script language='javascript'>";
		echo "alert('UPLOAD SUCCESSFUL!\tsize:".$up_size."');";
		echo "</script>";
	}
	
	//断开FTP连接
	ftp_quit($conn);

	echo "<script language='javascript'>";
	echo "location='../../data-upload-static.php'";
	echo "</script>";

?>
