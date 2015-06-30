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


   


echo "<h2>Panel admina</h2><hr/>";

if($uzytkownik['login'] != 'admin') echo "brak dostępu";
else {
	switch($_GET['act']){
		case 'gracze': require_once('admin/gracze.php'); break;
		case 'mass': require_once('admin/mass.php'); break;
		case 'vip': require_once('admin/vip.php'); break;
		default:
			echo "
				<ul>
					<li><a href='admin.php'>start</a>
					<li><a href='admin.php?act=gracze'>gracze</a>
					<li><a href='admin.php?act=mass'>wiadomość masowa</a>
					<li><a href='admin.php?act=vip'>dodaj kody VIP</a>
				</ul>
			";
		break;
	}
}


//pobieramy zawartość prawego bloku
require_once('prawy_blok.php');

//pobieramy stopkę
require_once('dol_strony.php');

//wyłączamy bufor
ob_end_flush();
?> 