<?
include ("include/conn.php");

$id = $_REQUEST['id'];
$deposito = mysqli_escape_string($db, strtoupper($_REQUEST['deposito']));
$nome = mysqli_escape_string($db, strtoupper($_REQUEST['ceramica']));
$cliente = mysqli_escape_string($db, strtoupper($_REQUEST['cliente']));
$quintali = mysqli_escape_string($db, $_REQUEST['quintali']);
$palette = mysqli_escape_string($db, $_REQUEST['palette']);
$note = mysqli_escape_string($db, $_REQUEST['note']);

//$query = "UPDATE prontidepositi SET deposito =\"$deposito\","."Ceramica =\"$nome\","."cliente =\"$cliente\","."quintali=\"$quintali\","."note=\"$note\""."Where id=$id";
$query = "UPDATE prontidepositi SET Deposito='$deposito', Ceramica='$nome', Cliente='$cliente', quintali='$quintali', palette='$palette', note='$note' WHERE id=$id";
$ris = mysqli_query($db, $query) or die(mysqli_error($db));

print '	<script language="javascript">
	         document.location.href="depositi.php?";
	           </script>';
?>