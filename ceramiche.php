<?
include("include/conn.php");
include("include/libreria.php");
include("include/color_picker_block.php");
include("include/header.php");
include("include/legend_functions.php");

$fileName = basename(__FILE__, ".php");

VerificaUtente(); //Verifico se l'utente connesso è un utente autorizzato

$today = getdate();
$dateNumber = $today['year'] . "-" . $today['mon'] . "-" . $today['mday'];
$dateStr = $today['mday']." ".$today['month'];

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
                              SELECT id, Ceramica, cliente, quintali, palette, selezionato, data_aggiunta, eliminato, autista, 
                                     data_eliminazione, idcer, nome, indirizzo, telefono, pc.note, c.note as noteCer, idgruppo, colore 
                              FROM pronticeramiche pc JOIN ceramica c
                              WHERE c.idgruppo=0 and c.nome=pc.ceramica and pc.eliminato=0 and date(pc.data_aggiunta) <= '$dataArchvio' $searchCondition
                              UNION
                              SELECT id, Ceramica, cliente, quintali, palette, selezionato, data_aggiunta, eliminato, autista, 
                                     data_eliminazione, idcer, nome, indirizzo, telefono, pc.note, c.note as noteCer, idgruppo, colore 
                              FROM pronticeramiche pc JOIN ceramica c
                              WHERE c.idgruppo=0 and c.nome=pc.ceramica and pc.eliminato=1 and ('$dataArchvio' between date(data_aggiunta) and data_eliminazione) $searchCondition
                            ) as T1
		ORDER by ceramica,cliente";
} else {
    $query = "SELECT id, Ceramica, cliente, quintali, palette, selezionato, data_aggiunta, eliminato, autista,
                    data_eliminazione, idcer, nome, indirizzo, telefono, pc.note, c.note as noteCer, idgruppo, colore 
              FROM pronticeramiche pc JOIN ceramica c 
              WHERE c.idgruppo=0 and c.nome=pc.ceramica and eliminato=0 $searchCondition
              ORDER by ceramica,cliente";
}

$ris = mysqli_query($db, $query) or die( mysqli_error($db) );
$rows = mysqli_fetch_all($ris, MYSQLI_ASSOC);
$ceramicheOccurrences = countSqlResultFieldOccurrence($rows, 'Ceramica');
$num = mysqli_num_rows($ris);
$cont=0;
$tot=0;
$tot_complessivo=0;
$i=0;

if($archivio) {
    $query =  "SELECT * FROM (
                                SELECT id, Ceramica, cliente, quintali, palette, selezionato, data_aggiunta, eliminato, autista,
                                       data_eliminazione, idcer, nome, indirizzo, telefono, pc.note, c.note as noteCer, idgruppo, colore 
                                FROM pronticeramiche pc JOIN ceramica c
                                WHERE c.idgruppo!=0 and c.nome=pc.ceramica and eliminato=0 and date(pc.data_aggiunta) <= '$dataArchvio' $searchCondition 
                                UNION
                                SELECT id, Ceramica, cliente, quintali, palette, selezionato, data_aggiunta, eliminato, autista, 
                                       data_eliminazione, idcer, nome, indirizzo, telefono, pc.note, c.note as noteCer, idgruppo, colore 
                                FROM pronticeramiche pc JOIN ceramica c
                                WHERE c.idgruppo!=0 and c.nome=pc.ceramica and eliminato=1 and ('$dataArchvio' between date(data_aggiunta) and data_eliminazione) $searchCondition 
                              ) AS T1
                ORDER by idgruppo,ceramica,cliente";
} else {
    $query = "SELECT id, Ceramica, cliente, quintali, palette, selezionato, data_aggiunta, eliminato, autista,
              data_eliminazione, idcer, nome, indirizzo, telefono, pc.note, c.note as noteCer, idgruppo, colore 
              FROM pronticeramiche pc JOIN ceramica c 
              WHERE c.idgruppo!=0 and c.nome=pc.ceramica and eliminato=0 $searchCondition
              ORDER by idgruppo,ceramica,cliente";
}

$ris2 = mysqli_query($db, $query) or die( mysqli_error($db) );
$rows2 = mysqli_fetch_all($ris2, MYSQLI_ASSOC);
$ceramicheOccurrences2 = countSqlResultFieldOccurrence($rows2, 'Ceramica');
$num2 = mysqli_num_rows($ris2);

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <title>CERAMICHE</title>
    <style type="text/css">
        <!--
        body,td,th {
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

<body>
<?php
if($archivio) {
    echo getHeader('archivio');
} else {
    echo getHeader('ceramiche');
}
?>

<p id="isArchivio" class="hidden"><?php echo $readOnly === true ? 'true' : 'false'?></p>
<div class="page-content">
    <table width="100%" height="80"  border="0" id="table1">
        <tr>
            <td width="40%"><div align="center" class="Titolo roboto-font underlined">
                    CERAMICHE : <? print $dateStr ?></div>
            </td>
            <td width="25%" class="no-print">
                <? echo getLegend($db, $fileName, $dateNumber); ?>
            </td>
            <td width="15%" class="archivio-hidden no-print">
                <form  id="frmEliminaEvidenziati" name="frmEliminaEvidenziati" method="post" action="functionEliminaProntiCeramicheEvidenziati.php">
                    <table>
                        <tr>
                            <td>
                                <label class="material-checkbox yellow" >
                                    <input title="Giallo" type="checkbox" class="filled-in" name="chkColorsToDelete[]" value="yellow">
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
                            <td colspan="2"> <input class="btn waves-effect waves-light" type="submit" name="Submit" value="Elimina evidenziati"> </td>
                        </tr>
                    </table>
                </form>
            </td>
            <td width="10%" class="archivio-hidden no-print"><form name="form1" method="post" action="ricercaceramiche.php">
                    <input type="submit" name="Submit" value="Ricerca">
                </form></td>
            <td width="10%" class="archivio-hidden no-print"><form name="form1" method="post" action="inserisciProntoCeramiche.php">
                    <input type="submit" name="Submit" value="Inserisci Pronto">
                </form></td>
        </tr>
    </table>
    <table width="100%"  border="0" cellspacing="0" cellpadding="0" bordercolor="#FFFFFF" id="table2">
        <tr>
            <th width="25%" bordercolor="999999" align="center"><strong>Ceramica</strong></th>
            <th width="20%" bordercolor="999999" align="center"><strong>Cliente</strong></th>
            <th width="15%" bordercolor="999999" align="center"><strong>Autista</strong></th>
            <th width="5%" bordercolor="999999" align="center"><strong>Q.li</strong></th>
            <th width="10%" bordercolor="999999" align="center"><strong>Palette</strong></th>
            <th width="17%" bordercolor="999999" align="center"><strong>Note</strong></th>
            <th width="7%" align="center"></th>
        </tr>
        <? $j=0;
        //                while ($array = mysqli_fetch_array($ris)){
        foreach ($rows as $array) {
            $id = $array['id'];
            $idcer = $array['idcer'];
            @$ceramica2 = $ceramica; //ceramica2 = ceramica on previous iteration
            $ceramica = $array['Ceramica'];
            $cliente = $array['cliente'];
            $autista = $array['autista'];
            $quintali = $array['quintali'];
            $palette = $array['palette'];
            $note = $array['note'];
            $indirizzo = $array['indirizzo'];
            $telefono = $array['telefono'];
            $noteCer = $array['noteCer'];
            $sel = $array['selezionato'];
            $colore = $array['colore'];
            $eliminato = $array['eliminato'];

            $descrizione = makeDescriptionString($indirizzo, $telefono, $noteCer);

            $cont++;
            $i++;
            $j++;
            @$tot_complessivo+=$quintali;
            if (trim($ceramica) != trim($ceramica2)){

                ?>
                <? if ($cont>1) {
                    ?>
                    <tr bordercolor="FFFFFF">
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td> </td>
                    <td width="150" bordercolor="999999" style="font-size:12px" align="center"><strong><? print "TOT : ".$tot ?></strong></td>
                    </tr><?
                }
                $cont=0; $tot=0;
                ?>
                <tr bordercolor="FFFFFF">
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td> </td>
                    <td> </td>
                    <td>&nbsp;</td>
                    <td align="center">&nbsp;</td>
                </tr>
                <tr id="datarow-<?php print $id?>" style="color:#FF0000" <?php if($eliminato) echo "class='data-row-del'"?>>
                    <td <? echo("rowspan=".$ceramicheOccurrences[trim($ceramica)]) ?> valign="top" width="180" bordercolor="999999" style="font-size:12px">
                        <strong>
                            <a href="gestioneProntoCeramica.php?nome=<? print $ceramica ?>"><? print $ceramica?></a>
                        </strong>
                        <br><? print $descrizione ?></br>
                    </td>
                    <td class = "colorable" width="180" <?php if($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999" style="font-size:12px"><a href="modificaProntoCeramiche.php?id=<? print $id ?>"><? print $cliente ?></a></td>
                    <!--                    <td class = "colorable" width="100" --><?php //if($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?><!-- bordercolor="999999" style="font-size:12px"><a href="--><?// createSearchURL( $_SERVER['REQUEST_URI'], "autista='PROVA'") ?><!--">--><?// print $autista ?><!--</a></td>-->
                    <td class = "colorable" width="100" <?php if($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999" style="font-size:12px">
                        <?
                        print '<a href="' . createSearchURL($_SERVER['REQUEST_URI'], "autista='$autista'") . '">' . "$autista </a>"
                        ?>
                    </td>
                    <td class="colorable" <?php if($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999" style="font-size:12px" align="center"><? print $quintali ?></td>
                    <td class="colorable" <?php if($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999" style="font-size:12px" align="center"><? print $palette ?></td>
                    <td class="colorable" <?php if($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999" style="font-size:12px" align="center" <? if(($note=="URGENTE") || ($note=="TASSATIVO")) { ?> style="color:#FF0000"  <? } ?>><? print $note ?></td>
                    <td class="row-actions" align="center" bordercolor="999999">
                        <a href="cancellaProntoCeramiche.php?id=<? print $id ?>">
                            <img src="img/cancellaAdminPiccolo.gif" width="16" height="16">
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
                    <td <?php if($colore!="") echo("bgcolor=\"$colore\" ") ?> width="3"></td>
                </tr>
                <?
                @$tot=$tot+$quintali;
            }
            else
            {
                @$tot=$tot+$quintali;
                ?>
                <tr id="datarow-<?php print $id?>"  <?php if($eliminato) echo "class='data-row-del'"?>>
                    <!--                    <td width="180" bordercolor="999999"></td>-->
                    <td class="colorable" <?php if($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999" style="font-size:12px"><a href="modificaProntoCeramiche.php?id=<? print $id ?>"><? print $cliente ?></a></td>
                    <td class = "colorable" width="100" <?php if($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999" style="font-size:12px">
                        <?
                        print '<a href="' . createSearchURL($_SERVER['REQUEST_URI'], "autista='$autista'") . '">' . "$autista </a>"
                        ?>
                    </td>
                    <td class="colorable" <?php if($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999" style="font-size:12px" align="center"><? print $quintali ?></td>
                    <td class="colorable" <?php if($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999" style="font-size:12px" align="center"><? print $palette ?></td>
                    <td class="colorable" <?php if($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999" style="font-size:12px" align="center" <? if(($note=="URGENTE") || ($note=="TASSATIVO")) { ?> style="color:#FF0000" <? } ?>><? print $note ?></td>
                    <td class="row-actions" align="center" bordercolor="999999">
                        <a href="cancellaProntoCeramiche.php?id=<? print $id ?>">
                            <img src="img/cancellaAdminPiccolo.gif" width="16" height="16" border="0">
                        </a>

                        <!--SPOSTA CERAMICA TO DEPOSITO-->
                        <a href="functionMoveProntoToDeposito.php?id=<? print $id ?>&from=<?php echo $fileName ?>&to=AA%20DEP.MOLISE&redirectUrl=<?print $_SERVER['PHP_SELF']?>">
                            <img class="" src="img/next.png" width="16" height="16" border="0">
                        </a>

                        <div class="evidenzia">
                            <img src="img/seleziona.jpg" width="16" height="16" border="0">
                            <?php print getColorPicker($fileName, $id) ?>
                        </div>
                    </td>
                    <td <?php if($colore!="") echo("bgcolor=\"$colore\" ") ?> width="3"></td>
                </tr>
                <?
            }
        }
        $i=0;
        $cont=0;
        ?>
    </table>
    <table width="100%"  border="0" cellspacing="0" cellpadding="0" bordercolor="#FFFFFF" id="table3">
        <tr bordercolor="FFFFFF" style=" ">
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td> </td>
            <td align="center">&nbsp;</td>
        </tr>
        <tr bordercolor="FFFFFF">
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td> </td>
            <td align="center">&nbsp;</td>
        </tr>
    </table>
    <table width="100%"  border="0" cellspacing="0" cellpadding="0" bordercolor="#FFFFFF" id="table4">
        <tr>
            <th width="25%" bordercolor="999999" align="center">&nbsp;</th>
            <th width="20%" bordercolor="999999" align="center">&nbsp;</th>
            <th width="15%" bordercolor="999999" align="center">&nbsp;</th>
            <th width="5%" bordercolor="999999" align="center">&nbsp;</th>
            <th width="10%" bordercolor="999999" align="center">&nbsp;</th>
            <th width="17%" bordercolor="999999" align="center">&nbsp;</th>
            <th width="7%" align="center"></th>
        </tr>

        <?
        //        while ($array = mysqli_fetch_array($ris2)){
        foreach ($rows2 as $array) {
            $id = $array['id'];
            $idcer = $array['idcer'];
            @$idgruppo2 = $idgruppo;
            $idgruppo = $array['idgruppo'];
            @$ceramica2 = $ceramica;
            $ceramica = $array['Ceramica'];
            $cliente = $array['cliente'];
            $autista = $array['autista'];
            $quintali = $array['quintali'];
            $palette = $array['palette'];
            $note = $array['note'];
            $indirizzo = $array['indirizzo'];
            $telefono = $array['telefono'];
            $noteCer = $array['noteCer'];
            $sel = $array['selezionato'];
            $colore = $array['colore'];
            $eliminato = $array['eliminato'];

            $descrizione = makeDescriptionString($indirizzo, $telefono, $noteCer);
            $cont++;
            $i++;
            @$tot_complessivo+=$quintali;
            if ($idgruppo != $idgruppo2){
                ?>
                <? if ($cont>1) { ?>
                    <tr bordercolor="FFFFFF">
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td> </td>
                    <td width="150" bordercolor="999999" style="font-size:12px" align="center"><strong><? print "TOT : ".$tot ?></strong></td>
                    </tr><? } $cont=0; $tot=0;?>
                <tr class="row-separator" bordercolor="#000000">
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
                    <td> </td>
                    <td align="center">&nbsp;</td>
                </tr>
                <?
                if (trim($ceramica) != trim($ceramica2)){
                    ?>
                    <tr bordercolor="FFFFFF">
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td> </td>
                        <td align="center">&nbsp;</td>
                    </tr>
                    <tr id="datarow-<?php print $id ?>" <?php if($eliminato) echo "class='data-row-del'"?>>
                        <td <? if($ceramicheOccurrences2[trim($ceramica)] > 1) echo("rowspan=".$ceramicheOccurrences2[trim($ceramica)]) ?> valign="top" width="180" bordercolor="999999" style="font-size:12px ">
                            <strong>
                                <a href="gestioneProntoCeramica.php?nome=<? print $ceramica ?>"><? print $ceramica ?></a>
                            </strong>
                            <br><? print $descrizione ?></br>
                        </td>
                        <td class="colorable" <?php if($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999" style="font-size:12px"><a href="modificaProntoCeramiche.php?id=<? print $id ?>"><? print $cliente ?></a></td>
                        <td class = "colorable" width="100" <?php if($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999" style="font-size:12px">
                            <?
                            print '<a href="' . createSearchURL($_SERVER['REQUEST_URI'], "autista='$autista'") . '">' . "$autista </a>"
                            ?>
                        </td>                        <td class="colorable" <?php if($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999" style="font-size:12px" align="center"><? print $quintali ?></td>
                        <td class="colorable" <?php if($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999" style="font-size:12px" align="center"><? print $palette ?></td>
                        <td class="colorable" <?php if($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999" style="font-size:12px" align="center" <? if(($note=="URGENTE") || ($note=="TASSATIVO")) { ?> style="color:#FF0000" <? } ?>><? print $note ?></td>
                        <td class="row-actions" align="center" bordercolor="999999"><a href="cancellaProntoCeramiche.php?id=<? print $id ?>">
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
                        <td <?php if($colore!="") echo("bgcolor=\"$colore\" ") ?> width="3"></td>
                    </tr>
                    <?
                    @$tot=$tot+$quintali;
                }
                else
                {
                    @$tot=$tot+$quintali;
                    ?>
                    <tr id="datarow-<?php print $id ?>" <?php if($eliminato) echo "class='data-row-del'"?>>
                        <!--                        <td width="180" bordercolor="999999"></td>-->
                        <td class="colorable" <?php if($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999" style="font-size:12px"><a href="modificaProntoCeramiche.php?id=<? print $id ?>"><? print $cliente ?></a></td>
                        <td class = "colorable" width="100" <?php if($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999" style="font-size:12px">
                            <?
                            print '<a href="' . createSearchURL($_SERVER['REQUEST_URI'], "autista='$autista'") . '">' . "$autista </a>"
                            ?>
                        </td>
                        <td class="colorable" <?php if($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999" style="font-size:12px"><? print $quintali ?></td>
                        <td class="colorable" <?php if($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999" style="font-size:12px"><? print $palette ?></td>
                        <td class="colorable" <?php if($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999" style="font-size:12px" <? if(($note=="URGENTE") || ($note=="TASSATIVO")) { ?> style="color:#FF0000" <? } ?>><? print $note ?></td>
                        <td class="row-actions" align="center" bordercolor="999999"><a href="cancellaProntoCeramiche.php?id=<? print $id ?>">
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
                        <td <?php if($colore!="") echo("bgcolor=\"$colore\" ") ?> width="3"></td>
                    </tr>
                    <?
                }
            }
            else if (trim($ceramica) != trim($ceramica2)){
                ?>
                <tr bordercolor="FFFFFF">
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td> </td>
                    <td> </td>
                    <td align="center">&nbsp;</td>
                </tr>
                <tr id="datarow-<?php print $id ?>" <?php if($eliminato) echo "class='data-row-del'"?>>
                    <td width="180" bordercolor="999999" style="font-size:12px "><strong><a href="gestioneProntoCeramica.php?nome=<? print $ceramica ?>"><? print $ceramica ?></a></strong></td>
                    <td class="colorable" <?php if($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999" style="font-size:12px"><a href="modificaProntoCeramiche.php?id=<? print $id ?>"><? print $cliente ?></a></td>
                    <td class = "colorable" width="100" <?php if($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999" style="font-size:12px">
                        <?
                        print '<a href="' . createSearchURL($_SERVER['REQUEST_URI'], "autista='$autista'") . '">' . "$autista </a>"
                        ?>
                    </td>
                    <td class="colorable" <?php if($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999" style="font-size:12px" align="center"><? print $quintali ?></td>
                    <td class="colorable" <?php if($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999" style="font-size:12px" align="center"><? print $palette ?></td>
                    <td class="colorable" <?php if($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999" style="font-size:12px" align="center" <? if(($note=="URGENTE") || ($note=="TASSATIVO")) { ?> style="color:#FF0000" <? } ?>><? print $note ?></td>
                    <td class="row-actions" align="center" bordercolor="999999">
                        <a href="cancellaProntoCeramiche.php?id=<? print $id ?>">
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
                    <td <?php if($colore!="") echo("bgcolor=\"$colore\" ") ?> width="3"></td>
                </tr>
                <?
                @$tot=$tot+$quintali;
            }
            else
            {
                @$tot=$tot+$quintali;
                ?>
                <tr id="datarow-<?php print $id ?>" <?php if($eliminato) echo "class='data-row-del'"?>>
                    <!--                    <td width="180" bordercolor="999999"></td>-->
                    <td class="colorable" <?php if($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999" style="font-size:12px"><a href="modificaProntoCeramiche.php?id=<? print $id ?>"><? print $cliente ?></a></td>
                    <td class = "colorable" width="100" <?php if($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999" style="font-size:12px">
                        <?
                        print '<a href="' . createSearchURL($_SERVER['REQUEST_URI'], "autista='$autista'") . '">' . "$autista </a>"
                        ?>
                    </td>
                    <td class="colorable" <?php if($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999" style="font-size:12px" align="center"><? print $quintali ?></td>
                    <td class="colorable" <?php if($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999" style="font-size:12px" align="center"><? print $palette ?></td>
                    <td class="colorable" <?php if($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999" style="font-size:12px" align="center" <? if(($note=="URGENTE") || ($note=="TASSATIVO")) { ?> style="color:#FF0000" <? } ?>><? print $note ?></td>
                    <td class="row-actions" align="center" bordercolor="999999">
                        <a href="cancellaProntoCeramiche.php?id=<? print $id ?>">
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
                    <td <?php if($colore!="") echo("bgcolor=\"$colore\" ") ?> width="3"></td>
                </tr>
                <?
            }
        }
        ?>
    </table>
    <hr>
    <table width="100%" id="table5">
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
