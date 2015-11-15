<?PHP
require_once("dbconnector.php");

DeleteProject();

function DeleteProject(){

	$newSport = false;
	$projectId = $_POST['projectId'];
	error_log("In DeleteProject - Proj ID : " . $projectId );
	
	$qry = "DELETE from tb_projects where id = " . $projectId;

	$dbConn = opendatabase();
	error_log("Deleting Project : " . $qry);

	if(!mysqli_query($dbConn, $qry)){
		echo ("error: deleting project!");
		error_log("Deleting Project : " . $qry);
		return false;
	} else {
		return true;
	}
	$dbConn.close();
}
?>