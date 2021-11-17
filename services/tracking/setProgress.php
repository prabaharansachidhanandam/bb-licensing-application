<?php

include("../includes/connection.php");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$json_params = file_get_contents("php://input");
$decoded_params = json_decode($json_params);

$sessionId = $decoded_params->{'sessionId'};
 $clientId = $decoded_params->{'clientId'};
 $courseId = $decoded_params->{'courseId'};
 $userEmail = $decoded_params->{'userEmail'};
 $bookmark = $decoded_params->{'bookmark'};
 $progress = $decoded_params->{'progress'};
 $score = $decoded_params->{'score'};
 $timeSpent = $decoded_params->{'timeSpent'};
 $courseStatus = $decoded_params->{'courseStatus'};


 if($clientId == 'null') {
    $clientId = 0;
 }
$sql = "SELECT * FROM progresstracking WHERE sessionId='" .  $sessionId . "'";
//echo $sql;
$result = $conn->query($sql);

//echo $result->num_rows;

if ($result->num_rows == 0) {

    $sql = "INSERT INTO progresstracking (sessionId, clientId, courseId, userEmail, bookmark, progress, score, timeSpent, courseStatus, startDate) VALUES ('$sessionId', $clientId, $courseId, '$userEmail', $bookmark, '$progress', $score, '$timeSpent', '$courseStatus', NOW())";
                    
    if ($conn->query($sql) === TRUE) {
        $JSONOutput =  '{"result": {"code": "1","message": "Success"}}';
    } else {
        $JSONOutput =  '{"result": {"code": "0","message": "Failed 1"}}';
    }
            	   
} else {

    $sql = "UPDATE progresstracking SET bookmark = " . $bookmark .", progress = '" . $progress ."', score = " . $score . ",timeSpent ='" .  $timeSpent . "', courseStatus = '" . $courseStatus . "' WHERE sessionId = '" . $sessionId . "'" ;

    if ($conn->query($sql) === TRUE) {
        $JSONOutput =  '{"result": {"code": "1","message": "Success"}}';
    } else {
        $JSONOutput =  '{"result": {"code": "0","message": "Failed"}}';
    }
}		


header('Content-type: application/json');

echo $JSONOutput;

$conn->close();




?>