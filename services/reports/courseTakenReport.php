<?php
	include("../includes/connection.php");
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
    
    $json_params = file_get_contents("php://input");
    $decoded_params = json_decode($json_params);

    $query = "SELECT course.courseId as courseId, COUNT(*) as cnt from usertracking, course WHERE ";
    $available = false;
    
    if(isset($decoded_params->course)) {
        $query = $query . "usertracking.courseId = " . $decoded_params->{'course'};
        $available = true;
    }
    if(isset($decoded_params->client)) {
        if( $available == true) {
            $query = $query . " AND ";
        }
        $available = true;
       $query = $query . "usertracking.clientId = " . $decoded_params->{'client'};
    }
    if(isset($decoded_params->startDate)) {
        if( $available == true) {
            $query = $query . " AND ";
        }
        $available = true;
        $query = $query . "usertracking.date >= '" . $decoded_params->{'startDate'} . "'";
    }
    if(isset($decoded_params->endDate)) {
        if( $available == true) {
            $query = $query . " AND ";
        }
        $query = $query . "usertracking.date <= '" . $decoded_params->{'endDate'} . "'";
    }

    if( $available == true) {
        $query = $query . " AND ";
    }

    $query = $query . "course.createdBy = " . $decoded_params->{'userId'} ." AND usertracking.courseId = course.courseId";
    $query = $query . " GROUP By course.courseId ORDER BY " . $decoded_params->{'order'};
 	$result = $conn->query($query);

    if ($result->num_rows > 0) {
		 while($row = $result->fetch_assoc()) {
			 $json[] = $row;
		}
		//header('Content-type: application/json');
		//echo json_encode($json);
	}else{
        $json = '[]';
	}   
    $progress = json_encode(array('progress' => $json));

    $courseQuery = "SELECT courseId, courseName FROM course";
    $courseResult = $conn->query($courseQuery);

    if ($courseResult->num_rows > 0) {
        while($row = $courseResult->fetch_assoc()) {
            $coursejson[] = $row;			
       }
   }else{
       $coursejson = [];
   }
   $courses = json_encode(array('courses' => $coursejson));

   header('Content-type: application/json');
  // echo json_encode($coursejson);
   $JSON = json_encode(array_merge(json_decode($progress, true),json_decode($courses, true)));
   echo $JSON;
    //echo $query;
	
?>