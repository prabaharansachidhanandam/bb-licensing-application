<?php

    include("../includes/connection.php");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $clientId = $_GET['clientId'];
    $userEmail = $_GET['userEmail'];

    $query = "SELECT adminEmail FROM clients WHERE clientId = " . $clientId;
	
	$emailResultSet = $conn->query($query);
    $pos = -1;
    if ($emailResultSet->num_rows > 0) {
		
		if ( $row = $emailResultSet->fetch_assoc()){
			$firstRow = $row;
            $pos = strpos($firstRow["adminEmail"], $userEmail);
		}
        
    }

   if($pos > -1) {
    $JSONOutput =  '{"result": {"code": "1","message": "Admin user"}}';
   } else{
    $JSONOutput =  '{"result": {"code": "0","message": "Standard user"}}';
   }
   
   header('Content-type: application/json');

    echo $JSONOutput;

    $conn->close();

?>