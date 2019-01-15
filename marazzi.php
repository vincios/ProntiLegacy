<?
include("include/conn.php");
include("include/libreria.php");
include("include/color_picker_block.php");
include("include/header.php");

$fileName = basename(__FILE__, ".php");

VerificaUtente(); //Verifico se l'utente connesso è un utente autorizzato

$today = getdate();
$date = $today['mday'] . " " . $today['month'];

$archivio = isset($_REQUEST['archivio']);

if($archivio) {
    $dataArchvio = $_REQUEST['archivio'];
    $date = date('d-m-Y', strtotime($dataArchvio));

    $query = "SELECT * FROM (
                                SELECT pm.id,pm.Deposito,pm.Cliente,pm.quintali,pm.dds,pm.note,m.indirizzo,pm.selezionato, pm.data_aggiunta, pm.eliminato, pm.data_eliminazione, m.colore 
                                FROM prontimarazzi pm JOIN marazzi m
                                WHERE pm.deposito=m.nome and pm.eliminato=0 and date(pm.data_aggiunta) <= '$dataArchvio'
                                UNION
                                SELECT pm.id,pm.Deposito,pm.Cliente,pm.quintali,pm.dds,pm.note,m.indirizzo,pm.selezionato, pm.data_aggiunta, pm.eliminato, pm.data_eliminazione, m.colore 
                                FROM prontimarazzi pm JOIN marazzi m
                                WHERE pm.deposito=m.nome and pm.eliminato=1 and ('$dataArchvio' between date(pm.data_aggiunta) and pm.data_eliminazione)
                            ) AS T1
              ORDER by deposito,cliente,dds";
} else {
    $query = "SELECT prontimarazzi.id,prontimarazzi.Deposito,prontimarazzi.Cliente,prontimarazzi.quintali,prontimarazzi.dds,prontimarazzi.note,marazzi.indirizzo,prontimarazzi.selezionato,marazzi.colore FROM prontimarazzi JOIN marazzi WHERE prontimarazzi.deposito=marazzi.nome and eliminato=0 ORDER by deposito,cliente,dds";
}
$ris = mysqli_query($db, $query) or die(mysqli_error($db));
$num = mysqli_num_rows($ris);
$cont = 0;
$tot = 0;
$tot_complessivo = 0;
$i = 0;
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/html">
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
            font-size: 21px;
        }

        -->
    </style>
    <link rel="stylesheet" type="text/css" href="css/color_popup.css">
    <link rel="stylesheet" type="text/css" href="styleCheck.css">
    <script type="application/javascript" src="functions.js"></script>
</head>

<body>
<?php
if($archivio) {
    echo getHeader('archivio');
} else {
    echo getHeader('marazzi');
}
?>

<p id="isArchivio" class="hidden"><?php echo $archivio === true ? 'true' : 'false'?></p>
<div class="page-content">
    <table width="100%" height="80" border="0">
        <tr>
            <td width="49%">
                <div align="center" class="Titolo roboto-font underlined">MARAZZI : <? print $date ?></div>
            </td>
            <td width="19%" class="archivio-hidden">
                <form id="frmEliminaEvidenziati" name="frmEliminaEvidenziati" method="post" action="functionEliminaProntiMarazziEvidenziati.php">
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
            <td width="12%" class="archivio-hidden">
                <form name="form1" method="post" action="ricercamarazzi.php">
                    <input type="submit" name="Submit" value="Ricerca">
                </form>
            </td>
            <td width="20%" class="archivio-hidden">
                <form name="form1" method="post" action="inserisciProntoMarazzi.php">
                    <input type="submit" name="Submit" value="Inserisci Pronto">
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
            <th width="120" bordercolor="999999" align="center"><strong>Note</strong></th>
            <th width="45" align="center"></th>
        </tr>
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
            $eliminato = isset($array['eliminato']) ? $array['eliminato'] : false;
            $cont++;
            $i++;
            if (trim($deposito) != trim($deposito2)) {
                $cliente2 = null;
                ?>
                <? if ($cont > 1) { ?>
                    <tr bordercolor="FFFFFF">
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td width="120" bordercolor="999999" style="font-size:12px" align="center">
                        <strong><? print "TOT : " . $tot ?></strong></td>
                    </tr><?
                }
                @$tot_complessivo += $tot;
                $cont = 0;
                $tot = 0;
                ?>
                <tr bordercolor="FFFFFF">
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td align="center">&nbsp;</td>
                </tr>
                <?
                @$tot = $tot + $quintali;
                if (trim($cliente) != trim($cliente2)) {
                    ?>
                    <tr id="datarow-<? print $id ?>" <?php if($eliminato) echo "class='data-row-del'"?>>
                        <td width="210" bordercolor="999999" style="font-size:12px"><a
                                    href="gestioneProntoMarazzi.php?nome=<? print $deposito ?>"><strong><? print $deposito ?>
                            </a></strong><br><? print $indirizzo ?></br></td>
                        <td class = "colorable" width="180" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                            style="font-size:12px "><a
                                    href="modificaProntoMarazzi.php?id=<? print $id ?>"><? print $cliente ?></a></td>
                        <td class = "colorable"  width="160" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                            style="font-size:12px "><? print $dds ?></td>
                        <td class = "colorable"  width="70" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                            style="font-size:12px" align="center"><? print $quintali ?></td>
                        <td class = "colorable"  width="120" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                            style="font-size:12px"
                            align="center" <? if (($note == "URGENTE") || ($note == "TASSATIVO")) { ?> style="color:#FF0000 " <? } ?>><? print $note ?></td>
                        <td class="row-actions" width="45" align="center" bordercolor="999999">
                            <a href="cancellaProntoMarazzi.php?id=<? print $id ?>">
                                <img src="img/cancellaAdminPiccolo.gif" width="16" height="16" border="0"></a>

                            <!--SPOSTA CERAMICA TO DEPOSITO-->
                            <a href="functionMoveProntoToDeposito.php?id=<? print $id ?>&from=<?php echo $fileName ?>&to=AA%20DEP.MOLISE&redirectUrl=<?print $_SERVER['PHP_SELF']?>">
                                <img class="" src="img/next.png" width="16" height="16" border="0">
                            </a>

                            <div class="evidenzia">
                                <img src="img/seleziona.jpg" width="16" height="16" border="0">
                                <? print getColorPicker($fileName, $id); ?>
                            </div></td>
                        <td <?php if ($colore != "") echo("bgcolor=\"$colore\" ") ?> width="3"></td>
                    </tr>
                    <?
                } else {
                    ?>
                    <tr id="datarow-<? print $id ?>" <?php if($eliminato) echo "class='data-row-del'"?>>
                        <td width="210" bordercolor="999999" style="font-size:12px">
                            <a href="gestioneProntoMarazzi.php?nome=<? print $deposito ?>">
                                <strong><? print $deposito ?></strong>
                            </a>
                        </td>
                        <td class="colorable" width="180" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                            style="font-size:12px "><a
                                    href="modificaProntoMarazzi.php?id=<? print $id ?>"><? print $cliente ?></a></td>
                        <td class="colorable" width="160" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                            style="font-size:12px"><? print $dds ?></td>
                        <td class="colorable" width="70" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                            style="font-size:12px" align="center"><? print $quintali ?></td>
                        <td class="colorable" width="120" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                            style="font-size:12px"
                            align="center" <? if (($note == "URGENTE") || ($note == "TASSATIVO")) { ?> style="color:#FF0000 " <? } ?>><? print $note ?></td>
                        <td class="row-actions" width="45" align="center" bordercolor="999999"><a
                                    href="cancellaProntoMarazzi.php?id=<? print $id ?>">
                                <img src="img/cancellaAdminPiccolo.gif" width="16" height="16" border="0"></a>

                            <!--SPOSTA CERAMICA TO DEPOSITO-->
                            <a href="functionMoveProntoToDeposito.php?id=<? print $id ?>&from=<?php echo $fileName ?>&to=AA%20DEP.MOLISE&redirectUrl=<?print $_SERVER['PHP_SELF']?>">
                                <img class="" src="img/next.png" width="16" height="16" border="0">
                            </a>

                            <div class="evidenzia">
                                <img src="img/seleziona.jpg" width="16" height="16" border="0">
                                <? print getColorPicker($fileName, $id); ?>
                            </div>
                        </td>
                        <td <?php if ($colore != "") echo("bgcolor=\"$colore\" ") ?> width="3"></td>
                    </tr>
                    <?
                }
            } else {
                @$tot = $tot + $quintali;
                if (trim($cliente) != trim($cliente2)) {
                    ?>
                    <tr id="datarow-<? print $id ?>" <?php if($eliminato) echo "class='data-row-del'"?>>
                        <td width="210" bordercolor="999999"></td>
                        <td class="colorable" width="180" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                            style="font-size:12px "><a
                                    href="modificaProntoMarazzi.php?id=<? print $id ?>"><? print $cliente ?></a></td>
                        <td class="colorable" width="160" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                            style="font-size:12px "><? print $dds ?></td>
                        <td class="colorable" width="70" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                            style="font-size:12px" align="center"><? print $quintali ?></td>
                        <td class="colorable" width="120" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                            style="font-size:12px"
                            align="center" <? if (($note == "URGENTE") || ($note == "TASSATIVO")) { ?> style="color:#FF0000 " <? } ?>><? print $note ?></td>
                        <td class="row-actions" width="45" align="center" bordercolor="999999">
                            <a href="cancellaProntoMarazzi.php?id=<? print $id ?>">
                                <img src="img/cancellaAdminPiccolo.gif" width="16" height="16" border="0">
                            </a>

                            <!--SPOSTA CERAMICA TO DEPOSITO-->
                            <a href="functionMoveProntoToDeposito.php?id=<? print $id ?>&from=<?php echo $fileName ?>&to=AA%20DEP.MOLISE&redirectUrl=<?print $_SERVER['PHP_SELF']?>">
                                <img class="" src="img/next.png" width="16" height="16" border="0">
                            </a>

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
                    <tr id="datarow-<? print $id ?>" <?php if($eliminato) echo "class='data-row-del'"?>>
                        <td width="210" bordercolor="999999"></td>
                        <td class="colorable" width="180" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                            style="font-size:12px "><a
                                    href="modificaProntoMarazzi.php?id=<? print $id ?>"><? print $cliente ?></a></td>
                        <td class="colorable" width="160" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                            style="font-size:12px "><? print $dds ?></td>
                        <td class="colorable" width="70" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                            style="font-size:12px" align="center"><? print $quintali ?></td>
                        <td class="colorable" width="120" <?php if ($sel) echo("bgcolor=\"$COLORE_SEL[$sel]\""); ?> bordercolor="999999"
                            style="font-size:12px"
                            align="center" <? if (($note == "URGENTE") || ($note == "TASSATIVO")) { ?> style="color:#FF0000 " <? } ?>><? print $note ?></td>
                        <td class="row-actions" width="45" align="center" bordercolor="999999">
                            <a href="cancellaProntoMarazzi.php?id=<? print $id ?>">
                                <img src="img/cancellaAdminPiccolo.gif" width="16" height="16" border="0"></a>

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

<!-- Add listener to elements-->
<script type="application/javascript" src="js/main_pages_loading.js" defer></script>
</body>
</html>
