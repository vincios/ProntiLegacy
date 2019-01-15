<?php
include_once("include/libreria.php");

function getColorPicker($page, $rowId) {
    global $COLORE_SEL;

    $html = '<table class="colorPopup" width = "100%" >
    <tr style="border-width: 0px">
   <td colspan="4" style="text-align: end; font-size: 0.7em;">Chiudi</td>
</tr>
    <tr >
        <td onclick = "sendRowColor(\'' . $page .'\', \'' . $rowId . '\', 0)" colspan = "4" > Nessuno</td >
    </tr >
    <tr >
        <td width = "20%" onclick = "sendRowColor(\'' . $page .'\', \'' . $rowId . '\', 4)" bgcolor = "' . $COLORE_SEL[4] . '" ></td >
        <td width = "20%" onclick = "sendRowColor(\'' . $page .'\', \'' . $rowId . '\', 3)" bgcolor = "' . $COLORE_SEL[3] . '" >&nbsp;</td >
        <td width = "20%" onclick = "sendRowColor(\'' . $page .'\', \'' . $rowId . '\', 2)" bgcolor = "' . $COLORE_SEL[2] . '" ></td >
        <td width = "20%" onclick = "sendRowColor(\'' . $page .'\', \'' . $rowId . '\', 1)" bgcolor = "' . $COLORE_SEL[1] . '" ></td >
    </tr >
</table >';

    return $html;
}