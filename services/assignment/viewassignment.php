<?php
	include("../includes/connection.php");
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	/* grab the CLIENTS from the db */
	
	
	$query = "SELECT * FROM course, assignment WHERE course.courseId = assignment.courseId AND assignment.clientId = " . $_GET['clientId'];
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