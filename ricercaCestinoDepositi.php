<?
include("include/conn.php");
include("include/libreria.php");
include("include/header.php");

VerificaUtente(); //Verifico se l'utente connesso è un utente autorizzato

$today = getdate();
$date = $today['mday'] . " " . $today['month'];

$search = isset($_REQUEST['search']);
//$searchParameter = $_REQUEST['search']; // search parameter must be a string as "FIELD='value'"
$searchCondition = $search ? (sprintf("and %s", $_REQUEST['search'])) : "";

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

    $query = "SELECT prontidepositi.id,prontidepositi.Deposito,prontidepositi.Ceramica,prontidepositi.Cliente,prontidepositi.autista,prontidepositi.quintali,prontidepositi.palette,prontidepositi.note,depositi.indirizzo,depositi.telefono,depositi.note as noteDep,prontidepositi.selezionato,depositi.colore FROM prontidepositi JOIN depositi WHERE prontidepositi.deposito=depositi.nome and eliminato=1 and (data_eliminazione between '$Dal_anno-$Dal_mese-$Dal_giorno' and '$Al_anno-$Al_mese-$Al_giorno') $searchCondition ORDER by deposito,ceramica,cliente";
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

    $query = "SELECT prontidepositi.id,prontidepositi.Deposito,prontidepositi.Ceramica,prontidepositi.Cliente,prontidepositi.autista,prontidepositi.quintali,prontidepositi.palette,prontidepositi.note,depositi.indirizzo,depositi.telefono,depositi.note as noteDep,prontidepositi.selezionato,depositi.colore FROM prontidepositi JOIN depositi WHERE prontidepositi.deposito=depositi.nome and eliminato=1 and data_eliminazione='$Giorno_anno-$Giorno_mese-$Giorno_giorno' $searchCondition ORDER by deposito,ceramica,cliente";
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
    <title>DEPOSITI</title>
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
            <div align="center" class="Titolo">Cestino pronti depositi</div>
        </td>
        <td width="19%">
            <form name="frmEliminaEvidenziati" method="post" action="functionEliminaProntiDepositiEvidenziati.php">
            </form>
        </td>
        <td width="12%">
            <form name="form1" method="post" action="ricercadepositi.php">
            </form>
        </td>
        <td width="20%">
            <form name="form1" method="post"
                  action="cancellaCestinoProntoDepositi.php?<? echo($_SERVER['QUERY_STRING']); ?>">
                <input type="submit" name="Submit" value="Elimina tutto">
            </form>
        </td>
    </tr>
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="0" bordercolor="#FFFFFF">
    <tr>
        <th width="20%" bordercolor="999999" align="center"><strong>Deposito</strong></th>
        <th width="18%" bordercolor="999999" align="center"><strong>Ceramica</strong></th>
        <th width="18%" bordercolor="999999" align="center"><strong>Cliente</strong></th>
        <th width="15%" bordercolor="999999" align="center"><strong>Autista</strong></th>
        <th width="7%" bordercolor="999999" align="center"><strong>Q.li</strong></th>
        <th width="7%" bordercolor="999999" align="center"><strong>Palette</strong></th>
        <th width="12%" bordercolor="999999" align="center"><strong>Note</strong></th>
        <th width="3%" align="center"></th>
    </tr>
    <? while ($array = mysqli_fetch_array($ris)) {
        $id = $array['id'];
        @$ceramica2 = $ceramica;
        @$deposito2 = $deposito;
        $deposito = $array['Deposito'];
        $ceramica = $array['Ceramica'];
        $cliente = $array['Cliente'];
        $autista = $array['autista'];
        $quintali = is_numeric($array['quintali']) ? $array['quintali'] : null;;
        $palette = $array['palette'];
        $note = $array['note'];
        $indirizzo = $array['indirizzo'];
        $telefono = $array['telefono'];
        $noteDep = $array['noteDep'];
        $sel = $array['selezionato'];
        $colore = $array['colore'];
        $descrizione = makeDescriptionString($indirizzo, $telefono, $noteDep);

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
                <td>&nbsp;</td>
                <td align="center">&nbsp;</td>
            </tr>
            <?
            @$tot = $tot + $quintali;
            if ($ceramica != $ceramica2) {

                ?>
                <tr>
                    <td width="210" bordercolor="999999" style="font-size:12px "><strong><a
                                    href="gestioneProntoDeposito.php?nome=<? print $deposito ?>"><? print $deposito ?></a></strong><br><? print $descrizione ?></br>
                    </td>
                    <td width="180" bordercolor="999999" style="font-size:12px "><? print $ceramica ?></td>
                    <td width="180" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                        style="font-size:12px "><a
                                href="modificaProntoDepositi.php?id=<? print $id ?>"><? print $cliente ?></a></td>
                    <td class = "colorable" width="100" <?php if($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999" style="font-size:12px">
                        <?
                        print '<a href="' . createSearchURL($_SERVER['REQUEST_URI'], "autista='$autista'") . '">' . "$autista </a>"
                        ?>
                    </td>
                    <td width="70" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                        style="font-size:12px" align="center"><? print $quintali ?></td>
                    <td width="70" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                        style="font-size:12px" align="center"><? print $palette ?></td>
                    <td width="120" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                        style="font-size:12px"
                        align="center" <? if (($note == "URGENTE") || ($note == "TASSATIVO")) { ?> style="color:#FF0000 " <? } ?>><? print $note ?></td>
                    <td width="35" align="center" bordercolor="999999" class="row-actions"><a
                                href="cancellaProntoDepositi.php?id=<? print ($id . "&query=" . urlencode($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING'])) ?>"><img
                                    src="img/cancellaAdminPiccolo.gif" width="16" height="16" border="0"></a><a
                                title="Ripristina"
                                href="ripristinaProntoDepositi.php?id=<? print ($id . "&query=" . urlencode($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING'])) ?>"><img
                                    src="img/seleziona.jpg" width="16" height="16" border="0"></a></td>
                    <td <?php if ($colore != "") echo("bgcolor=\"$colore\" ") ?> width="3"></td>
                </tr>
                <?
            } else {
                ?>
                <tr>
                    <td width="210" bordercolor="999999" style="font-size:12px "><strong><a
                                    href="gestioneProntoDeposito.php?nome=<? print $deposito ?>"><? print $deposito ?></a></strong>
                    </td>
                    </td>
                    <td width="180" bordercolor="999999"></td>
                    <td width="180" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                        style="font-size:12px "><a
                                href="modificaProntoDepositi.php?id=<? print $id ?>"><? print $cliente ?></a></td>
                    <td class = "colorable" width="100" <?php if($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999" style="font-size:12px">
                        <?
                        print '<a href="' . createSearchURL($_SERVER['REQUEST_URI'], "autista='$autista'") . '">' . "$autista </a>"
                        ?>
                    </td>
                    <td width="70" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                        style="font-size:12px" align="center"><? print $quintali ?></td>
                    <td width="70" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                        style="font-size:12px" align="center"><? print $palette ?></td>
                    <td width="120" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                        style="font-size:12px"
                        align="center" <? if (($note == "URGENTE") || ($note == "TASSATIVO")) { ?> style="color:#FF0000 " <? } ?>><? print $note ?></td>
                    <td width="35" align="center" bordercolor="999999" class="row-actions"><a
                                href="cancellaProntoDepositi.php?id=<? print ($id . "&query=" . urlencode($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING'])) ?>"><img
                                    src="img/cancellaAdminPiccolo.gif" width="16" height="16" border="0"></a><a
                                title="Ripristina"
                                href="ripristinaProntoDepositi.php?id=<? print ($id . "&query=" . urlencode($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING'])) ?>"><img
                                    src="img/seleziona.jpg" width="16" height="16" border="0"></a></td>
                    <td <?php if ($colore != "") echo("bgcolor=\"$colore\" ") ?> width="3"></td>
                </tr>
                <?
            }
        } else {
            @$tot = $tot + $quintali;
            if ($ceramica != $ceramica2) {

                ?>
                <tr>
                    <td width="210" bordercolor="999999"></td>
                    <td width="180" bordercolor="999999" style="font-size:12px "><? print $ceramica ?></td>
                    <td width="180" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                        style="font-size:12px "><a
                                href="modificaProntoDepositi.php?id=<? print $id ?>"><? print $cliente ?></a></td>
                    <td class = "colorable" width="100" <?php if($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999" style="font-size:12px">
                        <?
                        print '<a href="' . createSearchURL($_SERVER['REQUEST_URI'], "autista='$autista'") . '">' . "$autista </a>"
                        ?>
                    </td>
                    <td width="70" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                        style="font-size:12px" align="center"><? print $quintali ?></td>
                    <td width="70" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                        style="font-size:12px" align="center"><? print $palette ?></td>
                    <td width="120" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                        style="font-size:12px"
                        align="center" <? if (($note == "URGENTE") || ($note == "TASSATIVO")) { ?> style="color:#FF0000 " <? } ?>><? print $note ?></td>
                    <td width="35" align="center" bordercolor="999999" class="row-actions"><a
                                href="cancellaProntoDepositi.php?id=<? print($id . "&query=" . urlencode($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING'])) ?>"><img
                                    src="img/cancellaAdminPiccolo.gif" width="16" height="16" border="0"></a><a
                                title="Ripristina"
                                href="ripristinaProntoDepositi.php?id=<? print ($id . "&query=" . urlencode($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING'])) ?>"><img
                                    src="img/seleziona.jpg" width="16" height="16" border="0"></a></td>
                    <td <?php if ($colore != "") echo("bgcolor=\"$colore\" ") ?> width="3"></td>
                </tr>
                <?
            } else {
                ?>
                <tr>
                    <td width="210" bordercolor="999999"></td>
                    <td width="180" bordercolor="999999"></td>
                    <td width="180" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                        style="font-size:12px "><a
                                href="modificaProntoDepositi.php?id=<? print $id ?>"><? print $cliente ?></a></td>
                    <td class = "colorable" width="100" <?php if($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999" style="font-size:12px">
                        <?
                        print '<a href="' . createSearchURL($_SERVER['REQUEST_URI'], "autista='$autista'") . '">' . "$autista </a>"
                        ?>
                    </td>
                    <td width="70" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                        style="font-size:12px" align="center"><? print $quintali ?></td>
                    <td width="70" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                        style="font-size:12px" align="center"><? print $palette ?></td>
                    <td width="120" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                        style="font-size:12px"
                        align="center" <? if (($note == "URGENTE") || ($note == "TASSATIVO")) { ?> style="color:#FF0000 " <? } ?>><? print $note ?></td>
                    <td width="35" align="center" bordercolor="999999" class="row-actions"><a
                                href="cancellaProntoDepositi.php?id=<? print ($id . "&query=" . urlencode($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING'])) ?>"><img
                                    src="img/cancellaAdminPiccolo.gif" width="16" height="16" border="0"></a><a
                                title="Ripristina"
                                href="ripristinaProntoDepositi.php?id=<? print ($id . "&query=" . urlencode($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING'])) ?>"><img
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
