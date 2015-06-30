<?php

if(!empty($uzytkownik['gracz'])){
	if($uzytkownik['login'] == 'admin') 
		$opcja = " 
		<li> <a href='#'>Admin <img src='images/nav_bullet.jpg' alt='' /></a>
        <ul>
          <li><a href='admin.php?act=gracze'>Gracze</a></li>
          <li><a href='admin.php?act=vip'>Dodaj kody VIP</a></li>
		  <li><a href='admin.php?act=mass'>Wiadomość masowa</a></li>
        </ul>
      </li>"; 
	else 
		$opcja="";

	
	if($uzytkownik['poczta'] > 0) $poczta = "( ".$uzytkownik['poczta']." )"; else $poczta = "";
	echo"
    <ul id='nav'>
      <li class='left'>&nbsp;</li>
      <li> <a class='active'  href='konto.php'>Konto <img src='images/nav_bullet.jpg' alt='' /></a>
        <ul>
		  <li><a href='pokemony.php'>Pokemony</a></li>
          <li><a href='konto.php#zmien_haslo'>Zmiana hasła</a></li>
          <li><a href='konto.php#zmien_avatar'>Avatar</a></li>
          <li><a href='konto.php#zmien_opis'>Opis</a></li>
        </ul>
      </li>
      <li> <a href='#'>Arena <img src='images/nav_bullet.jpg' alt='' /></a>
        <ul>
          <li><a href='walki.php'>Walki</a></li>
          <li><a href='ranking.php'>Ranking</a></li>
        </ul>
      </li>
	  <li> <a href='#'>Miasto <img src='images/nav_bullet.jpg' alt='' /></a>
        <ul>
          <li><a href='sala.php'>Sala treningowa</a></li>
          <li><a href='lecznica.php'>Lecznica Pokemonów</a></li>
		  <li><a href='praca.php'>Praca</a></li>
		  <li><a href='handlarz.php'>Handlarz Pokemonów</a></li>
        </ul>
      </li>
	  <li> <a href='#'>Poczta ".$poczta." <img src='images/nav_bullet.jpg' alt='' /></a>
        <ul>
          <li><a href='poczta.php?act=odebrane'>Odebrane</a></li>
          <li><a href='poczta.php?act=wyslane'>Wysłane</a></li>
		  <li><a href='poczta.php?act=nowa'>Nowa wiadomość</a></li>
        </ul>
      </li>
      <li><a href='vip.php'>VIP</a></li>
	  ".$opcja ."
     <li class='sep'>&nbsp;</li>
      <li class='right'>&nbsp;</li>
    </ul>
	<div id='header'></div>
	<div id='content'>
	";
}
else 
echo "
    <ul id='nav'>
      <li class='left'>&nbsp;</li>
      <li><a class='active' href='index.php'>Start</a></li>
      <li><a href='regulamin.php'>Regulamin</a></li>
      <li><a href='pomoc.php'>Pomoc</a></li>
      <li><a href='kontakt.php'>Kontakt</a></li>
      <li class='sep'>&nbsp;</li>
      <li class='right'>&nbsp;</li>
    </ul>
	<div id='header'></div>
	<div id='content'>
";