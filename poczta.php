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

echo "<h2>Poczta</h2><hr/>";
switch($_GET['act']){
	case 'nowa':
		if(!empty($_POST['do']) && !empty($_POST['tytul']) && !empty($_POST['tresc'])){
			$do = mysql_real_escape_string(trim($_POST['do']));
			$tytul = mysql_real_escape_string(trim($_POST['tytul']));
			$tresc = mysql_real_escape_string(trim(nl2br($_POST['tresc'])));

			$do_gracz_id = mysql_fetch_array(mysql_query("select gracz from pokemon_gracze where login = '".$do."'"));
			

			if(empty($do_gracz_id)) echo "<p class='error'>nie ma takiego gracza</p><br class='clear'>"; else {
				mysql_query($q="
				insert into pokemon_poczta (od, do, typ, tytul, tresc, data) 
				values 
				(".$uzytkownik['gracz'].",".$do_gracz_id['gracz'].",1,'".$tytul."','".$tresc."',now()),
				(".$uzytkownik['gracz'].",".$do_gracz_id['gracz'].",2,'".$tytul."','".$tresc."',now())
				");

				echo "<p class='note'>wysłano</p><br class='clear'>";
			}
		}


		echo "
			<form action='poczta.php?act=nowa' method='post'>
			<table>
			<tr>
				<td>Do: </td>
				<td><input type='text' name='do' value='".$_GET['do']."'/></td>
			</tr>
			<tr>
				<td>Tytuł:</td>
				<td><input type='text' name='tytul' style='width:500px'/></td>
			</tr>
			<tr>
				<td>Treść:</td>
				<td align='right'><textarea name='tresc' style='width:500px; height:150px;'></textarea></td>
			</tr>
			<tr>
				<td colspan=2 align='center'><input type='submit' value='wyślij'/></td>
			</tr>
			</table>
			</form>	
				
		";
	break;
	case 'wyslane':
		if(!empty($_GET['usun'])){
			$_GET['usun'] = (int)$_GET['usun'];
			$del = mysql_query("delete from pokemon_poczta where id = ".$_GET['usun']." and od = ".$uzytkownik['gracz']." and typ = 2");
			if(mysql_affected_rows() == 1) echo "<p class='note'>usunięto wiadomość</p><br class='clear'>"; else echo "<p class='error'>nie ma takiej wiadomości</p><br class='clear'>";
		}
		$poczta = mysql_query("select p.*, login from pokemon_poczta p inner join pokemon_gracze on p.do = gracz where p.od = ".$uzytkownik['gracz']." and typ = 2 order by p.id desc");
		
		while($msg= mysql_fetch_array($poczta)){
			echo "
			<div>
				<h2><i>".$msg['login']."</i>: ".$msg['tytul']."<span style='float:right'>".$msg['data']."</span></h2>
			</div>
			<div>
				".$msg['tresc']."
			</div>
			<div>
				 <a href='poczta.php?act=wyslane&usun=".$msg['id']."'>usuń</a>
			</div>
			<br style='clear:both'/>
			";
		}
	break;
	default:
		//domyślnie pokaż folder odebrane
		if(!empty($_GET['usun'])){
			$_GET['usun'] = (int)$_GET['usun'];
			$del = mysql_query("delete from pokemon_poczta where id = ".$_GET['usun']." and do = ".$uzytkownik['gracz']." and typ = 1");
			if(mysql_affected_rows() == 1) echo "<p class='note'>usunięto wiadomość</p><br class='clear'>"; else echo "<p class='error'>nie ma takiej wiadomości</p><br class='clear'>";
		}
		$poczta = mysql_query("select p.*, login from pokemon_poczta p inner join pokemon_gracze on p.od = gracz where p.do = ".$uzytkownik['gracz']." and typ = 1 order by p.status asc, p.id desc" );
		
		while($msg= mysql_fetch_array($poczta)){
			if($msg['status'] == 0) $nowa = " <span style='color:#66FF00'>nowa!</span>"; else $nowa = '';
			echo "
			<div>
				<h2><i>".$msg['login']."</i>: ".$msg['tytul'].$nowa."<span style='float:right'>".$msg['data']."</span></h2>
			</div>
			<div>			
				".$msg['tresc']."
			</div>
			<div>
				<a href='poczta.php?act=nowa&do=".$msg['login']."'>odpisz</a> | <a href='poczta.php?act=odebrane&usun=".$msg['id']."'>usuń</a>
			</div>
			<br style='clear:both'/>
			";
		}

		mysql_query("update pokemon_poczta set status = 1 where do =".$uzytkownik['gracz']." and typ = 1 and status = 0");
		
	break;
}



//pobieramy zawartość prawego bloku
require_once('prawy_blok.php');

//pobieramy stopkę
require_once('dol_strony.php');

//wyłączamy bufor
ob_end_flush();
?> 