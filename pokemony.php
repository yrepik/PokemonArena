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

if(!empty($_GET['aktywny'])){
	$_GET['aktywny'] = (int)$_GET['aktywny'];
	$pokemon = mysql_fetch_array(mysql_query("select * from pokemon_pokemony_gracze where gracz_id = ".$uzytkownik['gracz']." and pokemon_id = ".$_GET['aktywny']));
	if(empty($pokemon)){
		echo "<p class='error'>Nie ma takiego Pokemona</p><br class='clear'>";
	} else {
		mysql_query("update pokemon_gracze set aktywny_pokemon = ".$_GET['aktywny']." where gracz = ".$uzytkownik['gracz']);

		$uzytkownik['nazwa'] = $pokemon['nazwa'];
		$uzytkownik['atak'] = $pokemon['atak'];
		$uzytkownik['obrona'] = $pokemon['obrona'];
		$uzytkownik['obrazenia_min'] = $pokemon['obrazenia_min'];
		$uzytkownik['obrazenia_max'] = $pokemon['obrazenia_max'];
		$uzytkownik['zycie'] = $pokemon['zycie'];
		$uzytkownik['zycie_max'] = $pokemon['zycie_max'];
		$uzytkownik['aktywny_pokemon'] = $_GET['aktywny'];

		echo "<p class='note'>Aktywowano Pokemona</p><br class='clear'>";
	}
}
if(!empty($_GET['pokemon']) && !empty($_POST['nazwa'])){
	$_GET['pokemon'] = (int)$_GET['pokemon'];
	if($_GET['pokemon'] == 0){
		echo "<p class='error'>Nie ma takiego Pokemona</p><br class='clear'>";
	} else {
		$_POST['nazwa'] = trim(substr($_POST['nazwa'],0,20));
		
		$nazwa_buff = $_POST['nazwa'];
		
		$_POST['nazwa'] = mysql_real_escape_string($_POST['nazwa']);
		mysql_query("update pokemon_pokemony_gracze set nazwa = '".$_POST['nazwa']."' where gracz_id = ".$uzytkownik['gracz']." and pokemon_id = ".$_GET['pokemon']);
		
		if(mysql_affected_rows() == 1){
			echo "<p class='note'>Zmieniono nazwę Pokemona</p><br class='clear'>";
			if($_GET['pokemon'] == $uzytkownik['aktywny_pokemon']) $uzytkownik['nazwa'] = $nazwa_buff;

		} else {
			echo "<p class='error'>Nie ma takiego pokemona lub nie wpisałeś poprawnej nazwy</p><br class='clear'>";
		}
	}
	

}
// 
echo "<h2>Aktywny Pokemon</h2><hr/>
	<table>
		<tr>
			<td>
				<img src='pokemony/".$uzytkownik['aktywny_pokemon'].".jpg' alt=''/>
				
			</td>
			
			<td>
				<b><i>".$uzytkownik['nazwa']."</i></b>
				<ul>
					<li>atak: ".$uzytkownik['atak']."
					<li>obrona: ".$uzytkownik['obrona']."
					<li>obrażenia: ".$uzytkownik['obrazenia_min']."-".$uzytkownik['obrazenia_max']."
					<li>życie: ".$uzytkownik['zycie']."/".$uzytkownik['zycie_max']."
				</ul>
				<form action='pokemony.php?pokemon=".$uzytkownik['aktywny_pokemon']."' method='post'>
					<input type='text' name='nazwa' value='".$uzytkownik['nazwa']."'/>
					<input type='submit' value='zmień nazwę'/>
				</form>
			</td>
		</tr>	
	</table>
";
$pokemony = mysql_query("select * from pokemon_pokemony_gracze where gracz_id = ".$uzytkownik['gracz']." and pokemon_id != ".$uzytkownik['aktywny_pokemon']);
if(mysql_num_rows($pokemony) > 0){
	echo "<h2>Twoje Pokemony</h2><hr/>
	<table style='width:100%'>
	";
	while ($pokemon = mysql_fetch_array($pokemony)){
		$i++;
		if($i % 2 == 1) $styl = " style='background:#D1D1D1'"; else $styl="";
		echo "
		<tr ".$styl.">
			<td style='width:250px'>
				<img src='pokemony/".$pokemon['pokemon_id'].".jpg' alt=''/>
				
			</td>
			
			<td style='padding:5px'>
				<b><i>".$pokemon['nazwa']."</i></b>
				<ul>
					<li>atak: ".$pokemon['atak']."
					<li>obrona: ".$pokemon['obrona']."
					<li>obrażenia: ".$pokemon['obrazenia_min']."-".$pokemon['obrazenia_max']."
					<li>życie: ".$pokemon['zycie']."/".$pokemon['zycie_max']."
				</ul>
				<a href='pokemony.php?aktywny=".$pokemon['pokemon_id']."'>[ustaw jako aktywny]</a><br/>
				<form action='pokemony.php?pokemon=".$pokemon['pokemon_id']."' method='post'>
					<input type='text' name='nazwa' value='".$pokemon['nazwa']."'/>
					<input type='submit' value='zmień nazwę'/>
				</form>
			</td>
		</tr>	
		";
	}
	echo "</table>";
} else echo "<p class='note'>Nie posiadasz więcej Pokemonów</p><br class='clear'>";

//pobieramy zawartość prawego bloku
require_once('prawy_blok.php');

//pobieramy stopkę
require_once('dol_strony.php');

//wyłączamy bufor
ob_end_flush();
?> 