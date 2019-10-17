<?
include("include/conn.php");
include("include/libreria.php");
include("include/color_picker_block.php");
include("include/header.php");
include("include/legend_functions.php");

VerificaUtente(); //Verifico se l'utente connesso è un utente autorizzato

$fileName = basename(__FILE__, ".php");

$today = getdate();
$dateStr = $today['mday'] . " " . $today['month'];
$dateNumber = $today['year'] . "-" . $today['mon'] . "-" . $today['mday'];
$archivio = isset($_REQUEST['archivio']);

if($archivio) {
    $dataArchvio = $_REQUEST['archivio'];
    $dateNumber = $dataArchvio;

    $dateStr = date('d-m-Y', strtotime($dataArchvio));
    $query = "SELECT *
 FROM (
	SELECT pd.id,pd.Deposito,pd.Ceramica,pd.Cliente,pd.quintali,pd.palette,pd.note,d.indirizzo,pd.selezionato, pd.data_aggiunta, pd.eliminato, pd.data_eliminazione, d.colore 
	FROM prontidepositi pd JOIN depositi d
	WHERE pd.deposito=d.nome and pd.eliminato=0 and date(pd.data_aggiunta) <= '$dataArchvio'
	UNION
	SELECT pd.id,pd.Deposito,pd.Ceramica,pd.Cliente,pd.quintali,pd.palette,pd.note,d.indirizzo,pd.selezionato, pd.data_aggiunta, pd.eliminato, pd.data_eliminazione, d.colore 
	FROM prontidepositi pd JOIN depositi d
	WHERE pd.deposito=d.nome and pd.eliminato=1 and ('$dataArchvio' between date(pd.data_aggiunta) and pd.data_eliminazione)
) AS T1
ORDER by deposito,ceramica,cliente";
} else {
    $query = "SELECT prontidepositi.id,prontidepositi.Deposito,prontidepositi.Ceramica,prontidepositi.Cliente,prontidepositi.quintali,prontidepositi.palette,prontidepositi.note,depositi.indirizzo,prontidepositi.selezionato,depositi.colore FROM prontidepositi JOIN depositi WHERE prontidepositi.deposito=depositi.nome and eliminato=0 ORDER by deposito,ceramica,cliente";
}

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
            font-size: 21px;
        }

        -->
    </style>
    <link rel="stylesheet" type="text/css" href="css/color_popup.css">
    <link rel="stylesheet" type="text/css" href="styleCheck.css">
    <script type="application/javascript" src="functions.js"></script>
</head>

<body style="overflow-x: hidden;">
<?php
if($archivio) {
    echo getHeader('archivio');
} else {
    echo getHeader('depositi');
}
?>

<p id="isArchivio" class="hidden"><?php echo $archivio === true ? 'true' : 'false'?></p>
<div class="page-content">
    <table width="100%" height="80" border="0">
        <tr>
            <td width="40%">
                <div align="center" class="Titolo roboto-font underlined">DEPOSITI : <? print $dateStr ?></div>
            </td>
            <td width="25%">
                <? echo getLegend($db, $fileName, $dateNumber); ?>
            </td>
            <td width="15%" class="archivio-hidden">
                <form id="frmEliminaEvidenziati" name="frmEliminaEvidenziati" method="post" action="functionEliminaProntiDepositiEvidenziati.php">
                    <table>
                        <tr>
                            <td>
                                <label class="material-checkbox yellow" >
                                    <input title="Giallo" type="checkbox" name="chkColorsToDelete[]" value="yellow">
                                    <span>Giallo</span>
                                </label>
                            </td>
                            <td>
                                <label class="material-checkbox red">
                                    <input title="Rosso" type="checkbox" name="chkColorsToDelete[]" value="red">
                                    <span>Rosso</span>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td> <label class="material-checkbox blue">
                                    <input title="Blu" type="checkbox" name="chkColorsToDelete[]" value="blue">
                                    <span>Blu</span>
                                </label>
                            </td>
                            <td>
                                <label class="material-checkbox green">
                                    <input title="Verde" type="checkbox" name="chkColorsToDelete[]" value="green">
                                    <span>Verde</span>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2"> <a style="cursor: pointer" onclick="selectColorCheckboxes();" >Tutti i colori </a></td>
                        </tr>
                        <tr>
                            <td colspan="2"> <input type="submit" name="Submit" value="Elimina evidenziati"> </td>
                        </tr>
                    </table>
                </form>
            </td>
            <td width="10%" class="archivio-hidden">
                <form name="form1" method="post" action="ricercadepositi.php">
                    <input type="submit" name="Submit" value="Ricerca">
                </form>
            </td>
            <td width="10%" class="archivio-hidden">
                <form name="form1" method="post" action="inserisciProntoDepositi.php">
                    <input type="submit" name="Submit" value="Inserisci Pronto">
                </form>
            </td>
        </tr>
    </table>
    <table width="100%" border="1" cellspacing="0" cellpadding="0" bordercolor="#FFFFFF">

    </table>
    <table id="datarow-table" width="100%" border="0" cellspacing="0" cellpadding="0" bordercolor="#FFFFFF">
        <tr>
            <th width="210" bordercolor="999999" align="center"><strong>Deposito</strong></th>
            <th width="180" bordercolor="999999" align="center"><strong>Ceramica</strong></th>
            <th width="180" bordercolor="999999" align="center"><strong>Cliente</strong></th>
            <th width="70" bordercolor="999999" align="center"><strong>Q.li</strong></th>
            <th width="70" bordercolor="999999" align="center"><strong>Palette</strong></th>
            <th width="120" bordercolor="999999" align="center"><strong>Note</strong></th>
            <th width="55" align="center"></th>
        </tr>
        <? while ($array = mysqli_fetch_array($ris)) {
            $id = $array['id'];
            @$ceramica2 = $ceramica;
            @$deposito2 = $deposito;
            $deposito = $array['Deposito'];
            $ceramica = $array['Ceramica'];
            $cliente = $array['Cliente'];
            $quintali = $array['quintali'];
            $palette = $array['palette'];
            $note = $array['note'];
            $indirizzo = $array['indirizzo'];
            $sel = $array['selezionato'];
            $colore = $array['colore'];
            $eliminato = isset($array['eliminato']) ? $array['eliminato'] : false;
            $cont++;
            $i++;

            @$tot_complessivo += $quintali;
            if (trim($deposito) != trim($deposito2)) {
                ?>
                <? if ($cont > 1) { ?>
                    <tr bordercolor="FFFFFF">
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td></td>
                    <td width="120" bordercolor="999999" style="font-size:12px" align="center">
                        <strong><? print "TOT : " . $tot ?></strong></td>
                    </tr><? }
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
                ?>
                <tr id="datarow-<? print $id ?>" <?php if($eliminato) echo "class='data-row-del'"?>>
                    <td width="210" bordercolor="999999" style="font-size:12px "><strong><a
                                    href="gestioneProntoDeposito.php?nome=<? print $deposito ?>"><? print $deposito ?></a></strong><br><? print $indirizzo ?></br>
                    </td>
                    <td width="180" bordercolor="999999" style="font-size:12px "><? print $ceramica ?></td>
                    <td class = "colorable"  width="180" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                        style="font-size:12px "><a
                                href="modificaProntoDepositi.php?id=<? print $id ?>"><? print $cliente ?></a></td>
                    <td class = "colorable"  width="70" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                        style="font-size:12px" align="center"><? print $quintali ?></td>
                    <td class = "colorable"  width="70" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                        style="font-size:12px" align="center"><? print $palette ?></td>
                    <td class = "colorable"  width="120" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                        style="font-size:12px"
                        align="center" <? if (($note == "URGENTE") || ($note == "TASSATIVO")) { ?> style="color:#FF0000 " <? } ?>><? print $note ?></td>
                    <td class="row-actions" width="55" align="center" bordercolor="999999">
                        <a href="cancellaProntoDepositi.php?id=<? print $id ?>">
                            <img src="img/cancellaAdminPiccolo.gif" width="16" height="16" border="0">
                        </a>

                        <?
                        if($deposito != "AA DEP.MOLISE") {
                            ?>
                            <!--SPOSTA CERAMICA TO DEPOSITO-->
                            <a href="functionMoveProntoToDeposito.php?id=<? print $id ?>&from=<?php echo $fileName ?>&to=AA%20DEP.MOLISE&redirectUrl=<?print $_SERVER['PHP_SELF']?>">
                                <img class="" src="img/next.png" width="16" height="16" border="0">
                            </a>
                        <? } ?>

                        <div class="evidenzia">
                            <img src="img/seleziona.jpg" width="16" height="16" border="0">
                            <?php print getColorPicker($fileName, $id); ?>
                        </div>
                    </td>
                    <td <?php if ($colore != "") echo("bgcolor=\"$colore\" ") ?> width="3"></td>
                </tr>
                <?
            } else {
                @$tot = $tot + $quintali;
                if (trim($ceramica) != trim($ceramica2)) {

                    ?>
                    <tr id="datarow-<? print $id ?>" <?php if($eliminato) echo "class='data-row-del'"?>>
                        <td width="210" bordercolor="999999">
                        </td>
                        <td width="180" bordercolor="999999" style="font-size:12px "><? print $ceramica ?></td>
                        <td class = "colorable" width="180" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                            style="font-size:12px "><a
                                    href="modificaProntoDepositi.php?id=<? print $id ?>"><? print $cliente ?></a></td>
                        <td class = "colorable"  width="70" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                            style="font-size:12px" align="center"><? print $quintali ?></td>
                        <td class = "colorable"  width="70" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                            style="font-size:12px" align="center"><? print $palette ?></td>
                        <td class = "colorable"  width="120" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                            style="font-size:12px"
                            align="center" <? if (($note == "URGENTE") || ($note == "TASSATIVO")) { ?> style="color:#FF0000 " <? } ?>><? print $note ?></td>
                        <td class="row-actions" width="55" align="center" bordercolor="999999">
                            <a href="cancellaProntoDepositi.php?id=<? print $id ?>">
                                <img src="img/cancellaAdminPiccolo.gif" width="16" height="16" border="0">
                            </a>

                            <?
                            if($deposito != "AA DEP.MOLISE") {
                                ?>
                                <!--SPOSTA CERAMICA TO DEPOSITO-->
                                <a href="functionMoveProntoToDeposito.php?id=<? print $id ?>&from=<?php echo $fileName ?>&to=AA%20DEP.MOLISE&redirectUrl=<?print $_SERVER['PHP_SELF']?>">
                                    <img class="" src="img/next.png" width="16" height="16" border="0">
                                </a>
                            <? } ?>

                            <div class="evidenzia">
                                <img src="img/seleziona.jpg" width="16" height="16" border="0">
                                <? print getColorPicker($fileName, $id); ?>
                            </div>
                        </td>
                        <td <?php if ($colore != "") echo("bgcolor=\"$colore\" ") ?> width="3"></td>
                    </tr>
                    <?
                } else {
                    ?>
                    <tr tr id="datarow-<? print $id ?>" <?php if($eliminato) echo "class='data-row-del'"?>>
                        <td width="210" bordercolor="999999"></td>
                        <td width="180" bordercolor="999999"></td>
                        <td class = "colorable" width="180" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                            style="font-size:12px "><a
                                    href="modificaProntoDepositi.php?id=<? print $id ?>"><? print $cliente ?></a></td>
                        <td class = "colorable" width="70" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                            style="font-size:12px" align="center"><? print $quintali ?></td>
                        <td class = "colorable" width="70" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                            style="font-size:12px" align="center"><? print $palette ?></td>
                        <td class = "colorable" width="120" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                            style="font-size:12px"
                            align="center" <? if (($note == "URGENTE") || ($note == "TASSATIVO")) { ?> style="color:#FF0000 " <? } ?>><? print $note ?></td>
                        <td class="row-actions" width="55" align="center" bordercolor="999999">
                            <a href="cancellaProntoDepositi.php?id=<? print $id ?>">
                                <img src="img/cancellaAdminPiccolo.gif" width="16" height="16" border="0">
                            </a>

                            <?
                            if($deposito != "AA DEP.MOLISE") {
                                ?>
                                <!--SPOSTA CERAMICA TO DEPOSITO-->
                                <a href="functionMoveProntoToDeposito.php?id=<? print $id ?>&from=<?php echo $fileName ?>&to=AA%20DEP.MOLISE&redirectUrl=<?print $_SERVER['PHP_SELF']?>">
                                    <img class="" src="img/next.png" width="16" height="16" border="0">
                                </a>
                            <? } ?>

                            <div class="evidenzia">
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
<?php echo getEditLegendModal($fileName, $dateNumber, $_SERVER['PHP_SELF']); ?>
<!-- Add listener to elements-->
<script type="application/javascript" src="js/main_pages_loading.js" defer></script>
<script type="application/javascript" src="js/edit_legend_modal.js" defer></script>
</body>
</html>
