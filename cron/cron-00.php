<?php
$host_bazy_danych = 'localhost'; //serwer bazy danych
$uzytkownik_bazy_danych = 'root'; //użytkownik bazy danych
$haslo_bazy_danych = ''; //hasło do bazy danych
$nazwa_bazy_danych = 'pokemon';  //nazwa bazy danych
//nawiązujemy połączenie z serwerem bazy danych, jeżeli się nie uda to pokaże informacje
$polacz = mysql_connect($host_bazy_danych, $uzytkownik_bazy_danych, $haslo_bazy_danych) or die('brak połączenia z serwerem');

//wybieramy odpowiednią bazę danych
mysql_select_db($nazwa_bazy_danych,$polacz) or die('nie ma takiej bazy danych');

//ustawiamy domyślne kodowanie znaków dla połączenia
mysql_query("SET NAMES 'utf8'");

mysql_query("update pokemon_gracze set akcje = akcje_max");
?>