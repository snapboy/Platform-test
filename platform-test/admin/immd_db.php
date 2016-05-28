<?php

	require_once "../sysconf.inc";
	
	$linker=mysql_connect($DBHOST,$DBUSER,$DBPWD);			//连接数据库
	mysql_select_db($DBNAME); 		//选择数据库
	$init=mysql_query("set name utf8");
	
	$str="update datab set immd='1' where num='$num'";
	$result=mysql_query($str, $linker); //执行
	mysql_close($linker);
	
	echo "<script language='javascript'>";
	echo "location='../admin_db.php'";
	echo "</script>";

?>