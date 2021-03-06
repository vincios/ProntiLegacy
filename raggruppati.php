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
$search = isset($_REQUEST['search']);
//$searchParameter = $_REQUEST['search']; // search parameter must be a string as "FIELD='value'"
$searchCondition = $search ? (sprintf("and %s", $_REQUEST['search'])) : "";

$readOnly = $archivio || $search;
if($archivio) {
    $dataArchvio = $_REQUEST['archivio'];
    $dateNumber = $dataArchvio;
    $dateStr = date('d-m-Y', strtotime($dataArchvio));

    $query = "SELECT * FROM (
                SELECT id, Ceramica, materiale, Cliente, autista, quintali, palette, pr.note, selezionato, data_aggiunta, 
                       eliminato, data_eliminazione, idcer, nome, indirizzo, telefono, c.note as noteCer, idgruppo, colore 
                FROM prontiraggruppati pr JOIN ceramica c
                WHERE c.idgruppo!=0 and c.nome=pr.ceramica and eliminato=0 and date(pr.data_aggiunta) <= '$dataArchvio' $searchCondition
                UNION 
//                SELECT *
                SELECT id, Ceramica, materiale, Cliente, autista, quintali, palette, pr.note, selezionato, data_aggiunta, 
                       eliminato, data_eliminazione, idcer, nome, indirizzo, telefono, c.note as noteCer, idgruppo, colore 
                FROM prontiraggruppati pr JOIN ceramica c
                WHERE c.idgruppo!=0 and c.nome=pr.ceramica and eliminato=1 and ('$dataArchvio' between date(pr.data_aggiunta) and pr.data_eliminazione) $searchCondition
            ) AS T1
            ORDER by idgruppo,ceramica,materiale,cliente";
} else {
    $query = "SELECT id, Ceramica, materiale, Cliente, autista, quintali, palette, prontiraggruppati.note, selezionato, 
              data_aggiunta, eliminato, data_eliminazione, idcer, nome, indirizzo, telefono, ceramica.note as noteCer, 
              idgruppo, colore 
              FROM prontiraggruppati JOIN ceramica 
              WHERE ceramica.idgruppo!=0 and ceramica.nome=prontiraggruppati.ceramica and eliminato=0 $searchCondition 
              ORDER by idgruppo,ceramica,materiale,cliente";
}

$ris = mysqli_query($db, $query) or die(mysqli_error($db));
$rows = mysqli_fetch_all($ris, MYSQLI_ASSOC);
$ceramicheOccurrences = countSqlResultFieldOccurrence($rows, 'Ceramica');
$num = mysqli_num_rows($ris);
$cont = 0;
$tot = 0;
$tot_complessivo = 0;
$i = 0;
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=latin1">
    <title>RAGGRUPPATI</title>
    <link rel="stylesheet" type="text/css" href="css/general.css">
    <style type="text/css">
        <!--
        body, td, th {
            font-family: Verdana, Arial, Helvetica, sans-serif;
            color: #000000;
            /*border: 1px solid;*/
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
    <link rel="stylesheet" type="text/css" href="css/general.css">
    <link rel="stylesheet" type="text/css" media="print" href="css/print.css" />
    <script type="application/javascript" src="functions.js"></script>
</head>

<body style="overflow-x: hidden;">
<?php
if($archivio) {
    echo getHeader('archivio');
} else {
    echo getHeader('raggruppati');
}
?>

<p id="isArchivio" class="hidden"><?php echo $readOnly === true ? 'true' : 'false'?></p>
<div class="page-content">
    <table width="100%" height="80"  border="0" id="table1">
        <tr>
            <td width="40%">
                <div align="center" class="Titolo roboto-font underlined">RAGGRUPPATI : <? print $dateStr ?></div>
            </td>
            <td width="25%" class="no-print">
                <? echo getLegend($db, $fileName, $dateNumber); ?>
            </td>
            <td width="15%" class="archivio-hidden no-print">
                <form id="frmEliminaEvidenziati" name="frmEliminaEvidenziati" method="post" action="functionEliminaProntiRaggruppatiEvidenziati.php">
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
            <td width="10%" class="archivio-hidden no-print">
                <form name="form1" method="post" action="ricercaraggruppati.php">
                    <input type="submit" name="Submit" value="Ricerca">
                </form>
            </td>
            <td width="10%" class="archivio-hidden no-print">
                <form name="form1" method="post" action="inserisciProntoRaggruppati.php">
                    <input type="submit" name="Submit" value="Inserisci Pronto">
                </form>
            </td>
        </tr>
    </table>
    <table width="100%" border="1" cellspacing="0" cellpadding="0" bordercolor="#FFFFFF">

    </table>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" bordercolor="#FFFFFF">
        <tr>
            <th width="17%" bordercolor="999999" align="center"><strong>Ceramica</strong></th>
            <th width="18%" bordercolor="999999" align="center"><strong>Materiale</strong></th>
            <th width="18%" bordercolor="999999" align="center"><strong>Cliente</strong></th>
            <th width="13%" bordercolor="999999" align="center"><strong>Autista</strong></th>
            <th width="6%" bordercolor="999999" align="center"><strong>Q.li</strong></th>
            <th width="6%" bordercolor="999999" align="center"><strong>Palette</strong></th>
            <th width="15%" bordercolor="999999" align="center"><strong>Note</strong></th>
            <th width="7%" align="center"></th>
        </tr>
        <?
        //        while ($array = mysqli_fetch_array($ris)) {
        foreach ($rows as $array) {
            $id = $array['id'];
            @$ceramica2 = $ceramica;
            @$idgruppo2 = $idgruppo;
            $materiale = $array['materiale'];
            $ceramica = $array['Ceramica'];
            $cliente = $array['Cliente'];
            $autista = $array['autista'];
            $quintali = is_numeric($array['quintali']) ? $array['quintali'] : null;
            $palette = $array['palette'];
            $note = $array['note'];
            $idgruppo = $array['idgruppo'];
            $indirizzo = $array['indirizzo'];
            $telefono = $array['telefono'];
            $noteCer = $array['noteCer'];
            $sel = $array['selezionato'];
            $colore = $array['colore'];
            $eliminato = isset($array['eliminato']) ? $array['eliminato'] : false;
            $cont++;
            $i++;
            $descrizione = makeDescriptionString($indirizzo, $telefono, $noteCer);

            @$tot_complessivo += $quintali;
            if ($idgruppo != $idgruppo2) {
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
                $cont = 0;
                $tot = 0; ?>
                <tr bordercolor="#000000" class="row-separator">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
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
                if (trim($ceramica) != trim($ceramica2)) {

                    ?>
                    <tr bordercolor="FFFFFF">
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td align="center">&nbsp;</td>
                    </tr>
                    <tr id="datarow-<? print $id ?>" <?php if($eliminato) echo "class='data-row-del'"?>>
                        <td <? echo("rowspan=".$ceramicheOccurrences[trim($ceramica)])?>
                                valign="top" width="180" bordercolor="999999" style="font-size:12px ">
                            <strong>
                                <a href="gestioneProntoRaggruppati.php?nome=<? print $ceramica ?>"><? print $ceramica ?></a>
                            </strong>
                            <br><? print $descrizione ?></br>
                        </td>
                        <td width="180" bordercolor="999999" style="font-size:12px "><? print $materiale ?></td>
                        <td class="colorable" width="180" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                            style="font-size:12px "><a
                                    href="modificaProntoRaggruppati.php?id=<? print $id ?>"><? print $cliente ?></a></td>
                        <td class = "colorable" width="100" <?php if($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999" style="font-size:12px">
                            <?
                            print '<a href="' . createSearchURL($_SERVER['REQUEST_URI'], "autista='$autista'") . '">' . "$autista </a>"
                            ?>
                        </td>
                        <td class = "colorable" width="70" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                            style="font-size:12px" align="center"><? print $quintali ?></td>
                        <td class = "colorable" width="70" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                            style="font-size:12px" align="center"><? print $palette ?></td>
                        <td class = "colorable" width="150" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                            style="font-size:12px"
                            align="center" <? if (($note == "URGENTE") || ($note == "TASSATIVO")) { ?> style="color:#FF0000 " <? } ?>><? print $note ?></td>
                        <td class="row-actions" width="50" align="center" bordercolor="999999">
                            <a href="cancellaProntoRaggruppati.php?id=<? print $id ?>">
                                <img src="img/cancellaAdminPiccolo.gif" width="16" height="16" border="0">
                            </a>

                            <!--SPOSTA CERAMICA TO DEPOSITO-->
                            <a href="functionMoveProntoToDeposito.php?id=<? print $id ?>&from=<?php echo $fileName ?>&to=AA%20DEP.MOLISE&redirectUrl=<?print $_SERVER['PHP_SELF']?>">
                                <img class="" src="img/next.png" width="16" height="16" border="0">
                            </a>

                            <div class="evidenzia">
                                <img src="img/seleziona.jpg" width="16" height="16" border="0">
                                <?php print getColorPicker($fileName, $id); ?>
                            </div>
                        </td>
                        <td <?php if ($colore != "") echo("bgcolor=\"$colore\" ") ?> width="3"></td>
                    </tr>
                    <?
                } else {
                    ?>
                    <tr id="datarow-<? print $id ?>" <?php if($eliminato) echo "class='data-row-del'"?>>
<!--                        <td width="180" bordercolor="999999"></td>-->
                        <td width="180" bordercolor="999999" style="font-size:12px "><? print $materiale ?></td>
                        <td class="colorable" width="180" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                            style="font-size:12px "><a
                                    href="modificaProntoRaggruppati.php?id=<? print $id ?>"><? print $cliente ?></a></td>
                        <td class = "colorable" width="100" <?php if($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999" style="font-size:12px">
                            <?
                            print '<a href="' . createSearchURL($_SERVER['REQUEST_URI'], "autista='$autista'") . '">' . "$autista </a>"
                            ?>
                        </td>
                        <td class="colorable" width="70" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                            style="font-size:12px" align="center"><? print $quintali ?></td>
                        <td class="colorable" width="70" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                            style="font-size:12px" align="center"><? print $palette ?></td>
                        <td class="colorable" width="150" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                            style="font-size:12px"
                            align="center" <? if (($note == "URGENTE") || ($note == "TASSATIVO")) { ?> style="color:#FF0000 " <? } ?>><? print $note ?></td>
                        <td class="row-actions" width="50" align="center" bordercolor="999999">
                            <a href="cancellaProntoRaggruppati.php?id=<? print $id ?>">
                                <img src="img/cancellaAdminPiccolo.gif" width="16" height="16" border="0">
                            </a>

                            <!--SPOSTA CERAMICA TO DEPOSITO-->
                            <a href="functionMoveProntoToDeposito.php?id=<? print $id ?>&from=<?php echo $fileName ?>&to=AA%20DEP.MOLISE&redirectUrl=<?print $_SERVER['PHP_SELF']?>">
                                <img class="" src="img/next.png" width="16" height="16" border="0">
                            </a>

                            <div class="evidenzia">
                                <img src="img/seleziona.jpg" width="16" height="16" border="0">
                                <?php print getColorPicker($fileName, $id); ?>
                            </div>
                        </td>
                        <td <?php if ($colore != "") echo("bgcolor=\"$colore\" ") ?> width="3"></td>
                    </tr>
                    <?
                }
            } else {
                @$tot = $tot + $quintali;
                if (trim($ceramica) != trim($ceramica2)) {
                    ?>
                    <tr bordercolor="FFFFFF">
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td align="center">&nbsp;</td>
                    </tr>
                    <tr id="datarow-<? print $id ?>" <?php if($eliminato) echo "class='data-row-del'"?>>
                        <td <? echo("rowspan=".$ceramicheOccurrences[trim($ceramica)])?>
                                valign="top" width="180" bordercolor="999999" style="font-size:12px ">
                            <strong>
                                <a href="gestioneProntoRaggruppati.php?nome=<? print $ceramica ?>"><? print $ceramica ?></a>
                            </strong>
                            <br><? print $descrizione ?></br>
                        </td>
                        <td width="180" bordercolor="999999" style="font-size:12px "><? print $materiale ?></td>
                        <td class="colorable" width="180" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                            style="font-size:12px "><a
                                    href="modificaProntoRaggruppati.php?id=<? print $id ?>"><? print $cliente ?></a></td>
                        <td class = "colorable" width="100" <?php if($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999" style="font-size:12px">
                            <?
                            print '<a href="' . createSearchURL($_SERVER['REQUEST_URI'], "autista='$autista'") . '">' . "$autista </a>"
                            ?>
                        </td>
                        <td class="colorable" width="70" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                            style="font-size:12px" align="center"><? print $quintali ?></td>
                        <td class="colorable" width="70" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                            style="font-size:12px" align="center"><? print $palette ?></td>
                        <td class="colorable" width="150" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                            style="font-size:12px"
                            align="center" <? if (($note == "URGENTE") || ($note == "TASSATIVO")) { ?> style="color:#FF0000 " <? } ?>><? print $note ?></td>
                        <td class="row-actions" width="50" align="center" bordercolor="999999">
                            <a href="cancellaProntoRaggruppati.php?id=<? print $id ?>">
                                <img src="img/cancellaAdminPiccolo.gif" width="16" height="16" border="0">
                            </a>

                            <!--SPOSTA CERAMICA TO DEPOSITO-->
                            <a href="functionMoveProntoToDeposito.php?id=<? print $id ?>&from=<?php echo $fileName ?>&to=AA%20DEP.MOLISE&redirectUrl=<?print $_SERVER['PHP_SELF']?>">
                                <img class="" src="img/next.png" width="16" height="16" border="0">
                            </a>

                            <div class="evidenzia">
                                <img src="img/seleziona.jpg" width="16" height="16" border="0">
                                <?php print getColorPicker($fileName, $id); ?>
                            </div>
                        </td>
                        <td <?php if ($colore != "") echo("bgcolor=\"$colore\" ") ?> width="3"></td>
                    </tr>
                    <?
                } else {
                    ?>
                    <tr id="datarow-<? print $id ?>" <?php if($eliminato) echo "class='data-row-del'"?>>
<!--                        <td width="180" bordercolor="999999"></td>-->
                        <td width="180" bordercolor="999999" style="font-size:12px "><? print $materiale ?></td>
                        <td class="colorable" width="180" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                            style="font-size:12px "><a
                                    href="modificaProntoRaggruppati.php?id=<? print $id ?>"><? print $cliente ?></a></td>
                        <td class = "colorable" width="100" <?php if($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999" style="font-size:12px">
                            <?
                            print '<a href="' . createSearchURL($_SERVER['REQUEST_URI'], "autista='$autista'") . '">' . "$autista </a>"
                            ?>
                        </td>
                        <td class="colorable" width="70" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                            style="font-size:12px" align="center"><? print $quintali ?></td>
                        <td class="colorable" width="70" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                            style="font-size:12px" align="center"><? print $palette ?></td>
                        <td class="colorable" width="150" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                            style="font-size:12px"
                            align="center" <? if (($note == "URGENTE") || ($note == "TASSATIVO")) { ?> style="color:#FF0000 " <? } ?>><? print $note ?></td>
                        <td class="row-actions" width="50" align="center" bordercolor="999999">
                            <a href="cancellaProntoRaggruppati.php?id=<? print $id ?>">
                                <img src="img/cancellaAdminPiccolo.gif" width="16" height="16" border="0">
                            </a>

                            <!--SPOSTA CERAMICA TO DEPOSITO-->
                            <a href="functionMoveProntoToDeposito.php?id=<? print $id ?>&from=<?php echo $fileName ?>&to=AA%20DEP.MOLISE&redirectUrl=<?print $_SERVER['PHP_SELF']?>">
                                <img class="" src="img/next.png" width="16" height="16" border="0">
                            </a>

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
<script type="application/javascript" src="js/edit_legend_modal.js" defer></script>
<script type="application/javascript" src="js/main_pages_loading.js" defer></script>
</body>
</html>
