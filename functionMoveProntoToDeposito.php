<?php
include('include/conn.php');
include('include/libreria.php');

$from = null;
$id = null;
$to = null;
$redirect = null;

if(isset($_REQUEST['from'])) {
    $from = $_REQUEST['from'];
}

if(isset($_REQUEST['to'])) {
    $to = $_REQUEST['to'];
}

if(isset($_REQUEST['id'])) {
    $id = $_REQUEST['id'];
}

if(isset($_REQUEST['redirectUrl'])) {
    $redirect = $_REQUEST['redirectUrl'];
}

if($id === null || $to === null || $from === null) {
    printError('Impossibile eseguire l\'operazione: dati mancanti', $redirect);
    exit();
}

$fromTable = null;
$deleteURL = null;

switch ($from) {
    case 'ceramiche':
        $fromTable = "pronticeramiche";
        $deleteURL = "cancellaProntoCeramiche.php";
        break;
    case 'depositi':
        $fromTable = "prontidepositi";
        $deleteURL = "cancellaProntoDepositi.php";
        break;
    case 'raggruppati':
        $fromTable = "prontiraggruppati";
        $deleteURL = "cancellaProntoRaggruppati.php";
        break;
    case 'marazzi':
        $fromTable = "prontimarazzi";
        $deleteURL = "cancellaProntoMarazzi.php";
        break;
    default:
        $fromTable = null; break;
}

if($fromTable === null) {
    printError("Tabella di partenza errata", $redirect);
    exit();
}

$query = "SELECT * FROM $fromTable WHERE id=$id";

$exec = mysqli_query($db, $query);
$res = mysqli_fetch_array($exec, MYSQLI_BOTH);

if(empty($res)) {
    printError("Impossibile trovare " . $from . " con id " . $id, $redirect);
    exit();
}

$prontoCeramica = null;

switch ($from) {
    case 'ceramiche':
    case 'depositi':
        $prontoCeramica = $res['Ceramica']; break;
    case 'raggruppati':
        $prontoCeramica = $res['materiale']; break;
    case 'marazzi':
        $prontoCeramica = $res['Deposito']; break;
    default:
        $prontoCeramica = null;
}

if($prontoCeramica === null) {
    printError("Tabella di partenza errata", $redirect);
    exit();
}

$prontoCeramica = mysqli_escape_string($db, $prontoCeramica);
$prontoCliente = $from == "ceramiche" ? mysqli_escape_string($db, $res['cliente']) : mysqli_escape_string($db, $res['Cliente']);
$prontoAutista = mysqli_escape_string($db, $res['autista']);
$prontoQuintali = mysqli_escape_string($db, $res['quintali']);
$prontoPalette = mysqli_escape_string($db, $res['palette']);
$prontoNote = mysqli_escape_string($db, $res['note']);
$prontoSelezionato = intval($res['selezionato']);

$query = "INSERT INTO prontidepositi(Deposito, Ceramica, Cliente, autista, quintali, palette, note, selezionato) VALUES " .
    "('$to', '$prontoCeramica', '$prontoCliente', '$prontoAutista','$prontoQuintali', '$prontoPalette', '$prontoNote', $prontoSelezionato)";

$res = mysqli_query($db, $query);

if(!$res) {
    printError(mysqli_error($db), $redirect);
    exit();
}
/*
$query = "DELETE FROM $fromTable where id=$id";
$res = mysqli_query($db, $query);*/

if($deleteURL !== null) {
    $deleteURL .= "?id=" . $id ."&query=" . $redirect;
    header("location:" . $deleteURL);
} else {
    header("location:" . $redirect);
}