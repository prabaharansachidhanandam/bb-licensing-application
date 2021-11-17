<?php
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
include("includes/connection.php");
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}	

$json_params = file_get_contents("php://input");
$decoded_params = json_decode($json_params, true);

$courseId = $decoded_params['courseId'];
$clientId = $decoded_params['reseller'];
$studentId = $decoded_params['id'];
$studentName = $decoded_params['name'];

$sql = "SELECT * FROM completion where courseId = '$courseId' and clientId = '$clientId' and studentId = '$studentId' and studentName = '$studentName'";

$result1 = $conn->query($sql);
$recordAvailable = $result1->num_rows;

if ($recordAvailable == 0) {	
	$query = "INSERT INTO completion ( courseId, clientId, studentId, studentName) VALUES (" .  $courseId .", '" . $clientId ."', '" .  $studentId ."',  '" . $studentName ."')" ;

	//$conn->query($query);

	if ($conn->query($query) === TRUE) {
		$JSONOutput =  '{"result": {"code": "1","message": "Success!"}}';
	}else{
		$JSONOutput =  '{"result": {"code": "0","message": "Failed!"}}';		
	}
	$conn->close();

	echo $JSONOutput;

}



?>