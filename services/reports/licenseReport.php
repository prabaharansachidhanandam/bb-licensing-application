<?php
    //include("../includes/connection.php");
    
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "license1";

// Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);


	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
    
    $json_params = file_get_contents("php://input");
    $decoded_params = json_decode($json_params);

    $query = "SELECT assignment.courseId, assignment.clientId, course.courseName, assignment.licenseCount FROM assignment,course WHERE ";
    $available = false;
    
    if(isset($decoded_params->clientId)) {
        $query = $query . "	assignment.clientId = " . $decoded_params->{'clientId'};
       // $available = true;
    }
   /* if(isset($decoded_params->courseId)) {
        if( $available == true) {
            $query = $query . " AND ";
        }
        $available = true;
       $query = $query . "assignment.courseId = " . $decoded_params->{'courseId'};
    }*/
     
    $query = $query . " AND assignment.courseId = course.courseId LIMIT 0, 20";

    echo  $query;

  //  $query = $query . " UNION SELECT COUNT(usertracking.id) AS count FROM usertracking, course WHERE usertracking.courseId = course.courseId AND usertracking.clientId = " .  $decoded_params->{'clientId'};

 	$result = $conn->query($query);
   
     if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {

            $courses .= $row["courseId"] . ",";

           // $json[] = $row;
          /* $query1 = "SELECT COUNT(id) AS count FROM usertracking WHERE courseId = " .  $row["courseId"] . " AND clientId = " . $row["clientId"];

           $result1 = $conn->query($query1);
           $row1 = $result1->fetch_assoc();
           $row["usedCount"] = $row1["count"];
           $json[] = $row;
          echo "courseId :: " . $row["courseId"] . ' --------Count ::  ' . $row1["count"] . '<br/>' . json_encode($json) . '<br/>';*/

       }
       //echo $courses;
      // echo "Total count :: " . count($json);
      // echo 'end';
      // header('Content-type: application/json');
      // echo json_encode($json);
   }else{
      // echo '[]';
   }


   
   // echo $query;
	
?>