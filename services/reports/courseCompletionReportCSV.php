<?php
	include("../includes/connection.php");
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
    

    $query = "SELECT course.courseName, clients.clientName, completion.studentId, completion.studentName, completion.completedOn FROM completion, course, clients WHERE ";
    $available = false;
   
	
    if(isset($_GET['course'])) {
        $query = $query . "completion.courseId = " .  $_GET['course'];
        $available = true;
    }
    if(isset($_GET['client'])) {
        if( $available == true) {
            $query = $query . " AND ";
        }
        $available = true;
       $query = $query . "completion.clientId = '" . $_GET['client'] . "'";
    }
    if(isset($_GET['startDate'])) {
        if( $available == true) {
            $query = $query . " AND ";
        }
        $available = true;
        $query = $query . "completion.completedOn >= '" . $_GET['startDate'] . "'";
    }
    if(isset($_GET['endDate'])) {
        if( $available == true) {
            $query = $query . " AND ";
        }
        $query = $query . "completion.completedOn <= '" . $_GET['endDate'] . "'";
    }

    if( $available == true) {
        $query = $query . " AND ";
    }
    
    $query = $query . "completion.courseId = course.courseId AND completion.clientId = clients.licenseName AND course.createdBy = " . $_GET['userId'] ."";
 	$result = $conn->query($query);
	
	
    $filename = "completionReport.csv";
    $fp = fopen('php://output', 'w');
    header('Content-type: application/csv');
    header('Content-Disposition: attachment; filename='.$filename);
    $header = array("Course Name", "Client Name", "Student Id", "Student Name", "Completion (Date & Time)");
    fputcsv($fp, $header);
	
	if ($result->num_rows > 0) {
		 while($row = $result->fetch_assoc()) { 
			fputcsv($fp, $row);
		}
		
	}else{
		//echo '[]';
	}
	exit;
    //echo $query;
	
?>