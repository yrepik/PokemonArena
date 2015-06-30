<?php
//sprawdzamy czy w sesji zapisano nr gracza, czyli czy jest zalogowany
if(empty($_SESSION['user'])){
    //nie jest zalogowany, przenieś do strony logowania
    header("Location: logowanie.php");
} else {
    //dodatkowo zabezpieczymy sesję, rzutując wartość na liczbę
    $_SESSION['user'] = (int)$_SESSION['user'];

    //pobieramy dane gracza z bazy
    $uzytkownik = mysql_fetch_array(mysql_query("select *, (select count(*) from pokemon_poczta where typ = 1 and do = gracz and status = 0) as poczta from pokemon_gracze left join pokemon_pokemony_gracze on gracz_id = gracz and pokemon_id = aktywny_pokemon where gracz = ".$_SESSION['user']));
	

	
    //jeżeli nie pobrało gracza, to znaczy, że ktoś kombinuje coś z sesją i trzeba go wylogować
    if(empty($uzytkownik)) header("Location: wyloguj.php");

	//nie ma jeszcze pokemona
	if($uzytkownik['aktywny_pokemon'] == 0){
		mysql_query($q="insert into pokemon_pokemony_gracze(gracz_id, pokemon_id, nazwa, wartosc, atak, obrona, obrazenia_min, obrazenia_max, zycie, zycie_max) value (".$uzytkownik['gracz'].",1,'Pikachu',600,5,5,3,5,25,25)");
		mysql_query("update pokemon_gracze set aktywny_pokemon = 1 where gracz = ".$uzytkownik['gracz']);
		header("Location: pokemony.php");
	}

	//jeżeli skończył się okres vip
	if(($uzytkownik['vip'] > 0) && ($uzytkownik['vip'] < time())){
		mysql_query("update pokemon_gracze set vip = 0, akcje_max = 100 where gracz = ".$uzytkownik['gracz']);
		$uzytkownik['vip'] = 0;
		$uzytkownik['akcje_max'] = 100;
		if($uzytkownik['akcje'] > 100) $uzytkownik['akcje'] = 100;
	}

}
?> 