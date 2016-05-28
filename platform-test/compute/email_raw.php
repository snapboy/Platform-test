<?php
	header("content-type:text/html;charset=utf-8");
	ini_set("magic_quotes_runtime",0);
	require "../assets/phpmailer/class.phpmailer.php";
	require "../sysconf.inc";
	
	//邮件设置
	$mail = new PHPMailer(true); 
	$mail->IsSMTP();
	$mail->CharSet='UTF-8'; //设置邮件的字符编码，这很重要，不然中文乱码
	$mail->SMTPAuth   = true;                  //开启认证
	$mail->Port       = 25;                    
	$mail->Host       = $SMTP_HOST; 
	$mail->Username   = $SMTP_ADDR;    
	$mail->Password   = $SMTP_PWD;            
	//$mail->IsSendmail(); //如果没有sendmail组件就注释掉，否则出现“Could  not execute: /var/qmail/bin/sendmail ”的错误提示
	$mail->AddReplyTo($SMTP_ADDR,$SMTP_NAME);//回复地址
	$mail->From       = $SMTP_ADDR;
	$mail->FromName   = $SMTP_NAME;
	//记得改，下面这是收件人
	$to = "2996254124@qq.com";
	$mail->AddAddress($to);
	$mail->Subject  = "platform反馈";
	
	//连接数据库
	$linker=mysql_connect($DBHOST,$DBUSER,$DBPWD);			
	//选择数据库
	mysql_select_db($DBNAME);
	$str="select num,owner,name,time from datab where immd='0'";
	$result=mysql_query($str,$linker);
	
	while($row=mysql_fetch_array($result))
	{
		//进入目录
		$chdir = 
		
		//读取邮件正文
		$mail->Body = file_get_contents("body.txt");
		$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; //当邮件不支持html时备用显示，可以省略
		$mail->WordWrap   = 80; // 设置每行字符串的长度
		$mail->AddAttachment("EColi_Rev_NoFrag_50_100_120313_7.txt","result.txt");  //添加附件
		$mail->IsHTML(true); 
		$mail->Send();
		
	}
	
?>