<? 
	include ("include/conn.php");
	include("include/header.php");
	$query = "SELECT * FROM marazzi ORDER by nome";
	$ris = mysqli_query($db, $query);
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
.Stile2 {font-size: 12px}
-->
</style></head>
<script language="JavaScript">
function ApriPopup(url,w,h)
{
   	var l = Math.floor((screen.width-w)/2);
   	var t = Math.floor((screen.height-h)/2);

   	window.open(url,"","width=" + w + ",height=" + h + ",top=" + t + ",left=" + l);
	return (false);
}
</script>

<body>
<?php echo getHeader('gestione') ?>

<table width="100%"  border="0">
  <tr>
    <td width="83%" height="100">&nbsp;</td>
    <td width="17%"><form name="form1" method="post" action="inserisciMarazzi.php">
	<input type="submit" name="Submit" value="Inserisci Marazzi">
	</form></td>
  </tr>
</table>
<table width="96%"  border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="225" valign="top">
			  <table width="222" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td width="35" height="30"><div align="center"><img src="img/cancellaAdmin.gif" width="26" height="25"></div></td>
                  <td width="187"><div align="left" class="Stile2">Cancella Marazzi </div></td>
                </tr>
				<tr>
                  <td width="35" height="30"><div align="center"><img src="img/vediAdmin.gif" width="26" height="25"></div></td>
                  <td width="187"><div align="left" class="Stile2">Visualizza Info </div></td>
                </tr>
				<tr>
                  <td width="35" height="30"><div align="center"><img src="img/nuovoAdmin.gif" width="26" height="25"></div></td>
                  <td width="187"><div align="left" class="Stile2">Modifica Marazzi </div></td>
                </tr>
              </table></td>
              <td width="520" valign="top">			  
			  <table width="317" align="left" border="0" cellpadding="0" cellspacing="2">
                <?
					while ($array = mysqli_fetch_array($ris)) {
						$nome = $array['nome'];
						$id = $array['id'];
				?>
                <tr>
                  <td width="263" height="27" align="left" bgcolor="#FFFFFF" class="barra"><strong><? print $nome ?></strong></td>				 
                  <td width="48" bgcolor="#FFFFFF" align="center"><a href="cancellaMarazzi.php?id=<? print $id ?>"><img src="img/cancellaAdmin.gif" border="0"></a></td>
                  <td width="48" bgcolor="#FFFFFF" align="center"><img src="img/adminView.gif" border="0" onClick="return ApriPopup('infoMarazzi.php?id=<? print $id; ?>',350,300)"></a></td>
                  <td width="48" bgcolor="#FFFFFF" align="center"><a href="modificaMarazzi.php?id=<? print $id ?>"><img src="img/nuovoAdmin.gif" width="26" height="25" border="0"></a></td>  
			    </tr>				                                
                <?
			}
			?>
              </table></td>
            </tr>
</table>
</body>
</html>
