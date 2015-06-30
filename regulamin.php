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
<h2>Regulamin</h2><hr/>
<p>Wpisz regulamin gry</p>
<?php
//pobieramy zawartość prawego bloku
require_once('prawy_blok.php');
//pobieramy stopkę
require_once('dol_strony.php');
//wyłączamy bufor
ob_end_flush();
?> 