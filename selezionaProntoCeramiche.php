<?
include ("include/conn.php");
include("include/libreria.php");

$id = $_REQUEST['id'];
$color = $_REQUEST['color'];

$res = array(
    "result" => false,
    "color" => null
);

if(!is_numeric($color) || $color < 0 || $color >= count($COLORE_SEL)) {
    echo json_encode($res);
    exit();
}

$query = "UPDATE pronticeramiche set selezionato=". $color . " WHERE id = '" . $id . "'";
mysqli_query($db, $query) or die( mysqli_error($db) );

$query = "SELECT selezionato from pronticeramiche WHERE id = '" . $id . "'";
$sel = mysqli_query($db, $query) or die( mysqli_error($db) );
$sel = mysqli_fetch_array($sel, MYSQLI_BOTH);
$sel = $sel['selezionato'];

if($sel === $color) {
    $res['result'] = true;
    $res['color'] = $sel;
    echo json_encode($res);
} else {
    echo json_encode($res);
}

exit();
/*VaiURL("ceramiche.php");
print '	<script language="javascript">
	        document.location.href="ceramiche.php";
	        </script>';
*/
?>
