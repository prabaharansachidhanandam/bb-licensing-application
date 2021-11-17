<?php
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
include("includes/connection.php");
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}	

$json_params = file_get_contents("php://input");
$decoded_params = json_decode($json_params, true);

//echo $decoded_params['name'];

$query = "INSERT INTO feedback ( courseId, clientId, moduleId, moduleName, studentId, studentName, feedback) VALUES (" . $decoded_params['courseId']. ", '" . $decoded_params['reseller'] ."', " . $decoded_params['moduleId'] .", '" . $decoded_params['moduleName'] ."','".  $decoded_params['id'] ."', '" .  $decoded_params['name'] . "', '" . $decoded_params['feedback'] . "')" ;

//$conn->query($query);

if ($conn->query($query) === TRUE) {
	$JSONOutput =  '{"result": {"code": "1","message": "Success!"}}';
}else{
	$JSONOutput =  '{"result": {"code": "0","message": "Failed!"}}';		
}
 $conn->close();
 
 echo $JSONOutput;

?>