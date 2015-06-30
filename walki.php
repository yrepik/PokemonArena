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


echo "<h2>Walki rankingowe</h2><hr/>
Walki o miejsce w rankingu są najbardziej prestiżowe i dochodowe. Tylko zwycięzca otrzymuje nagrodę.<br/>
Za wygranie pojedynku otrzymasz pewną kwotę pieniędzy i trochę punktów rankingowych.<br/><br/>
<b>Koszt pojedynku to 25 akcji, jednym pokemonem możesz walczyć raz na 1h</b><br/>
Do walki staje aktywny pokemon przeciwnika<hr/>
";


if(!empty($_POST['login'])){
	$_POST['login'] = mysql_real_escape_string(trim($_POST['login']));
	

	if($uzytkownik['akcje'] < 25){
		echo "<p class='error'>Posiadasz za mało punktów akcji</p><br class='clear'>";
	} elseif($uzytkownik['zycie'] == 0){
			echo "<p class='error'>Twój pokemon jest ranny</p><br class='clear'>";
	} elseif($uzytkownik['ostatnia_walka'] + 3600 > time()){
			$pozostalo = $uzytkownik['ostatnia_walka'] + 3600 - time();
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
			<p class='error'>Twój pokemon niedawno walczył, daj mu odpocząć. Pozostało: <span id='zegar'></span><script type='text/javascript'>liczCzas(".$pozostalo.")</script> </p><br class='clear'>";
	} else {
		$vs = mysql_fetch_array(mysql_query("select * from pokemon_gracze inner join pokemon_pokemony_gracze on gracz_id = gracz and pokemon_id = aktywny_pokemon where login = '".$_POST['login']."'"));
		if(empty($vs)){
			echo "<p class='error'>Nie ma takiego gracza!</p><br class='clear'>";
		} elseif($vs['gracz'] == $uzytkownik['gracz']){
			echo "<p class='error'>Nie możesz walczyć sam ze sobą</p><br class='clear'>";
		} elseif($vs['zycie'] ==0){
			echo "<p class='error'>Przeciwnik jest ranny i nie może brać udziału w walce</p><br class='clear'>";
		} else {
			// przebieg walki
			echo "<p class='note'>Wyzwałeś użytkownika <b>".$vs['login']."</b> na pojedynek, do walki stanął <b>".$vs['nazwa']."</b> </p><br class='clear'>

			<table id='rank'>
			<tr>
				<th><b>".$uzytkownik['login']."<b></th>
				<th><b>".$vs['login']."<b></th>
			</tr>
			<tr>
				<td><img src='pokemony/".$uzytkownik['aktywny_pokemon'].".jpg' alt='' width='125px'/></td>
				<td><img src='pokemony/".$vs['aktywny_pokemon'].".jpg' alt='' width='125px'/></td>
			</tr>
			<tr>
				<td>
					<b><i>".$uzytkownik['nazwa']."</i></b>
					<ul>
						<li>atak: ".$uzytkownik['atak']."
						<li>obrona: ".$uzytkownik['obrona']."
						<li>obrażenia: ".$uzytkownik['obrazenia_min']."-".$uzytkownik['obrazenia_max']."
						<li>życie: ".$uzytkownik['zycie']."/".$uzytkownik['zycie_max']."
					</ul>
				</td>
				<td>
					<b><i>".$vs['nazwa']."</i></b>
					<ul>
						<li>atak: ".$vs['atak']."
						<li>obrona: ".$vs['obrona']."
						<li>obrażenia: ".$vs['obrazenia_min']."-".$vs['obrazenia_max']."
						<li>życie: ".$vs['zycie']."/".$vs['zycie_max']."
					</ul>
				</td>
			</tr>
			</table>
			<hr/>
			";
			$runda = 0;
			$punktyA = 0;
			$punktyB = 0;
			$wynik = 0;
			
			while(($vs['zycie'] > 0) && ($uzytkownik['zycie'] > 0) && ($runda < 20)){
				echo "<br/><br/><b>Runda ".++$runda."</b><br/><br/>";
				$szansa = floor($uzytkownik['atak'] / $vs['obrona'] *100);

				//max szansa na trafienie = 75%, a minimalna 25%
				if($szansa >= 75) $szansa = 75;
				elseif ($szansa < 25) $szansa = 25;

				$rand = rand(1,100);
				if($szansa >= $rand) {
					$rany = rand($uzytkownik['obrazenia_min'],$uzytkownik['obrazenia_max']);
					//minimalnie można zadać 1 ranę
					if($rany < 1) $rany = 1;
					$vs['zycie'] -= $rany;
					$punktyA += $rany;

					if($vs['zycie'] < 1){
						$vs['zycie'] = 0;
						echo "<b>".$uzytkownik['nazwa']."</b> <span style='color:#CC0000'>decydującym ciosem</span> powala <b>".$vs['nazwa']."</b> i wygrywa walkę. <br/>";
						$wynik = 1;
					} else {
						echo "<b>".$uzytkownik['nazwa']."</b> zadaje <span style='color:#CC0000'>".$rany."</span> obrażeń przeciwnikowi. <br/>";
						$szansa = floor($vs['atak'] / $uzytkownik['obrona'] *100);

						//max szansa na trafienie = 75%, a minimalna 25%
						if($szansa >= 75) $szansa = 75;
						elseif ($szansa < 25) $szansa = 25;

						$rand = rand(1,100);
						if($szansa >= $rand) {
							$rany = rand($vs['obrazenia_min'],$vs['obrazenia_max']);
							//minimalnie można zadać 1 ranę
							if($rany < 1) $rany = 1;
							$uzytkownik['zycie'] -= $rany;

							$punktyB += $rany;

							if($uzytkownik['zycie'] < 1){
								$uzytkownik['zycie'] = 0;
								echo "<b>".$vs['nazwa']."</b> <span style='color:#CC0000'>decydującym ciosem</span> powala <b>".$uzytkownik['nazwa']."</b> i wygrywa walkę. <br/>";
								$wynik = 2;
							} else {
								echo "<b>".$vs['nazwa']."</b> zadaje <span style='color:#CC0000'>".$rany."</span> obrażeń przeciwnikowi. <br/>";
							}
						} else {
							echo "<b>".$vs['nazwa']."</b> nie mógł trafić przeciwnika.<br/>";
						}
					}
				} else {
					echo "<b>".$uzytkownik['nazwa']."</b> nie mógł trafić przeciwnika.<br/>";
						$szansa = floor($vs['atak'] / $uzytkownik['obrona'] *100);

						//max szansa na trafienie = 75%, a minimalna 25%
						if($szansa >= 75) $szansa = 75;
						elseif ($szansa < 25) $szansa = 25;

						$rand = rand(1,100);
						if($szansa >= $rand) {
							$rany = rand($vs['obrazenia_min'],$vs['obrazenia_max']);
							//minimalnie można zadać 1 ranę
							if($rany < 1) $rany = 1;
							$uzytkownik['zycie'] -= $rany;

							$punktyB += $rany;

							if($uzytkownik['zycie'] < 1){
								$uzytkownik['zycie'] = 0;
								echo "<b>".$vs['nazwa']."</b> <span style='color:#CC0000'>decydującym ciosem</span> powala <b>".$uzytkownik['nazwa']."</b> i wygrywa walkę. <br/>";
								$wynik = 2;
							} else {
								echo "<b>".$vs['nazwa']."</b> zadaje <span style='color:#CC0000'>".$rany."</span> obrażeń przeciwnikowi. <br/>";
							}
						} else {
							echo "<b>".$vs['nazwa']."</b> nie mógł trafić przeciwnika.<br/>";
						}
				}
			}

			if($wynik == 0){
				//padł remis, nikt nikogo nie dobił, sprawdzamy punkty
				if($punktyA >= $punktyB) $wynik = 1; else $wynik = 2;
				echo "<br/><b> W walce padł remis, wygrywa ten, kto zadał więcej obrażeń</b><br/>";
			}

			if($wynik == 1){
				$punkty = floor($vs['punkty'] / 100) + 3;
				$kasa =  floor($vs['punkty'] / 10) + 100;
				echo "
				<p class='note'>
					Po zwycięskiej walce <b>".$uzytkownik['login']."</b> otrzymuje ".$punkty ." punktów i ".$kasa." kasy. <br/>
				</p><br class='clear'>";

				mysql_query("update pokemon_gracze set akcje = akcje - 25, kasa = kasa + ".$kasa.", punkty = punkty + ".$punkty." where gracz = ".$uzytkownik['gracz']);
				mysql_query("update pokemon_pokemony_gracze set zycie = ".$uzytkownik['zycie'].", ostatnia_walka = ".time()." where gracz_id = ".$uzytkownik['gracz']." and pokemon_id = ".$uzytkownik['aktywny_pokemon']);

				$uzytkownik['akcje'] -= 25;
				$uzytkownik['kasa'] += $kasa;
				$uzytkownik['punkty'] += $punkty;
				$uzytkownik['ostatnia_walka'] = time();

				mysql_query("update pokemon_pokemony_gracze set zycie = ".$vs['zycie']." where gracz_id = ".$vs['gracz']." and pokemon_id = ".$vs['aktywny_pokemon']);

				mysql_query("insert into pokemon_poczta(od, do , typ, tytul, tresc, data) value (1,".$vs['gracz'].",1,'Wyzwano Cię na pojedynek','Przegrałeś pojedynek z graczem ".$uzytkownik['login']."', now())");
				
			} else {
				$punkty = floor($uzytkownik['punkty'] / 100) + 3;
				$kasa =  floor($uzytkownik['punkty'] / 10) + 100;
				echo "
				<p class='note'>
					Pp przegranej walce z  <b>".$vs['nazwa']."</b> opuszczasz arenę ze spuszczoną głową... 
				</p><br class='clear'>";

				mysql_query("update pokemon_gracze set akcje = akcje - 25 where gracz = ".$uzytkownik['gracz']);
				mysql_query("update pokemon_pokemony_gracze set zycie = ".$uzytkownik['zycie'].", ostatnia_walka = ".time()." where gracz_id = ".$uzytkownik['gracz']." and pokemon_id = ".$uzytkownik['aktywny_pokemon']);

				$uzytkownik['akcje'] -= 25;
				$uzytkownik['ostatnia_walka'] = time();

				mysql_query("update pokemon_pokemony_gracze set zycie = ".$vs['zycie']." where gracz_id = ".$vs['gracz']." and pokemon_id = ".$vs['aktywny_pokemon']);
				mysql_query("update pokemon_gracze set kasa = kasa + ".$kasa.", punkty = punkty + ".$punkty." where gracz = ".$vs['gracz']);

				mysql_query("insert into pokemon_poczta(od, do , typ, tytul, tresc, data) value (1,".$vs['gracz'].",1,'Wyzwano Cię na pojedynek','Wygrałeś pojedynek z graczem ".$uzytkownik['login']." i zdobyłeś ".$punkty ." punktów i ".$kasa." kasy', now())");
			}

			
			
		}
	}
}
if(!empty($_GET['walcz'])){
	$_GET['walcz'] = (int)$_GET['walcz'];

	if($_GET['walcz'] == $uzytkownik['gracz']){
		echo "<p class='error'>Nie możesz walczyć sam ze sobą</p><br class='clear'>";
	} elseif($uzytkownik['akcje'] < 25){
		echo "<p class='error'>Posiadasz za mało punktów akcji</p><br class='clear'>";
	} elseif($uzytkownik['zycie'] == 0){
		echo "<p class='error'>Twój pokemon jest ranny</p><br class='clear'>";
	} elseif($uzytkownik['ostatnia_walka'] + 3600 > time()){
		$pozostalo = $uzytkownik['ostatnia_walka'] + 3600 - time();
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
		<p class='error'>Twój pokemon niedawno walczył, daj mu odpocząć. Pozostało: <span id='zegar'></span><script type='text/javascript'>liczCzas(".$pozostalo.")</script> </p><br class='clear'>";
	} else {
		$vs = mysql_fetch_array(mysql_query("select * from pokemon_gracze inner join pokemon_pokemony_gracze on gracz_id = gracz and pokemon_id = aktywny_pokemon where gracz = ".$_GET['walcz']));
		if(empty($vs)){
			echo "<p class='error'>Nie ma takiego gracza!</p><br class='clear'>";
		} elseif($vs['zycie'] ==0){
			echo "<p class='error'>Przeciwnik jest ranny i nie może brać udziału w walce</p><br class='clear'>";
		} else {
			// przebieg walki
			echo "<p class='note'>Wyzwałeś użytkownika <b>".$vs['login']."</b> na pojedynek, do walki stanął <b>".$vs['nazwa']."</b> </p><br class='clear'>

			<table id='rank'>
			<tr>
				<th><b>".$uzytkownik['login']."<b></th>
				<th><b>".$vs['login']."<b></th>
			</tr>
			<tr>
				<td><img src='pokemony/".$uzytkownik['aktywny_pokemon'].".jpg' alt='' width='125px'/></td>
				<td><img src='pokemony/".$vs['aktywny_pokemon'].".jpg' alt='' width='125px'/></td>
			</tr>
			<tr>
				<td>
					<b><i>".$uzytkownik['nazwa']."</i></b>
					<ul>
						<li>atak: ".$uzytkownik['atak']."
						<li>obrona: ".$uzytkownik['obrona']."
						<li>obrażenia: ".$uzytkownik['obrazenia_min']."-".$uzytkownik['obrazenia_max']."
						<li>życie: ".$uzytkownik['zycie']."/".$uzytkownik['zycie_max']."
					</ul>
				</td>
				<td>
					<b><i>".$vs['nazwa']."</i></b>
					<ul>
						<li>atak: ".$vs['atak']."
						<li>obrona: ".$vs['obrona']."
						<li>obrażenia: ".$vs['obrazenia_min']."-".$vs['obrazenia_max']."
						<li>życie: ".$vs['zycie']."/".$vs['zycie_max']."
					</ul>
				</td>
			</tr>
			</table>
			<hr/>
			";
			$runda = 0;
			$punktyA = 0;
			$punktyB = 0;
			$wynik = 0;
			while(($vs['zycie'] > 0) && ($uzytkownik['zycie'] > 0) && ($runda < 20)){
				echo "<br/><br/><b>Runda ".++$runda."</b><br/><br/>";
				$szansa = floor($uzytkownik['atak'] / $vs['obrona'] *100);

				//max szansa na trafienie = 75%, a minimalna 25%
				if($szansa >= 75) $szansa = 75;
				elseif ($szansa < 25) $szansa = 25;

				$rand = rand(1,100);
				if($szansa >= $rand) {
					$rany = rand($uzytkownik['obrazenia_min'],$uzytkownik['obrazenia_max']);
					//minimalnie można zadać 1 ranę
					if($rany < 1) $rany = 1;
					$vs['zycie'] -= $rany;
					$punktyA += $rany;

					if($vs['zycie'] < 1){
						$vs['zycie'] = 0;
						echo "<b>".$uzytkownik['nazwa']."</b> <span style='color:#CC0000'>decydującym ciosem</span> powala <b>".$vs['nazwa']."</b> i wygrywa walkę. <br/>";
						$wynik = 1;
					} else {
						echo "<b>".$uzytkownik['nazwa']."</b> zadaje <span style='color:#CC0000'>".$rany."</span> obrażeń przeciwnikowi. <br/>";
						$szansa = floor($vs['atak'] / $uzytkownik['obrona'] *100);

						//max szansa na trafienie = 75%, a minimalna 25%
						if($szansa >= 75) $szansa = 75;
						elseif ($szansa < 25) $szansa = 25;

						$rand = rand(1,100);
						if($szansa >= $rand) {
							$rany = rand($vs['obrazenia_min'],$vs['obrazenia_max']);
							//minimalnie można zadać 1 ranę
							if($rany < 1) $rany = 1;
							$uzytkownik['zycie'] -= $rany;

							$punktyB += $rany;

							if($uzytkownik['zycie'] < 1){
								$uzytkownik['zycie'] = 0;
								echo "<b>".$vs['nazwa']."</b> <span style='color:#CC0000'>decydującym ciosem</span> powala <b>".$uzytkownik['nazwa']."</b> i wygrywa walkę. <br/>";
								$wynik = 2;
							} else {
								echo "<b>".$vs['nazwa']."</b> zadaje <span style='color:#CC0000'>".$rany."</span> obrażeń przeciwnikowi. <br/>";
							}
						} else {
							echo "<b>".$vs['nazwa']."</b> nie mógł trafić przeciwnika.<br/>";
						}
					}
				} else {
					echo "<b>".$uzytkownik['nazwa']."</b> nie mógł trafić przeciwnika.<br/>";
						$szansa = floor($vs['atak'] / $uzytkownik['obrona'] *100);

						//max szansa na trafienie = 75%, a minimalna 25%
						if($szansa >= 75) $szansa = 75;
						elseif ($szansa < 25) $szansa = 25;

						$rand = rand(1,100);
						if($szansa >= $rand) {
							$rany = rand($vs['obrazenia_min'],$vs['obrazenia_max']);
							//minimalnie można zadać 1 ranę
							if($rany < 1) $rany = 1;
							$uzytkownik['zycie'] -= $rany;

							$punktyB += $rany;

							if($uzytkownik['zycie'] < 1){
								$uzytkownik['zycie'] = 0;
								echo "<b>".$vs['nazwa']."</b> <span style='color:#CC0000'>decydującym ciosem</span> powala <b>".$uzytkownik['nazwa']."</b> i wygrywa walkę. <br/>";
								$wynik = 2;
							} else {
								echo "<b>".$vs['nazwa']."</b> zadaje <span style='color:#CC0000'>".$rany."</span> obrażeń przeciwnikowi. <br/>";
							}
						} else {
							echo "<b>".$vs['nazwa']."</b> nie mógł trafić przeciwnika.<br/>";
						}
				}
			}

			if($wynik == 0){
				//padł remis, nikt nikogo nie dobił, sprawdzamy punkty
				if($punktyA >= $punktyB) $wynik = 1; else $wynik = 2;
				echo "<br/><b> W walce padł remis, wygrywa ten, kto zadał więcej obrażeń</b><br/>";
			}

			if($wynik == 1){
				$punkty = floor($vs['punkty'] / 100) + 3;
				$kasa =  floor($vs['punkty'] / 10) + 100;
				echo "
				<p class='note'>
					Po zwycięskiej walce <b>".$uzytkownik['login']."</b> otrzymuje ".$punkty ." punktów i ".$kasa." kasy. <br/>
				</p><br class='clear'>";

				mysql_query("update pokemon_gracze set akcje = akcje - 25, kasa = kasa + ".$kasa.", punkty = punkty + ".$punkty." where gracz = ".$uzytkownik['gracz']);
				mysql_query("update pokemon_pokemony_gracze set zycie = ".$uzytkownik['zycie'].", ostatnia_walka = ".time()." where gracz_id = ".$uzytkownik['gracz']." and pokemon_id = ".$uzytkownik['aktywny_pokemon']);

				$uzytkownik['akcje'] -= 25;
				$uzytkownik['kasa'] += $kasa;
				$uzytkownik['punkty'] += $punkty;
				$uzytkownik['ostatnia_walka'] = time();

				mysql_query("update pokemon_pokemony_gracze set zycie = ".$vs['zycie']." where gracz_id = ".$vs['gracz']." and pokemon_id = ".$vs['aktywny_pokemon']);

				mysql_query("insert into pokemon_poczta(od, do , typ, tytul, tresc, data) value (1,".$vs['gracz'].",1,'Wyzwano Cię na pojedynek','Przegrałeś pojedynek z graczem ".$uzytkownik['login']."', now())");
				
			} else {
				$punkty = floor($uzytkownik['punkty'] / 100) + 3;
				$kasa =  floor($uzytkownik['punkty'] / 10) + 100;
				echo "
				<p class='note'>
					Pp przegranej walce z  <b>".$vs['nazwa']."</b> opuszczasz arenę ze spuszczoną głową... 
				</p><br class='clear'>";

				mysql_query("update pokemon_gracze set akcje = akcje - 25 where gracz = ".$uzytkownik['gracz']);
				mysql_query("update pokemon_pokemony_gracze set zycie = ".$uzytkownik['zycie'].", ostatnia_walka = ".time()." where gracz_id = ".$uzytkownik['gracz']." and pokemon_id = ".$uzytkownik['aktywny_pokemon']);

				$uzytkownik['akcje'] -= 25;
				$uzytkownik['ostatnia_walka'] = time();

				mysql_query("update pokemon_pokemony_gracze set zycie = ".$vs['zycie']." where gracz_id = ".$vs['gracz']." and pokemon_id = ".$vs['aktywny_pokemon']);
				mysql_query("update pokemon_gracze set kasa = kasa + ".$kasa.", punkty = punkty + ".$punkty." where gracz = ".$vs['gracz']);

				mysql_query("insert into pokemon_poczta(od, do , typ, tytul, tresc, data) value (1,".$vs['gracz'].",1,'Wyzwano Cię na pojedynek','Wygrałeś pojedynek z graczem ".$uzytkownik['login']." i zdobyłeś ".$punkty ." punktów i ".$kasa." kasy', now())");
			}

			
			
		}
	}
}


$przeciwnicy = mysql_query("select * from pokemon_gracze where punkty >= ".$uzytkownik['punkty']." and gracz  != ".$uzytkownik['gracz']." limit 5");

if(mysql_num_rows($przeciwnicy) == 0){
	echo "<p class='note'>Brak przeciwników wyżej w rankingu, skorzystaj z wyszukiwania po nicku</p><br class='clear'>";
} else {
	echo "
	<table id='rank'>
		<tr style='background:#8F8F8F;'>
			<th>Pozycja</th>
			<th>Gracz</th>
			<th></th>
		</tr>
	";

	if($uzytkownik['akcje'] >= 25){
		while($przeciwnik = mysql_fetch_array($przeciwnicy)){
			$i++;
			if($i % 2 == 0) $styl = " style='background:#B2B2B2'"; else $styl="";
			echo "
			<tr align='center' ".$styl.">
				<td>".$i."</td>
				<td>".$przeciwnik['login']."</td>
				<td><a href='walki.php?walcz=".$przeciwnik['gracz']."'>walcz</a></td>
			</tr>";
		}
	} else {
		while($przeciwnik = mysql_fetch_array($przeciwnicy)){
			$i++;
			if($i % 2 == 0) $styl = " style='background:#B2B2B2'"; else $styl="";
			echo "
			<tr align='center' ".$styl.">
				<td>".$i."</td>
				<td>".$przeciwnik['login']."</td>
				<td>za mało punktów akcji</td>
			</tr>";
		}
	}
	echo "</table>";
}

echo "
Jeżeli nie chcesz używać podpowiedzi systemu, to wpisz login użytkownika tutaj:<br/>
<form action='walki.php' method='post'>
login: <input type='text' name='login'/> <input type='submit' value='walcz'>
</form>
";



//pobieramy zawartość prawego bloku
require_once('prawy_blok.php');

//pobieramy stopkę
require_once('dol_strony.php');

//wyłączamy bufor
ob_end_flush();
?> 