<?
/*
# File: auth.php
# Script Name: vAuthenticate 3.0.1
# Author: Vincent Ryan Ong
# Email: support@beanbug.net
#
# Description:
# vAuthenticate is a revolutionary authentication script which uses
# PHP and MySQL for lightning fast processing. vAuthenticate comes 
# with an admin interface where webmasters and administrators can
# create new user accounts, new user groups, activate/inactivate 
# groups or individual accounts, set user level, etc. This may be
# used to protect files for member-only areas. vAuthenticate 
# uses a custom class to handle the bulk of insertion, updates, and
# deletion of data. This class can also be used for other applications
# which needs user authentication.
#
# This script is a freeware but if you want to give donations,
# please send your checks (coz cash will probably be stolen in the
# post office) to:
#
# Vincent Ryan Ong
# Rm. 440 Wellington Bldg.
# 655 Condesa St. Binondo, Manila
# Philippines, 1006
*/
?>
<?php


class auth{
	// CHANGE THESE VALUES TO REFLECT YOUR SERVER'S SETTINGS
	//var $HOST = "62.149.150.64";	// Change this to the proper DB HOST
	//var $USERNAME = "Sql119682";	// Change this to the proper DB USERNAME
	//var $PASSWORD = "b3899afa";	// Change this to the proper DB USER PASSWORD
	//var $DBNAME = "Sql119682_1";	// Change this to the proper DB NAME 

    var $HOST = "localhost";	// Change this to the proper DB HOST
   // var $HOST = "192.168.1.11";	// Change this to the proper DB HOST
	var $USERNAME = "menzione";	// Change this to the proper DB USERNAME
	var $PASSWORD = "trasporti";	// Change this to the proper DB USER PASSWORD
	var $DBNAME = "Sql119682_1";	// Change this to the proper DB NAME

	// getcliente
	function getutente($username, $password, $idutente) {
		$connection = mysqli_connect($this->HOST, $this->USERNAME, $this->PASSWORD);

		$SelectedDB = mysqli_select_db($connection, $this->DBNAME);
		
		$iduser = "";
		$query = "SELECT * FROM utenti WHERE username='$username' AND password='$password'";
		$rstemp=mysqli_query($connection, $query) or die ("errore in inserimento nuovo ordine");
		while ($row = mysqli_fetch_array($rstemp, MYSQLI_ASSOC))
		{
			$idutente = $row["username"];
		}
		mysqli_free_result($rstemp);
		
	} // End: function getcliente
	

	// AUTHENTICATE
	function authenticate($username, $password) {
		$query = "SELECT * FROM utenti WHERE username='$username' AND password='$password'";

		$connection = mysqli_connect($this->HOST, $this->USERNAME, $this->PASSWORD);

		$SelectedDB = mysqli_select_db($connection, $this->DBNAME);
		$result = mysqli_query($connection, $query);
		
		$numrows = mysqli_num_rows($result);
		$row = mysqli_fetch_array($result);
		
		// CHECK IF THERE ARE RESULTS
		// Logic: If the number of rows of the resulting recordset is 0, that means that no
		// match was found. Meaning, wrong username-password combination.
		if ($numrows == 0) {
			return 0;
		}
     
		else {
			return $row;
		}
	} // End: function authenticate

	// PAGE CHECK
	// This function is the one used for every page that is to be secured. This is not the same one
	// used in the initial login screen
	function page_check($username, $password) 
	{
	
		$query = "SELECT * FROM utenti WHERE username='$username' AND password='$password'";

		$connection = mysqli_connect($this->HOST, $this->USERNAME, $this->PASSWORD);
		
		$SelectedDB = mysqli_select_db($connection, $this->DBNAME);
		$result = mysqli_query($connection, $query);
		
		$numrows = mysqli_num_rows($result);
		$row = mysqli_fetch_array($result);

		// CHECK IF THERE ARE RESULTS
		// Logic: If the number of rows of the resulting recordset is 0, that means that no
		// match was found. Meaning, wrong username-password combination.

		if ($numrows == 0) 
		{
			$ret = "0";
		}
		else 
		{
			$ret = "1";
		}
		return $ret;
	} // End: function page_check
	
} // End: class auth
?>
