<? 
	include("include/conn.php");
    include("include/header.php");
	include("auth.php");
	
    if (isset($_COOKIE['USERNAME']) && isset($_COOKIE['PASSWORD']))
    {
        // Get values from superglobal variables
        $USERNAME = $_COOKIE['USERNAME'];
        $PASSWORD = $_COOKIE['PASSWORD'];

        $CheckSecurity = new auth();
        $check = $CheckSecurity->page_check($USERNAME, $PASSWORD);
		
		if ($check == false)
		{
			echo "Utente non autorizzato";	
			exit;
		}
	}
	else
	{
			echo "Utente non autorizzato";	
			exit;
	}

	if ((isset($_REQUEST['nome'])) || (isset($_REQUEST['deposito'])))
	{		
		$ok = 1;
	}
	else
	{
		$ok = 0;				
		$_REQUEST['nome'] = "";
		$_REQUEST['deposito'] = "";		
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
.Stile2 {
	font-size: 12px;
	font-weight: bold;
}
.Stile4 {
	color: #FF0000;
	font-weight: bold;
}
-->
</style></head>

<body>
<?php echo getHeader('ragno') ?>

<?	
	if ($ok==1)
	{
		if (isset($_REQUEST['nome']))
		{
			$query = " SELECT * FROM prontiragno WHERE cliente = '".mysqli_escape_string($db, $_REQUEST['nome'])."' ORDER by deposito";
			$ris = mysqli_query($db, $query);
			?>
		  <table width="100%"  border="0">
		  <tr>
			<td width="36%" height="24">&nbsp;</td>
			<td width="29%" height="30">&nbsp;</td>
			<td width="35%">&nbsp;</td>
		  </tr>
		  <tr>
			<td>&nbsp;</td>
			<td><div align="center" class="Stile4">Risultati Ricerca : <? print $_REQUEST['nome'] ?></div></td>
			<td>&nbsp;</td>
		  </tr>
		  <tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		  </tr>
		  <tr>
			<td align="center" width="36%" style="color:#000066"><strong>DEPOSITO</strong></td>
			<td align="center" width="29%" style="color:#000066"><strong>Q.LI</strong></td>
			<td align="center" width="35%" style="color:#000066"><strong>NOTE</strong></td>
		  </tr>		
		<?
			
			while ($array = mysqli_fetch_array($ris))
			{
				$deposito = $array['Deposito'];
				$quintali = $array['quintali'];
			    $note = $array['note'];	
				?>
				<tr>
				<td style="font-size:12px " align="center"><? print $deposito ?></td>
				<td style="font-size:12px " align="center"><? print $quintali ?></td>
				<td style="font-size:12px " align="center"><? print $note ?></td>
				</tr>
				<?
			}
			?>
			</table>
			<?				
		}
		elseif (isset($_REQUEST['deposito']))
		{
			$query2 = " SELECT * FROM prontiragno WHERE deposito = '".mysqli_escape_string($db, $_REQUEST['deposito'])."' ORDER by cliente";
			$ris = mysqli_query($db, $query2);
			?>
		  <table width="100%"  border="0">
		  <tr>
			<td width="36%" height="24">&nbsp;</td>
			<td width="29%" height="30">&nbsp;</td>
			<td width="35%">&nbsp;</td>
		  </tr>
		  <tr>
			<td>&nbsp;</td>
			<td><div align="center" class="Stile4">Risultati Ricerca : <? print $_REQUEST['deposito'] ?></div></td>
			<td>&nbsp;</td>
		  </tr>
		  <tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		  </tr>
		  <tr>
			<td align="center" width="36%" style="color:#000066"><strong>CLIENTE</strong></td>
			<td align="center" width="29%" style="color:#000066"><strong>Q.LI</strong></td>
			<td align="center" width="35%" style="color:#000066"><strong>NOTE</strong></td>
		  </tr>		
		<?
			
			while ($array = mysqli_fetch_array($ris))
			{
				$cliente = $array['Cliente'];
				$quintali = $array['quintali'];
			    $note = $array['note'];	
				?>
				<tr>
				<td style="font-size:12px " align="center"><? print $cliente ?></td>
				<td style="font-size:12px " align="center"><? print $quintali ?></td>
				<td style="font-size:12px " align="center"><? print $note ?></td>
				</tr>
				<?
			}
			?>
			</table>
			<?					
		}	
		$_REQUEST['nome']="";
		$_REQUEST['deposito']="";				
	}
?>
<form method="post" name="form" action="<? print $_SERVER['PHP_SELF'] ?>">
<table width="100%"  border="0">
  <tr>
    <td width="36%" height="30">&nbsp;</td>
    <td width="25%" height="20">&nbsp;</td>
    <td width="39%" height="30">&nbsp;</td>
  </tr>
  <tr>
    <td height="30"><div align="right" class="Stile2">Ricerca per cliente </div></td>
    <td height="30"><input type="text" name="nome" value="<? print $_REQUEST['nome'] ?>"></td>
    <td height="30"><input name="submit" type="submit" id="cliente" value="Invia"></td>
  </tr>
</table>
</form>
<form method="post" name="form" action="<? print $_SERVER['PHP_SELF'] ?>">
<table width="100%"  border="0">
  <tr>
    <td width="36%" height="30">&nbsp;</td>
    <td width="25%" height="20">&nbsp;</td>
    <td width="39%" height="30">&nbsp;</td>
  </tr>
  <tr>
    <td height="30"><div align="right" class="Stile2">Ricerca per deposito </div></td>
    <td height="30"><input type="text" name="deposito" value="<? print $_REQUEST['deposito'] ?>"></td>
    <td height="30"><input name="submit" type="submit" id="ceramica2" value="Invia"></td>
  </tr>
</table>
</form>
</body>
</html>
