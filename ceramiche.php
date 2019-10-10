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

if($archivio) {
    $dataArchvio = $_REQUEST['archivio'];
    $dateNumber = $dataArchvio;
    $dateStr = date('d-m-Y', strtotime($dataArchvio));

    $query = "SELECT * FROM (
                              select * from pronticeramiche pc
                              JOIN ceramica c
                              WHERE c.idgruppo=0 and c.nome=pc.ceramica and pc.eliminato=0 and date(pc.data_aggiunta) <= '$dataArchvio'
                              UNION
                              SELECT * from pronticeramiche pc
                              JOIN ceramica c
                              WHERE c.idgruppo=0 and c.nome=pc.ceramica and pc.eliminato=1 and ('$dataArchvio' between date(data_aggiunta) and data_eliminazione)
                            ) as T1
		ORDER by ceramica,cliente";
} else {
    $query = "SELECT * FROM pronticeramiche JOIN ceramica WHERE ceramica.idgruppo=0 and ceramica.nome=pronticeramiche.ceramica and eliminato=0 ORDER by ceramica,cliente";
}

$ris = mysqli_query($db, $query) or die( mysqli_error($db) );
$num = mysqli_num_rows($ris);
$cont=0;
$tot=0;
$tot_complessivo=0;
$i=0;

if($archivio) {
    $query =  "SELECT * FROM (
                                SELECT * FROM pronticeramiche 
                                JOIN ceramica 
                                WHERE ceramica.idgruppo!=0 and ceramica.nome=pronticeramiche.ceramica and eliminato=0 and date(pronticeramiche.data_aggiunta) <= '$dataArchvio' 
                                UNION
                                SELECT * FROM pronticeramiche 
                                JOIN ceramica 
                                WHERE ceramica.idgruppo!=0 and ceramica.nome=pronticeramiche.ceramica and eliminato=1 and ('$dataArchvio' between date(data_aggiunta) and data_eliminazione) 
                              ) AS T1
                ORDER by idgruppo,ceramica,cliente";
} else {
    $query = "SELECT * FROM pronticeramiche JOIN ceramica WHERE ceramica.idgruppo!=0 and ceramica.nome=pronticeramiche.ceramica and eliminato=0 ORDER by idgruppo,ceramica,cliente";
}

$ris2 = mysqli_query($db, $query) or die( mysqli_error($db) );
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

<p id="isArchivio" class="hidden"><?php echo $archivio === true ? 'true' : 'false'?></p>
<div class="page-content">
    <table width="100%" height="80"  border="0">
        <tr>
            <td width="40%"><div align="center" class="Titolo roboto-font underlined">
                    CERAMICHE : <? print $dateStr ?></div>
            </td>
            <td width="25%">
                <? echo getLegend($db, $fileName, $dateNumber); ?>
            </td>
            <td width="15%" class="archivio-hidden">
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
            <td width="10%" class="archivio-hidden"><form name="form1" method="post" action="ricercaceramiche.php">
                    <input type="submit" name="Submit" value="Ricerca">
                </form></td>
            <td width="10%" class="archivio-hidden"><form name="form1" method="post" action="inserisciProntoCeramiche.php">
                    <input type="submit" name="Submit" value="Inserisci Pronto">
                </form></td>
        </tr>
    </table>
    <table width="100%"  border="0" cellspacing="0" cellpadding="0" bordercolor="#FFFFFF">
        <tr>
            <th width="170" bordercolor="999999" align="center"><strong>Ceramica</strong></th>
            <th width="180" bordercolor="999999" align="center"><strong>Cliente</strong></th>
            <th width="70" bordercolor="999999" align="center"><strong>Q.li</strong></th>
            <th width="70" bordercolor="999999" align="center"><strong>Palette</strong></th>
            <th width="150" bordercolor="999999" align="center"><strong>Note</strong></th>
            <th width="40" align="center"></th>
        </tr>
        <? $j=0;
        while ($array = mysqli_fetch_array($ris)){
            $id = $array['id'];
            $idcer = $array['idcer'];
            @$ceramica2 = $ceramica; //ceramica2 = ceramica on previous iteration
            $ceramica = $array['Ceramica'];
            $cliente = $array['cliente'];
            $quintali = $array['quintali'];
            $palette = $array['palette'];
            $note = $array['note'];
            $indirizzo = $array['indirizzo'];
            $sel = $array['selezionato'];
            $colore = $array['colore'];
            $eliminato = $array['eliminato'];
            $cont++;
            $i++;
            $j++;
            if (trim($ceramica) != trim($ceramica2)){

                ?>
                <? if ($cont>1) {
                    ?>
                    <tr bordercolor="FFFFFF">
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td> </td>
                    <td width="150" bordercolor="999999" style="font-size:12px" align="center"><strong><? print "TOT : ".$tot ?></strong></td>
                    </tr><?
                }
                @$tot_complessivo+=$tot; $cont=0; $tot=0;
                ?>
                <tr bordercolor="FFFFFF">
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td> </td>
                    <td>&nbsp;</td>
                    <td align="center">&nbsp;</td>
                </tr>
                <tr id="datarow-<?php print $id?>" style="color:#FF0000" <?php if($eliminato) echo "class='data-row-del'"?>>
                    <td width="180" bordercolor="999999" style="font-size:12px"><strong><a href="gestioneProntoCeramica.php?nome=<? print $ceramica ?>"><? print $ceramica?></a></strong><br><? print $indirizzo ?></br></td>
                    <td class = "colorable" width="180" <?php if($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999" style="font-size:12px"><a href="modificaProntoCeramiche.php?id=<? print $id ?>"><? print $cliente ?></a></td>
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
                <tr id="datarow-<?php print $id ?>"  <?php if($eliminato) echo "class='data-row-del'"?>>
                    <td width="180" bordercolor="999999"></td>
                    <td class="colorable" <?php if($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999" style="font-size:12px"><a href="modificaProntoCeramiche.php?id=<? print $id ?>"><? print $cliente ?></a></td>
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
    <table width="100%"  border="0" cellspacing="0" cellpadding="0" bordercolor="#FFFFFF">
        <tr bordercolor="FFFFFF" style=" ">
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
            <td> </td>
            <td align="center">&nbsp;</td>
        </tr>
    </table>
    <table width="100%"  border="0" cellspacing="0" cellpadding="0" bordercolor="#FFFFFF">
        <? while ($array = mysqli_fetch_array($ris2)){
            $id = $array['id'];
            $idcer = $array['idcer'];
            @$idgruppo2 = $idgruppo;
            $idgruppo = $array['idgruppo'];
            @$ceramica2 = $ceramica;
            $ceramica = $array['Ceramica'];
            $cliente = $array['cliente'];
            $quintali = $array['quintali'];
            $palette = $array['palette'];
            $note = $array['note'];
            $indirizzo = $array['indirizzo'];
            $sel = $array['selezionato'];
            $colore = $array['colore'];
            $eliminato = $array['eliminato'];
            $cont++;
            $i++;
            if ($idgruppo != $idgruppo2){
                ?>
                <? if ($cont>1) { ?>
                    <tr bordercolor="FFFFFF">
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td> </td>
                    <td width="150" bordercolor="999999" style="font-size:12px" align="center"><strong><? print "TOT : ".$tot ?></strong></td>
                    </tr><? } @$tot_complessivo+=$tot; $cont=0; $tot=0;?>
                <tr bordercolor="#000000">
                    <td>___________________</td>
                    <td>___________________</td>
                    <td>________</td>
                    <td>________</td>
                    <td>________________</td>
                </tr>
                <tr bordercolor="FFFFFF">
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td> </td>
                    <td align="center">&nbsp;</td>
                </tr>
                <?
                if (trim($ceramica) != trim($ceramica2)){
                    ?>
                    <tr id="datarow-<?php print $id ?>" <?php if($eliminato) echo "class='data-row-del'"?>>
                    <tr bordercolor="FFFFFF">
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td> </td>
                        <td align="center">&nbsp;</td>
                    </tr>
                    <td width="180" bordercolor="999999" style="font-size:12px "><strong><a href="gestioneProntoCeramica.php?nome=<? print $ceramica ?>"><? print $ceramica ?></a></strong><br><? print $indirizzo ?></br></td>
                    <td class="colorable" <?php if($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999" style="font-size:12px"><a href="modificaProntoCeramiche.php?id=<? print $id ?>"><? print $cliente ?></a></td>
                    <td class="colorable" <?php if($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999" style="font-size:12px" align="center"><? print $quintali ?></td>
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
                        <td width="180" bordercolor="999999"></td>
                        <td class="colorable" <?php if($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999" style="font-size:12px"><a href="modificaProntoCeramiche.php?id=<? print $id ?>"><? print $cliente ?></a></td>
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
                    <td align="center">&nbsp;</td>
                </tr>
                <tr id="datarow-<?php print $id ?>" <?php if($eliminato) echo "class='data-row-del'"?>>
                    <td width="180" bordercolor="999999" style="font-size:12px "><strong><a href="gestioneProntoCeramica.php?nome=<? print $ceramica ?>"><? print $ceramica ?></a></strong></td>
                    <td class="colorable" <?php if($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999" style="font-size:12px"><a href="modificaProntoCeramiche.php?id=<? print $id ?>"><? print $cliente ?></a></td>
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
                    <td width="180" bordercolor="999999"></td>
                    <td class="colorable" <?php if($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999" style="font-size:12px"><a href="modificaProntoCeramiche.php?id=<? print $id ?>"><? print $cliente ?></a></td>
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
    <table width="100%"><tr><td width="33%">&nbsp;</td><td width="33%">&nbsp;</td><td><?php echo("<b>TOTALE COMPLESSIVO:</b> $tot_complessivo"); ?></td></tr></table>
</div>

<?php echo getEditLegendModal($fileName, $dateNumber, $_SERVER['PHP_SELF']); ?>
<!-- Add listener to elements-->
<script type="application/javascript" src="js/edit_legend_modal.js" defer></script>
<script type="application/javascript" src="js/main_pages_loading.js" defer></script>
</body>
</html>
