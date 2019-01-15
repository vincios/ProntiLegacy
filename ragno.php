<?
include("include/conn.php");
include("include/libreria.php");
include("include/color_picker_block.php");
include("include/header.php");

VerificaUtente(); //Verifico se l'utente connesso è un utente autorizzato

$fileName = basename(__FILE__, ".php");

$today = getdate();
$date = $today['mday'] . " " . $today['month'];

$query = "SELECT prontiragno.id,prontiragno.Deposito,prontiragno.Cliente,prontiragno.quintali,prontiragno.dds,prontiragno.note,ragno.indirizzo,prontiragno.selezionato,ragno.colore FROM prontiragno JOIN ragno WHERE prontiragno.deposito=ragno.nome ORDER by deposito,cliente,dds";
$ris = mysqli_query($db, $query) or die(mysqli_error($db));
$num = mysqli_num_rows($ris);
$cont = 0;
$tot = 0;
$tot_complessivo = 0;
$i = 0;
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <title>RAGNO</title>
    <link rel="stylesheet" type="text/css" href="css/general.css">
    <style type="text/css">
        <!--
        body, td, th {
            font-family: Verdana, Arial, Helvetica, sans-serif;
            color: #000000;
        }

        body {
            background-color: #FFFFFF;
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

        .Titolo {
            font-size: 18px;
            font-weight: bold;
        }

        -->
    </style>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <link rel="stylesheet" type="text/css" href="styleCheck.css">
    <script type="application/javascript" src="functions.js"></script>
</head>
<body>
<div class="page-content">
    <?php echo getHeader('ragno') ?>

    <div class="page-content">
        <table width="100%" height="80" border="0">
            <tr>
                <td width="68%">
                    <div align="center" class="Titolo"><u>RAGNO : <? print $date ?></u></div>
                </td>
                <td width="12%">
                    <form name="form1" method="post" action="ricercaragno.php">
                        <input type="submit" name="Submit" value="Ricerca">
                    </form>
                </td>
                <td width="20%">
                    <form name="form1" method="post" action="inserisciProntoRagno.php">
                        <input type="submit" name="Submit" value="Inserisci Pronto">
                    </form>
                </td>
            </tr>
        </table>
        <table width="100%" border="1" cellspacing="0" cellpadding="0" bordercolor="#FFFFFF">
            <tr>
                <td width="200" bordercolor="999999" align="center"><strong>Deposito</strong></td>
                <td width="180" bordercolor="999999" align="center"><strong>Cliente</strong></td>
                <td width="165" bordercolor="999999" align="center"><strong>D.D.S</strong></td>
                <td width="70" bordercolor="999999" align="center"><strong>Q.li</strong></td>
                <td width="120" bordercolor="999999" align="center"><strong>Note</strong></td>
                <td width="25" align="center"></td>
            </tr>
        </table>
        <table width="100%" border="0" cellspacing="0" cellpadding="0" bordercolor="#FFFFFF">
            <? while ($i <= $num) {
                $array = mysqli_fetch_array($ris);
                $id = $array['id'];
                @$cliente2 = $cliente;
                @$deposito2 = $deposito;
                $deposito = $array['Deposito'];
                $cliente = $array['Cliente'];
                $dds = $array['dds'];
                $quintali = $array['quintali'];
                $note = $array['note'];
                $indirizzo = $array['indirizzo'];
                $sel = $array['selezionato'];
                $colore = $array['colore'];
                $cont++;
                $i++;
                if ($deposito != $deposito2) {
                    ?>
                    <? if ($cont > 1) { ?>
                        <tr bordercolor="FFFFFF">
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td width="120" bordercolor="999999" style="font-size:12px" align="center">
                            <strong><? print "TOT : " . $tot ?></strong></td>
                        </tr><? }
                    $tot_complessivo += $tot;
                    $cont = 0;
                    $tot = 0; ?>
                    <tr bordercolor="FFFFFF">
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td align="center">&nbsp;</td>
                    </tr>
                    <?
                    $tot = $tot + $quintali;
                    if ($cliente != $cliente2) {

                        ?>
                        <tr id="datarow-<? print $id ?>">
                            <td width="210" bordercolor="999999" style="font-size:12px "><a
                                        href="gestioneProntoRagno.php?nome=<? print $deposito ?>"><strong><? print $deposito ?>
                                </a></strong><br><? print $indirizzo ?></br></td>
                            <td class="colorable" width="180" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                                style="font-size:12px "><a
                                        href="modificaProntoRagno.php?id=<? print $id ?>"><? print $cliente ?></a></td>
                            <td class="colorable" width="160" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                                style="font-size:12px "><? print $dds ?></td>
                            <td class="colorable" width="70" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                                style="font-size:12px" align="center"><? print $quintali ?></td>
                            <td class="colorable" width="120" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                                style="font-size:12px"
                                align="center" <? if (($note == "URGENTE") || ($note == "TASSATIVO")) { ?> style="color:#FF0000 " <? } ?>><? print $note ?></td>
                            <td width="35" align="center" bordercolor="999999">
                                <a href="cancellaProntoRagno.php?id=<? print $id ?>">
                                    <img src="img/cancellaAdminPiccolo.gif" width="16" height="16" border="0">
                                </a>
                                <div class="evidenzia"onmouseover="showPicker(this)" onmouseout="hidePicker(this)">
                                    <img src="img/seleziona.jpg" width="16" height="16" border="0">
                                    <?php print getColorPicker($fileName, $id); ?>
                                </div>
                            </td>
                            <td <?php if ($colore != "") echo("bgcolor=\"$colore\" ") ?> width="3"></td>
                        </tr>
                        <?
                    } else {
                        ?>
                        <tr id="datarow-<? print  id ?>">
                            <td width="210" bordercolor="999999" style="font-size:12px "><a
                                        href="gestioneProntoRagno.php?nome=<? print $deposito ?>"><strong><? print $deposito ?></strong></a>
                            </td>
                            <td class="colorable" width="180" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                                style="font-size:12px "><a
                                        href="modificaProntoRagno.php?id=<? print $id ?>"><? print $cliente ?></a></td>
                            <td class="colorable" width="160" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                                style="font-size:12px "><? print $dds ?></td>
                            <td class="colorable" width="70" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                                style="font-size:12px" align="center"><? print $quintali ?></td>
                            <td class="colorable" width="120" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                                style="font-size:12px"
                                align="center" <? if (($note == "URGENTE") || ($note == "TASSATIVO")) { ?> style="color:#FF0000 " <? } ?>><? print $note ?></td>
                            <td width="35" align="center" bordercolor="999999">
                                <a href="cancellaProntoRagno.php?id=<? print $id ?>">
                                    <img src="img/cancellaAdminPiccolo.gif" width="16" height="16" border="0">
                                </a>
                                <div class="evidenzia" onmouseout="hidePicker(this)" onmouseover="showPicker(this)">
                                    <img src="img/seleziona.jpg" width="16" height="16" border="0">
                                    <?php print getColorPicker($fileName, $id); ?>
                                </div>
                            </td>
                            <td <?php if ($colore != "") echo("bgcolor=\"$colore\" ") ?> width="3"></td>
                        </tr>
                        <?
                    }
                } else {
                    $tot = $tot + $quintali;
                    if ($cliente != $cliente2) {

                        ?>
                        <tr id="datarow-<? print $id ?>">
                            <td width="210" bordercolor="999999"></td>
                            <td class="colorable" width="180" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                                style="font-size:12px "><a
                                        href="modificaProntoRagno.php?id=<? print $id ?>"><? print $cliente ?></a></td>
                            <td class="colorable" width="160" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                                style="font-size:12px "><? print $dds ?></td>
                            <td class="colorable" width="70" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                                style="font-size:12px" align="center"><? print $quintali ?></td>
                            <td class="colorable" width="120" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                                style="font-size:12px"
                                align="center" <? if (($note == "URGENTE") || ($note == "TASSATIVO")) { ?> style="color:#FF0000 " <? } ?>><? print $note ?></td>
                            <td width="35" align="center" bordercolor="999999">
                                <a href="cancellaProntoRagno.php?id=<? print $id ?>">
                                    <img src="img/cancellaAdminPiccolo.gif" width="16" height="16" border="0">
                                </a>
                                <div class="evidenzia" onmouseover="showPicker(this)" onmouseout="hidePicker(this)">
                                    <img src="img/seleziona.jpg" width="16" height="16" border="0">
                                    <?php print getColorPicker($fileName, $id); ?>
                                </div>
                            </td>
                            <td <?php if ($colore != "") echo("bgcolor=\"$colore\" ") ?> width="3"></td>
                        </tr>
                        <?
                    } else {
                        ?>
                        <tr id="datarow-<? print $id ?>">
                            <td width="210" bordercolor="999999"></td>
                            <td class="colorable" width="180" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                                style="font-size:12px "><a
                                        href="modificaProntoRagno.php?id=<? print $id ?>"><? print $cliente ?></a></td>
                            <td class="colorable" width="160" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                                style="font-size:12px "><? print $dds ?></td>
                            <td class="colorable" width="70" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                                style="font-size:12px" align="center"><? print $quintali ?></td>
                            <td class="colorable" width="120" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                                style="font-size:12px"
                                align="center" <? if (($note == "URGENTE") || ($note == "TASSATIVO")) { ?> style="color:#FF0000 " <? } ?>><? print $note ?></td>
                            <td width="35" align="center" bordercolor="999999">
                                <a href="cancellaProntoRagno.php?id=<? print $id ?>">
                                    <img src="img/cancellaAdminPiccolo.gif" width="16" height="16" border="0">
                                </a>
                                <div class="evidenzia" onmouseout="hidePicker(this)" onmouseover="showPicker(this)">
                                    <img src="img/seleziona.jpg" width="16" height="16" border="0">
                                    <?php print getColorPicker($fileName, $id); ?>
                                </div>
                            </td>
                            <td <?php if ($colore != "") echo("bgcolor=\"$colore\" ") ?> width="3"></td>
                        </tr>
                        <?
                    }

                }
            }
            ?>
        </table>
        <hr>
        <table width="100%">
            <tr>
                <td width="33%">&nbsp;</td>
                <td width="33%">&nbsp;</td>
                <td><?php echo("<b>TOTALE COMPLESSIVO:</b> $tot_complessivo"); ?></td>
            </tr>
        </table>

    </div>
    </body>
</html>
