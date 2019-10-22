<?
include("include/conn.php");
$query="select * from ceramica where idcer='".$_REQUEST['idcer']."'";

$ris=mysqli_query($db, $query);
$array=mysqli_fetch_array($ris);

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <title>Amministratore</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <link rel="stylesheet" href="stiliAdmin.css" type="text/css">
    <style type="text/css">
        <!--
        body {
            margin-left: 0px;
            margin-top: 0px;
            margin-right: 0px;
            margin-bottom: 0px;
        }
        -->
    </style></head>

<body>
<table width="100%"  border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td align="center" valign="middle" bordercolor="#CCE0A3"><table width="350" height="250"  border="0" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
                <tr>
                    <td width="10" class="testoPiccolo">&nbsp;</td>
                    <td width="100" height="50" class="testoPiccolo">&nbsp;</td>
                    <td valign="middle"><span class="barra">
          <div align="left"><? print $array['nome']; ?></div>
        </span></td>
                    <td width="10">&nbsp;</td>
                </tr>
                <tr>
                    <td class="testo">&nbsp;</td>
                    <td class="barra"><div align="left" class="testo">Indirizzo</div></td>
                    <td class="testo-nero"><div align="left"><? print $array['indirizzo']; ?> </div></td>
                </tr>
                <tr>
                    <td class="testo">&nbsp;</td>
                    <td class="barra"><div align="left">Telefono</div></td>
                    <td class="testo-nero"><div align="left"><? print $array['telefono']; ?> </div></td>
                </tr>
                <tr>
                    <td class="testo">&nbsp;</td>
                    <td class="barra"><div align="left">Note</div></td>
                    <td class="testo-nero"><div align="left"><? print $array['note']; ?> </div></td>
                </tr>
                <tr>
                    <td class="testo">&nbsp;</td>
                    <td class="barra"><div align="left">IdGruppo</div></td>
                    <td class="testo-nero"><div align="left"><? print $array['idgruppo']; ?> </div></td>
                </tr>
            </table>
            <table width="350" height="50" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td valign="middle"><div align="center">
                            <input type="submit" name="Submit" value="chiudi" onClick="window.close();">
                        </div></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
