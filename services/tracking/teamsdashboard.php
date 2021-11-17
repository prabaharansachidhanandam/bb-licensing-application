<?php
	include("../includes/connection.php");
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
    
    $clientId = $_GET['clientId'];

	$todayDate = date('Y-m-d', strtotime('1 days')) . " " . '00:00:00';
	$startDate = date('Y-m-d', strtotime('-30 days')) . " " . '00:00:00';	
	
	
    /************************************ ********************/
    /****** GET THE TOP 5 TRENDING COURSES ******************/
    /************************************ ********************/

	$query = "SELECT course.courseName, progresstracking.courseId, COUNT(DISTINCT userEmail) AS cnt FROM course, progresstracking WHERE progresstracking.clientId = " . $clientId . " AND course.courseId = progresstracking.courseId AND progresstracking.startDate between '" . $startDate . "' AND '" . $todayDate ."' group by course.courseId ORDER BY cnt DESC LIMIT 5";
	
	$result = $conn->query($query);

	if ($result->num_rows > 0) {
		 while($row = $result->fetch_assoc()) {
			 $json[] = $row;			
		}
	}else{
		$json = [];
	}
	
	$courseGraph = json_encode(array('courseGraph' => $json)); 
	
    
    /************************************ ************************/
    /****** GET THE USER LOGIN IN LAST 30 DAYS *******************/
    /************************************ ***********************/

    $newUserQuery = "SELECT COUNT(DISTINCT userEmail) as count, date(startDate) as date FROM `progresstracking` WHERE clientId = " . $clientId . " AND startDate between '" . $startDate . "' AND '" . $todayDate ."' GROUP BY date(startDate)";

  
	$newUserResult = $conn->query($newUserQuery);

	if ($newUserResult->num_rows > 0) {
		 while($row = $newUserResult->fetch_assoc()) {
			 $newuserjson[] = $row;			
		}
	}else{
		$newuserjson = [];
	}
	
    $newuserGraph = json_encode(array('newuserGraph' => $newuserjson));
    
    /************************************ ************************/
    /****** GET THE TOTAL USER *********************************/
    /************************************ ***********************/
	
	$totalUserQuery = "SELECT COUNT(DISTINCT userEmail) as totalusers FROM `progresstracking` WHERE clientId = " . $clientId;
    
    
	$totalUserResult = $conn->query($totalUserQuery);

	if ($totalUserResult->num_rows > 0) {
		 while($row = $totalUserResult->fetch_assoc()) {
			 $totalUserjson = $row["totalusers"];			
		}
	}else{
		$totalUserjson = 0;
	}
	
	$totaluser = json_encode(array('totaluser' => $totalUserjson));
    
    
    /************************************ ************************/
    /************************** GET THE TOTAL COURSES ***************/
    /************************************ ***********************/

    $totalCourseQuery = "SELECT count(assignId) AS totalcourses FROM assignment WHERE clientId = " . $clientId;
    
	$totalCourseResult = $conn->query($totalCourseQuery);

	if ($totalCourseResult->num_rows > 0) {
		 while($row = $totalCourseResult->fetch_assoc()) {
			 $totalCoursejson = $row["totalcourses"];			
		}
	}else{
		$totalCoursejson = 0;
	}
	
    $totalCourse = json_encode(array('totalCourse' => $totalCoursejson));


    /************************************ ************************/
    /******************** GET THE TOTAL TIME SPENT ***************/
    /************************************ ***********************/

    $totalTimeQuery = "SELECT sum(timeSpent) as totaltime FROM `progresstracking` WHERE clientId = " . $clientId;
    
	$totalTimeResult = $conn->query($totalTimeQuery);

	if ($totalTimeResult->num_rows > 0) {
		 while($row = $totalTimeResult->fetch_assoc()) {
			 $totalTimejson = $row["totaltime"];			
		}
	}else{
		$totalTimejson = 0;
	}
	
	$totalTime = json_encode(array('totalTime' => $totalTimejson));
	

	/************************************ ******************************/
    /******************** GET THE TOTAL ACCESSED COURSES ***************/
	/************************************ *****************************/
	
	$totalAccessedCourseQuery = "SELECT COUNT(DISTINCT courseId) as totalAccessed FROM `progresstracking` WHERE clientId = " . $clientId;
    
	$totalAccessedCoursesResult = $conn->query($totalAccessedCourseQuery);

	if ($totalAccessedCoursesResult->num_rows > 0) {
		 while($row = $totalAccessedCoursesResult->fetch_assoc()) {
			 $totalAccessedjson = $row["totalAccessed"];			
		}
	}else{
		$totalAccessedjson = 0;
	}
	
	$totalAccessedCourses = json_encode(array('totalAccessedCourses' => $totalAccessedjson));

	/************************************ ******************************/
    /******************** GET THE TIME SPENT ON 30 DAYS ***************/
	/************************************ *****************************/
	
	$timeSpentQuery = "SELECT SUM(timeSpent) as count, date(startDate) as date FROM `progresstracking` WHERE clientId = " . $clientId . " AND startDate between '" . $startDate . "' AND '" . $todayDate ."' GROUP BY date(startDate)";
    
	$timeSpentResult = $conn->query($timeSpentQuery);

	if ($timeSpentResult->num_rows > 0) {
		 while($row = $timeSpentResult->fetch_assoc()) {
			 $timeSpentjson[] = $row;			
		}
	}else{
		$timeSpentjson = [];
	}
	
	$timeSpent = json_encode(array('timeSpent' => $timeSpentjson));
	

	/************************************ ******************************/
    /******************** GET THE TIME SPENT ON 30 DAYS ***************/
	/************************************ *****************************/
	
	$timeSpentbyUsersQuery = "SELECT userEmail, SUM(timeSpent) AS cnt FROM progresstracking WHERE clientId = " . $clientId . " AND startDate between '" . $startDate . "' AND '" . $todayDate ."' group by userEmail ORDER BY cnt DESC LIMIT 10";
    
	$timeSpentbyUsersResult = $conn->query($timeSpentbyUsersQuery);

	if ($timeSpentbyUsersResult->num_rows > 0) {
		 while($row = $timeSpentbyUsersResult->fetch_assoc()) {
			 $timeSpentbyUsersjson[] = $row;			
		}
	}else{
		$timeSpentbyUsersjson = [];
	}
	
    $timeSpentbyUsersjson = json_encode(array('timeSpentbyUsers' => $timeSpentbyUsersjson));
    
		
    $JSON =json_encode(array_merge(json_decode($courseGraph, true),json_decode($newuserGraph, true),json_decode($totaluser, true),json_decode($totalCourse, true),json_decode($totalTime, true),json_decode($totalAccessedCourses, true), json_decode($timeSpent, true), json_decode($timeSpentbyUsersjson, true)));
    
	echo $JSON;
?>