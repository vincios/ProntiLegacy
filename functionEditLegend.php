<?php
require('include/conn.php');
require('include/libreria.php');

$tableName = $_REQUEST['table'];
$date = $_REQUEST['date'];
$redirectUrl = $_REQUEST['redirectUrl'];

$blueLegend = $_REQUEST['blueText'];
$greenLegend = $_REQUEST['greenText'];
$redLegend = $_REQUEST['redText'];
$yellowLegend = $_REQUEST['yellowText'];

$newLegend = array(
    'blue' => $blueLegend,
    'green' => $greenLegend,
    'red' => $redLegend,
    'yellow' => $yellowLegend
);
$newLegendJSON = json_encode($newLegend);

$getLegendQuery = "SELECT id FROM legend WHERE table_name = '$tableName' AND creation_date = '$date'";
$result = mysqli_query($db, $getLegendQuery);
$legendResult = mysqli_fetch_assoc($result);
$legendId = $legendResult !== null ? $legendResult['id'] : -1;

$updateLegendQuery = "";

if($legendId >= 0) {
   $updateLegendQuery = "UPDATE legend SET legend='$newLegendJSON' WHERE id=$legendId";
} else {
    $updateLegendQuery = "INSERT INTO legend(CREATION_DATE, TABLE_NAME, LEGEND) VALUES ('$date', '$tableName', '$newLegendJSON')";
}
$res = mysqli_query($db, $updateLegendQuery);

if(!$res) {
    printError(mysqli_error($db), $redirect);
    exit();
}

header("location:" . $redirectUrl);
