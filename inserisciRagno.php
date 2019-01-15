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

	if (isset($_REQUEST['nome']))
		$ok = 1;
	else
	{
		$ok = 0;		
		$_REQUEST['nome'] = "";
		$_REQUEST['indirizzo'] = "";
		$_REQUEST['telefono'] = "";
		$_REQUEST['colore'] = "FFFFFF";		
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
.Stile1 {
	font-size: 12px;
	font-weight: bold;
}
.Stile2 {font-size: 12px}
-->
</style></head>

<body onLoad="form.colore.value='<?php echo($_REQUEST["colore"]); ?>'">
<?php echo getHeader('gestione') ?>

<table width="100%"  border="0">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><div align="center" class="Stile1">Inserimento Ragno </div></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
<form method="post" name="form" action="<? print $_SERVER['PHP_SELF'] ?>">
<table width="100%"  border="0">
  <tr>
    <td width="32%"><div align="right" class="Stile2">
      <div align="right">Nome</div>
    </div></td>
    <td width="68%"><input type="text" name="nome" value="<? print $_REQUEST['nome'] ?>" maxlength="25"></td>
  </tr>
  <tr>
    <td><div align="right"><span class="Stile2">Indirizzo</span></div></td>
    <td><input type="text" name="indirizzo" value="<? print $_REQUEST['indirizzo'] ?>" maxlength="100"></td>
  </tr>
  <tr>
    <td><div align="right"><span class="Stile2">Telefono</span></div></td>
    <td><input type="text" name="telefono" value="<? print $_REQUEST['telefono'] ?>" maxlength="20"></td>
  </tr>
  <tr>
    <td><div align="right"><span class="Stile2">Colore</span></div></td>
    <td><select name="colore">
      <option value="FFFFFF">Nessuno</option>
      <option value="FF9900">Arancione</option>
      <option value="0000FF">Blu</option>
      <option value="00FFFF">Celeste</option>
      <option value="FFFF00">Giallo</option>
      <option value="FF00FF">Magenta</option>
      <option value="000000">Nero</option>
      <option value="FF0000">Rosso</option>
      <option value="00FF00">Verde</option>
    </select></td>
  </tr>  
  <tr>
    <td><div align="right"><span class="Stile2"></span></div></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><div align="right"><span class="Stile2"></span></div></td>
    <td><input type="submit" name="Submit" value="Inserisci"></td>
  </tr>
</table>
</form>

<?
	if ($ok == 1){
		$_REQUEST['nome']=strtoupper($_REQUEST['nome']);
		$query="insert into ragno (nome,indirizzo,telefono,colore) values(\"" . mysqli_escape_string($db, $_REQUEST['nome']) . "\",'". mysqli_escape_string($db, $_REQUEST['indirizzo']) ."','". mysqli_escape_string($db, $_REQUEST['telefono']) ."', \"" . mysqli_escape_string($db, $_REQUEST["colore"]). "\")";
		mysqli_query($db, $query) or die( mysqli_error($db) );
		
		print '	<script language="javascript">	
	         document.location.href="gestioneragno.php?";
	           </script>';
	}	
?>
		
</body>
</html>
