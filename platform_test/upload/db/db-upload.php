<?php
	header("Content-type: text/html; charset=utf-8");
	//读取当前用户学工号
	$user_num=$_SESSION["number"];
	
	require_once("../dbconn.inc");
	
	//创建目录
	$fp = opendir("../../save");
	$destination="";
	
	$up = copy($_FILES["file"]["tmp_name"],"../../save\\".$_FILES["file"]["name"]);
	if($up == 1)
	{
		closedir($fp);
	}

?>
