<?PHP
/********************
Author:
Function:
*********************/

$projId = $_POST['ID'];
//$sportId = 4;

date_default_timezone_set('Australia/Sydney');
error_log("In project_get_detail.php...");

require_once("dbconnector.php");
//require_once("check_session.php");
/*
if (!checkSession()){
	error_log("Session Time out.");
	echo "error: Session has timed out. Please login again...";
	return false;
}
*/

GetProjectDetail($projId);

function GetProjectDetail($projId){
	//Return metadata about the columns in each table for a given database (table_schema)
	$qry = "SELECT id, p_name, p_details FROM tb_projects where id = " . $projId;
	date_default_timezone_set('Australia/Sydney');
	error_log("In project_get_detail.php...\n" . $qry);
	
	$dbConn = opendatabase();

	$result = mysqli_query($dbConn, $qry);
	
	date_default_timezone_set('Australia/Sydney');	
	error_log("Records in Projects: " . mysqli_num_rows($result));
	
	if(!$result || mysqli_num_rows($result) <= 0){
		echo("Could not obtain metadata information.");
		return false;
	}
	/*****************************************************************/
	$xml = new XMLWriter();
	//$projXml  = new DOMDocument();
	//$xml->openURI("php://output");
	$xml->openMemory();
	
	$xml->startDocument();
		$xml->setIndent(true);
		$xml->startElement("projects");
			while ($row = mysqli_fetch_assoc($result)) {
				$xml->startElement("project");
					$xml->writeAttribute('id', $projId);
					$xml->writeRaw($row['p_name']);
				$xml->endElement();
				
				$xml->startElement("project_details");
					$xml->startCData("details");
						$xml->writeRaw($row['p_details']);
					$xml->endCData();
				$xml->endElement();				
			}
		$xml->endElement();
	$xml->endDocument();
	$dbConn -> close();
	header('Content-type: text/xml');

	$strXML = $xml->outputMemory(TRUE);
	$xml->flush();
	date_default_timezone_set('Australia/Sydney');
	error_log("String XML:\n " . $strXML);
	//$projXml->loadXML($strXML);
	echo $strXML;
	/*****************************************************************
	$options = array();
	while ($row = mysqli_fetch_assoc($result)){
		$options['object_row'][] = $row;
	}
	echo json_encode($options);
	*****************************************************************/
}
?>