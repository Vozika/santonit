<?php
$mess="";
include ("config.inc.php");
if (isset($_GET['validate']) and isset($_GET['id'])) {
    $fp = fopen ("emails.txt", "r");
    $fpbuf = fopen ("emails_buf.txt", "w");
    $act=0;
    while ($data=fgetcsv($fp,100,',')) {
    	if ($data[0]==$_GET['id'] and $data[7]==$_GET['validate']) {
    		fputs($fpbuf,$data[0].",".$data[1].",".$data[2].",".$data[3].",".$data[4].",".$data[5].",2,".$data[7]."\n");
            $act=1;$em=$data[3];
    	} else fputs($fpbuf,$data[0].",".$data[1].",".$data[2].",".$data[3].",".$data[4].",".$data[5].",".$data[6].",".$data[7]."\n");
    }
    if ($act==1) $mess='<b><font style="font-size:14px;">Спасибо за потверждение расcылки! Адрес <a href="mailto:'.$em.'">'.$em.'</a> подтвержден.</b></font><br><font style="font-size:11px;">(Если ваш браузер не поддерживает автоматическую переадресацию, нажмите <a href=javascript:history.back(-1) style=text-decoration:none;>здесь</a>)';
    else $mess='<b><font style="font-size:14px;">Неправильная пара ID/CODE!</font></b>';
    $mess.='<br><br><a href="'.$mainurl.'" onclick="window.close();return false;">[закрыть]</a> <a href="'.$mainurl.'">[на сайт]</a>';
    fclose($fp);
    fclose($fpbuf);
    copy ("emails_buf.txt", "emails.txt");
    unlink("emails_buf.txt");
    $title=2;
} elseif (isset($_GET['unsub']) and isset($_GET['id'])) {
    $fp = fopen ("emails.txt", "r");
    $fpbuf = fopen ("emails_buf.txt", "w");
    $act=0;
    while ($data=fgetcsv($fp,100,',')) {
    	if ($data[0]==$_GET['id'] and $data[7]==$_GET['unsub']) {
    		fputs($fpbuf,$data[0].",".$data[1].",".$data[2].",".$data[3].",".$data[4].",".$data[5].",3,".$data[7]."\n");
            $act=1;$em=$data[3];
            $temp=fopen("letters/unsub_letter.txt","r");
	   		$letterbody="";
			while(!feof($temp)) $letterbody.=fgets($temp);
			fclose($temp);
	  		$label = array("|","[name]","[mail]","[sendername]","[url]","[folder]","[id]","[code]");
	  	    $orig = array("\n",$data[2],$data[3],$sendername,$senderurl,$senderfolder,$data[0],$data[7]);
      	    $headers = "From: $sendername<$sendermail>\n";
      	    $headers .= "X-Sender: TSB Subscription\n";
      	    $headers .= "Content-Type: text/plain; charset=".$charset;
	  	 	mail($data[3],$sendertheme,str_replace($label,$orig,$letterbody),$headers);
    	} else fputs($fpbuf,$data[0].",".$data[1].",".$data[2].",".$data[3].",".$data[4].",".$data[5].",".$data[6].",".$data[7]."\n");
    }
    if ($act==1) $mess='<b><font style="font-size:14px;">Ваш адрес <a href="mailto:'.$em.'">'.$em.'</a> был удален из списка рассылки.</b></font><br><font style="font-size:11px;">(Если ваш браузер не поддерживает автоматическую переадресацию, нажмите <a href=javascript:history.back(-1) style=text-decoration:none;>здесь</a>)';
    else $mess='<b><font style="font-size:14px;">Неправильная пара ID/CODE!</font></b>';
    $mess.='<br><br><a href="'.$mainurl.'" onclick="window.close();return false;">[закрыть]</a> <a href="'.$mainurl.'">[на сайт]</a>';
    fclose($fp);
    fclose($fpbuf);
    copy ("emails_buf.txt", "emails.txt");
    unlink("emails_buf.txt");
    $title=3;
} else {
	$checkmail="false";
	$checkname="false";
	$checksign="false";
	if (!file_exists("emails.txt")) {$fp=fopen("emails.txt","w"); fclose($fp);}
	if(strstr($_POST['mail'],"@")) {$checkmail="true";} else {$mess='<b><font color=red>ОШИБКА:</font> Напишите, пожалуйста, правильный адрес для получения рассылки!</b><br><br><a href="'.$mainurl.'" onclick="window.close();return false;">[закрыть]</a>';$title=0;}
	if($_POST['name']!="1" or $_POST['name']!="[ ваше имя ]") {$checkname="true";} else {$mess='<b><font color=red>ОШИБКА:</font> Напишите, пожалуйста, свое имя для получения рассылки!</b><br><br><a href="'.$mainurl.'" onclick="window.close();return false;">[закрыть]</a>';$title=0;}
	if(!strstr($_POST['name'],",") and !strstr($_POST['mail'],",")) {$checksign="true";} else {$mess='<b><font color=red>ОШИБКА:</font> Имя и адрес не должны содержать символ "," !</b><br><br><a href="'.$mainurl.'" onclick="window.close();return false;">[закрыть]</a>';$title=0;}
	$fp = fopen ("emails.txt", "r");
	if ($checkmail=="true" and $checkname=="true" and $checksign=="true")	{
		$email = explode('@',$_POST['mail']);
		$emailhost = $email[1];
//		if (!getmxrr($emailhost,$mxhosts)) $mailserver=1;
//		else $mailserver=$mxhosts[(count($mxhosts)-1)];
		$mailserver=0;
		$validnum=date("dmy").rand(100000,999999);
        $emailsf=file("emails.txt");
        $valid=1;
        for ($i=0;$i<count($emailsf);$i++) {
        	$data=explode(",",$emailsf[$i]);
        	if($data[0]>=$valid) $valid=$data[0]+1;
        }
		$str = $valid.",".date("d.m.Y").",".strip_tags($_POST['name']).",".strip_tags($_POST['mail']).",".$mailserver.",".$_SERVER['REMOTE_ADDR'].",1,".$validnum."\n";
      	$sub_str = file("emails.txt");
	    fclose($fp);
	    $fp = fopen ("emails.txt", "w");
	    array_unshift ($sub_str, $str);
	    for ($i=0; $i<count($sub_str); $i++) {fputs ($fp,$sub_str[$i]);}
	    fclose($fp);
	    $temp=fopen("letters/sub_letter.txt","r");
	    $letterbody="";
		while(!feof($temp)) $letterbody.=fgets($temp);
		fclose($temp);
	    $label = array("|","[name]","[mail]","[sendername]","[url]","[folder]","[id]","[code]");
	    $orig = array("\n",$_POST['name'],$_POST['mail'],$sendername,$senderurl,$senderfolder,$valid,$validnum);
        $headers = "From: $sendername<$sendermail>\n";
        $headers .= "X-Sender: TSB Subscription\n";
        $headers .= "Content-Type: text/plain; charset=".$charset;
	    mail($_POST['mail'],$sendertheme,str_replace($label,$orig,$letterbody),$headers);
        $mess='<b>Вы добавлены в базу рассылки! На ваш почтовый адрес было отправлено подтверждающее письмо.</b><br><br>Внимание! Ваш почтовый сервис мог направить письмо в папку <u>рассылки</u> или <u>спам</u>. Проверьте, пожалуйста, эти папки также.<br><br><a href="'.$mainurl.'" onclick="window.close();return false;">[закрыть]</a>';
        $title=1;
	}
}
?>
<html>
<head>
<?php
	if (isset($title)) {
		switch ($title) {
			case 0: {echo'<title>Ошибка!</title>'; break;}
			case 1: {echo'<title>Добавление...</title>'; break;}
            case 2: {echo'<meta http-equiv="Refresh" content="4; url='.$mainurl.'"><title>Подтверждение подписки...</title>'; break;}
            case 3: {echo'<meta http-equiv="Refresh" content="4; url='.$mainurl.'"><title>Удаление подписчика...</title>'; break;}
		}
	}
?>
</head>
<style>
	body {margin-top:10px;}
	a:link {color:blue; text-decoration:none;}
	a:visited {color:blue; text-decoration:none;}
	a:hover {color:#A8A8A8; text-decoration:none;}
	a:active {color:#A8A8A8; text-decoration:none;}
	.bl{font-size:11px;font-family:verdana;text-align:center;}
</style>
<body>
<?php
echo '<div class="bl">'.$mess.'<br><br><font style="font-size:10px;font-family:verdana;">Powered by <a href="http://tsb.mimozaf.ru" target="_blank">TSB Subscription</a> v. 1.37.5 </font>';
$str = file("emails.txt");
echo '</body></html>';

?>