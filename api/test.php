<?php
/**
 * Created by PhpStorm.
 * User: Jesus
 * Date: 27/10/2015
 * Time: 11:19
 */

// Include db_config.php
include_once('db_config.php');

if($_SERVER['REQUEST_METHOD'] == "POST"){
    // Get data
    $placeId = isset($_POST['PlaceId']) ? mysql_real_escape_string($_POST['PlaceId']) : "";
    $paper = isset($_POST['Paper']) ? mysql_real_escape_string($_POST['Paper']) : "";
    $size = isset($_POST['Size']) ? mysql_real_escape_string($_POST['Size']) : "";
    $waitTime = isset($_POST['WaitTime']) ? mysql_real_escape_string($_POST['WaitTime']) : "";
    $cleanliness = isset($_POST['Cleanliness']) ? mysql_real_escape_string($_POST['Cleanliness']) : "";
    $smell = isset($_POST['Smell']) ? mysql_real_escape_string($_POST['Smell']) : "";

    // Create connection
    $conn = new mysqli($serverName, $userName, $password, $dbName);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "INSERT INTO Score (PlaceId, Paper, Size, WaitTime, Cleanliness, Smell)
	VALUES ('$placeId', '$paper', '$size', '$waitTime', '$cleanliness', '$smell')";

    if ($conn->query($sql) === TRUE) {
        $json = array("status" => 1, "msg" => "New score added succesfully");
    } else {
        $json = array("status" => 0, "msg" => "Error adding score <br>" . $sql . "<br>" . $conn->error);
    }

    $conn->close();
}else{
    $json = array("status" => 0, "msg" => "Request method not accepted");
}

@mysql_close($conn);

/* Output header */
	header('Content-type: application/json');
	echo json_encode($json);

?>
