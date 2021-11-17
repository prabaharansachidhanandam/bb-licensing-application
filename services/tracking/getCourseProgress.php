<?php

    include("../includes/connection.php");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $clientId = $_GET['clientId'];
    $courseId = $_GET['courseId'];

    $query = "SELECT progresstracking.courseId, progresstracking.userEmail, progresstracking.score, progresstracking.courseStatus, progresstracking.progress, DATE_FORMAT(progresstracking.acessedDate,'%m/%d/%Y %r') as acessedDate FROM progresstracking, (SELECT courseId,max(acessedDate) AS acessedDate,userEmail FROM progresstracking where progresstracking.courseId  = " . $courseId . " group by userEmail) max_user WHERE progresstracking.courseId = max_user.courseId AND progresstracking.acessedDate=max_user.acessedDate AND progresstracking.clientId = " . $clientId . " AND progresstracking.courseId = " . $courseId . " order BY progresstracking.courseId";
	
	$coursesResultSet = $conn->query($query);
	
    if ($coursesResultSet->num_rows > 0) {
        while($row = $coursesResultSet->fetch_assoc()) {
            $courseJson[] = $row;
        }			
    }else{
        $courseJson = [];
    }	
    
    $courses = json_encode(array('progress' => $courseJson)); 

    header('Content-type: application/json');

    echo $courses;

    $conn->close();

?>