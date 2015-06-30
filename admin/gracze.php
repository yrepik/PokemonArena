

<?php
if(!empty($_GET['ban'])){
	$_GET['ban'] = (int)$_GET['ban'];
	mysql_query("update pokemon_gracze set zbanowany = 1 where gracz = ".$_GET['ban']);
	echo "<p class='note'>zbanowano gracza</p><br class='clear'>";
}elseif(!empty($_GET['unban'])){
	$_GET['unban'] = (int)$_GET['unban'];
	mysql_query("update pokemon_gracze set zbanowany = 0 where gracz = ".$_GET['unban']);
	echo "<p class='note'>odbanowano gracza</p><br class='clear'>";
}

echo "
<p><b>Lista graczy</b><hr/></p>
<table>
<tr>
	<th>Lp</th>
	<th>Gracz</th>

	<th></th>
</tr>
";
$gracze = mysql_query("select * from pokemon_gracze  order by zbanowany desc");
while ($g = mysql_fetch_array($gracze)){

	if($g['zbanowany'] == 0){
		$opcje = "
			<a href='admin.php?act=gracze&ban=".$g['gracz']."'>[banuj]</a> |
			<a href='poczta.php?act=nowa&do=".$g['login']."'>[wiadomość]</a> 
		";
	} else {
		$opcje = "
			<a href='admin.php?act=gracze&unban=".$g['gracz']."'>[odbanuj]</a>
		";
	}
	echo "
	<tr>
		<td>".++$i."</td>
		<td align='center'>".$g['login']."</td>
		<td>".$opcje."</td>
	</tr>";
}
echo "</table>";
?>