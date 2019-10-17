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

$id = $_REQUEST['id'];
$query = "SELECT * FROM prontidepositi WHERE id = '".$id."'";
$ris = mysqli_query($db, $query);
$array = mysqli_fetch_array($ris);
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

<body>
<?php echo getHeader('depositi') ?>

<table width="100%"  border="0">
    <tr>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td><div align="center" class="Stile1">Modifica ProntoDepositi</div></td>
    </tr>
    <tr>
        <td>&nbsp;</td>
    </tr>
</table>
<form method="post" name="form" action="functionModificaProntoDepositibis.php">
    <input type="hidden" name="id" value="<? print $id ?>">
    <table width="100%"  border="0">
        <tr>
            <td width="32%"><div align="right" class="Stile2">
                    <div align="right">Deposito</div>
                </div></td>
            <td width="68%"><input type="text" name="deposito" value="<? print $array['Deposito'] ?>" maxlength="45"></td>
        </tr>
        <tr>
            <td width="32%"><div align="right" class="Stile2">
                    <div align="right">Ceramica</div>
                </div></td>
            <td width="68%"><input type="text" name="ceramica" value="<? print $array['Ceramica'] ?>" maxlength="45"></td>
        </tr>
        <tr>
            <td><div align="right"><span class="Stile2">Cliente</span></div></td>
            <td><input type="text" name="cliente" value="<? print $array['Cliente'] ?>" maxlength="45"></td>
        </tr>
        <tr>
            <td><div align="right"><span class="Stile2">Quintali</span></div></td>
            <td><input type="text" name="quintali" value="<? print $array['quintali'] ?>" maxlength="45"></td>
        </tr>
        <tr>
            <td><div align="right"><span class="Stile2">Palette</span></div></td>
            <td><input type="text" name="palette" value="<? print $array['palette'] ?>" maxlength="45"></td>
        </tr>
        <tr>
            <td><div align="right"><span class="Stile2">Note</span></div></td>
            <td><input type="text" name="note" value="<? print $array['note'] ?>" maxlength="45"></td>
        </tr>
        <tr>
            <td><div align="right"><span class="Stile2"></span></div></td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td><div align="right"><span class="Stile2"></span></div></td>
            <td><input type="submit" name="Submit" value="Modifica"></td>
        </tr>
    </table>
</form>

</body>
</html>
