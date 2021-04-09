<?
	//$db = mysql_connect('62.149.150.64','Sql119682','b3899afa') or die ("Impossibile connettersi al server");
$db = mysqli_connect('localhost','menzione','trasporti') or die ("Impossibile connettersi al server");
//$db = mysqli_connect('192.168.1.11','menzione','trasporti') or die ("Impossibile connettersi al server");

	mysqli_select_db($db, 'sql119682_1') or die ("Impossibile connettersi al database");
?>
