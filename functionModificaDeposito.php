<?
include ("include/conn.php");

$id = $_REQUEST['id'];
$nome = mysqli_escape_string($db, $_REQUEST['nome']);
$indirizzo = mysqli_escape_string($db, $_REQUEST['indirizzo']);
$telefono = mysqli_escape_string($db, $_REQUEST['telefono']);
$note = mysqli_escape_string($db, $_REQUEST['note']);
$colore = mysqli_escape_string($db, $_REQUEST["colore"]);

$query = "UPDATE depositi SET nome='$nome', indirizzo ='$indirizzo', telefono='$telefono', note='$note', colore='$colore' WHERE id=$id";
$ris = mysqli_query($db, $query);

print '	<script language="javascript">	
	         document.location.href="gestioneDepositi.php?";
	           </script>';
?>