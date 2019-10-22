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

if (isset($_REQUEST['ceramica']))
    $ok = 1;
else
{
    $ok = 0;
    $_REQUEST['ceramica'] = "";
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
<?php echo getHeader('ceramiche') ?>

<table width="100%"  border="0">
    <tr>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td><div align="center" class="Stile1">Inserimento ProntoCeramiche </div></td>
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
            <td width="68%"><input type="text" name="ceramica" value="<? print $_REQUEST['ceramica'] ?>" maxlength="50"></td>
        </tr>
        <tr>
            <td><div align="right"><span class="Stile2">Cliente</span></div></td>
            <td><input type="text" name="cliente" value="<? print $_REQUEST['cliente'] ?>" maxlength="50"></td>
        </tr>
        <tr>
            <td><div align="right"><span class="Stile2">Quintali</span></div></td>
            <td><input type="text" name="quintali" value="<? print $_REQUEST['quintali'] ?>" maxlength="50"></td>
        </tr>
        <tr>
            <td><div align="right"><span class="Stile2">Palette</span></div></td>
            <td><input type="text" name="palette" value="<? print $_REQUEST['palette'] ?>" maxlength="50"></td>
        </tr>
        <tr>
            <td><div align="right"><span class="Stile2">Note</span></div></td>
            <td><input type="text" name="note" value="<? print $_REQUEST['note'] ?>" maxlength="50"></td>
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

    $cer = $_REQUEST['ceramica'];
    $query = "SELECT * FROM ceramica WHERE nome = '".mysqli_escape_string($db, $cer)."'";
    $ris = mysqli_query($db, $query);
    $n = mysqli_affected_rows($db);

    if ($n > 0){
        $ceramica = mysqli_escape_string($db, strtoupper($_REQUEST['ceramica']));
        $cliente = mysqli_escape_string($db, strtoupper($_REQUEST['cliente']));
        $quintali = mysqli_escape_string($db, $_REQUEST['quintali']);
        $palette = mysqli_escape_string($db, $_REQUEST['palette']);
        $note = mysqli_escape_string($db, $_REQUEST['note']);
        $query="insert into pronticeramiche (Ceramica,cliente,quintali,palette,note) values('$ceramica', '$cliente', '$quintali', '$palette', '$note')";
        mysqli_query($db, $query) or die (mysqli_error($db));

        print '	<script language="javascript">	
	         document.location.href="ceramiche.php?";
	           </script>';
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
