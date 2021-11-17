<?php
	include("../includes/connection.php");
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	/* grab the CLIENTS from the db */
	
	
	$query = "SELECT courseId FROM trialcourses WHERE clientId = " . $_GET['clientId'];
	$result = $conn->query($query);
	$courseId = '';
	if ($result->num_rows > 0) {
		//$row = $result->fetch_assoc();
		 while($row = $result->fetch_assoc()) {
			if($courseId != ''){
				$courseId = $courseId . ',' . $row["courseId"];
			}else{
				$courseId = $row["courseId"];
			}					
		}
	}
	//echo "courseId :: " . $courseId . "</br>";
	if($courseId != ''){
		$query = "SELECT DISTINCT course.familyId, course.courseId, coursefamily.familyName, course.courseName, course.coursePath FROM course, coursefamily WHERE coursefamily.familyId = course.familyId AND course.createdBy = " . $_GET['userId'] ." AND course.courseId NOT IN (" . $courseId . ") ORDER BY course.familyId";
	}else{
		$query = "SELECT DISTINCT course.familyId, course.courseId, coursefamily.familyName, course.courseName, course.coursePath FROM course, coursefamily WHERE coursefamily.familyId = course.familyId AND course.createdBy = " . $_GET['userId'] ." ORDER BY course.familyId";
	}
	$resultSet = $conn->query($query);
	
	if ($resultSet) {
		if ($resultSet->num_rows > 0) {
			 while($row = $resultSet->fetch_assoc()) {
				 $json[] = $row;
				//echo "id: " . $row["course.familyId"]. " - Name: " . $row["coursefamily.familyName"] . "<br>";
			}
			header('Content-type: application/json');
			echo json_encode($json);
		}else{
			echo '[]';
		}
		
		//header('Content-type: application/json');
		//echo json_encode($json);
	}else{
		echo '[]';
	}

?>