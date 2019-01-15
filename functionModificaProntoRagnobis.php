<?
	include("include/conn.php");
	
	$id = $_REQUEST['id'];
	$deposito = mysqli_escape_string($db, strtoupper($_REQUEST['deposito']));
	$dds = mysqli_escape_string($db, $_REQUEST['dds']);
	$cliente = mysqli_escape_string($db, strtoupper($_REQUEST['cliente']));
	$quintali = mysqli_escape_string($db, $_REQUEST['quintali']);
	$note = mysqli_escape_string($db, $_REQUEST['note']);
	
	$query = "UPDATE prontiragno SET deposito =\"$deposito\","."Cliente =\"$cliente\","."dds=\"$dds\","."quintali=\"$quintali\","."note=\"$note\""."Where id=$id";
	$ris = mysqli_query($db, $query);
	
	print '	<script language="javascript">	
	         document.location.href="gestioneProntoRagno.php?nome='.$deposito.'";
	           </script>';
?>