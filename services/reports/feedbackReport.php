<?php
	include("../includes/connection.php");
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
    
    $json_params = file_get_contents("php://input");
    $decoded_params = json_decode($json_params);

    $query = "SELECT course.courseName, clients.clientName, feedback.moduleId, feedback.moduleName, feedback.studentId, feedback.studentName, feedback.feedback, feedback.date FROM feedback, course, clients WHERE ";
    $available = false;
    
    if(isset($decoded_params->course)) {
        $query = $query . "feedback.courseId = " . $decoded_params->{'course'};
        $available = true;
    }
    if(isset($decoded_params->client)) {
        if( $available == true) {
            $query = $query . " AND ";
        }
        $available = true;
       $query = $query . "feedback.clientId = '" . $decoded_params->{'client'} . "'";
    }
    if(isset($decoded_params->startDate)) {
        if( $available == true) {
            $query = $query . " AND ";
        }
        $available = true;
        $query = $query . "feedback.date >= '" . $decoded_params->{'startDate'} . "'";
    }
    if(isset($decoded_params->endDate)) {
        if( $available == true) {
            $query = $query . " AND ";
        }
        $query = $query . "feedback.date <= '" . $decoded_params->{'endDate'} . "'";
    }

    if( $available == true) {
        $query = $query . " AND ";
    }

    $query = $query . "feedback.courseId = course.courseId AND feedback.clientId = clients.licenseName AND course.createdBy = " . $decoded_params->{'userId'} ."";
 	$result = $conn->query($query);

	if ($result->num_rows > 0) {
		 while($row = $result->fetch_assoc()) {
			 $json[] = $row;
		}
		header('Content-type: application/json');
		echo json_encode($json);
	}else{
		echo '[]';
	}
   
    //echo $query;
	
?>