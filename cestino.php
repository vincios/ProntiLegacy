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
    <title>Documento senza titolo</title>
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
        -->
    </style>
    <link rel="stylesheet" type="text/css" href="css/general.css">
</head>

<body>
<?php echo getHeader('cestino') ?>

<table width="100%" height="100%"  border="0">
    <tr>
        <td height="60" colspan="3">&nbsp;</td>
    </tr>
    <tr>
        <td width="27%" height="30"><div align="center"><strong></strong></div></td>
        <td width="49%" bordercolor="#000000" bgcolor="#FFFF99"><div align="center"><strong><a href="cestinoceramiche.php">Cestino Pronti Ceramiche </a></strong></div></td>
        <td width="24%" align="left" valign="middle"><form name="form1" method="post" action="functionSvuotaCestinoProntiCeramiche.php">
                <input type="submit" name="Submit" value="Svuota">
            </form></td>
    </tr>
    <tr>
        <td height="30">&nbsp;</td>
        <td height="30" bordercolor="#000000" bgcolor="#FFFF99"><div align="center"><strong><a href="cestinodepositi.php">Cestino Pronti Depositi</a></strong></div></td>
        <td width="24%" align="left" valign="middle"><form name="form1" method="post" action="functionSvuotaCestinoProntiDepositi.php">
                <input type="submit" name="Submit" value="Svuota">
            </form></td>
    </tr>
    <tr>
        <td height="30">&nbsp;</td>
        <td height="30" bordercolor="#000000" bgcolor="#FFFF99"><div align="center"><strong><a href="cestinoraggruppati.php">Cestino Pronti Raggruppati</a> </strong></div></td>
        <td width="24%" align="left" valign="middle"><form name="form1" method="post" action="functionSvuotaCestinoProntiRaggruppati.php">
                <input type="submit" name="Submit" value="Svuota">
            </form></td>
    </tr>
    <tr>
        <td height="30">&nbsp;</td>
        <td height="30" bordercolor="#000000" bgcolor="#FFFF99"><div align="center"><strong><a href="cestinomarazzi.php">Cestino Pronti Marazzi </a> </strong></div></td>
        <td width="24%" align="left" valign="middle"><form name="form1" method="post" action="functionSvuotaCestinoProntiMarazzi.php">
                <input type="submit" name="Submit" value="Svuota">
            </form></td>
    </tr>
    <tr>
        <td height="30">&nbsp;</td>
        <td height="30" bordercolor="#000000">&nbsp;</td>
        <td height="30">&nbsp;</td>
    </tr>
</table>
</body>
</html>
