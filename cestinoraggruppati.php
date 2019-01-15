<?
	include("include/conn.php");
	include("include/header.php");
	include("auth.php");
	
    if (isset($_COOKIE['USERNAME']) && isset($_COOKIE['PASSWORD']))
    {
        // Get values from superglobal variables
        $USERNAME = $_COOKIE['USERNAME'];
        $PASSWORD = $_COOKIE['PASSWORD'];

        $CheckSecurity = new auth();
        $check = $CheckSecurity->page_check($USERNAME, $PASSWORD);
		
		if ($check == false)
		{
			echo "Utente non autorizzato";	
			exit;
		}
	}
	else
	{
			echo "Utente non autorizzato";	
			exit;
	}

$today = date("d-m-Y", mktime(0,0,0));
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Documento senza titolo</title>
    <link rel="stylesheet" type="text/css" href="css/general.css">
    <script type="application/javascript" src="functions.js"></script>
<style type="text/css">
<!--
body,td,th {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	color: #000000;
}
body {
	background-color: #EAEAEA;
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
-->
</style></head>

<body>
<?php echo getHeader('cestino') ?>

<div class="page-content">
    <h3 class="bold roboto-font centered">Ricerca nel cestino dei pronti raggruppati</h3>
    <div class="centered" style="width: 600px; padding: 20px;">
        <div class="inner-left" style="padding: 20px;">
            <form action="ricercaCestinoRaggruppati.php" method="get">
                <span class="roboto-font" style="display: inline-block; width: 100px">Intervallo</span>
                <span style="display: inline-block">
                <label for="Dal" style="display:block;">dal giorno</label>
                <input type="text" name="Dal" maxlength="10">
            </span>
                <span style="display: inline-block">
                <label for="Al" style="display:block;">al giorno</label>
                <input type="text" name="Al" maxlength="10">
            </span>
                <input type="submit" value="Invia">
            </form>
        </div>
        <div class="inner-left" style="padding: 20px;">
            <form action="ricercaCestinoRaggruppati.php" method="get">
                <label for="Giorno" class="roboto-font" style="display: inline-block; width: 100px">Giorno</label>
                <input type="text" name="Giorno" maxlength="10">
                <input type="submit" value="Invia">
            </form>
        </div>
        <div class="inner-left" style="padding: 20px">
            <form action="ricercaCestinoRaggruppati.php" method="get">
                <span class="roboto-font" style="display: inline-block; width: 100px">Giorno Corrente</span>
                <input type="hidden" name="Giorno" value="<?php echo $today?>">
                <input type="submit" value="Invia">
            </form>
        </div>
    </div>
</div>

<script type="application/javascript" defer>
    let inputs = document.getElementsByTagName("input");

    for(var i=0; i<inputs.length; i++) {
        if(inputs[i].type === "text") {
            inputs[i].addEventListener("keydown", dateAutoCompletition);
        }
    }
</script>
</body>
</html>
