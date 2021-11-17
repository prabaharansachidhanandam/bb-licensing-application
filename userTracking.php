<?php

include("includes/connection.php");
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}



/*$studentId = 203;
 $studentName = 'saji1';
 $courseId = 1;
 $clientId = 1;*/

 $studentId = $_GET['id'];
 $studentName = $_GET['name'];
 $courseId = $_GET['course'];
 $reseller = $_GET['reseller'];
 
 //echo $reseller;
 $studentName = str_replace("'","",$studentName);
 
 if($reseller == 'trial'){
	 $clientId = $_GET['client'];
	 $sql = "SELECT expiryDate FROM trialcourses where courseId = " . $courseId . " AND clientId = " . $clientId;
	 $result = $conn->query($sql);
	 
	 if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {		 
		   $Expiry = $row["expiryDate"];
		}	   
	}
	$today_time = strtotime(date('Y-m-d'));
	$expire_time = strtotime($Expiry);
	
	if ($expire_time < $today_time) { 		
		$JSONOutput =  '{"result": {"code": "-2","message": "Trial Period has been expired"}}';
	}else{
		$JSONOutput =  '{"result": {"code": "1","message": "Valid time period"}}';
	}
	
 } else { 
	$today = date('Y-m-d');

	$sql = "SELECT clients.clientId, assignment.courseTypeId, assignment.licenseCount FROM clients, assignment where clients.licenseName = '$reseller' and clients.endDate >= '$today' and assignment.courseId = '$courseId' and clients.clientId = assignment.clientId";
	//echo $sql;
	$result = $conn->query($sql);

	//echo $result->num_rows;

	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
		   $clientId = $row["clientId"];
		   $LicenseCount = $row["licenseCount"];
		   $courseTypeId = $row["courseTypeId"];

		   $sql = "SELECT * FROM usertracking where clientId = '$clientId' and courseId = '$courseId' and studentId = '$studentId'";
			$result1 = $conn->query($sql);
			$userAvailable = $result1->num_rows;

			if ($userAvailable > 0) {	
				//echo "Existing User. No action required";  
				//$JSONOutput =  "result: {code : '1', success: 'true', message : 'Existing User'}";
				//$JSONOutput =  '{"result": {"code": "1","message": "Existing User"}}';
				$JSONOutput = getCourseStructure($courseId,  $courseTypeId);
			} else {
					
				$sql = "SELECT * FROM usertracking where clientId = '$clientId' and courseId = '$courseId'";
				$result = $conn->query($sql);
				$totalUsers = $result->num_rows;	
				
				//echo "totalUsers :: " . $totalUsers . "<br/>";
				
				if($totalUsers < $LicenseCount){
				
					$sql = "INSERT INTO usertracking (clientId, courseId, studentId	, studentName) VALUES ('$clientId', '$courseId', '$studentId', '$studentName')";
						
					if ($conn->query($sql) === TRUE) {
						$JSONOutput = getCourseStructure($courseId,  $courseTypeId);
						//$JSONOutput =  '{"result": {"code": "1","message": "User added successfully"}}';
					} else {
						//echo "Error: " . $sql . "<br>" . $conn->error;
					}
				}else{
					$sql = "INSERT INTO unlicensedtracking (clientId, courseId, studentId, studentName) VALUES ('$clientId', '$courseId', '$studentId', '$studentName')";
					$conn->query($sql);
					
					$JSONOutput =  '{"result": {"code": "0","message": "License limit reached"}}';
				}
			}

		  
		}	   
	} else {

		$JSONOutput =  '{"result": {"code": "-1","message": "License expired"}}';
	}		

}
header('Content-type: application/json');

echo $JSONOutput;

$conn->close();


function getCourseStructure($courseId,  $courseTypeId) {
	include("includes/connection.php");
	$sql = "SELECT structure FROM coursedetails where courseId = '$courseId' and courseTypeId = $courseTypeId";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
		   $structure = $row["structure"];
		}
	   
	} else {
		$sql = "SELECT structure FROM coursedetails where courseId = '$courseId' and courseTypeId = 1";
		$result1 = $conn->query($sql);
		if ($result1->num_rows > 0) {
			while($row = $result1->fetch_assoc()) {
			   $structure = $row["structure"];
			}
		   
		}
	}

	$sql = "SELECT language FROM interfacetext";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
		   $language = $row["language"];
		}
	   
	}


   return '{"result": {"code": "1","message": "Valid user", "structure" : ' . $structure . ', "interfaceText" : ' .  $language .' }}';
}

?>