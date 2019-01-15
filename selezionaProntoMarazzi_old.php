<?
	include ("include/conn.php");
	include("include/libreria.php");
	
	$id = $_REQUEST['id'];		
	
	$query = "SELECT selezionato from prontimarazzi WHERE id = '" . $id . "'";
	$sel = mysqli_query($db, $query) or die( mysqli_error($db) );
	$sel = mysqli_fetch_array($sel, MYSQLI_BOTH);
	$sel = $sel['selezionato'];
	
	if($sel == 0) $sel = 1;
	else $sel = 0;
	
	$query = "UPDATE prontimarazzi set selezionato=". $sel . " WHERE id = '" . $id . "'";
	mysqli_query($db, $query) or die( mysqli_error($db) );

	//VaiURL("marazzi.php");
	print '	<script language="javascript">	
	         document.location.href="marazzi.php";
	         </script>';	
?>
