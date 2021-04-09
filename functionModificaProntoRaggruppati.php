<?
include ("include/conn.php");

$id = $_REQUEST['id'];
$ceramica = mysqli_escape_string($db, strtoupper($_REQUEST['ceramica']));
$materiale = mysqli_escape_string($db, strtoupper($_REQUEST['materiale']));
$cliente = mysqli_escape_string($db, strtoupper($_REQUEST['cliente']));
$autista = mysqli_escape_string($db, strtoupper($_REQUEST['autista']));
$quintali = mysqli_escape_string($db, $_REQUEST['quintali']);
$palette = mysqli_escape_string($db, $_REQUEST['palette']);
$note = mysqli_escape_string($db, $_REQUEST['note']);

//$query = "UPDATE prontiraggruppati SET ceramica =\"$ceramica\","."materiale =\"$materiale\","."cliente =\"$cliente\","."quintali=\"$quintali\","."note=\"$note\""."Where id=$id";
$query = "UPDATE prontiraggruppati SET Ceramica='$ceramica', materiale='$materiale', Cliente='$cliente', autista='$autista', quintali='$quintali', palette='$palette', note='$note' WHERE id=$id";
$ris = mysqli_query($db, $query) or die(mysqli_error($db));

print '	<script language="javascript">	
	         document.location.href="raggruppati.php?";
	           </script>';
?>