<?
	include ("include/conn.php");
	
	$id = $_REQUEST['id'];
	$ceramica = mysqli_escape_string($db, strtoupper($_REQUEST['ceramica']));
	$materiale = mysqli_escape_string($db, strtoupper($_REQUEST['materiale']));
	$cliente = mysqli_escape_string($db, strtoupper($_REQUEST['cliente']));
	$quintali = mysqli_escape_string($db, $_REQUEST['quintali']);
	$note = mysqli_escape_string($db, $_REQUEST['note']);
	
	$query = "UPDATE prontiraggruppati SET ceramica =\"$ceramica\","."materiale =\"$materiale\","."cliente =\"$cliente\","."quintali=\"$quintali\","."note=\"$note\""."Where id=$id";
	$ris = mysqli_query($db, $query);
	
	print '	<script language="javascript">	
	         document.location.href="raggruppati.php?";
	           </script>';
?>