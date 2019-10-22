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

if (isset($_REQUEST['ceramica']) && isset($_REQUEST['cliente']))
    $ok = 1;
else
{
    $ok = 0;
    if (!isset($_REQUEST['ceramica']))
        $_REQUEST['ceramica'] = "";
    $_REQUEST['materiale'] = "";
    $_REQUEST['cliente'] = "";
    $_REQUEST['quintali'] = "";
    $_REQUEST['palette'] = "";
    $_REQUEST['note'] = "";
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

<body>
<?php echo getHeader('raggruppati') ?>

<table width="100%"  border="0">
    <tr>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td><div align="center" class="Stile1">Inserimento ProntoDeposito </div></td>
    </tr>
    <tr>
        <td>&nbsp;</td>
    </tr>
</table>
<form method="post" name="form" action="<? print $_SERVER['PHP_SELF'] ?>">
    <table width="100%"  border="0">
        <tr>
            <td width="32%"><div align="right" class="Stile2">
                    <div align="right">Ceramica</div>
                </div></td>
            <td colspan="4"><input type="text" name="ceramica" value="<? print $_REQUEST['ceramica'] ?>" maxlength="50"></td>
        </tr>
        <tr>
            <td><div align="right" class="Stile2">
                    <div align="right">Materiale</div>
                </div></td>
            <td colspan="4"><input type="text" name="materiale" value="<? print $_REQUEST['materiale'] ?>" maxlength="50"></td>
        </tr>
        <tr>
            <td><div align="right"><span class="Stile2">Cliente</span></div></td>
            <td colspan="4"><input type="text" name="cliente" value="<? print $_REQUEST['cliente'] ?>" maxlength="50"></td>
        </tr>
        <tr>
            <td><div align="right"><span class="Stile2">Quintali</span></div></td>
            <td colspan="4"><input type="text" name="quintali" value="<? print $_REQUEST['quintali'] ?>" maxlength="50"></td>
        </tr>
        <tr>
            <td><div align="right"><span class="Stile2">Palette</span></div></td>
            <td colspan="4"><input type="text" name="palette" value="<? print $_REQUEST['palette'] ?>" maxlength="50"></td>
        </tr>
        <tr>
            <td><div align="right"><span class="Stile2">Note</span></div></td>
            <td colspan="4"><input type="text" name="note" value="<? print $_REQUEST['note'] ?>" maxlength="50"></td>
        </tr>
        <tr>
            <td><div align="right"><span class="Stile2"></span></div></td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td><div align="right"><span class="Stile2"></span></div></td>
            <td width="6%"><input type="submit" name="Submit" value="Inserisci"></td>
            <td width="3%">&nbsp;</td>
            <td width="6%" bordercolor="#000000" bgcolor="#CCCCCC"><div align="center" class="Stile3"><a href="gestioneProntoRaggruppati.php?nome=<? print $_REQUEST['ceramica'] ?>"><strong>Finito</strong></a></div></td>
            <td width="53%">&nbsp;</td>
        </tr>
    </table>
</form>

<?
if ($ok == 1){

    $ceramica = $_REQUEST['ceramica'];
    $query = "SELECT * FROM ceramica WHERE nome = '".mysqli_escape_string($db, $ceramica)."'";
    $ris = mysqli_query($db, $query);
    $n = mysqli_affected_rows($db);

    if ($n > 0){
        $ceramica = mysqli_escape_string($db, strtoupper($_REQUEST['ceramica']));
        $cliente = mysqli_escape_string($db, strtoupper($_REQUEST['cliente']));
        $materiale = mysqli_escape_string($db, strtoupper($_REQUEST['materiale']));
        $quintali = mysqli_escape_string($db, $_REQUEST['quintali']);
        $palette = mysqli_escape_string($db, $_REQUEST['palette']);
        $note = mysqli_escape_string($db, $_REQUEST['note']);

//        $query="insert into prontiraggruppati (Ceramica,materiale,Cliente,quintali,note) values(\"" . mysqli_escape_string($db, $ceramica) . "\",'". mysqli_escape_string($db, $_REQUEST['materiale']) ."','". mysqli_escape_string($db, $_REQUEST['cliente']) ."','". mysqli_escape_string($db, $_REQUEST['quintali']) ."','". mysqli_escape_string($db, $_REQUEST['note']) ."')";
        $query="INSERT INTO prontiraggruppati (Ceramica,materiale,Cliente,quintali,palette,note) VALUES ('$ceramica', '$materiale', '$cliente', '$quintali', '$palette', '$note')";
        mysqli_query($db, $query) or die( mysqli_error($db) );

        print '	<script language="javascript">	
	         document.location.href="inserisciProntoRaggruppatibis.php?ceramica='.$ceramica.'"</script>';

    }
    else{

        print '	<script language="javascript">	
	         alert("Ceramica errata o non esistente");
	           </script>';

    }

}
?>

</body>
</html>
