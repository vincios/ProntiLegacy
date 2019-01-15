<?
	include ('include/conn.php');
	
	$id = $_REQUEST['id'];		
	
	$query = "DELETE FROM cliente WHERE id = '".$id."'";
	mysqli_query($db, $query);
	
	print '	<script language="javascript">	
	         document.location.href="gestioneclienti.php?";
	           </script>';
?>
