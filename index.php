<?php
//włączamy bufor
ob_start();
//pobieramy zawartość pliku ustawień
require_once('var/ustawienia.php');
//startujemy lub przedłużamy sesję
session_start();
//pobieramy nagłówek strony
require_once('gora_strony.php');

//pobieramy zawartość menu
require_once('menu.php');

?>
<h2>Witamy w grze Pokemon Arena</h2><hr/>
<p>
Silnik możesz pobrać <a href='http://gryviawww.pl/?x=pokemon_engine'>tutaj</a><br/><br/>
Opcje silnika
</p>
<ul>
	<li>logowanie, rejestracja
	<li>opcje konta (zmiana hasła/avataru/opisu)
	<li>kupowanie/sprzedawanie Pokemonów
	<li>leczenie rannych Pokemonów
	<li>ustawianie aktywnych Pokemonów
	<li>szkolenie Pokemonów na sali treningowej 
	<li>możliwość pracowania i zdobywania dodatkowej kasy
	<li>opcje VIP (dodawanie bonusowych akcji)
	<li>wiadomości (odebrane, wysłane, wyślij nową)
	<li>ranking
	<li>walki z innymi Pokemonami graczy
	<li>CRON (reset akcji co 24h)
</ul>
<p>
Panel admina
</p>
<ul>
	<li>dodawanie kodów VIP
	<li>wiadomości masowa
	<li>gracze (banowanie, odbanowanie)	
</ul>

     
<?php

//pobieramy zawartość prawego bloku
require_once('prawy_blok.php');
//pobieramy stopkę
require_once('dol_strony.php');
//wyłączamy bufor
ob_end_flush();
?> 