<?php  
require_once ('email.class.php'); 
//########################################## 
$smtpserver = "smtp.exmail.qq.com";//SMTP服务器 
$smtpserverport =25;//SMTP服务器端口 
$smtpusermail = "wellnet@buildnet.net.cn";//SMTP服务器的用户邮箱 
$smtpemailto = $email;//发送给谁 
$smtpuser = "wellnet@buildnet.net.cn";//SMTP服务器的用户帐号 
$smtppass = "weiante123gongzhen";//SMTP服务器的用户密码 
$mailsubject = $nickname;//邮件主题 
$mailbody = '性名：'.$nickname.'<br>手机：'.$mobile.'<br>内容：'.$content.'<br><img src="http://buildnet.net.cn/Uploads/message/'.$img.'.gif" /><br>时间：'.date("Y-m-d H:i:s", $cTime);//邮件内容 
$mailtype = "HTML";//邮件格式（HTML/TXT）,TXT为文本邮件 
########################################## 
@$smtp = new smtp($smtpserver,$smtpserverport,true,$smtpuser,$smtppass);//这里面的一个true是表示使用身份验证,否则不使用身份验证. 
//@$smtp->debug = false;//是否显示发送的调试信息 
@$smtp->sendmail($smtpemailto, $smtpusermail, $mailsubject, $mailbody, $mailtype); 