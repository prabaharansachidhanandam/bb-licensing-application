<?php

    include("../includes/connection.php");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    /*$json_params = file_get_contents("php://input");
    $decoded_params = json_decode($json_params);


    $clientId = $decoded_params->{'clientId'};
    $userEmail = $decoded_params->{'userEmail'};
    */

    $clientId = $_GET['clientId'];
    $userEmail = $_GET['userEmail'];

    $query = "SELECT course.courseId, course.courseName, course.artwork, course.keywords, course.category FROM course, assignment, clients, coursefamily WHERE course.courseId = assignment.courseId AND assignment.clientId = clients.clientId AND coursefamily.familyId = course.familyId AND clients.clientId = " . $clientId;
	
	$coursesResultSet = $conn->query($query);
	
    if ($coursesResultSet->num_rows > 0) {
        while($row = $coursesResultSet->fetch_assoc()) {
            $courseJson[] = $row;
        }			
    }else{
        $courseJson = [];
    }
	
    
    $courses = json_encode(array('courses' => $courseJson)); 

    //$sql = "SELECT DISTINCT(courseId), score, courseStatus, max(acessedDate) AS lastDate FROM progresstracking WHERE clientId=" .  $clientId . " AND userEmail = '" . $userEmail . "' GROUP BY courseId" ;
    //echo $sql;

    //$sql = "SELECT SUBSTRING_INDEX( GROUP_CONCAT(DISTINCT id ORDER BY id DESC), ',', 1 ) as id, courseId, SUBSTRING_INDEX( GROUP_CONCAT(DISTINCT courseStatus ORDER BY courseStatus DESC), ',', 1 ) as courseStatus, SUBSTRING_INDEX( GROUP_CONCAT(DISTINCT score ORDER BY score DESC), ',', 1 ) as score, SUBSTRING_INDEX( GROUP_CONCAT(DISTINCT acessedDate ORDER BY acessedDate DESC), ',', 1 ) as acessedDate FROM progresstracking where userEmail = '" . $userEmail . "' GROUP BY courseId ORDER BY courseId ASC";

    $sql = "SELECT progresstracking.* FROM progresstracking,
    (SELECT courseId,max(acessedDate) AS acessedDate
    FROM progresstracking
    GROUP BY courseId) max_user
      WHERE progresstracking.courseId = max_user.courseId
      AND progresstracking.acessedDate=max_user.acessedDate AND progresstracking.userEmail = '" . $userEmail . "' AND progresstracking.clientId = " . $clientId;

    $progressResultSet = $conn->query($sql);

    if ($progressResultSet->num_rows > 0) {
		 while($row = $progressResultSet->fetch_assoc()) {
			$progressJson[] = $row;		
		}
	}else{
        $progressJson = [];
    }

    $progress = json_encode(array('progress' => $progressJson)); 
   
    $JSONOutput =json_encode(array_merge(json_decode($courses, true),json_decode($progress, true)));
    header('Content-type: application/json');

    echo $JSONOutput;

    $conn->close();




?>