<?php
/******************************
Author: Tony Edwards
Date last modified: 08/11/2015
******************************/

	define("MYSQLHOST", 'localhost');
	define("MYSQLUSER",	'root');
	define("MYSQLPASS",	'');	
	define("MYSQLDB", 'db_pragmatic');
	
	function opendatabase(){
		$db = new mysqli(MYSQLHOST, MYSQLUSER, MYSQLPASS, MYSQLDB);
		/* check connection */
		
		try{
			if (mysqli_connect_errno()) {
				$exceptionstring = "Error connecting to database: <br/>";
				$exceptionstring .= mysqli_connect_errno() . ": " . mysqli_error();
				throw new exception ($exceptionstring);
				//exit();
			} else {
				return $db;
			}
		} catch (exception $e){
			error_log("In dbConnector: Error = " . $e->getmessage());
			echo $e->getmessage();
			die();
		}
	}
	function closedatabase(){
	
	}
	        /********************************/
		/*    Example of Transaction    */
		/********************************/
		/*  connect to database
		$dbh = mysqli_connect($host, $user, $pass, $db);

		// turn off auto-commit
		mysqli_autocommit($dbh, FALSE);

		// run query 1
		$result = mysqli_query($dbh, $query1);
		if ($result !== TRUE) {
			mysqli_rollback($dbh);  // if error, roll back transaction
		}
		*/

?>
