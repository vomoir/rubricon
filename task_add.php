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

EditTask();

function EditTask(){

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
  $task_desc    = $_POST['task_desc'];
error_log("task_add.php: project_id = " . $project_id);
error_log("task_add.php: task_desc = " . $task_desc);

	$dbConn = opendatabase();	
	$stmt = $dbConn->stmt_init(); 
	$sql = "INSERT INTO tb_tasks(project_id, task_text) VALUES (?,?);";

	error_log("QRY: " . $sql);
	
	if($stmt->prepare($sql)){
		// Bind parameters:	s - string, b - blob, i - int, etc
		$stmt -> bind_param("is", $project_id, $task_desc);
		/* Execute it */
		$stmt -> execute();
		$last_id = $stmt -> insert_id;
		error_log("New Record has id: " . $last_id);
		/* Close statement */
		$stmt -> close();
		error_log("Insert/update successful.",0);
		echo $last_id;
		
	} else {
		error_log("Error!Prepare failed: (" . $dbConn->errno . ") " . $dbConn->error ,0);
	}	

  $dbConn -> close();
}

?>