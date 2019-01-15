<?
	include ("include/conn.php");
	
	$id = $_REQUEST['id'];
	$nome = mysqli_escape_string($db, $_REQUEST['nome']);
	$indirizzo = mysqli_escape_string($db, $_REQUEST['indirizzo']);
	$telefono = mysqli_escape_string($db, $_REQUEST['telefono']);
	$idgruppo = mysqli_escape_string($db, $_REQUEST['idgruppo']);
	$colore = mysqli_escape_string($db, $_REQUEST["colore"]);
	
	$query = "UPDATE ceramica SET nome =\"$nome\","."indirizzo =\"$indirizzo\","."telefono=\"$telefono\","."idgruppo=\"$idgruppo\","."colore=\"$colore\" "."Where idcer=$id";
	$ris = mysqli_query($db, $query);
	
	print '	<script language="javascript">	
	         document.location.href="gestioneceramiche.php?";
	           </script>';
?>