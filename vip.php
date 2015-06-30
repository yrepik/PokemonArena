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

echo "<h2>Opcje VIP</h2><hr/>";

if(!empty($_POST['kod'])){
	$kod = mysql_real_escape_string(trim($_POST['kod']));

	$jest = mysql_fetch_array(mysql_query("select id from pokemon_kody where kod = '".$kod."' and status = 0"));


	if(empty($jest)) echo "<p class='error'>Nie ma takiego kodu</p><br class='clear'>"; else {
		if($uzytkownik['vip'] > time()){
			mysql_query("update pokemon_gracze set vip = vip + ".(30*86400).", akcje_max = 150 where gracz = ".$uzytkownik['gracz']);
			$uzytkownik['vip'] += 30*86400;
			$uzytkownik['akcje_max'] = 150;
		} else {
			mysql_query("update pokemon_gracze set vip = ".(time() + 30*86400).", akcje_max = 150 where gracz = ".$uzytkownik['gracz']);
			$uzytkownik['vip'] = time() + 30*86400;
			$uzytkownik['akcje_max'] = 150;
			$uzytkownik['akcje'] += 50;
		}
		
		mysql_query("update pokemon_kody set status = 1, gracz_id = ".$uzytkownik['gracz']." where id = ".$jest['id']);

		echo "<p class='note'>Ustawiono konto VIP</p><br class='clear'>";
	}
}
echo"

<p>
Posiadając konto VIP masz do wykorzystania o 50 akcji dziennie więcej<br/>
Używając kodu przedłużasz czas trawania konta VIP o 30 dni<br/><br/>";

if($uzytkownik['vip'] > time()){
	$pozostalo = $uzytkownik['vip'] - time();
	echo "

	<script type='text/javascript'>        
        function liczCzas(ile) {
			dni = Math.floor(ile / 86400);
            godzin = Math.floor((ile - dni * 86400)/ 3600);
            minut = Math.floor((ile - dni * 86400 - godzin * 3600) / 60);
            sekund = ile - dni * 86400 - minut * 60 - godzin * 3600;
            if (godzin < 10){ godzin = '0'+ godzin; }
            if (minut < 10){ minut = '0' + minut; }
            if (sekund < 10){ sekund = '0' + sekund; }
            if (ile > 0) {
                ile--;
                document.getElementById('zegar').innerHTML = dni + ' dni ' +godzin + ':' + minut + ':' + sekund;
                setTimeout('liczCzas('+ile+')', 1000);
            } else {
                document.getElementById('zegar').innerHTML = '[koniec]';
            }
        }
    </script>
	Do końca okresu VIP pozostało: <b><span id='zegar'></span></b><script type='text/javascript'>liczCzas(".$pozostalo.")</script>  
	";
}

echo "
<form action='vip.php' method='post'>
Podaj kod: <input type='text' style='width:200px' name='kod' /> <td colspan=2 align='center'><input type='submit' value='użyj'/>
</form>
<hr/>
Aby otrzymać kod wyślij SMS o treści XXXX na numer XXXXX<br/>
Cena 2,44zł z VAT
";




//pobieramy zawartość prawego bloku
require_once('prawy_blok.php');

//pobieramy stopkę
require_once('dol_strony.php');

//wyłączamy bufor
ob_end_flush();
?> 