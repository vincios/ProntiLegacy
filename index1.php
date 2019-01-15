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

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Benvenuto</title>
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
<?php echo getHeader('') ?>

<table width="100%" height="100%"  border="0">
  <tr>
    <td height="30">&nbsp;</td>
  </tr>
  <tr>
    <td height="100"><div align="center"><strong><span class="Stile1">BENVENUTO </span></strong></div></td>
  </tr>
</table>
</body>
</html>
