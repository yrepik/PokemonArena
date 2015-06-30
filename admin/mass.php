<?php
if(!empty($_POST['tytul']) && !empty($_POST['tresc'])){
	$tytul = mysql_real_escape_string(trim($_POST['tytul']));
	$tresc = mysql_real_escape_string(trim(nl2br($_POST['tresc'])));

	mysql_query("
		insert into pokemon_poczta (od, do, typ, tytul, tresc, data) 
		select 1, gracz, 1,'".$tytul."','".$tresc."',now() from pokemon_gracze
		");

	echo "<p class='note'>wysłano</p><br class='clear'>";
	
}
?>
<p><b>Wiadomość masowa</b></p><hr/>
<form action='admin.php?act=mass' method='post'>
<table>
<tr>
	<td>Tytuł:</td>
	<td><input type='text' name='tytul' style='width:500px'/></td>
</tr>
<tr>
	<td>Treść:</td>
	<td align='right'><textarea name='tresc' style='width:500px; height:140px;'></textarea></td>
</tr>
<tr>
	<td colspan=2 align='center'><input type='submit' value='wyślij'/></td>
</tr>
</table>
</form>