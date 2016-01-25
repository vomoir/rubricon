<?PHP
require_once("dbconnector.php");
//require_once("check_session.php");
error_log("In project_get_list.php\nChecking Session...");

if(!isset($_POST['submit'])){
/*
	if (!checkSession()){
		error_log("Session Time out.");
		echo "error: Session has timed out. Please login again...";
		return false;
		exit;
	} else {
		GetProjectsList();
	}
*/
		GetRubricsList();	
}

function GetRubricsList(){
/*
	if($_SESSION['admin'] == "false"){
		error_log("In project_get_list.php: NOT and admin!");
		echo "error: not authorised.";
		return false;
	
	}
*/
	$project_id = $_GET['project_id'];
	$task_id  = $_GET['task_id'];

	//Return metadata about the columns in each table for a given database (table_schema)
	$qry = "SELECT id, p_name, p_details FROM tb_projects order by id";
	
	$dbConn = opendatabase();

	$result = mysqli_query($dbConn, $qry);
	if(!$result || mysqli_num_rows($result) <= 0){
		echo("Could not obtain metadata information.");
		return false;
	}
	$options = "";
	while($row = mysqli_fetch_array($result)) {
		$options .= "<option value='" . $row['id'] . "'>";
		$options .= $row['p_name'] . "</option>";
	} 
	echo $options;
	//return $options;
}
?>