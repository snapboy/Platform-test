<?php

	$up = copy($_FILES["file"]["tmp_name"],"../../save/".$_FILES["file"]["name"]);
	
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