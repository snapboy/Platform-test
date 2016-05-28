<?php

	require_once "../sysconf.inc";
	
	$linker=mysql_connect($DBHOST,$DBUSER,$DBPWD);			//连接数据库
	mysql_select_db($DBNAME); 		//选择数据库
	$init=mysql_query("set name utf8");
	
	$str="select time from datab where num='$num'";
	$result=mysql_query($str,$linker);
	$row = mysql_fetch_array($result);
	$fold_name=$row["time"];
	
	//获取当前用户信息
	$user_num = $_SESSION["number"];
	
	
	//连接FTP服务器
	$conn = ftp_connect($IP_ADDR);
	if(!$conn)
	{
		echo "<script language='javascript'>";
		echo "alert('CONNECT FAILED!');";
		echo "</script>";
	}
	
	//登录FTP
	$login = ftp_login($conn,"bio",$FTPpwd);
	if(!$login)
	{
		echo "<script language='javascript'>";
		echo "alert('LOGIN FAILED!');";
		echo "</script>";
	}
	
	//进入个人数据库文件夹
	$chdir = ftp_chdir($conn,$user_num);
	$chdir2 = ftp_chdir($conn,"DB");
	$chdir3 = ftp_chdir($conn,$fold_name);
	
	//删除文件
	$rmfile = ftp_delete($conn,"A-".$user_num."-".$fold_name.".txt");
	$rmfile2 = ftp_delete($conn,"para.txt");
	
	//删除文件夹
	$chdir4 = ftp_cdup($conn);
	$rmdir = ftp_rmdir($conn,$fold_name);
	
	if(!$rmdir)
	{
		echo "<script language='javascript'>";
		echo "alert('DELETE FAILED".$fold_name."!');";
		echo "</script>";
	}
	else
	{
		//删除数据库中对应项
		$str2="delete from datab where num='$num'";
		$result2=mysql_query($str2, $linker); //执行
		mysql_close($linker);
		
		echo "<script language='javascript'>";
		echo "alert('DELETE SUCCESSFUL!');";
		echo "location='../DBdel.php'";
		echo "</script>";
	}

?>