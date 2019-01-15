<?
	include ("include/conn.php");

	$id = $_REQUEST['id'];
	$nome = mysqli_escape_string($db, $_REQUEST['nome']);
	$indirizzo = mysqli_escape_string($db, $_REQUEST['indirizzo']);
	$telefono = mysqli_escape_string($db, $_REQUEST['telefono']);

	$query = "UPDATE cliente SET nome =\"$nome\","."indirizzo =\"$indirizzo\","."telefono=\"$telefono\""."Where id=$id";
	$ris = mysqli_query($db, $query);

	print '	<script language="javascript">	
	         document.location.href="gestioneClienti.php?";
	           </script>';
?>