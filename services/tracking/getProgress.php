<?php

    include("../includes/connection.php");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $json_params = file_get_contents("php://input");
    $decoded_params = json_decode($json_params);


    $clientId = $decoded_params->{'clientId'};
    $courseId = $decoded_params->{'courseId'};
    $userEmail = $decoded_params->{'userEmail'};
    $userType = $decoded_params->{'userType'};
    
    $sql = "SELECT * FROM progresstracking WHERE courseId= " .  $courseId . " AND userEmail = '" . $userEmail . "' ORDER BY id DESC LIMIT 1" ;
    //echo $sql;
    $result = $conn->query($sql);


    if($userType == 'free') {

        $courseSql = "SELECT freecoursestructure.structure as structure, course.downloadPath as downloadPath FROM freecoursestructure, course where course.courseId = freecoursestructure.courseId AND course.courseId = '$courseId' and freecoursestructure.courseTypeId = 1";

        $course = $conn->query($courseSql);

        if ($course->num_rows > 0) {
            while($row = $course->fetch_assoc()) {
                $coursejson = json_decode($row["structure"], true);
                $downloadFile = $row["downloadPath"];		
            }
        }

    } else {

        $courseSql = "SELECT coursedetails.structure as structure, course.downloadPath as downloadPath FROM coursedetails, course where course.courseId = coursedetails.courseId AND course.courseId = '$courseId' and courseTypeId = 5";
   
        $course = $conn->query($courseSql);

        if ($course->num_rows > 0) {
            while($row = $course->fetch_assoc()) {
                $coursejson = json_decode($row["structure"], true);
                $downloadFile = $row["downloadPath"];		
            }
        } else {
            $courseSql = "SELECT coursedetails.structure, course.downloadPath FROM coursedetails, course where course.courseId = coursedetails.courseId AND course.courseId = '$courseId' and courseTypeId = 2";
    
            $course = $conn->query($courseSql);
            
            if ($course->num_rows > 0) {
                while($row = $course->fetch_assoc()) {
                    $coursejson = json_decode($row["structure"], true);	
                    $downloadFile = $row["downloadPath"];	
                }
            } else {

                $courseSql = "SELECT coursedetails.structure, course.downloadPath FROM coursedetails, course where course.courseId = coursedetails.courseId AND course.courseId = '$courseId' and courseTypeId = 1";
    
                $course = $conn->query($courseSql);
                
                if ($course->num_rows > 0) {
                    while($row = $course->fetch_assoc()) {
                        $coursejson = json_decode($row["structure"], true);	
                        $downloadFile = $row["downloadPath"];	
                    }
                }
            }
        }

    }
    

    $sql = "SELECT language FROM interfacetext";
	$langresult = $conn->query($sql);
	if ($langresult->num_rows > 0) {
		while($row = $langresult->fetch_assoc()) {
		   $language = json_decode($row["language"], true);
		}
	   
    }

   // echo $coursejson;

   // $coursejson = json_encode(array('structure' => $coursejson));

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $row['sessionId'] =  $userEmail . strtotime("now");
            $row['structure'] = $coursejson;
            $row['downloadPath'] = $downloadFile;
            $row['interfaceText'] = $language;
            $json[] = $row;  
        }
       // $JSONOutput = json_encode(array('result' => $json));      
    } else {
       // $json =  '{"result": [{"sessionId":"' .  $userEmail . strtotime("now") . '", "bookmark": "", "progress": ""}]}';
       $row['sessionId'] = $userEmail . strtotime("now");
       $row['bookmark'] = '';
       $row['progress'] = '';
       $row['structure'] = $coursejson;
      $row['interfaceText'] = $language;
       $json[] = $row;
    }		

    
    
    //$progressjson = json_encode(array('progress' => $json));

    //$JSONOutput = array_merge($json, $coursejson);

    $JSONOutput = json_encode($json); 	

    header('Content-type: application/json');

    echo $JSONOutput;

    $conn->close();




?>