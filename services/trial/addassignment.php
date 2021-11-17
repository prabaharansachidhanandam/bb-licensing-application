<?php

include("../includes/connection.php");
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}	

$json_params = file_get_contents("php://input");
$decoded_params = json_decode($json_params);

$query = "INSERT INTO trialcourses (clientId, courseId, expiryDate, downloadPath, 	createdBy) VALUES (" .  $decoded_params->{'clientId'} .", " . $decoded_params->{'courseId'} .", '" .  $decoded_params->{'expiryDate'} ."',  '" . $decoded_params->{'zipPath'} ."',  '" . $decoded_params->{'userId'} . "')";

$conn->query($query);

/*if ($conn->query($query) === TRUE) {
	//$JSONOutput =  '{"result": {"code": "1","message": "Course has been added Successfully!"}}';
}else{
	//$JSONOutput =  '{"result": {"code": "0","message": "Adding new Course has been Failed!"}}';		
 */
 $conn->close();
 
 //echo $JSONOutput;

?>