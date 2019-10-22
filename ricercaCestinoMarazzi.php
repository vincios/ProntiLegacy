<?
include("include/conn.php");
include("include/libreria.php");
include("include/header.php");

VerificaUtente(); //Verifico se l'utente connesso è un utente autorizzato

$today = getdate();
$date = $today['mday'] . " " . $today['month'];

if (isset($_GET['Dal']) && isset($_GET['Al'])) {
    $Dal = $_GET['Dal'];
    $Al = $_GET['Al'];
    if (!preg_match('/^[0-9]{1,2}\-[0-9]{1,2}\-[0-9]{4}/', $Dal) || !preg_match('/^[0-9]{2}\-[0-9]{1,2}\-[0-9]{4}/', $Al)) {
        echo("La data deve essere specificata nel formato gg-mm-aaaa!");
        exit;
    }
    $Dal_giorno = strtok($Dal, '-');
    $Dal_mese = strtok('-');
    $Dal_anno = substr($Dal, -4);
    $Al_giorno = strtok($Al, '-');
    $Al_mese = strtok('-');
    $Al_anno = substr($Al, -4);

    $query = "SELECT prontimarazzi.id,prontimarazzi.Deposito,prontimarazzi.Cliente,prontimarazzi.quintali,prontimarazzi.palette,prontimarazzi.dds,prontimarazzi.note,marazzi.indirizzo,marazzi.telefono,marazzi.note as noteMar,prontimarazzi.selezionato,marazzi.colore FROM prontimarazzi JOIN marazzi WHERE prontimarazzi.deposito=marazzi.nome and eliminato=1 and data_eliminazione between '$Dal_anno-$Dal_mese-$Dal_giorno' and '$Al_anno-$Al_mese-$Al_giorno' ORDER by deposito,cliente,dds";
    $ris = mysqli_query($db, $query) or die(mysqli_error($db));
    $num = mysqli_num_rows($ris);
    $cont = 0;
    $tot = 0;
    $tot_complessivo = 0;
    $i = 0;
} elseif (isset($_GET['Giorno'])) {
    $Giorno = $_GET['Giorno'];
    $Giorno_giorno = strtok($Giorno, '-');
    $Giorno_mese = strtok('-');
    $Giorno_anno = substr($Giorno, -4);
    if (!preg_match('/^[0-9]{1,2}\-[0-9]{1,2}\-[0-9]{4}/', $Giorno)) {
        echo("La data deve essere specificata nel formato gg-mm-aaaa!");
        exit;
    }
    $query = "SELECT prontimarazzi.id,prontimarazzi.Deposito,prontimarazzi.Cliente,prontimarazzi.quintali,prontimarazzi.palette,prontimarazzi.dds,prontimarazzi.note,marazzi.indirizzo,marazzi.telefono,marazzi.note as noteMar,prontimarazzi.selezionato,marazzi.colore FROM prontimarazzi JOIN marazzi WHERE prontimarazzi.deposito=marazzi.nome and eliminato=1 and data_eliminazione='$Giorno_anno-$Giorno_mese-$Giorno_giorno' ORDER by deposito,cliente,dds";
    $ris = mysqli_query($db, $query) or die(mysqli_error($db));
    $num = mysqli_num_rows($ris);
    $cont = 0;
    $tot = 0;
    $tot_complessivo = 0;
    $i = 0;
} else {
    echo("Errore passaggio parametri GET!");
    exit;
}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <title>MARAZZI</title>
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

    <script type="application/javascript" src="functions.js"></script>
</head>

<body>
<?php echo getHeader('cestino') ?>

<table width="100%" height="80" border="0">
    <tr>
        <td width="49%">
            <div align="center" class="Titolo">Cestino pronti marazzi</div>
        </td>
        <td width="19%">
            <form name="frmEliminaEvidenziati" method="post" action="functionEliminaProntiMarazziEvidenziati.php">
            </form>
        </td>
        <td width="12%">
            <form name="form1" method="post" action="ricercamarazzi.php">
            </form>
        </td>
        <td width="20%">
            <form name="form1" method="post"
                  action="cancellaCestinoProntoMarazzi.php?<? echo($_SERVER['QUERY_STRING']); ?>">
                <input type="submit" name="Submit" value="Elimina tutto">
            </form>
        </td>
    </tr>
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="0" bordercolor="#FFFFFF">
    <tr>
        <th width="200" bordercolor="999999" align="center"><strong>Deposito</strong></th>
        <th width="180" bordercolor="999999" align="center"><strong>Cliente</strong></th>
        <th width="165" bordercolor="999999" align="center"><strong>D.D.S</strong></th>
        <th width="70" bordercolor="999999" align="center"><strong>Q.li</strong></th>
        <th width="70" bordercolor="999999" align="center"><strong>Palette</strong></th>
        <th width="120" bordercolor="999999" align="center"><strong>Note</strong></th>
        <th width="25" align="center"></th>
    </tr>
    <? while ($array = mysqli_fetch_array($ris)) {
        $id = $array['id'];
        @$cliente2 = $cliente;
        @$deposito2 = $deposito;
        $deposito = $array['Deposito'];
        $cliente = $array['Cliente'];
        $dds = $array['dds'];
        $quintali = $array['quintali'];
        $palette = $array['palette'];
        $note = $array['note'];
        $indirizzo = $array['indirizzo'];
        $telefono = $array['telefono'];
        $noteMar = $array['noteMar'];
        $sel = $array['selezionato'];
        $colore = $array['colore'];
        $descrizione = makeDescriptionString($indirizzo, $telefono, $noteMar);

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
                <td>&nbsp;</td>
                <td width="120" bordercolor="999999" style="font-size:12px" align="center">
                    <strong><? print "TOT : " . $tot ?></strong></td>
                </tr><? }
            @$tot_complessivo += $tot;
            $cont = 0;
            $tot = 0; ?>
            <tr bordercolor="FFFFFF">
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td align="center">&nbsp;</td>
            </tr>
            <?
            @$tot = $tot + $quintali;
            if ($cliente != $cliente2) {

                ?>
                <tr>
                    <td width="210" bordercolor="999999" style="font-size:12px"><a
                                href="gestioneProntoMarazzi.php?nome=<? print $deposito ?>"><strong><? print $deposito ?>
                        </a></strong><br><? print $descrizione ?></br></td>
                    <td width="180" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                        style="font-size:12px "><a
                                href="modificaProntoMarazzi.php?id=<? print $id ?>"><? print $cliente ?></a></td>
                    <td width="160" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                        style="font-size:12px "><? print $dds ?></td>
                    <td width="70" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                        style="font-size:12px" align="center"><? print $quintali ?></td>
                    <td width="70" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                        style="font-size:12px" align="center"><? print $palette ?></td>
                    <td width="120" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                        style="font-size:12px"
                        align="center" <? if (($note == "URGENTE") || ($note == "TASSATIVO")) { ?> style="color:#FF0000 " <? } ?>><? print $note ?></td>
                    <td width="35" align="center" bordercolor="999999" class="row-actions"><a
                                href="cancellaProntoMarazzi.php?id=<? print ($id . "&query=" . urlencode($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING'])) ?>"><img
                                    src="img/cancellaAdminPiccolo.gif" width="16" height="16" border="0"></a><a
                                title="Ripristina"
                                href="ripristinaProntoMarazzi.php?id=<? print ($id . "&query=" . urlencode($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING'])) ?>"><img
                                    src="img/seleziona.jpg" width="16" height="16" border="0"></a></td>
                    <td <?php if ($colore != "") echo("bgcolor=\"$colore\" ") ?> width="3"></td>
                </tr>
                <?
            } else {
                ?>
                <tr>
                    <td width="210" bordercolor="999999" style="font-size:12px"><a
                                href="gestioneProntoMarazzi.php?nome=<? print $deposito ?>"><strong><? print $deposito ?></strong></a>
                    </td>
                    <td width="180" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                        style="font-size:12px "><a
                                href="modificaProntoMarazzi.php?id=<? print $id ?>"><? print $cliente ?></a></td>
                    <td width="160" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                        style="font-size:12px"><? print $dds ?></td>
                    <td width="70" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                        style="font-size:12px" align="center"><? print $quintali ?></td>
                    <td width="70" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                        style="font-size:12px" align="center"><? print $palette ?></td>
                    <td width="120" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                        style="font-size:12px"
                        align="center" <? if (($note == "URGENTE") || ($note == "TASSATIVO")) { ?> style="color:#FF0000 " <? } ?>><? print $note ?></td>
                    <td width="35" align="center" bordercolor="999999" class="row-actions"><a
                                href="cancellaProntoMarazzi.php?id=<? print ($id . "&query=" . urlencode($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING'])) ?>"><img
                                    src="img/cancellaAdminPiccolo.gif" width="16" height="16" border="0"></a><a
                                title="Ripristina"
                                href="ripristinaProntoMarazzi.php?id=<? print ($id . "&query=" . urlencode($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING'])) ?>"><img
                                    src="img/seleziona.jpg" width="16" height="16" border="0"></a></td>
                    <td <?php if ($colore != "") echo("bgcolor=\"$colore\" ") ?> width="3"></td>
                </tr>
                <?
            }
        } else {
            @$tot = $tot + $quintali;
            if ($cliente != $cliente2) {

                ?>
                <tr>
                    <td width="210" bordercolor="999999"></td>
                    <td width="180" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                        style="font-size:12px "><a
                                href="modificaProntoMarazzi.php?id=<? print $id ?>"><? print $cliente ?></a></td>
                    <td width="160" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                        style="font-size:12px "><? print $dds ?></td>
                    <td width="70" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                        style="font-size:12px" align="center"><? print $quintali ?></td>
                    <td width="70" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                        style="font-size:12px" align="center"><? print $palette ?></td>
                    <td width="120" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                        style="font-size:12px"
                        align="center" <? if (($note == "URGENTE") || ($note == "TASSATIVO")) { ?> style="color:#FF0000 " <? } ?>><? print $note ?></td>
                    <td width="35" align="center" bordercolor="999999" class="row-actions"><a
                                href="cancellaProntoMarazzi.php?id=<? print ($id . "&query=" . urlencode($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING'])) ?>"><img
                                    src="img/cancellaAdminPiccolo.gif" width="16" height="16" border="0"></a><a
                                title="Ripristina"
                                href="ripristinaProntoMarazzi.php?id=<? print ($id . "&query=" . urlencode($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING'])) ?>"><img
                                    src="img/seleziona.jpg" width="16" height="16" border="0"></a></td>
                    <td <?php if ($colore != "") echo("bgcolor=\"$colore\" ") ?> width="3"></td>
                </tr>
                <?
            } else {
                ?>
                <tr>
                    <td width="210" bordercolor="999999"></td>
                    <td width="180" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                        style="font-size:12px "><a
                                href="modificaProntoMarazzi.php?id=<? print $id ?>"><? print $cliente ?></a></td>
                    <td width="160" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                        style="font-size:12px "><? print $dds ?></td>
                    <td width="70" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                        style="font-size:12px" align="center"><? print $quintali ?></td>
                    <td width="70" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                        style="font-size:12px" align="center"><? print $palette ?></td>
                    <td width="120" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                        style="font-size:12px"
                        align="center" <? if (($note == "URGENTE") || ($note == "TASSATIVO")) { ?> style="color:#FF0000 " <? } ?>><? print $note ?></td>
                    <td width="35" align="center" bordercolor="999999" class="row-actions"><a
                                href="cancellaProntoMarazzi.php?id=<? print ($id . "&query=" . urlencode($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING'])) ?>"><img
                                    src="img/cancellaAdminPiccolo.gif" width="16" height="16" border="0"></a><a
                                title="Ripristina"
                                href="ripristinaProntoMarazzi.php?id=<? print ($id . "&query=" . urlencode($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING'])) ?>"><img
                                    src="img/seleziona.jpg" width="16" height="16" border="0"></a></td>
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
<script type="application/javascript" src="js/main_pages_loading.js" defer></script>
</body>
</html>
