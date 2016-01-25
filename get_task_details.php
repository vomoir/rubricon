<?PHP

$taskId = $_POST['task_id'];

date_default_timezone_set('Australia/Sydney');
error_log("In get_task_details.php...");

require_once("dbconnector.php");
//require_once("check_session.php");
/*
if (!checkSession()){
	error_log("Session Time out.");
	echo "error: Session has timed out. Please login again...";
	return false;
}
*/

GetTaskDetails($taskId);

function GetTaskDetails($taskId){
	//Return metadata about the columns in each table for a given database (table_schema)
	$qry = "SELECT r_level, r_text, tb_tasks.task_text FROM tb_rubrics " .
		"INNER JOIN tb_tasks on tb_rubrics.task_id = tb_tasks.id " .
		"WHERE task_id = " . $taskId . " ORDER by r_level;";

	date_default_timezone_set('Australia/Sydney');
	error_log("In get_task_details.php...\n" . $qry);
	
	$dbConn = opendatabase();

	$result = mysqli_query($dbConn, $qry);
	
	date_default_timezone_set('Australia/Sydney');	

	error_log("Records in Projects: " . mysqli_num_rows($result));
	$row_cnt = mysqli_num_rows($result);
	$field_cnt = $result->field_count;
	error_log("Fields: " . $field_cnt);
	
	if(!$result || mysqli_num_rows($result) <= 0){
		//echo("Could not obtain metadata information.");
		return false;
	}
	/*****************************************************************/
	$xml = new XMLWriter();
	$xml->openMemory();
	
	$xml->startDocument();
		$xml->setIndent(true);
		$xml->startElement("task_rubrics");
		/* fetch associative array */
		while ($row = mysqli_fetch_assoc($result)) {
			$xml->startElement("task");
				$xml->writeAttribute('id', $taskId);
				$xml->writeRaw($row['task_text']);
				$xml->startElement("rubric_details");
					$xml->writeAttribute('r_level', $row['r_level']);
					$xml->startCData("details");
						$xml->writeRaw($row['r_text']);
					$xml->endCData();
				$xml->endElement();				
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
	//echo $strXML;

    $projXml = new DOMDocument;
    $projXml->loadXML($strXML);
     
    //error_log($projXml->saveXML());
    error_log("loading xsl document...");
	
    $xsl = new DOMDocument;
	$xsl->load('xsl/task_rub_html.xsl');
    //error_log($xsl->saveXML()); 
    // Configure the transformer
    $proc = new XSLTProcessor;
    $proc->importStyleSheet($xsl); // attach the xsl rules
	$projRubs = $proc->transformToXML($projXml);
	error_log("XML Transform result\n" . $projRubs);
    echo $proc->transformToXML($projXml);
}
?>