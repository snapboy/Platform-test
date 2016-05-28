<?php
	ini_set("magic_quotes_runtime",0);
	require "../assets/phpmailer/class.phpmailer.php";
	require_once "../sysconf.inc";
	
	//SMTP设置
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
	$mail->Subject  = "作业反馈";
	$mail->Body = "您上传的作业已计算完毕，计算结果见附件";
	
	$linker=mysql_connect($DBHOST,$DBUSER,$DBPWD);			//连接数据库
	mysql_select_db($DBNAME); 		//选择数据库
	$init=mysql_query("set name utf8");
	
	$str="select * from raw";
	$result=mysql_query($str, $linker); //执行
	
	while($row=mysql_fetch_array($result))
	{
		/*调用批处理脚本进行计算
			exec("start xxx.bat");
		*/
			if(compute($row))
			{
				//发送邮件
				
				//delete from raw where num=$row["num"];
			}
			else
			{
				//保存异常信息
			}
		
	}
	
	mysql_close($linker);

?>