<?php
ob_start();
if (file_exists("config.inc.php")) include("config.inc.php");
header("Cache-Control: no-store");
session_start();
if(isset($_SESSION['log']) and $_SESSION['log']==1) {
?>
<script language="JavaScript">
function checkall(form) {
		for (var i=1;i<document.subscr.elements.length;i++) {
			document.subscr.elements[i].checked = document.subscr.all.checked;
		}
}
</script>
<?php
if (isset($_POST['process'])) {
	switch ($_POST['process']) {
	    case "delete": {
	        include("head.php");
	        if (file_exists("emails.txt")) {
	            if (isset($_POST['delcheck'])) {
	            unlink("emails.txt");
	            echo "База подписчиков была удалена!";
	        } else echo "Вы должны подтвердить удаление базы!";
	        }
	        else echo "Базы данных подписчиков не существует!";
	        echo '<br><a href=javascript:history.back(-1) style="font-size:14;" class=exp>--назад--</a></font>';
	        break;
	    }
	    case "save_temp":
	    {
	    	include ("head.php");
	    	if (isset($_POST['send'])) {
				if(isset($HTTP_POST_FILES['filename_up']) and $HTTP_POST_FILES['filename_up']!="") {
	                if(move_uploaded_file($HTTP_POST_FILES['filename_up']['tmp_name'], "./files/".$HTTP_POST_FILES['filename_up']['name'])) echo 'Файл загружен';
                    $fname=$HTTP_POST_FILES['filename_up']['name'];
	        	}
                if (isset($_POST['filename']) and $_POST['filename']!=""){
                	$fname=$_POST['filename'];
                }
                if(!isset($fname)) $fname="";
                if($lettype=="html") $en="<br>"; else $en="\r\n";
				include ("config.inc.php");
				if($fname=="") {
	                $headers = "From: $sendername<$sendermail>\n";
	                $headers .= "Errors-To: ".$sendermail."\n";
	                $headers .= "X-Mailer: PHP/".phpversion()."\n";
	                $headers .= "X-Sender: TSB_Subscription\n";
	                $headers .= "Content-Type: text/".$lettype."; charset=".$charset;
	                $body = str_replace("\r\n",$en,$_POST['letterbody']);
                } else {
                	echo '<br>Прикрепленный файл: '.$fname;
					$file = fopen("files/".$fname, "r");
					$contents = fread($file, filesize ("files/".$fname));
					$encoded_attach = chunk_split(base64_encode($contents));
					fclose($file);
					$headers = "From: $sendername<$sendermail>\r\n";
					$headers .= "Errors-To: ".$sendermail."\r\n";
					$headers .= "X-Mailer: PHP / ".phpversion()."\r\n";
					$headers .= "X-Sender: TSB_Subscription\r\n";
					$headers .= "MIME-version: 1.0\n";
					$headers .= "Content-type: multipart/mixed; boundary=\"TSB-Boundary\"\n\n";
					$body  = "--TSB-Boundary\nContent-Type:text/".$lettype."; charset=".$charset." \r\n";
					$body .= "Content-Transfer-Encoding: 8bit\n\n";
					$body .= str_replace("\r\n",$en,$_POST['letterbody'])."\n\n";
					$body .= "--TSB-Boundary\n";
					$body .= "Content-Type: application/octet-stream; name=\"".$fname."\"\n";
					$body .= "Content-Transfer-Encoding: base64\n";
					$body .= "Content-Disposition: attachment; filename=\"".$fname."\"\n\n";
					$body .=  "$encoded_attach\n";
                }
                $label = array("[name]","[mail]","[sendername]","[url]","[folder]","[id]","[code]");
                $sendlet = array("Иван Петрович","ivan@petrovich.mail.ru",$sendername,$senderurl,$senderfolder,"1000","1234567890");
                echo '<table style="font-family:verdana;font-size:12px;border:1px dotted gray;width:510;"><tr><td>'.str_replace($label,$sendlet,str_replace("\r\n",$en,$_POST['letterbody'])).'</td></tr></table>';
            	echo '<br><u>Отчет о рассылке:</u><br>';
            	echo '<table style="font-size:10;font-family:verdana;">';
            	$str_mail=file("emails.txt");
            	for ($i=0;$i<count($str_mail);$i++) {
            		$emails=explode(",",$str_mail[$i]);
                    if($emails[6]==2) {
                       	$orig = array($emails[2],$emails[3],$sendername,$senderurl,$senderfolder,$emails[0],$emails[7]);
	                    echo '<tr><td>'.$emails[0]."&nbsp;</td><td> <b>".$emails[3]."</b></td>";
	                    if(mail($emails[3],$sendertheme,str_replace($label,$orig,$body),$headers)) echo '<td>отослано</td></tr>';  else '<td><font color=red>ошибка</td></tr>';
            		}
            	}
            	echo '</table>';
	    	} else {
	            $temp=fopen("letters/".$_POST['lettername'],"w");
	            fputs($temp,$_POST['letterbody']);
	            fclose($temp);
	            echo 'Шаблон <b>'.$_POST['lettername'].'</b> удачно сохранен!';
	            echo '<br><a href="action.php?act=7&letname='.$_POST['lettername'].'" style="font-size:14;" class=exp>--назад--</a></font>';
	        }
	        break;
		}
	}
}
if (isset($_REQUEST['process'])) {
switch ($_REQUEST['process'])
{
  case "export":
  {
  include("head.php");
  if (file_exists("emails.txt")) {
  $fp = fopen ("emails.txt", "r");
# -------TheBat!--------------
$kol=0;
if ($_POST['pocht']=="1"){
	$fp_new = fopen ("emails_thebat.txt", "a");
  	while ($sim=fgetcsv($fp, 1000, ",")) fputs($fp_new,$sim[2]." <".$sim[3].">\n");
  	echo "На сервере сгенерирован файл для экспорта в почтовую программу <b>TheBat!</b><br><a href=emails_thebat.txt class=exp>--загрузить--</a>";
  	fclose ($fp);
   	fclose ($fp_new);
}
#--------Outlook express-------
if ($_POST['pocht']=="0"){
    $fp_new = fopen ("emails_outlook.csv", "a");
    fputs ($fp_new, "Имя;Электронная почта; \n");
    while ($sim= fgetcsv ($fp, 1000, ",")) fputs($fp_new,$sim[2].";".$sim[3]." \n");
    echo "На сервере сгенерирован файл для экспорта в почтовую программу <b>Outlook Express</b> <br><a href=emails_outlook.csv class=exp>--загрузить--</a>";
    fclose ($fp);
    fclose ($fp_new);
}
#-----------Becky!-----------------
if ($_POST['pocht']=="2"){
    $fp_new = fopen("emails_becky.txt", "a");
    while ($sim= fgetcsv ($fp, 1000, ",")) fputs($fp_new,$sim[2].",".$sim[3]."\n");
    fclose ($fp_new);
    fclose ($fp);
    echo "На сервере сгенерирован файл для экспорта в почтовую программу <b>Becky!</b><br><a href=emails_becky.txt class=exp>--загрузить--</a>";
}
#-----------Mazilla Mail------------
if ($_POST['pocht']=="3"){
    $fp_new = fopen("emails_mazilla.ldif", "w");
    while ($data= fgetcsv ($fp, 1000, ",")) fputs($fp_new, "dn: cn=$data[2],mail=$data[3]\nobjectclass: top\nobjectclass: person\nobjectclass: organizationalPerson\nobjectclass: inetOrgPerson\nobjectclass: mozillaAbPersonObsolete\ngivenName: $data[2]\ncn: $data[2]\nmail: $data[3]\nmodifytimestamp: 0Z\n\n");
    echo "На сервере сгенерирован файл для экспорта в почтовую программу <b>Mazilla.</b><br><a href=emails_mazilla.ldif class=exp>--загрузить--</a>";
    fclose ($fp);
    fclose ($fp_new);
}
} else echo 'Базы данных подписчиков не существует!';
  echo '<br><br><a href=javascript:history.back(-1) style="font-size:14;" class=exp>--назад--</a></font>';
  break;
  }
  case "backup":
  {
  	include("head.php");
    if (file_exists("emails.txt"))
    {
      copy("emails.txt", "emails_backup.txt");
      echo "Резервная копия создана!";
    }
    else echo "Базы данных подписчиков не существует!";
    echo '<br><a href=javascript:history.back(-1) style="font-size:14;" class=exp>--назад--</a></font>';
    break;
  }
  case "setup":
  {
  	include("head.php");
  	$config=fopen("config.inc.php", "w");
    fputs ($config,"<?php\n\$mainurl=\"".$_POST['mainurl']."\";\n\$sendermail=\"".$_POST['sendermail']."\";\n\$sendername=\"".$_POST['sendername']."\";\n\$sendertheme=\"".$_POST['sendertheme']."\";\n\$senderurl=\"".$_POST['senderurl']."\";\n\$senderfolder=\"".$_POST['senderfolder']."\";\n\$username=\"".$_POST['username']."\";\n\$password=\"".$_POST['password']."\";\n\$letterbody= <<<EOD\n\nEOD;\n?>");
    fclose($config);
    chmod ("config.inc.php", 0777);
    echo "Скрипт установлен успешно...Спасибо за использование...";
    break;
  }
  case "config": {
  	$_POST['senderfolder'] = str_replace("\\","",$_POST['senderfolder']);
  	if(isset($_POST['style'])) $style=1; else $style=0;
  	if(!isset($_POST['lettype'])) $lettype="plain"; else $lettype=$_POST['lettype'];
    $config=fopen("config.inc.php", "w");
    fputs ($config,"<?php\n\$mainurl=\"".$_POST['mainurl']."\";\n\$style=\"".$style."\";\n\$sendermail=\"".$_POST['sendermail']."\";\n\$sendername=\"".$_POST['sendername']."\";\n\$sendertheme=\"".$_POST['sendertheme']."\";\n\$senderurl=\"".$_POST['senderurl']."\";\n\$senderfolder=\"".$_POST['senderfolder']."\";\n\$charset=\"".$_POST['charset']."\";\n\$lettype=\"".$lettype."\";\n\$username=\"".$_POST['username']."\";\n\$password=\"".$_POST['password']."\";\n?>");
    fclose($config);
    Header("Location: ".$_SERVER['HTTP_REFERER']);
  	break;
  }
  case "preview": {header("Location: action.php?act=6&preview=true");break;}
  case "view": {
  	include("head.php");
  	if(isset($_POST['action'])) {
  		switch ($_POST['action']) {
  			case "del": {
	            if (isset($_POST['check'])) {
	                $putfile="false";
	                $base=fopen("emails.txt","r");
	                $emails_buf=fopen("emails_buf.txt", "w");
	                while ($data=fgetcsv($base,5025,",")){
	                    $str=$data[0].",".$data[1].",".$data[2].",".$data[3].",".$data[4].",".$data[5].",".$data[6].",".$data[7]."\n";
	                    if (is_array($_POST['check'])) {
	                        foreach ($_POST['check'] as $value) {
	                            if ($data[0]!=$value){$putfile="true";}
	                            else {$putfile="false";break;}
	                        }
	                    }
	                    if ($putfile=="true") fputs ($emails_buf, $str);
	                }
	                fclose($base);
	                fclose ($emails_buf);
	                copy ("emails_buf.txt","emails.txt");
	                unlink ("emails_buf.txt");
	            }
	            break;
			}
			case "delbad": {
	                $putfile="false";
	                $base=fopen("emails.txt","r");
	                $emails_buf=fopen("emails_buf.txt", "w");
	                while ($data=fgetcsv($base,5025,",")){
	                    $str=$data[0].",".$data[1].",".$data[2].",".$data[3].",".$data[4].",".$data[5].",".$data[6].",".$data[7]."\n";
	                    if ($data[4]!=1) {fputs ($emails_buf, $str); $putfile="true";}
	                }
	                fclose($base);
	                fclose ($emails_buf);
	                copy ("emails_buf.txt","emails.txt");
	                unlink ("emails_buf.txt");
	              //  if($putfile=="false") echo 'Удалять нечего - несуществующих адресов нет!'; //else echo 'Несуществующие адреса были удалены!';
				break;
			}
		}
	}
    if (file_exists("emails.txt")) {
    	$base=fopen("emails.txt","r");
    	echo '<form name="subscr" action="" method="post">';
    	echo '<input type="hidden" name="process" value="view">';
    	echo '<table cellspacing=0 cellpadding=3 border=0 style="text-align:center;font-size:12;">';
    	echo '<tr><td>Всего: '.count(file("emails.txt")).'</td><td colspan=7 align=right><select name="action"><option value="del">Удалить выделенные<option value="delbad">Удалить несуществующие';
    /*	echo '<option value="send">Отослать письмо (disabled)
    	      <option value="delbad">Удалить несуществующие (disabled)
    	      <option value="delunsub">Удалить отписанные (disabled)';
    */	echo '</select>&nbsp;<input type="submit" value="ok"></td></tr>';
    	echo '<tr bgcolor=silver><td><input type="checkbox" name="all" onclick="checkall(this.form);"></td><td><b>ID</td><td><b>Номер</td><td><b>Адрес</td><td><b>Сервер</td><td><b>IP адрес</td><td><b>Подтверждён</td></tr>';
        $num=0;
        $kol = count(file("emails.txt"));
      	while($data=fgetcsv($base,300,",")) {
      		if ($data[4]==1) {echo '<tr bgcolor=#FF6464';} else {if ($data[6]==1) {echo '<tr bgcolor=#75BAFF';} elseif($data[6]==3) echo '<tr bgcolor=yellow'; else {echo '<tr bgcolor='; if($num%2==0) echo'#EBEBEB';}} echo'><td><input type="checkbox" name="check[]" value="'.$data[0].'"></td><td><b>'.$data[0].'</b></td><td>'.$data[1].'</td><td>'.$data[3].'</td><td>';if (isset($data[4])) {if ($data[4]!=1) {echo $data[4];} else echo "не существует";} else echo'Вы не запускали update.php';echo'</td><td>';if (isset($data[5])) echo $data[5]; else echo'Вы не запускали update.php'; echo '</td><td>'; if ($data[6]==1) echo "нет"; elseif ($data[6]==2) echo'да'; elseif($data[6]==3) echo 'отписан';  echo'</td></tr>';
            $num++;
            $kol--;
      	}
      	echo '</table>';
      	echo '</form>';
    } else echo 'Базы данных подписчиков не существует.';
    echo '<a href=javascript:history.back(-1) style="font-size:14;" class=exp>--назад--</a></font>';
  	break;
  }
  case "restore": {
  	include ("head.php");
  	if (file_exists("emails_backup.txt")) {
  		copy("emails_backup.txt","emails.txt");
  		echo 'База восстановлена из резервной копии!';
  	} else {
  		echo 'Резервная копия не существует!';
  	}
  	echo '<br><a href=javascript:history.back(-1) style="font-size:14;" class=exp>--назад--</a></font>';
  	break;
  }
}
}
echo '<br><font style="font-size:11px;font-family:verdana;">Powered by <a href="http://tsb.mimozaf.ru" target="_blank">TSB Subscription</a> v. ';show_ver('ver');echo'</font>';

} else {require("action.php");}
ob_end_flush();
?>