<? 
	include("include/conn.php");
	include("include/header.php");
	$nome = $_REQUEST['nome'];
	
	$query = "SELECT * FROM prontiragno WHERE deposito = '". mysqli_escape_string($db, $nome)."' ORDER by cliente";
	$ris = mysqli_query ($db, $query);
	
	if (isset($_REQUEST['id']))
	{		
		$id = $_REQUEST['id'];				
		$query = "DELETE FROM prontiragno WHERE id = '".$id."'";
		mysqli_query($db, $query);
		if (mysqli_affected_rows($db)>0)
			print '<script language="javascript"> location.reload() </script>';	
	}
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Documento senza titolo</title>
    <link rel="stylesheet" type="text/css" href="css/general.css">
<style type="text/css">
<!--
body,td,th {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	color: #000000;
}
body {
	background-color: #EAEAEA;
}
.Stile1 {color: #FF0000}
a:link {
	color: #000000;
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #000000;
}
a:hover {
	text-decoration: none;
}
a:active {
	text-decoration: none;
}
-->
</style></head>

<body>
<?php echo getHeader('ragno') ?>

<table width="100%" height="50"  border="0">
  <tr>
    <td width="68%"></td>
	<td width="12%"></td>
    <td width="20%"><a href="inserisciProntoRagnobis.php?deposito=<? print $nome ?>"><strong><u>INSERISCI PRONTO</u></strong></a></td>
  </tr>
</table>
<table width="100%"  border="0" cellspacing="0" cellpadding="0" bordercolor="#FFFFFF">
   <tr bordercolor="EAEAEA">
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		  <td align="center">&nbsp;</td>
  </tr>  
   <tr bordercolor="EAEAEA">
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		  <td align="center">&nbsp;</td>
  </tr>  
  <tr>
  	<td width="180" bordercolor="999999" align="center"><strong>Deposito</strong></td>
    <td width="180" bordercolor="999999" align="center"><strong>Cliente</strong></td>
    <td width="180" bordercolor="999999" align="center"><strong>D.D.S</strong></td>
    <td width="70" bordercolor="999999" align="center"><strong>Q.li</strong></td>
    <td width="150" bordercolor="999999" align="center"><strong>Note</strong></td>
    <td width="25" align="center"></td>
  </tr> 
  <tr bordercolor="EAEAEA">
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		  <td align="center">&nbsp;</td>
  </tr>   
</table>
<table width="100%"  border="0" cellspacing="0" cellpadding="0" bordercolor="#FFFFFF">
  <? while ($array = mysqli_fetch_array($ris)){
				$id = $array['id'];	
				$deposito = $array['Deposito'];										
				$dds = $array['dds'];
				$cliente = $array['Cliente'];
				$quintali = $array['quintali'];
				$note = $array['note'];							
  ?>     
  <tr>
  	<td width="180" bordercolor="999999" style="font-size:12px"><strong><? print $deposito?></strong></td>
    <td width="180" bordercolor="999999" style="font-size:12px"><a href="modificaProntoRagnobis.php?id=<? print $id ?>&nome=<? print $deposito?>"><? print $cliente ?></a></td>
    <td width="180" bordercolor="999999" style="font-size:12px"><? print $dds ?></td>
    <td width="70" bordercolor="999999" style="font-size:12px" align="center"><? print $quintali ?></td>
    <td width="150" bordercolor="999999" style="font-size:12px" align="center"><? print $note ?></td>
    <td width="25" align="center" bordercolor="999999"><a href="gestioneProntoRagno.php?id=<? print $id ?>&nome=<? print $deposito ?>"><img src="img/cancellaAdminPiccolo.gif" width="16" height="16" border="0"></a></td>
  </tr>
  <?
		}
  ?>

</body>
</html>
