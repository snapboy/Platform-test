<?php

	require_once "../sysconf.inc";
	
	$linker=mysql_connect($DBHOST,$DBUSER,$DBPWD);			//连接数据库
	mysql_select_db($DBNAME); 		//选择数据库
	$init=mysql_query("set name utf8");
	
	$str="select * from raw where immd='1'";
	$result=mysql_query($str, $linker); //执行
	
	while($row=mysql_fetch_array($result))
	{
		/*调用批处理脚本进行计算
		//创建脚本
		$fp=fopen("mail.bat","w+");
		$write=fwrite($fp,"run xxx.java");//**********************动态生成脚本
		fclose($fp);
		
		//执行脚本
		exec("start F:/AppServ/www/platform_test/compute/mail.bat");//要绝对路径，通过批处理文件调用java程序立即执行
		if(compute($row))
		{
			//发送邮件
			delete from raw where num=$row["num"];
		}
		else
		{
			//保存异常信息
		}
		*/
	}
	
	mysql_close($linker);

?>