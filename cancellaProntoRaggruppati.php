<?
	include ('include/conn.php');
	
	$id = $_REQUEST['id'];		
	$today = getdate();
	
	$giorno =$today['mday'];
	$mese = $today['mon'];
	$anno = $today['year'];
	
	if(!empty($id)){
	$query = "select eliminato from prontiraggruppati where id=$id";
	$query = mysqli_query($db, $query);
	$ris = mysqli_fetch_array($query,MYSQLI_BOTH);
	
	if($ris["eliminato"]==0){
	
	$query = "UPDATE prontiraggruppati set eliminato=1, data_eliminazione='$anno-$mese-$giorno' WHERE id = '".$id."'";
	mysqli_query($db, $query);

	}
	else{
		$query = "DELETE from prontiraggruppati WHERE id = '".$id."'";
		mysqli_query($db, $query);

	}
	}
	if(!isset($_GET["query"])){
			print "<script language=\"javascript\">	
	         document.location.href=\"raggruppati.php\";
	           </script>";
	}
	else{
					print "<script language=\"javascript\">	
	         document.location.href=\"".$_GET["query"]."\";
	           </script>";	
	}
?>