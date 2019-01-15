<?
include ("include/conn.php");
include("include/libreria.php");

if(isset($_REQUEST['chkColorsToDelete'])) {
    $colorToDelete = $_REQUEST['chkColorsToDelete'];
    $today = getdate();

    $giorno = $today['mday'];
    $mese = $today['mon'];
    $anno = $today['year'];

    $queryColors = "(";
    $i = 0;
    foreach ($colorToDelete as $colorName) {
        if ($i > 0) { //not add or to fist color
            $queryColors .= " OR ";
        }

        $queryColors .= " selezionato = " . $COLORE_SEL_INDEXES[$colorName];
        $i++;
    }
    $queryColors .= ")";

    $query = "UPDATE prontidepositi set eliminato=1, data_eliminazione='$anno-$mese-$giorno' WHERE eliminato=0 AND " . $queryColors;
    $ris = mysqli_query($db, $query) or die(mysqli_error($db));
}
//VaiURL("depositi.php");
print '	<script language="javascript">	
	        document.location.href="depositi.php";
	        </script>';
?>