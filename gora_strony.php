<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Pokemon Arena!!!</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" href="css/style.css" type="text/css" />
<script type="text/javascript" src="js/mootools-release-1.11.js"></script>
<script src="js/dropDownMenu.js" type="text/javascript"></script>
<!--[if IE 7]><style>#dropdownMenu li ul ul {margin-left: 100px;}</style><![endif]-->
</head>
<body>
<div id="container">
  <div id="wrapper">
    <div id="top">
      <ul class='login'>
	  <?php
	  if(empty($uzytkownik['gracz'])){
        echo "<li class='left'>&nbsp;</li>
        <li>Witaj gościu</li>
        <li>|</li>
        <li><a href='logowanie.php'>Logowanie</a></li>
        <li>|</li>
        <li><a href='rejestracja.php'>Rejestracja</a></li>";
	  } else {
		echo "<li class='left'>&nbsp;</li>
        <li>Witaj ".$uzytkownik['login']."</li>
        <li>|</li>
        <li><a href='wyloguj.php'>Wyloguj</a></li>";
	  }
	  ?>
      </ul>
    </div>