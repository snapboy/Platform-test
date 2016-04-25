<?php
	//获取当前用户信息
	$user_num = $_SESSION["number"];
	
	//系统配置文件
	require_once("../../dbconn.inc");
		
	//获取时间戳
	$t = time();
	
	//连接FTP服务器
	$conn = ftp_connect("169.254.225.30");
	if(!$conn)
	{
		echo "<script language='javascript'>";
		echo "alert('CONNECT FAILED!');";
		echo "</script>";
	}
	
	//登录FTP
	$login = ftp_login($conn,"bio","123456");
	if(!$login)
	{
		echo "<script language='javascript'>";
		echo "alert('LOGIN FAILED!');";
		echo "</script>";
	}
	
	//创建并打开个人文件夹
	$mkdir = ftp_mkdir($conn,$user_num);
	$chdir = ftp_chdir($conn,$user_num);
	
	//创建并打开数据库目录
	$mkdir_d = ftp_mkdir($conn,"DB");
	$chdir_d = ftp_chdir($conn,"DB");
	
	//创建并打开本次上传文件夹（以时间戳命名）
	$mkdir_t = ftp_mkdir($conn,$t);
	$chdir_t = ftp_chdir($conn,$t);
	
	//上传文件
	$upload = ftp_put($conn,$user_num."-".$t.".txt",$_FILES["file"]["tmp_name"],FTP_ASCII);
	if(!$upload)
	{
		echo "<script language='javascript'>";
		echo "alert('UPLOAD FAILED!');";
		echo "</script>";
	}
	else
	{
		$linker=mysql_connect($DBHOST,$DBUSER,$DBPWD);			//连接数据库
		mysql_select_db($DBNAME); 		//选择数据库
		$init=mysql_query("set name utf8");
		$str="insert into datab values(NULL,'$number',NULL,'0')";
		$result=mysql_query($str, $linker); //执行查询
		mysql_close($linker);
		
		echo "<script language='javascript'>";
		echo "alert('UPLOAD SUCCESSFUL!');";
		echo "</script>";
		
		$fp = fopen("../../FTPsave/".$user_num."/DB/parameters.txt");
		file_put_contents("../../FTPsave/".$user_num."/DB/".$t."/parameters.txt",implode(",",$_POST['check']));
	}
	
	//断开FTP连接
	ftp_quit($conn);

	echo "<script language='javascript'>";
	echo "location='../../DBupload.html'";
	echo "</script>";

?>
