<?
	include ('include/conn.php');
	
	$id = $_REQUEST['id'];		
	$today = getdate();
	
	$giorno =$today['mday'];
	$mese = $today['mon'];
	$anno = $today['year'];

	if(isset($_GET['Dal']) && isset($_GET['Al'])){
		$Dal = $_GET['Dal'];
		$Al = $_GET['Al'];
		if(!preg_match('/^[0-9]{1,2}\-[0-9]{1,2}\-[0-9]{4}/',$Dal) || !preg_match('/^[0-9]{2}\-[0-9]{1,2}\-[0-9]{4}/',$Al)){ echo("La data deve essere specificata nel formato gg-mm-aaaa!"); exit;}
		$Dal_giorno = strtok($Dal,'-');
		$Dal_mese = strtok('-');
		$Dal_anno = substr($Dal,-4);
		$Al_giorno = strtok($Al,'-');
		$Al_mese = strtok('-');
		$Al_anno = substr($Al,-4);
		
		$query = "DELETE FROM prontiraggruppati WHERE eliminato=1 and data_eliminazione between '$Dal_anno-$Dal_mese-$Dal_giorno' and '$Al_anno-$Al_mese-$Al_giorno'";
		mysqli_query($db, $query) or die( mysqli_error($db) );

	
		$query = "DELETE FROM prontiraggruppati WHERE eliminato=1 and data_eliminazione between '$Dal_anno-$Dal_mese-$Dal_giorno' and '$Al_anno-$Al_mese-$Al_giorno'";
		mysqli_query($db, $query) or die( mysqli_error($db) );
	
	}
	elseif(isset($_GET['Giorno'])){
		$Giorno = $_GET['Giorno'];
		$Giorno_giorno = strtok($Giorno,'-');
		$Giorno_mese = strtok('-');
		$Giorno_anno = substr($Giorno,-4);
		
		if(!preg_match('/^[0-9]{1,2}\-[0-9]{1,2}\-[0-9]{4}/',$Giorno)){ echo("La data deve essere specificata nel formato gg-mm-aaaa!"); exit;}		
		$query = "DELETE FROM prontiraggruppati WHERE eliminato=1 and data_eliminazione='$Giorno_anno-$Giorno_mese-$Giorno_giorno'";
		mysqli_query($db, $query) or die( mysqli_error($db) );

	
		$query = "DELETE FROM prontiraggruppati WHERE eliminato=1 and data_eliminazione='$Giorno_anno-$Giorno_mese-$Giorno_giorno'";
		mysqli_query($db, $query) or die( mysqli_error($db) );

	}
	else{
		echo("Errore passaggio parametri GET!");
		exit;
	}
			print "<script language=\"javascript\">	
	         document.location.href=\"cestino.php\";
	           </script>";
	
?>
