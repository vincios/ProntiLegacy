<?php
function getLegend($db, $name, $date) {
    $queryLegend = "SELECT l.legend FROM legend l WHERE l.table_name = '$name' AND l.creation_date <= '$date' ORDER BY l.creation_date DESC LIMIT 1";
    $legend_query_res = mysqli_query($db, $queryLegend) or die(mysqli_error($db));
    $legend = json_decode(mysqli_fetch_assoc($legend_query_res)['legend'], true);
    $GLOBALS['legend'] = $legend;
    $html = '<div ';

    if($legend === null) {
        $html .= 'class="hidden" ';
    }

    $html .= '<div style="padding-bottom: 5px">
                        <span style="font-size: medium">Legenda</span>
                        <a href="#" id="editLegendBtn" class="archivio-hidden row-actions"><img src="img/seleziona.jpg" alt="edit" width="14" height="14"/></a>
                    </div>
                    <div class="legend">
                        <span class="legend blue">' .$legend["blue"].'</span>
                        <span class="legend green">'.$legend["green"].'</span>
                        <span class="legend red">'.$legend["red"].'</span>
                        <span class="legend yellow">'.$legend["yellow"].'</span>
                    </div>
                </div>';

    return $html;
}

function getEditLegendModal($name, $date, $redirectUrl) {
    $currentLegend = $GLOBALS['legend'];
    $currentLegendBlue = $currentLegend !== null ? $currentLegend['blue'] : "";
    $currentLegendGreen = $currentLegend !== null ? $currentLegend['green'] : "";
    $currentLegendRed = $currentLegend !== null ? $currentLegend['red'] : "";
    $currentLegendYellow = $currentLegend !== null ? $currentLegend['yellow'] : "";

    return "<!-- The Modal -->
            <div id=\"editLegendModal\" class=\"modal\">
                <!-- Modal content -->
                <div class=\"modal-content legend\">
                    <div class=\"modal-header\">
                        <span class=\"modal-close\">&times;</span>
                        <h2>Modifica legenda</h2>
                    </div>
                    <form class='edit-legend-form' action='functionEditLegend.php'>
                    <input type='hidden' name='table' value='$name'/>
                    <input type='hidden' name='date' value='$date'/>
                    <input type='hidden' name='redirectUrl' value='$redirectUrl'/>
                        <div class=\"modal-body\">
                            <div style='padding-bottom: 7px'>
                                <span style='display: inline' class='legend blue'>Blu</span>
                                <input style='margin-left: 25px' type='text' maxlength='500' size='30' name='blueText' value='$currentLegendBlue'/>
                            </div>
                            <div style='padding-bottom: 7px'>
                                <span style='display: inline' class='legend green'>Verde</span>
                                <input style='margin-left: 10px' type='text' maxlength='500' size='30' name='greenText' value='$currentLegendGreen'/>
                            </div>
                            <div style='padding-bottom: 7px'>
                                <span style='display: inline' class='legend red'>Rosso</span>
                                <input style='margin-left: 8px' type='text' maxlength='500' size='30' name='redText' value='$currentLegendRed'/>
                            </div>
                            <div style='padding-bottom: 7px'>
                                <span style='display: inline' class='legend yellow'>Giallo</span>
                                <input style='margin-left: 10px' type='text' maxlength='500' size='30' name='yellowText' value='$currentLegendYellow'/>
                            </div>
                        </div>
                        <div class=\"modal-footer clearfix\">
                            <input style='float: right' class=\"btn waves-effect waves-light\" type=\"submit\" name=\"Submit\" value=\"Salva\" />
                        </div>
                    </form>
                </div>
            </div>";
}
