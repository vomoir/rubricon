<?PHP

require_once("dbconnector.php");

/*
require_once("check_session.php");

if (!checkSession()){
	error_log("Session Time out.");
	echo "error: Session has timed out. Please login again...";
	return false;
}
*/
EditRubric();	


function EditRubric(){

	$newproject = false;
	$project_id = 0;
/*	
	if($_SESSION['admin'] == "false"){
		error_log("In project_edit: NOT an admin!");
		echo "error: not authorised.";
		return false;	
	}
*/	
	$project_id = $_POST['project_id'];
	$task_id    = $_POST['task_id'];
	$r_level    = $_POST['r_level'];
	$r_text     = $_POST['r_text'];

	$dbConn = opendatabase();	
	$stmt = $dbConn->stmt_init(); 
	$sql = "UPDATE tb_rubrics SET r_text = ? WHERE task_id = ? and r_level = ?;";
	error_log("QRY: " . $sql);
	error_log("VALUES: " . $task_id . ", " . $r_level . ", " . $r_text);
	if($stmt->prepare($sql)){
		// Bind parameters:	s - string, b - blob, i - int, etc
		$stmt -> bind_param("sii", $r_text, $task_id, $r_level);
		/* Execute it */
		$stmt -> execute();
		/* Close statement */
		$stmt -> close();
		error_log("Insert/update successful.",0);
	} else {
		error_log("Error!Prepare failed: (" . $dbConn->errno . ") " . $dbConn->error ,0);
	}	

  $dbConn -> close();
}

?>