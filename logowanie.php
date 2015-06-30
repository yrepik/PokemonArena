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

echo "    <h2>Logowanie</h2><hr/>";
//jeżeli wciśnięto guzik logowania
if(!empty($_POST)){
    //jeżeli podano wszystkie dane
    if (!empty($_POST['login']) && !empty($_POST['haslo'])){
        //zabezpiecz zmienną
        $_POST['login'] = mysql_real_escape_string($_POST['login']);
        //zakoduj hasło
        $_POST['haslo'] = md5($_POST['haslo']);
        

        //pobierz dane gracza
        $gracz = mysql_fetch_array(mysql_query("select gracz, zbanowany from pokemon_gracze where login = '".$_POST['login']."' and haslo = '".$_POST['haslo']."' limit 1"));

        //jeżeli nie pobrano danych, to wyświetl komunikat
        if(empty($gracz)) echo "<p class='error'>Podano niprawidłowe dane</p><br class='clear'>";
		elseif($gracz['zbanowany'] == 1) echo "<p class='error'>ten gracz jest zbanowany</p><br class='clear'>";
        else {
            //jeżeli pobrano dane to przeczyść sesję
            $_SESSION = array();
            //ustaw numer gracza jako wskaźnik w sesji
            $_SESSION['user'] = $gracz['gracz'];

            //przenieś do strony konta gracza
            header('Location: konto.php');
        }
    } else {
        //jeżeli nie podano wszystkich danych
        echo "<p class='error'>Wypełnij wszystkie pola poprawnie</p><br class='clear'>";
    }
}

?>

    
     <p>
        <form action='logowanie.php' method='post'>
            <table>
            <tr>
                <td>login:</td>
                <td><input type='text' name='login' style='width:200px' value='<?php echo $_POST['login'] ?>'/></td>
            </tr>
            <tr>
                <td>hasło:</td>
                <td><input type='password' style='width:200px' name='haslo'/></td>
            </tr>
            <tr>
                <td colspan=2 align='center'>
                    <input type='submit' style='width:100px' value='zaloguj'/>
                </td>
            </tr>
            </table>
        
        </form>
     </p>

<?php

//pobieramy zawartość prawego bloku
require_once('prawy_blok.php');

//pobieramy stopkę
require_once('dol_strony.php');

//wyłączamy bufor
ob_end_flush();
?>