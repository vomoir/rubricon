<?PHP
/******************************
******************************/
require_once("dbconnector.php");
/*
require_once("check_session.php");

if (!checkSession()){
	error_log("Session Time out.");
	echo "error: Session has timed out. Please login again...";
	return false;
}
*/

EditProject();

function Editproject(){

	$newproject = false;
	$project_id = 0;
/*	
	if($_SESSION['admin'] == "false"){
		error_log("In project_edit: NOT an admin!");
		echo "error: not authorised.";
		return false;	
	}
*/	
	if(is_null($_POST['projects_list']) || ($_POST['projects_list'] == 0)){
		$newproject = true;
	} else {
		$project_id = $_POST['projects_list'];
	}

	$project = $_POST['project'];
	$project_details = $_POST['p_details'];

	if(!$newproject){
		$qry = "UPDATE tb_projects SET p_name='" . $project . "',p_details = '" . $project_details . "' WHERE id = " . $project_id;
	} else {
		$qry = "INSERT INTO tb_projects( p_name, p_details) " .
		"VALUES ('" . $project . "', '" . $project_details . "');";
	}
	
	error_log($qry);

	$dbConn = opendatabase();

	if(!mysqli_query($dbConn, $qry)){
		echo "error: Error inserting projects choice data to the table\nquery:" . $qry;
		mysqli_close($dbConn);
		return false;
	} else {
		echo "Success!";
		mysqli_close($dbConn);
		return true;
	}
}
?>