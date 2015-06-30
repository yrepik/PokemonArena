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

echo "<h2>Praca</h2><hr/>";
if(($uzytkownik['pracuje'] < time())  && ($uzytkownik['pracuje'] > 0)){
	mysql_query("update pokemon_gracze set pracuje = 0, pracuje_godzin = 0, kasa = kasa + ".($uzytkownik['pracuje_godzin'] * 100)." where gracz =".$uzytkownik['gracz']);
	header("location: praca.php");
}
if(isset($_GET['przerwij']) && ($uzytkownik['pracuje'] > 0)){
	mysql_query("update pokemon_gracze set pracuje = 0, pracuje_godzin = 0 where gracz =".$uzytkownik['gracz']);
	header("location: praca.php");
}

if(!empty($_GET['praca']) && ($uzytkownik['pracuje'] == 0)){
	switch($_GET['praca']){
		case 1:
			mysql_query("update pokemon_gracze set pracuje = ".(time() + 3600).", pracuje_godzin = 1 where gracz =".$uzytkownik['gracz']);
			header("location: praca.php");
		break;
		case 2:
			mysql_query("update pokemon_gracze set pracuje = ".(time() + 7200).", pracuje_godzin = 2 where gracz =".$uzytkownik['gracz']);
			header("location: praca.php");
		break;
		case 3:
			mysql_query("update pokemon_gracze set pracuje = ".(time() + 10800).", pracuje_godzin = 3 where gracz =".$uzytkownik['gracz']);
			header("location: praca.php");
		break;
		default:
			echo "<p class='error'>Nieprawidłowa wartość</p><br class='clear'>";
		break;
	}	
}

if($uzytkownik['pracuje'] > 0){
	$pozostalo = $uzytkownik['pracuje'] - time();
	echo "
	<script type='text/javascript'>        
        function liczCzas(ile) {
	            godzin = Math.floor((ile )/ 3600);
            minut = Math.floor((ile  - godzin * 3600) / 60);
            sekund = ile  - minut * 60 - godzin * 3600;
            if (godzin < 10){ godzin = '0'+ godzin; }
            if (minut < 10){ minut = '0' + minut; }
            if (sekund < 10){ sekund = '0' + sekund; }
            if (ile > 0) {
                ile--;
                document.getElementById('zegar').innerHTML = godzin + ':' + minut + ':' + sekund;
                setTimeout('liczCzas('+ile+')', 1000);
            } else {
                document.getElementById('zegar').innerHTML = '[koniec]';
            }
        }
    </script>
	<p class='note'>
		Do końca pracy pozostało: <b><span id='zegar'></span> <a href='praca.php?przerwij' style='color:#000; text-decoration:none' title='przerwij'>[ X ]</a></b><script type='text/javascript'>liczCzas(".$pozostalo.")</script>  
	</p><br class='clear'>";
} else {

	echo "
		Możesz iść do pracy, za każdą godzinę dostaniesz 100$
		<ul>
		<li>1 godizna <a href='praca.php?praca=1'>pracuj</a></li>
		<li>2 godziny <a href='praca.php?praca=2'>pracuj</a></li>
		<li>3 godizny <a href='praca.php?praca=3'>pracuj</a></li>
		</ul>
	";

}




//pobieramy zawartość prawego bloku
require_once('prawy_blok.php');

//pobieramy stopkę
require_once('dol_strony.php');

//wyłączamy bufor
ob_end_flush();
?> 