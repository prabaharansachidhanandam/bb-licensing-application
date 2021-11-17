<?php
	include("../includes/connection.php");
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
    
    $query = "SELECT course.courseName, clients.clientName, feedback.moduleId, feedback.moduleName, feedback.studentId, feedback.studentName, feedback.feedback, feedback.date FROM feedback, course, clients WHERE ";
    $available = false;
    
    if(isset($_GET['course'])) {
        $query = $query . "feedback.courseId = " . $_GET['course'];
        $available = true;
    }
    if(isset($_GET['client'])) {
        if( $available == true) {
            $query = $query . " AND ";
        }
        $available = true;
       $query = $query . "feedback.clientId = '" . $_GET['client'] . "'";
    }
    if(isset($_GET['startDate'])) {
        if( $available == true) {
            $query = $query . " AND ";
        }
        $available = true;
        $query = $query . "feedback.date >= '" . $_GET['startDate'] . "'";
    }
    if(isset($_GET['endDate'])) {
        if( $available == true) {
            $query = $query . " AND ";
        }
        $query = $query . "feedback.date <= '" . $_GET['endDate'] . "'";
    }

    if( $available == true) {
        $query = $query . " AND ";
    }

    $query = $query . "feedback.courseId = course.courseId AND feedback.clientId = clients.licenseName AND course.createdBy = " . $decoded_params->{'userId'} ."";
 	$result = $conn->query($query);

     $filename = "feedbackReport.csv";
     $fp = fopen('php://output', 'w');
     header('Content-type: application/csv');
     header('Content-Disposition: attachment; filename='.$filename);
     $header = array("Course Name", "Client Name", "Lesson Number" , "Lesson Name", "Student Id", "Student Name", "Feedback", "Completion (Date & Time)");
     fputcsv($fp, $header);
     
     if ($result->num_rows > 0) {
          while($row = $result->fetch_assoc()) { 
             fputcsv($fp, $row);
         }
         
     }else{
         //echo '[]';
     }
     exit;
   
   // echo $query;
	
?>