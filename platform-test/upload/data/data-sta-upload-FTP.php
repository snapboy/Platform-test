<?php
	//获取当前用户信息
	$user_num = $_SESSION["number"];
	
	//获取时间戳
	$t = time();
	
	//连接FTP服务器
	$conn = ftp_connect("169.254.225.30");
	if(!$conn)
	{
		echo "<script language='javascript'>";
		echo "alert('连接失败');";
		echo "</script>";
	}
	
	//登录FTP
	$login = ftp_login($conn,"bio","123456");
	if(!$login)
	{
		echo "<script language='javascript'>";
		echo "alert('登录失败');";
		echo "</script>";
	}
	
	//创建并打开个人文件夹
	$mkdir = ftp_mkdir($conn,$user_num);
	$chdir = ftp_chdir($conn,$user_num);
	
	//创建并打开本次作业文件夹（以时间戳命名）
	$mkdir_t = ftp_mkdir($conn,$t);
	$chdir_t = ftp_chdir($conn,$t);
	
	//上传文件
	$upload = ftp_put($conn,$user_num.time().".txt",$_FILES["file"]["tmp_name"],FTP_ASCII);
	if(!$upload)
	{
		echo "<script language='javascript'>";
		echo "alert('上传失败');";
		echo "</script>";
	}
	
	//断开FTP连接
	ftp_quit($conn);

?>
