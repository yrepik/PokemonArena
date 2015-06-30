<?php
//włączamy bufor
ob_start();

//pobieramy zawartość pliku ustawień
require_once('var/ustawienia.php');

//startujemy lub przedłużamy sesję
session_start();

//dołączamy plik, który sprawdzi czy napewno mamy dostęp do tej strony
require_once('test_zalogowanego.php');


//pobieramy nagłówek strony
require_once('gora_strony.php');

//pobieramy zawartość menu
require_once('menu.php');
?>
<h2>Ranking</h2><hr/>
<table id='rank'>
<tr style='background:#8F8F8F;'>
	<th>Miejsce</th>
	<th>Gracz</th>
	<th>Punkty</th>
</tr>


<?php
$gracze = mysql_query("select * from pokemon_gracze  order by punkty desc ");
while ($g = mysql_fetch_array($gracze)){
	$i++;
	if($i % 2 == 0) $styl = " style='background:#B2B2B2'"; else $styl="";
	echo "
	<tr align='center' ".$styl.">
		<td>".$i."</td>
		<td>".$g['login']."</td>
		<td>".$g['punkty']."</td>
	</tr>";
}

echo "</table>";


//pobieramy zawartość prawego bloku
require_once('prawy_blok.php');

//pobieramy stopkę
require_once('dol_strony.php');

//wyłączamy bufor
ob_end_flush();
?> 