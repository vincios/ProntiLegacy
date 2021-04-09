<?
include ("include/conn.php");

$id = $_REQUEST['id'];
$nome = mysqli_escape_string($db, strtoupper($_REQUEST['ceramica']));
$cliente = mysqli_escape_string($db, strtoupper($_REQUEST['cliente']));
$autista = mysqli_escape_string($db, strtoupper($_REQUEST['autista']));
$quintali = mysqli_escape_string($db, $_REQUEST['quintali']);
$palette = mysqli_escape_string($db, $_REQUEST['palette']);
$note = mysqli_escape_string($db, $_REQUEST['note']);

//	$query = "UPDATE pronticeramiche SET Ceramica =\"$nome\","."cliente =\"$cliente\","."quintali=\"$quintali\", palette='$palette',"."note=\"$note\""."Where id=$id";
$query = "UPDATE pronticeramiche SET Ceramica='$nome', cliente='$cliente', autista='$autista', quintali='$quintali', palette='$palette', note='$note' WHERE id=$id";
$ris = mysqli_query($db, $query);

if(!$ris) {
    echo mysqli_error($db);
} else {
    print '	<script language="javascript">	
	         document.location.href="ceramiche.php?";
	           </script>';
}
?>