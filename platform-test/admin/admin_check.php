<?php
	header("Content-type: text/html; charset=utf-8");

	$aid=$_POST["aid"];
	
	require_once("../sysconf.inc");
	
	//连接数据库
	$linker=mysql_connect($DBHOST,$DBUSER,$DBPWD);			
	//选择数据库
	mysql_select_db($DBNAME);
	
	//查询是否存在相应信息
	$str="select ad_num,pwd from admin where aid ='$aid'";
	$result=mysql_query($str,$linker);
	
	list($anum,$password)=mysql_fetch_row($result);
	//如果密码输入正确
	if($password==$_POST["pwd"])
	{
		$_SESSION["anum"]=$anum;
		
		//转到个人页面	
		echo "<script language='javascript'>";	
		echo "location='../admin_db.php';";
		echo "</script>";
	}
	//密码输入错误
	else
	{
		echo "<script language='javascript'>";	
		echo "alert('管理员信息输入有误！');";
		echo"location='../admin_log.html';";
		echo "</script>";
	}
	mysql_close($linker);
?>