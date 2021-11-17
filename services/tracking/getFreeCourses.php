<?php

    include("../includes/connection.php");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    
    $query = "SELECT courseId, courseName, artwork, keywords, category FROM course WHERE free = 1";
	
	$coursesResultSet = $conn->query($query);
	
    if ($coursesResultSet->num_rows > 0) {
        while($row = $coursesResultSet->fetch_assoc()) {
            $courseJson[] = $row;
        }			
    }else{
        $courseJson = [];
    }
    
    header('Content-type: application/json');

    echo json_encode($courseJson);

    $conn->close();




?>