<?
	include ('include/conn.php');
		
	$query = "DELETE FROM prontidepositi WHERE eliminato=1";
	mysqli_query($db, $query) or die( mysqli_error($db) );

	
	print "<script language=\"javascript\">	
	  document.location.href=\"cestino.php\";
	</script>";
	
?>
