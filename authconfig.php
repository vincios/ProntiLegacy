<?

$resultpage = "authenticate.php";	// THIS IS THE PAGE THAT WOULD CHECK FOR AUTHENTICITY
$failure = "../index.php";	// THIS IS THE PAGE TO BE SHOWN IF USERNAME-PASSWORD COMBINATION DOES NOT MATCH
$success = "../index1.php";
	
// The $_SERVER['HTTP_HOST'] takes care of the root directory of the web server
// This makes it possible to implement the script even on IP-based systems.
// For name-based systems, just think of $_SERVER['HTTP_HOST'] as the domain name
// example: $_SERVER['HTTP_HOST'] will have to be www.yourdomain.com
// For IP-based systems, this will replace the IP address
// example: $_SERVER['HTTP_HOST'] will have to be 66.199.47.5

/* $HOST = "62.149.150.64";	// Change this to the proper DB HOST
	 $USERNAME = "Sql119682";	// Change this to the proper DB USERNAME
	 $PASSWORD = "b3899afa";	// Change this to the proper DB USER PASSWORD
	var $DBNAME = "Sql119682_1";	// Change this to the proper DB NAME */
// DB SETTINGS
$dbhost = "localhost";	// Change this to the proper DB Host name
$dbusername = "root"; 	// Change this to the proper DB User

$dbpass = "";	// Change this to the proper DB User password
$dbname	= "Sql119682_1"; 	// Change this to the proper DB Name

?>
