<?php
	header("Content-type: text/html; charset=utf-8");

	error_reporting(1);

	//获取当前用户信息
	$user_num = $_SESSION["number"];
	$err_num = 0;
	
	//系统配置文件
	require_once("../../sysconf.inc");
	
	//获取时间戳
	$t = time();
	
	//获取上传文件名、扩展名以及所选数据库编号
	$f_name = $_FILES["file"]["name"];
	$f_path = pathinfo($f_name);
	$f_type = $f_path[extension];
	$db_num = $_POST["database"];
	
	//获取参数
	$PAT = $_POST["PAT"];
	$FAT = $_POST["FAT"];
	$IPMD = $_POST["IPMD"];
	$IPMD2 = $_POST["IPMD2"];
	$IPACO = $_POST["IPACO"];
	$IPACO2 = $_POST["IPACO2"];
	$IPMDO = $_POST["IPMDO"];
	$IPMDO2 = $_POST["IPMDO2"];
	$IPADOM = $_POST["IPADOM"];
	$IPADOM2 = $_POST["IPADOM2"];
	$IPAD = $_POST["IPAD"];
	$IPAD2 = $_POST["IPAD2"];
	$IPADO = $_POST["IPADO"];
	$IPADO2 = $_POST["IPADO2"];
	$IPADOM = $_POST["IPADOM"];
	$IPADOM2 = $_POST["IPADOM2"];
	$Window = $_POST["Window"];
	$PMFs = $_POST["PMFs"];
	$PTM_Score = $_POST["PTM_Score"];
	$Spectrum_Range = $_POST["Spectrum_Range"];
	$Spectrum_Range2 = $_POST["Spectrum_Range2"];
	
	//连接FTP服务器
	$conn = ftp_connect($IP_ADDR);
	if(!$conn)
	{
		$err_num = 0;
	}
	
	//登录FTP
	$login = ftp_login($conn,"bio",$FTPpwd);
	if(!$login)
	{
		$err_num = 1;
	}
	
	//打开FTP被动模式
	$pasv = ftp_pasv($conn,true);
	
	//创建并打开个人文件夹
	$mkdir = ftp_mkdir($conn,$user_num);
	$chdir = ftp_chdir($conn,$user_num);
	
	//创建并打开计算文件目录
	$mkdir_d = ftp_mkdir($conn,"RAW");
	$chdir_d = ftp_chdir($conn,"RAW");
	
	//创建并打开本次上传文件夹（以时间戳命名）
	$mkdir_t = ftp_mkdir($conn,$t);
	$chdir_t = ftp_chdir($conn,$t);
	
	//上传文件
	$upload = ftp_put($conn,"A-".$user_num."-".$t.".RAW",$_FILES["file"]["tmp_name"],FTP_BINARY);
	
	//临时文件信息
	$up_err = $_FILES["file"]["error"];
	$up_size = $_FILES["file"]["size"];
	
	if(!$upload)
	{
		$err_num = 2;
		echo "<script language='javascript'>";
		echo "alert('UPLOAD FAILED!\terror:".$err_num."');";
		echo "</script>";
	}
	else
	{
		$linker=mysql_connect($DBHOST,$DBUSER,$DBPWD);			//连接数据库
		mysql_select_db($DBNAME); 		//选择数据库
		$init=mysql_query("set name utf8");
		
		//插入上传信息
		$str="insert into raw values(NULL,'$user_num','$f_name','$t','$db_num','1','0')";
		$result=mysql_query($str, $linker); //执行查询
		
		//数据库使用次数加一
		$str2="update datab set used=used+1 where num='$db_num'";
		$result2=mysql_query($str2,$linker);
				
		$str3="update users set rnum=rnum+1 where number ='$number'";
		$result3=mysql_query($str3,$linker);
		mysql_close($linker);
		
		echo "<script language='javascript'>";
		echo "alert('UPLOAD SUCCESSFUL!\tsize:".$up_size."');";
		echo "</script>";
		
		//记录参数
		$fp = fopen("../../FTPsave/".$user_num."/RAW/".$t."/para.txt");
		$para = "PAT\t".$PAT."\nFAT\t".$FAT."\nIPMD\t".$IPMD."\nIPMD2\t".$IPMD2."\nIPACO\t".$IPACO."\nIPACO2\t".$IPACO2."\nIPMDO\t".$IPMDO."\nIPMDO2\t".$IPMDO2."\nIPMDOM\t".$IPMDOM."\nIPMDOM2\t".$IPMDOM2."\nIPAD\t".$IPAD."\nIPAD2\t".$IPAD2."\nIPADO\t".$IPADO."\nIPADO2\t".$IPADO2."\nIPADOM\t".$IPADOM."\nIPADOM2\t".$IPADOM2."\nWindow\t".$Window."\nPMFs\t".$PMFs."\nPTM_Score\t".$PTM_Score."\nSpectrum_Range\t".$Spectrum_Range."\nSpectrum_Range2\t".$Spectrum_Range2."\n#";//参数字符串，以#结束
		fwrite($fp,$para);
		fclose($fp);
	}
	
	//断开FTP连接
	ftp_quit($conn);

	echo "<script language='javascript'>";
	echo "location='../../data-upload-dynamic.php'";
	echo "</script>";

?>
