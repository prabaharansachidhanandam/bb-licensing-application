<link rel="icon" href="https://getbiggerbrains.com/wp-content/uploads/2017/09/favicon1.png" sizes="32x32" />
<link rel="icon" href="https://getbiggerbrains.com/wp-content/uploads/2017/09/favicon1.png" sizes="192x192" />
<title>Client Access</title>
<?php

include("includes/crypto.php");
include("includes/connection.php");
include("includes/config.php");

$base64_crypt = $_GET['YDRKEE5N2'];
$decrypt =  Encryption::decode($base64_crypt);
//$clientId = $clientString[1];
//echo "licenname :: " . $decrypt;
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
<style>
    body {
        font-family: Arial, Helvetica, sans-serif;
    }
    table {
        border-collapse: collapse;        
    }
    th {
       
        background: #6c7ae0;
        color: #FFF;
        padding: 2px 6px;
        position: sticky;
        top: 0;
        border: 1px solid #DDD;
    }
    .subheader {
        background: #6c7ae0;
        color: #FFF;
        padding: 2px 6px;
        position: sticky;
        top: 0;
        font-size: 1.1em;
        border: 1px solid #DDD;     
    }
    td {
        padding: 8px;
        font-family: Arial, Helvetica, sans-serif;
        font-size: .8em;
        border: 1px solid #DDD;
    }
</style>
<?php

$index = 2;
//$query = "SELECT * FROM course, assignment, clients WHERE course.courseId = assignment.courseId AND assignment.clientId = clients.clientId AND clients.licenseName = '" . $decrypt . "'";

$query = "SELECT CL.clientName, C.courseName, A.licenseCount, COUNT(U.courseId) AS Used, C.description, C.artwork, C.youtube, A.downloadPath FROM assignment A LEFT JOIN usertracking U ON A.clientId = U.clientId AND A.courseId = U.courseId LEFT JOIN course C ON C.courseId = A.courseId LEFT JOIN clients CL ON CL.clientId = A.clientId WHERE CL.licenseName = '" . $decrypt . "' GROUP BY CL.clientName, C.courseName, A.licenseCount, C.description, C.artwork, C.youtube, A.downloadPath";

//$query = "SELECT CL.clientName, C.courseName, A.licenseCount, C.description, C.artwork, C.youtube, A.downloadPath FROM assignment A LEFT JOIN usertracking U ON A.clientId = U.clientId AND A.courseId = U.courseId LEFT JOIN course C ON C.courseId = A.courseId LEFT JOIN clients CL ON CL.clientId = A.clientId WHERE CL.licenseName = '" . $decrypt . "' GROUP BY A.courseId Order BY C.courseName ASC";

//$query = "SELECT CL.clientName, C.courseName, A.licenseCount, C.description, C.artwork, C.youtube, A.downloadPath FROM assignment A LEFT JOIN course C ON C.courseId = A.courseId LEFT JOIN clients CL ON CL.clientId = A.clientId WHERE CL.licenseName = '" . $decrypt . "' Order BY C.courseName ASC";

$resultSet = $conn->query($query);
if ($resultSet) {
    if ($row = $resultSet->fetch_assoc()) {
        $firstRow = $row;
        
        echo '<h4 style="margin-top:20px; margin-bottom: 5px;">Welcome ' . $firstRow["clientName"] . ',</h4>';
        echo '<p>You can download your course packages, course descriptions, artwork files, and more from the table below.</p>';
        
        echo "<table border=1><tr><th style=width:5%;>Sno</th><th style=width: 25%;>Course Name</th><th style='width: 5%;' colspan='2'>License Count</th><th style=width: 50%;>Description</th><th style=width: 50%;>Artwork</th><th style=width: 50%;>Youtube Sample</th><th style=width: 50%;>Course</th></tr>";

        echo "<tr class='subheader'><td colspan='2'></td><td class='subheader'>Used</td><td  class='subheader'>Total</td><td colspan='4'></td></tr>";

        echo "<tr>";
        echo "<td align=center>1</td>";
        echo "<td>" . $firstRow["courseName"] . "</td>";
        echo "<td align=center>" . $firstRow["Used"] . "</td>";
        echo "<td align=center>" . $firstRow["licenseCount"] . "</td>";
        echo "<td align=center><a href='../courses/handouts/" . $firstRow["description"] . "' download>Download</a></td>";
        echo "<td align=center><a href='../courses/handouts/" . $firstRow["artwork"] . "' download>Download</a></td>";
        echo "<td><a href='" . $firstRow["youtube"] . "' target='_blank'>" . $firstRow["youtube"]. "</a></td>";
        echo "<td align=center><a href='" . $firstRow["downloadPath"] . "' download>Download</a>";
        echo "</tr>";

            while($row = $resultSet->fetch_assoc()) {
                echo "<tr>";
                echo "<td align=center>" . $index . "</td>";
                echo "<td>" . $row["courseName"] . "</td>";
                echo "<td align=center>" . $row["Used"] . "</td>";
                echo "<td align=center>" . $row["licenseCount"] . "</td>";
                echo "<td align=center><a href='../courses/handouts/" . $row["description"] . "' download>Download</a></td>";
                echo "<td align=center><a href='../courses/handouts/" . $row["artwork"] . "' download>Download</a></td>";
                echo "<td><a href='" . $row["youtube"] . "' target='_blank'>" . $row["youtube"]. "</a></td>";
                echo "<td align=center><a href='" . $row["downloadPath"] . "' download>Download</a>";
                echo "</tr>";
                $index++;
               
        }
      
    }else{
     
    }
    
    
}else{
    
}

?>