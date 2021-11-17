<?php

    include("../includes/connection.php");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $clientId = $_GET['clientId'];
    $userEmail = urldecode($_GET['userEmail']);

    //echo $_GET['userEmail'];
    //exit;

    /*$query = "SELECT course.courseName, progresstracking.courseId, progresstracking.userEmail, progresstracking.score, progresstracking.courseStatus, progresstracking.progress, DATE_FORMAT(progresstracking.acessedDate,'%m/%d/%Y %r') as acessedDate FROM progresstracking,
    (SELECT courseId,max(acessedDate) AS acessedDate
    FROM progresstracking WHERE userEmail = '" . $userEmail . "'
    GROUP BY courseId) max_user, course
      WHERE progresstracking.courseId = max_user.courseId
      AND progresstracking.acessedDate=max_user.acessedDate AND progresstracking.clientId = " . $clientId . " AND userEmail = '" . $userEmail . "' AND course.courseId = progresstracking.courseId order BY courseId";
    */
    
    $query = "SELECT course.courseName, progresstracking.courseId, progresstracking.userEmail, progresstracking.score, progresstracking.courseStatus, progresstracking.progress, DATE_FORMAT(progresstracking.acessedDate,'%m/%d/%Y %r') as acessedDate FROM progresstracking,
    (SELECT courseId,max(acessedDate) AS acessedDate
    FROM progresstracking WHERE userEmail = '" . $userEmail . "'
    GROUP BY courseId) max_user, course
      WHERE progresstracking.courseId = max_user.courseId
      AND progresstracking.acessedDate=max_user.acessedDate AND userEmail = '" . $userEmail . "' AND course.courseId = progresstracking.courseId order BY courseId";

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