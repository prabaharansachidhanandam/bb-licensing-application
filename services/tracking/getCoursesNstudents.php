<?php

    include("../includes/connection.php");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $clientId = $_GET['clientId'];
    $userEmail = $_GET['userEmail'];

    $query = "SELECT course.courseId, course.courseName, course.artwork, course.keywords, course.category FROM course, assignment, clients, coursefamily WHERE course.courseId = assignment.courseId AND assignment.clientId = clients.clientId AND coursefamily.familyId = course.familyId AND clients.clientId = " . $clientId . " ORDER BY course.courseName";
	
	$coursesResultSet = $conn->query($query);
	
    if ($coursesResultSet->num_rows > 0) {
        while($row = $coursesResultSet->fetch_assoc()) {
            $courseJson[] = $row;
        }			
    }else{
        $courseJson = [];
    }
	
    
    $courses = json_encode(array('courses' => $courseJson)); 

    $sql = "SELECT userEmail FROM progresstracking WHERE clientId = " . $clientId . " Group By userEmail";

    $usersSet = $conn->query($sql);

    if ($usersSet->num_rows > 0) {
		 while($row = $usersSet->fetch_assoc()) {
			$userJson[] = $row;		
		}
	}else{
        $userJson = [];
    }

    $user = json_encode(array('user' => $userJson)); 
   
    $JSONOutput =json_encode(array_merge(json_decode($courses, true),json_decode($user, true)));
    header('Content-type: application/json');

    echo $JSONOutput;

    $conn->close();




?>