<?php
	include("../includes/connection.php");
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	
	$json_params = file_get_contents("php://input");
	$decoded_params = json_decode($json_params);
	
	$query = "Update trialcourses SET expiryDate = '" . $decoded_params->{'expiryDate'} . "' WHERE trialId = " . $decoded_params->{'trialId'};
	
	$resultSet = $conn->query($query);
	if ($resultSet) {
		echo '{"result": {"code": "1","message": "License count has been updated successfully!"}}'; 
	}else{
		echo '{"result": {"code": "0","message": "License License count updation failed!"}}'; 
	}

?>