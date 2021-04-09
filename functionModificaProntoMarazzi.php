<?
include ("include/conn.php");

$id = $_REQUEST['id'];
$deposito = mysqli_escape_string($db, strtoupper($_REQUEST['deposito']));
$dds = mysqli_escape_string($db, $_REQUEST['dds']);
$cliente = mysqli_escape_string($db, strtoupper($_REQUEST['cliente']));
$autista = mysqli_escape_string($db, strtoupper($_REQUEST['autista']));
$quintali = mysqli_escape_string($db, $_REQUEST['quintali']);
$palette = mysqli_escape_string($db, $_REQUEST['palette']);
$note = mysqli_escape_string($db, $_REQUEST['note']);

//$query = "UPDATE prontimarazzi SET deposito =\"$deposito\","."cliente =\"$cliente\","."dds =\"$dds\","."quintali=\"$quintali\","."note=\"$note\""."Where id=$id";
$query = "UPDATE prontimarazzi SET Deposito='$deposito', Cliente='$cliente', autista='$autista', dds='$dds', quintali='$quintali', palette='$palette', note='$note' WHERE id=$id";
$ris = mysqli_query($db, $query) or die(mysqli_error($db));

print '	<script language="javascript">	
	         document.location.href="marazzi.php?";
	           </script>';
?>