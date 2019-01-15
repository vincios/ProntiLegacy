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

if(!isset($_REQUEST['page'])) {
    echo "Parametro page mancante!";
    exit(1);
} else {
    $page = $_REQUEST['page'];
}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <title>Documento senza titolo</title>
    <link rel="stylesheet" type="text/css" href="css/general.css">
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
    </style>
    <script type="application/javascript" src="functions.js"></script>

    <script type="application/javascript">
        function findGetParameter(parameterName) {
            var result = null,
                tmp = [];
            location.search
                .substr(1)
                .split("&")
                .forEach(function (item) {
                    tmp = item.split("=");
                    if (tmp[0] === parameterName) result = decodeURIComponent(tmp[1]);
                });
            return result;
        }

        function loadPage() {
            let dataEl = document.getElementsByName("txtGiorno");
            var data = dataEl[0].value.trim();

            if(data === "") {
                let d = new Date();
                let day = d.getDate().toString();
                let month = (d.getMonth() + 1).toString();

                data = day.padStart(2, "0") + "-" + month.padStart(2, "0") + "-" + + d.getFullYear();
            }

            let patt = new RegExp("[0-9]{2}[-]{1}[0-9]{2}[-]{1}[0-9]{4}");

            if(patt.test(data)) {
                let dataElements = data.split("-");
                let date = dataElements[2] + "-" + dataElements[1] + "-" + dataElements[0];
                let page = findGetParameter("page") + ".php" + "?archivio=" + date;
                window.location = page;
            } else {
                alert("La data deve essere nel formato gg-mm-aaaa");
            }
        }
    </script>
</head>
<body>
<?php echo getHeader('archivio') ?>

<table width="100%" height="100%"  border="0">
    <tr align="center" valign="middle">
        <td height="60" colspan="3"><table width="514" border="0">
                <tr valign="bottom">
                    <td height="77" colspan="4" align="left"><strong>Seleziona data archivio <?php echo $page ?></strong></td>
                </tr>
                <tr>
                    <td align="left" valign="middle">Giorno</td>
                    <td align="left" valign="middle"><input name="txtGiorno" type="text" id="txtGiorno" maxlength="10"></td>
                    <td align="left" valign="middle"><input type="submit" id="btnSubmit" onclick="loadPage()"></td>
                    <td align="left" valign="middle"><input type="submit" id="btnOggi" value="Oggi" onclick="loadPage()"></td>
                    <td>&nbsp;</td>
                </tr>
            </table></td>
    </tr>
    <tr>
        <td width="27%" height="30">&nbsp;</td>
        <td width="49%" height="30" bordercolor="#000000">&nbsp;</td>
        <td width="24%" height="30">&nbsp;</td>
    </tr>
</table>

<script type="application/javascript" defer>

    var txtGiorno = document.getElementById("txtGiorno");

    txtGiorno.addEventListener("keydown", function (event) {
        if(event.keyCode === 13) {
            event.preventDefault();
            document.getElementById("btnSubmit").click();
        } else {
            dateAutoCompletition(event);
        }
    })
</script>
</body>
</html>
