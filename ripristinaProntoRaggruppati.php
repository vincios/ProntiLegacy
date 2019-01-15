<?
	include ('include/conn.php');
	
	$id = $_REQUEST['id'];		
	$today = getdate();
	
	$giorno =$today['mday'];
	$mese = $today['mon'];
	$anno = $today['year'];
	
	if(!empty($id)){
	$query = "select data_eliminazione from prontiraggruppati where id=$id";
	$query = mysqli_query($db, $query);
	$ris = mysqli_fetch_array($query,MYSQLI_BOTH);

	$m = substr($ris[0],5,2);
	$d = substr($ris[0],-2);
	$y = substr($ris[0],0,4);
	//Confronto date
	$data_elim = mktime(0,0,0, $m, $d, $y);
	$data_oggi = mktime(0,0,0,$mese,$giorno,$anno);
	
	if($data_elim!=$data_oggi){ echo("Il record non può essere ripristinato perchè non è stato eliminato oggi!"); exit;}
	
	$query = "UPDATE prontiraggruppati set eliminato=0, data_eliminazione='00-00-0000' WHERE id = '".$id."'";
	mysqli_query($db, $query);

	}
		print "<script language=\"javascript\">	
	      document.location.href=\"".$_GET["query"]."\";
	    </script>";

?>
