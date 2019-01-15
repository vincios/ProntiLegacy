<?
	include ('include/conn.php');
	
	$idcer = $_REQUEST['idcer'];		
	
	/*$query = "SELECT nome FROM ceramica WHERE idcer = '".$idcer."'";
	$ris = mysql_query($query,$db); 
	$array = mysql_fetch_array($ris);
	$nome = $array['nome'];*/
	$query = "DELETE FROM ceramica WHERE idcer = '".$idcer."'";
	mysqli_query($db, $query);
	
	/*$query ="DELETE FROM pronticeramiche WHERE ceramica = '".$nome."'";
	mysql_query($query,$db);*/
	
	print '	<script language="javascript">	
	         document.location.href="gestioneceramiche.php?";
	           </script>';
?>