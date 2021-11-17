<?php
	include("../includes/connection.php");
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
   
    $query = "SELECT course.courseName, clients.clientName, usertracking.studentId, usertracking.studentName,  usertracking.date FROM usertracking, course, clients WHERE ";
    $available = false;
    
    if(isset($_GET['course'])) {
        $query = $query . "usertracking.courseId = " . $_GET['course'];
        $available = true;
    }
    if(isset($_GET['client'])) {
        if( $available == true) {
            $query = $query . " AND ";
        }
        $available = true;
       $query = $query . "usertracking.clientId = " . $_GET['client'];
    }
    if(isset($_GET['startDate'])) {
        if( $available == true) {
            $query = $query . " AND ";
        }
        $available = true;
        $query = $query . "usertracking.date >= '" . $_GET['startDate'] . "'";
    }
    if(isset($_GET['endDate'])) {
        if( $available == true) {
            $query = $query . " AND ";
        }
        $query = $query . "usertracking.date <= '" . $_GET['endDate'] . "'";
    }

    if( $available == true) {
        $query = $query . " AND ";
    }

    $query = $query . "usertracking.courseId = course.courseId AND usertracking.clientId = clients.clientId AND course.createdBy = " . $_GET['userId'];
 	$result = $conn->query($query);

	$filename = "courseAccessReport.csv";
    $fp = fopen('php://output', 'w');
    header('Content-type: application/csv');
    header('Content-Disposition: attachment; filename='.$filename);
    $header = array("Course Name", "Client Name", "Student Id", "Student Name", "Completion (Date & Time)");
    fputcsv($fp, array("Course Access Report"));
    fputcsv($fp, $header);
	
	if ($result->num_rows > 0) {
		 while($row = $result->fetch_assoc()) { 
			fputcsv($fp, $row);
		}
		
	}else{
		//echo '[]';
	}
	exit;
	
?>