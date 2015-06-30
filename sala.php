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

echo "<h2>Sala treningowa</h2><hr/>
Wartość Pokemona wzrasta o połowę wydanej kasy na trening<hr/>";

if(!empty($_GET['trenuj'])){
	switch($_GET['trenuj']){
		case 1:
			if($uzytkownik['kasa'] < $uzytkownik['atak'] * 100)
				echo "<p class='error'>Za mało gotówki</p><br class='clear'>";
			elseif($uzytkownik['akcje'] < $uzytkownik['atak'])
				echo "<p class='error'>Za mało punktów akcji</p><br class='clear'>";
			else {
				mysql_query("update pokemon_gracze set akcje = akcje - ".$uzytkownik['atak'].",  kasa = kasa - ".(100*$uzytkownik['atak'])." where gracz = ".$uzytkownik['gracz']);

				mysql_query("update pokemon_pokemony_gracze set atak = atak + 1, wartosc = wartosc + ".(50*$uzytkownik['obrazenia_max'])." where gracz_id = ".$uzytkownik['gracz']." and pokemon_id = ".$uzytkownik['aktywny_pokemon']);

				
				$uzytkownik['kasa'] -= $uzytkownik['atak'] * 100;
				$uzytkownik['akcje'] -= $uzytkownik['atak'];
				$uzytkownik['atak']++;

				echo "<p class='note'>Trening udany</p><br class='clear'>";

			}
		break;
		case 2:
			if($uzytkownik['kasa'] < $uzytkownik['obrona'] * 120)
				echo "<p class='error'>Za mało gotówki</p><br class='clear'>";
			elseif($uzytkownik['akcje'] < $uzytkownik['obrona'])
				echo "<p class='error'>Za mało punktów akcji</p><br class='clear'>";
			else {
				mysql_query("update pokemon_gracze set akcje = akcje - ".$uzytkownik['obrona'].",  kasa = kasa - ".(120*$uzytkownik['obrona'])." where gracz = ".$uzytkownik['gracz']);

				mysql_query("update pokemon_pokemony_gracze set obrona = obrona + 1, wartosc = wartosc + ".(60*$uzytkownik['obrazenia_max'])." where gracz_id = ".$uzytkownik['gracz']." and pokemon_id = ".$uzytkownik['aktywny_pokemon']);

				
				$uzytkownik['kasa'] -= $uzytkownik['obrona'] * 100;
				$uzytkownik['akcje'] -= $uzytkownik['obrona'];
				$uzytkownik['obrona']++;


				echo "<p class='note'>Trening udany</p><br class='clear'>";

			}
		break;
		case 3:
			if($uzytkownik['kasa'] < $uzytkownik['obrazenia_max'] * 150)
				echo "<p class='error'>Za mało gotówki</p><br class='clear'>";
			elseif($uzytkownik['akcje'] < $uzytkownik['obrazenia_max'])
				echo "<p class='error'>Za mało punktów akcji</p><br class='clear'>";
			else {
				mysql_query("update pokemon_gracze set akcje = akcje - ".$uzytkownik['obrazenia_max'].",  kasa = kasa - ".(150*$uzytkownik['obrazenia_max'])." where gracz = ".$uzytkownik['gracz']);

				mysql_query("update pokemon_pokemony_gracze set obrazenia_min = obrazenia_min + 1 , obrazenia_max = obrazenia_max + 1, wartosc = wartosc + ".(75*$uzytkownik['obrazenia_max'])." where gracz_id = ".$uzytkownik['gracz']." and pokemon_id = ".$uzytkownik['aktywny_pokemon']);

				
				$uzytkownik['kasa'] -= $uzytkownik['obrazenia_max'] * 150;
				$uzytkownik['akcje'] -= $uzytkownik['obrazenia_min'];
				$uzytkownik['obrazenia_min']++;
				$uzytkownik['obrazenia_max']++;


				echo "<p class='note'>Trening udany</p><br class='clear'>";

			}
		break;
		case 4:
			if($uzytkownik['kasa'] < $uzytkownik['zycie_max'] * 110)
				echo "<p class='error'>Za mało gotówki</p><br class='clear'>";
			elseif($uzytkownik['akcje'] < $uzytkownik['zycie_max'])
				echo "<p class='error'>Za mało punktów akcji</p><br class='clear'>";
			else {
				mysql_query("update pokemon_gracze set akcje = akcje - ".$uzytkownik['zycie_max'].",  kasa = kasa - ".(110*$uzytkownik['zycie_max'])." where gracz = ".$uzytkownik['gracz']);

				mysql_query("update pokemon_pokemony_gracze set zycie_max = zycie_max + 5, zycie = zycie + 5, wartosc = wartosc + ".(55*$uzytkownik['obrazenia_max'])." where gracz_id = ".$uzytkownik['gracz']." and pokemon_id = ".$uzytkownik['aktywny_pokemon']);

				
				$uzytkownik['kasa'] -= $uzytkownik['zycie_max'] * 110;
				$uzytkownik['akcje'] -= $uzytkownik['zycie_max'];
				$uzytkownik['zycie_max'] +=5;
				$uzytkownik['zycie'] +=5;

				echo "<p class='note'>Trening udany</p><br class='clear'>";

			}
		break;		
		default:
			echo "<p class='error'>Nieprawidłowa wartość</p><br class='clear'>";
		break;
	}

}

echo "<table>";

if( ($uzytkownik['kasa'] > $uzytkownik['atak'] * 100) && ($uzytkownik['akcje'] > $uzytkownik['atak']) && ($uzytkownik['akcje'] > 0) )
	echo "
		<tr>
			<td>atak</td>
			<td>".$uzytkownik['atak']."</td>
			<td><a href='sala.php?trenuj=1'>trenuj (+1 za: ".$uzytkownik['atak']." akcji, ".($uzytkownik['atak'] * 100)." $)</a> </td>
		</tr>
	";
else
	echo "
		<tr>
			<td>atak</td>
			<td>".$uzytkownik['atak']."</td>
			<td> -  (+1 za: ".$uzytkownik['atak']." akcji, ".($uzytkownik['atak'] * 100)." $) </td>
		</tr>
	";

if( ($uzytkownik['kasa'] > $uzytkownik['obrona'] * 120) && ($uzytkownik['akcje'] > $uzytkownik['obrona']) && ($uzytkownik['akcje'] > 0) )
	echo "
		<tr>
			<td>walka w parterze</td>
			<td>".$uzytkownik['obrona']."</td>
			<td><a href='sala.php?trenuj=2'>trenuj (+1 za: ".$uzytkownik['obrona']." akcji, ".($uzytkownik['obrona'] * 120)." $)</a> </td>
		</tr>
	";
else
	echo "
		<tr>
			<td>obrona</td>
			<td>".$uzytkownik['obrona']."</td>
			<td> -  (+1 za: ".$uzytkownik['obrona']." akcji, ".($uzytkownik['obrona'] * 120)." $) </td>
		</tr>
	";


if( ($uzytkownik['kasa'] > $uzytkownik['obrazenia_max'] * 150) && ($uzytkownik['akcje'] > $uzytkownik['obrazenia_max']) && ($uzytkownik['akcje'] > 0) )
	echo "
		<tr>
			<td>obrazenia</td>
			<td>".$uzytkownik['obrazenia_min']." - ".$uzytkownik['obrazenia_max']."</td>
			<td><a href='sala.php?trenuj=3'>trenuj (+1 za: ".$uzytkownik['obrazenia_max']." akcji, ".($uzytkownik['obrazenia_max'] * 150)." $)</a> </td>
		</tr>
	";
else
	echo "
		<tr>
			<td>obrazenia</td>
			<td>".$uzytkownik['obrazenia_min']." - ".$uzytkownik['obrazenia_max']."</td>
			<td>- (+1 za: ".$uzytkownik['obrazenia_max']." akcji, ".($uzytkownik['obrazenia_max'] * 150)." $) </td>
		</tr>
	";


if( ($uzytkownik['kasa'] > $uzytkownik['zycie_max'] * 110) && ($uzytkownik['akcje'] > $uzytkownik['zycie_max']) && ($uzytkownik['akcje'] > 0) )
	echo "
		<tr>
			<td>zycie</td>
			<td>".$uzytkownik['zycie']."/".$uzytkownik['zycie_max']."</td>
			<td><a href='sala.php?trenuj=4'>trenuj (+5 za: ".$uzytkownik['zycie_max']." akcji, ".($uzytkownik['zycie_max'] * 110)." $)</a> </td>
		</tr>
	";
else
	echo "
		<tr>
			<td>zycie</td>
			<td>".$uzytkownik['zycie']."/".$uzytkownik['zycie_max']."</td>
			<td>- (+5 za: ".$uzytkownik['zycie_max']." akcji, ".($uzytkownik['zycie_max'] * 110)." $) </td>
		</tr>
	";


echo "</table>";

//pobieramy zawartość prawego bloku
require_once('prawy_blok.php');

//pobieramy stopkę
require_once('dol_strony.php');

//wyłączamy bufor
ob_end_flush();
?> 