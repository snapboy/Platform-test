<?php
	//获取当前用户信息
	$user_num=$_SESSION["number"];
	
	//进入上传文件夹
	if(!file_exists("../../save/"))
	{
		mkdir("../../save/");
	}
	$fp = opendir("../../save");
	
	//检测该用户个人文件夹是否存在，若否，创建
	if(!file_exists("../../save/".$user_num))
	{
		mkdir("../../save/".$user_num);
	}
	
	//拷贝文件
	$up = copy($_FILES["file"]["tmp_name"],"../../save/".$user_num."/".$_FILES["file"]["name"]);
	
	//关闭文件夹
	if($up == 1)
	{
		closedir($fp);
		
		echo "<script language='javascript'>";
		echo "alert('UPLOAD SUCCESSFUL!');";
		echo "location='../../data-upload-static.php'";
		echo "</script>";
	}
	else
	{
		echo "<script language='javascript'>";
		echo "alert('UPLOAD FAILED!');";
		echo "</script>";
	}

?>
