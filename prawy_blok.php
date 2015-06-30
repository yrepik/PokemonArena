		</div>
	<div id="sidebar">
	<?php
	if(!empty($uzytkownik['gracz'])){
		echo "
		<h2>Statystyki</h2>
		<table style='width:100%'>
		<tr>
			<td style='border-bottom:dashed 1px #000'>$$</td>
			<td align='right' style='border-bottom:dashed 1px #000'>".number_format($uzytkownik['kasa'],0,',','.')."</td>
		</tr>
		<tr>
			<td style='border-bottom:dashed 1px #000'>akcje</td>
			<td align='right' style='border-bottom:dashed 1px #000'>".$uzytkownik['akcje']." / ".$uzytkownik['akcje_max']."</td>
		</tr>
		<tr>
			<td style='border-bottom:dashed 1px #000'>punkty</td>
			<td align='right' style='border-bottom:dashed 1px #000'>".$uzytkownik['punkty']."</td>
		</tr>
		</table>

		<h2>Aktywny Pokemon</h2>
		<table style='width:100%'>
		<tr>
			<td style='border-bottom:dashed 1px #000'>imię</td>
			<td style='border-bottom:dashed 1px #000' align='right'><b>".$uzytkownik['nazwa']."</b></td>
		</tr>
		<tr>
			<td style='border-bottom:dashed 1px #000'>atak</td>
			<td style='border-bottom:dashed 1px #000' align='right'>".$uzytkownik['atak']."</td>
		</tr>
		<tr>
			<td style='border-bottom:dashed 1px #000'>obrona</td>
			<td style='border-bottom:dashed 1px #000' align='right'>".$uzytkownik['obrona']."</td>
		</tr>
		<tr>
			<td style='border-bottom:dashed 1px #000'>życie</td>
			<td style='border-bottom:dashed 1px #000' align='right'>".$uzytkownik['zycie']."/".$uzytkownik['zycie_max']."</td>
		</tr>
		<tr>
			<td style='border-bottom:dashed 1px #000'>obrażenia</td>
			<td style='border-bottom:dashed 1px #000' align='right'>".$uzytkownik['obrazenia_min']."-".$uzytkownik['obrazenia_max']."</td>
		</tr>
		<tr>
			<td colspan=2><img src='pokemony/".$uzytkownik['pokemon_id'].".jpg' alt=''/></td>
		</tr>
		</table>
		";
	}
	?>
      
   </div>
    