<?
include("include/conn.php");

$id = $_REQUEST['id'];
$materiale = mysqli_escape_string($db, $_REQUEST['materiale']);
$nome = mysqli_escape_string($db, strtoupper($_REQUEST['ceramica']));
$cliente = mysqli_escape_string($db, strtoupper($_REQUEST['cliente']));
$autista = mysqli_escape_string($db, strtoupper($_REQUEST['autista']));
$quintali = mysqli_escape_string($db, $_REQUEST['quintali']);
$palette = mysqli_escape_string($db, $_REQUEST['palette']);
$note = mysqli_escape_string($db, $_REQUEST['note']);

//$query = "UPDATE prontiraggruppati SET ceramica =\"$nome\","."materiale=\"$materiale\","."cliente =\"$cliente\","."quintali=\"$quintali\","."note=\"$note\""."Where id=$id";
$query = "UPDATE prontiraggruppati SET Ceramica='$nome', materiale='$materiale', Cliente='$cliente', autista='$autista', quintali='$quintali', palette='$palette', note='$note' WHERE id=$id";
$ris = mysqli_query($db, $query) or die(mysqli_error($db));

print '	<script language="javascript">	
	         document.location.href="gestioneProntoRaggruppati.php?nome='.$nome.'";
	           </script>';
?>