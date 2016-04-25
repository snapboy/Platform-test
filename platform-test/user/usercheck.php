<?php
	header("Content-type: text/html; charset=utf-8");

	$number=$_POST["number"];
	
	require_once("../dbconn.inc");
	
	//连接数据库
	$linker=mysql_connect($DBHOST,$DBUSER,$DBPWD);			
	//选择数据库
	mysql_select_db($DBNAME);
	
	//查询是否存在相应信息
	$str="select pwd from users where number ='$number'";
	$result=mysql_query($str,$linker);
	
	list($password)=mysql_fetch_row($result);
	//如果密码输入正确
	if($password==$_POST["pwd"])
	{
		$_SESSION["number"]=$number;
		
		//转到个人页面	
		echo "<script language='javascript'>";	
		echo "location='../index.html?number=".$number."';";
		echo "</script>";
	}
	//密码输入错误
	else
	{
		echo "<script language='javascript'>";	
		echo "alert('用户信息输入有误！');";
		echo"location='../login.html';";
		echo "</script>";
	}
	mysql_close($linker);
?>