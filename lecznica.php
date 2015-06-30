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
if($uzytkownik['pracuje'] > 0) header('location: praca.php');

//pobieramy zawartość menu
require_once('menu.php');

echo "<h2>Lecznica</h2><hr/>";

if(!empty($_POST['pkt'])){
	$_POST['pkt'] = (int)$_POST['pkt'];

	if($_POST['pkt'] < 1) 
		echo "<p class='error'>Nieprawidłowa wartość</p><br class='clear'>";
	elseif($_POST['pkt'] > $uzytkownik['zycie_max'] - $uzytkownik['zycie']) 
		echo "<p class='error'>Podano za dużą wartość</p><br class='clear'>";
	elseif($_POST['pkt'] * 25 > $uzytkownik['kasa']) 
		echo "<p class='error'>Masz za mało kasy</p><br class='clear'>";
	else {
		mysql_query("update pokemon_pokemony_gracze set zycie = zycie + ".$_POST['pkt']." where gracz_id = ".$uzytkownik['gracz']." and pokemon_id = ".$uzytkownik['aktywny_pokemon']);

		mysql_query("update pokemon_gracze set kasa = kasa - ".($_POST['pkt'] * 25)." where gracz = ".$uzytkownik['gracz']);
		
		$uzytkownik['zycie'] += $_POST['pkt'];
		$uzytkownik['kasa'] -= $_POST['pkt'] * 25;

		echo "<p class='note'>Zregenerowano <i><b>".$_POST['pkt']."</b></i> punktów życia</p><br class='clear'>";
	}
}

if($uzytkownik['zycie'] < $uzytkownik['zycie_max']){
	echo"
		Jeżeli Twój pokemon jest ranny, to miejscowa pielęgniarka się nim dobrze zajmie.<br/>
		1 punkt życia = 25 $<br/>
		<form action='lecznica.php' method='post'>
			<input type='text' name='pkt' /> 
			<input type='submit' value='wylecz'/>
		</form>
	";
} else echo "<p class='note'>Twój Pokemon jest zdrowy</p>";

//pobieramy zawartość prawego bloku
require_once('prawy_blok.php');

//pobieramy stopkę
require_once('dol_strony.php');

//wyłączamy bufor
ob_end_flush();
?> 