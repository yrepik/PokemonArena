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

echo "    <h2>Rejestracja</h2><hr/>";
//jeżeli wciśnięto guzik rejestruj
if(!empty($_POST)){
    //jeżeli wypełniono wszystkie dane
    if (!empty($_POST['login']) && !empty($_POST['haslo']) && !empty($_POST['haslo2']) && ($_POST['haslo'] == $_POST['haslo2']) && !empty($_POST['email'])){
        $_POST['login'] = mysql_real_escape_string($_POST['login']); //zabezpiecz zmienną
        $_POST['email'] = mysql_real_escape_string($_POST['email']); //zabezpiecz zmienną
        
        if(strlen($_POST['login']) <5 ) {
            echo "<p class='error'>login za krótki [5-10 znaków]</p><br class='clear'>";
        } elseif(strlen($_POST['login']) >10 ) {
            echo "<p class='error'>login za długi [5-10 znaków] </p><br class='clear'>";
        } elseif(strlen($_POST['haslo']) <5 ) {
            echo "<p class='error'>hasło za krótkie [5-10 znaków]</p><br class='clear'>";
        } elseif(strlen($_POST['haslo']) >10 ) {
            echo "<p class='error'> hasło za długie [5-10 znaków] </p><br class='clear'>";
        } elseif(strlen($_POST['email']) <8 ) {
            echo "<p class='error'>email za krótki [8-24 znaków]</p><br class='clear'>";
        } elseif(strlen($_POST['email']) >24 ) {
            echo "<p class='error'> email za długi [8-24 znaków] </p><br class='clear'>";
        } else {
            $_POST['haslo'] = md5($_POST['haslo']);
            $_POST['haslo2'] = md5($_POST['haslo2']);

            //pobierz dane dla danego loginu i emaila
            $zajety = mysql_fetch_array(mysql_query("select count(*) as blad from pokemon_gracze where login = '".$_POST['login']."' or email ='".$_POST['email']."'    "));

            if(empty($zajety)) {
                //jeżeli z jakiegoś powodu nie udało się pobrać wyniku zapytania
                echo "<p class='error'>nieoczekiwany błąd</p><br class='clear'>";
            } elseif($zajety['blad'] > 0){
                //jeżeli znaleziono już rekordy o takim loginie lub emailu
                echo "<p class='error'>login lub email zajęty</p><br class='clear'>";
            } else {
                //login i email wolne, można dodać nowego użytkownika
                mysql_query("insert into pokemon_gracze (login, haslo, email) value (                '".$_POST['login']."','".$_POST['haslo']."','".$_POST['email']."')");
                if(mysql_insert_id() == 0) echo "<p class='error'>Nieoczekiwany błąd</p><br class='clear'>";
                else {
                    echo "<p class='note'>poprawnie zarejestrowano gracza</p><br class='clear'>";
                }
            }
        }
    } else {
        echo "<p class='error'>Wypełnij wszystkie pola poprawnie</p><br class='clear'>";
    }
    

}
?>
    

    
     <p>
        <form action='rejestracja.php' method='post'>
            <table>
            <tr>
                <td>login [5-10 znaków]:</td>
                <td><input type='text' style='width:200px' name='login' value='<?php echo $_POST['login'] ?>'/></td>
            </tr>
            <tr>
                <td>hasło [5-10 znaków]:</td>
                <td><input type='password' style='width:200px' name='haslo'/></td>
            </tr>
            <tr>
                <td>powtórz hasło:</td>
                <td><input type='password' style='width:200px' name='haslo2'/></td>
            </tr>
            <tr>
                <td>email [8-24 znaków]:</td>
                <td><input type='text' style='width:200px' name='email'/></td>
            </tr>
            <tr>
				<td></td>
                <td align='center'>
                    <input type='submit' style='width:100px' value='rejestruj'/>
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