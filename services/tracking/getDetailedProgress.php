<?php

    include("../includes/connection.php");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $courseId = $_GET['courseId'];
    $user = $_GET['user'];

    $query = "SELECT courseStatus, timeSpent, score, DATE_FORMAT(startDate,'%m/%d/%Y %r') as startDate, DATE_FORMAT(acessedDate,'%m/%d/%Y %r') as acessedDate FROM progresstracking WHERE courseId = " . $courseId . " AND userEmail = '" . $user . "' ORDER BY acessedDate DESC";
	
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