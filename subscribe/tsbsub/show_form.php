<script language="JavaScript">
function fclear(idf) {
	idf.value="";
}
</script>
<style>.tf {border:1px solid black; font-size:12px; width:100px; text-align:center;}</style>
<?php
$path= __FILE__;
$path=str_replace("/show_form.php","",$path);
$path=str_replace("\show_form.php","",$path);
include($path."/config.inc.php");
echo '<table>';
echo '<form name="subform" action="'.$senderfolder.'/write.php" method="post" target="sub">';
if(!isset($style) or $style==1) {
	echo '<tr><td><input type="textbox" name="name" class="tf" value="[ ваше имя ]" id="fn" onClick="fclear(this);"></td></tr>';
	echo '<tr><td><input type="textbox" name="mail" class="tf" value="[ ваша почта ]" id="fm" onclick="fclear(this);"></td></tr>';
	echo '<tr><td><input type="submit" value="Подписаться" class="tf" onclick="sub=window.open(\'\',\'sub\',\'width=550,height=150,top=0,left=0,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no\');sub.document.write(\'<b>Идет подписка...</b>\');"></td></tr>';
} else {
	echo '<tr><td><input type="textbox" name="mail" class="tf" value="[ ваша почта ]" id="fm" onclick="fclear(this);"></td>';
    echo '<td><input type="submit" value="ok" class="tf" style="width:20;" onclick="sub=window.open(\'\',\'sub\',\'width=550,height=150,top=0,left=0,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no\');sub.document.write(\'<b>Идет подписка...</b>\');"></td></tr>';
}
?>
</form>
</table>