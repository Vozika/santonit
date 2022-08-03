<html>
<head>
  <title>Onoaiiaea TSB Subscription</title>
</head>
<link href="style.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<body>
<?php
if (isset($_POST['process']) and $_POST['process']=="setup") {
    include("head.php");
  	$config=fopen("config.inc.php", "w");
    fputs ($config,"<?php\n\$mainurl=\"".$_POST['mainurl']."\";\n\$style=\"1\";\n\$sendermail=\"".$_POST['sendermail']."\";\n\$sendername=\"".$_POST['sendername']."\";\n\$sendertheme=\"".$_POST['sendertheme']."\";\n\$senderurl=\"".$_POST['senderurl']."\";\n\$senderfolder=\"".str_replace("\\","",$_POST['senderfolder'])."\";\n\$charset=\"windows-1251\";\n\$username=\"".$_POST['username']."\";\n\$password=\"".$_POST['password']."\";?>");
    fclose($config);
    chmod ("config.inc.php", 0777);
    echo '<b>Negeio onoaiiaeai oniaoii... Nianeai ca eniieuciaaiea...</b><br><br>Iaiiaeaiey ?oiai e agoaeo negeioia: <a href="http://tsb.mimozaf.ru" target="_blank">http://tsb.mimozaf.ru</a><br>Oigoi iiaaag?ee: <a href="http://tsb.mimozaf.ru/forum" target="_blank">http://tsb.mimozaf.ru/forum</a><br><br>';
    echo 'Iaiai nnueeaie: <a href="http://tsb.mimozaf.ru?menu=users" target="_blank">http://tsb.mimozaf.ru?menu=users</a><br><font style="font-size:9px;font-family:verdana;">(Anee au eniieucoaoa negeiou TSB Scripts, aao ganogn aoaao iiiauai a niioa. eaoaaigee, iiieii auagaiiie.)</font>';
} else {
	echo '<div align=center>';
    echo '<form name=\"config\" action="" method=post>';
    echo '<table><tr><td>';
    echo "<input type='hidden' name='process' value='setup'>";
	echo '<b>Iauea ianogieee:</b><br>';
	echo '<input type="text" name="mainurl">  Aeaaiay<br><br>';
	echo '<b>Aaciianiinou:</b><br><input type="text" name="username" style="width:200;"> Eiy iieuciaaoaey<br><input type="password" name="password" style="width:200;"> Iagieu<br><br>';
	echo '<b>Ianogieee oaaaiieoaeuiiai ienuia:</b><br>';
	echo '<input type="text" name="sendermail" style="width:200;"> E-mail (iiea reply-to)<br>';
	echo '<input type="text" name="sendername" style="width:200;"> Eiy (iiea from) - [sendername]<br>';
	echo '<input type="text" name="sendertheme" style="width:200;"> Oaia (iiea subject)<br><br>';
	echo '<input type="text" name="senderurl" value="http://'.$_SERVER['SERVER_NAME'].'" style="width:200;"> Aagan (url) naeoa - [url]<br>';
	echo '<input type="text" name="senderfolder" value="'.dirname($_SERVER['PHP_SELF']).'" style="width:200;"> Ioou e iaiea ni negeioii - [folder]<br><br>';
    echo "<br><input type=submit value='Onoaiiaeou'>";
    echo '</td></tr></table>';
    echo "</form>";
}
echo '<br><br><font style="font-size:11px;font-family:verdana;">Powered by <a href="http://tsb.mimozaf.ru" target="_blank">TSB Subscription</a> v. 1.37.3 </font>';
?>
</body>
</html>