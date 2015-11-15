<?PHP

session_start();

error_log("\nIn check_session.php");
checkSession();

function checkSession(){

	if(is_null($_SESSION['id_of_user'])){
		error_log("User id is null.");
		echo "error: User id is null.";
		return false;	
	}
	
	if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
		// last request was more than 30 minutes ago
		session_unset();     // unset $_SESSION variable for the run-time 
		session_destroy();   // destroy session data in storage
		echo "error: Session has timed out.";
		return false;
	}

	$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
	$userId = $_SESSION['id_of_user'];
	error_log("User Id: " . $userId);

	error_log("\nIn check_session.php\n");
	error_log($_SERVER["QUERY_STRING"]);
	error_log(parse_str($_SERVER['QUERY_STRING']));
	return true;
}
?>