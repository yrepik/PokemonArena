<?php
if(!empty($_POST['kody'])){
	$kody = explode("\n",$_POST['kody']);
	$query = "insert into pokemon_kody (kod) values ";
	$query2 = "";
	if(is_array($kody)){
		foreach($kody as $kod){
			$kod = trim($kod);
			if(!empty($kod)) $query2 .="('".$kod."'),";
		}
	}

	if(!empty($query2)){
		$query2 = substr($query2, 0, - 1);
		mysql_query($query.$query2);
		echo "<p class='note'>dodano</p><br class='clear'>";
	} else echo "<p class='error'>błąd</p><br class='clear'>>";
}

$kodow = mysql_fetch_array(mysql_query("select count(*) as ile from pokemon_kody where status = 0"));
?>
<b>Wprowadź nowe kody VIP</b><br/>
Każdy kod zapisz w nowej linijce, wpisuj <b>TYLKO</b> liczby i litery<hr/>
W bazie jest <b><?php echo $kodow['ile'] ?></b> nieużytych kodów<hr/>
<form action='admin.php?act=vip' method='post'>
<textarea name='kody' style='width:400px; height:200px;'></textarea><br/>
<input type='submit' value='dodaj kody' style='width:80px'/>
</form>