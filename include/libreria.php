<?php
include("auth.php");

//LIBRERIA DI FUNZIONI PHP PER TRASPORTI MENZIONE
$COLORE_SEL = array('#FFF', '#FFCC33', '#ff4500', '#1e90ff', '#00fa9a'); //COLORE PER LA SELEZIONE DELLE RIGHE
$COLORE_SEL_INDEXES = array(
    'nothing' => 0,
    'yellow' => 1,
    'red' => 2,
    'blue' => 3,
    'green' => 4
); //COLORE PER LA SELEZIONE DELLE RIGHE

//Restituisce il dominio in cui è pubblicato il sito
function Dominio(){
    return "http://localhost/TrasportiMenzione/Programma/";
}

//Reindirizza il browser sull'url passato come parametro
function VaiURL($Url)
{
    header ("Location: " . Dominio() . $Url);
}

//VerificaUtente() - Verifica se l'utente connesso ha i diritti di accesso
function VerificaUtente(){

    if (isset($_COOKIE['USERNAME']) && isset($_COOKIE['PASSWORD']))
    {

        // Get values from superglobal variables
        $USERNAME = $_COOKIE['USERNAME'];
        $PASSWORD = $_COOKIE['PASSWORD'];

        $CheckSecurity = new auth();
        $check = $CheckSecurity->page_check($USERNAME, $PASSWORD);

        if ($check == false)
        {
            echo "Utente non autorizzato";
            exit;
        }
    }
    else
    {
        echo "Utente non autorizzato";
        exit;
    }

}

//news
function printError($error, $redirectUrl) {
    print "<h3>" . $error . "</h3>";
    print "<br>";
    print "<a href=\"" . $redirectUrl . "\"> Torna indietro </a>";
}

?>