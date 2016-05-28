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
	
	//获取上传文件名
	$f_name = $_FILES["file"]["name"];
	
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
	
	//创建并打开数据库目录
	$mkdir_d = ftp_mkdir($conn,"DB");
	$chdir_d = ftp_chdir($conn,"DB");
	
	//创建并打开本次上传文件夹（以时间戳命名）
	$mkdir_t = ftp_mkdir($conn,$t);
	$chdir_t = ftp_chdir($conn,$t);
	
	//临时文件信息
	$up_err = $_FILES["file"]["error"];
	$up_size = $_FILES["file"]["size"];
	
	//上传文件
	$upload = ftp_put($conn,"A-".$user_num."-".$t.".txt",$_FILES["file"]["tmp_name"],FTP_BINARY);
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
		$str="insert into datab values(NULL,'$number','$f_name','$t','0','0','0')";
		$result=mysql_query($str, $linker); //执行查询
				
		$str2="update users set dnum=dnum+1 where number ='$number'";
		$result2=mysql_query($str2,$linker);
		mysql_close($linker);
		
		echo "<script language='javascript'>";
		echo "alert('UPLOAD SUCCESSFUL!');";
		echo "</script>";
		
		$fp = fopen("../../FTPsave/".$user_num."/DB/".$t."/para.txt");
		file_put_contents("../../FTPsave/".$user_num."/DB/".$t."/para.txt",implode(",",$_POST['check']));
		fclose($fp);
	}
	
	//断开FTP连接
	ftp_quit($conn);

	echo "<script language='javascript'>";
	echo "location='../../DBupload.php'";
	echo "</script>";

?>
