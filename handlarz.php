<?php
//włączamy bufor
ob_start();

//pobieramy zawartość pliku ustawień
require_once('var/ustawienia.php');

//startujemy lub przedłużamy sesję
session_start();

//dołączamy plik, który sprawdzi czy napewno mamy dostęp do tej strony
require_once('test_zalogowanego.php');
if($uzytkownik['pracuje'] > 0) header('location: praca.php');

//pobieramy nagłówek strony
require_once('gora_strony.php');

//pobieramy zawartość menu
require_once('menu.php');    

if(!empty($_GET['kup'])){
	$_GET['kup'] = (int)$_GET['kup'];
	$pokemon = mysql_fetch_array(mysql_query("select p.* from pokemon_pokemony p left join pokemon_pokemony_gracze on gracz_id = ".$uzytkownik['gracz']." and pokemon_id = p.pokemon where pokemon_id is null and pokemon = ".$_GET['kup']));
	if(empty($pokemon)){
		echo "<p class='error'>Nie ma takiego Pokemona lub posiadasz już takiego</p><br class='clear'>";
	} elseif($pokemon['cena'] > $uzytkownik['kasa']){
		echo "<p class='error'>Nie stać Cię na takiego Pokemona</p><br class='clear'>";
	} else {

		mysql_query("insert into pokemon_pokemony_gracze(gracz_id, pokemon_id, nazwa, wartosc, atak, obrona, obrazenia_min, obrazenia_max, zycie, zycie_max) value(".$uzytkownik['gracz'].",".$pokemon['pokemon'].",'".$pokemon['nazwa']."',".($pokemon['cena']*0.9).",".$pokemon['atak'].",".$pokemon['obrona'].",".$pokemon['obrazenia_min'].",".$pokemon['obrazenia_max'].",".$pokemon['zycie'].",".$pokemon['zycie'].")");
		mysql_query("update pokemon_gracze set kasa = kasa - ".$pokemon['cena']." where gracz = ".$uzytkownik['gracz']);

		$uzytkownik['kasa'] -= $pokemon['cena'];
	

		echo "<p class='note'>Kupiono Pokemona</p><br class='clear'>";
	}
}

if(!empty($_GET['sprzedaj'])){
	$_GET['sprzedaj'] = (int)$_GET['sprzedaj'];
	$pokemon = mysql_fetch_array(mysql_query("select * from pokemon_pokemony_gracze  where gracz_id = ".$uzytkownik['gracz']." and pokemon_id != ".$uzytkownik['aktywny_pokemon']." and pokemon_id = ".$_GET['sprzedaj']));
	if(empty($pokemon)){
		echo "<p class='error'>Nie ma takiego Pokemona</p><br class='clear'>";
	} else {

		mysql_query("delete from pokemon_pokemony_gracze where gracz_id = ".$uzytkownik['gracz']." and pokemon_id = ".$_GET['sprzedaj']);
		mysql_query("update pokemon_gracze set kasa = kasa + ".$pokemon['wartosc']." where gracz = ".$uzytkownik['gracz']);

		$uzytkownik['kasa'] += $pokemon['wartosc'];
	

		echo "<p class='note'>Sprzedano Pokemona</p><br class='clear'>";
	}
}


$pokemony = mysql_query("select p.* from pokemon_pokemony p left join pokemon_pokemony_gracze on gracz_id = ".$uzytkownik['gracz']." and pokemon_id = p.pokemon where pokemon_id is null");
if(mysql_num_rows($pokemony) > 0){
	echo "<h2>Pokemony</h2><hr/>
	<table style='width:100%'>
	";
	while ($pokemon = mysql_fetch_array($pokemony)){
		$i++;
		if($i % 2 == 1) $styl = " style='background:#D1D1D1'"; else $styl="";
		echo "
		<tr ".$styl.">
			<td style='width:250px'>
				<img src='pokemony/".$pokemon['pokemon'].".jpg' alt=''/>
				
			</td>
			
			<td style='padding:5px'>
				<b><i>".$pokemon['nazwa']."</i></b>
				<ul>
					<li>atak: ".$pokemon['atak']."
					<li>obrona: ".$pokemon['obrona']."
					<li>obrażenia: ".$pokemon['obrazenia_min']."-".$pokemon['obrazenia_max']."
					<li>życie: ".$pokemon['zycie']."/".$pokemon['zycie']."
				</ul>
				<a href='handlarz.php?kup=".$pokemon['pokemon']."'>[kup za ".$pokemon['cena']."$]</a><br/>
				
			</td>
		</tr>	
		";
	}
	echo "</table>";
} else echo "<p class='note'>Posiadasz już wszystkie dostępne pokemony</p><br class='clear'>";

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
				<a href='handlarz.php?sprzedaj=".$pokemon['pokemon_id']."'>[sprzedaj za ".$pokemon['wartosc']."$]</a><br/>
				
			</td>
		</tr>	
		";
	}
	echo "</table>";
} else echo "<p class='note'>Nie posiadasz wolnych Pokemonów</p><br class='clear'>";

//pobieramy zawartość prawego bloku
require_once('prawy_blok.php');

//pobieramy stopkę
require_once('dol_strony.php');

//wyłączamy bufor
ob_end_flush();
?> 