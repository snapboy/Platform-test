<?php
	header("Content-type: text/html; charset=utf-8");

	$name=$_POST["name"]; 
	$number=$_POST["number"];
	$pwd=$_POST["pwd"];
	$phone=$_POST["phone"];
	$email=$_POST["email"];
	
	if($_POST["pwd"]!=$_POST["rpt_pwd"])
	{
		echo "<script language='javascript'>";
		echo "alert('两次输入密码不一致!');";
		echo "location='../register.html';";
		echo "</script>";
	}
	else
	{
		$t=time();
		
		require_once("../sysconf.inc");	//系统配置文件
		
		$linker=mysql_connect($DBHOST,$DBUSER,$DBPWD);			//连接数据库
		mysql_select_db($DBNAME); 		//选择数据库
		$init=mysql_query("set name utf8");
		$str="insert into users values('$name','$number','$pwd','$phone','$email','$t','$t','0','0')";
		$result=mysql_query($str, $linker); //执行查询
		mysql_close($linker);
		
		//跳转至登录页面
		echo "<script language='javascript'>";
		echo "location='../login.html';";
		echo "</script>";
	}
?>
