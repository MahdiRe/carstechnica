<?php

$data = json_decode(file_get_contents('php://input'));

$data->activeStatus = 1;

$json=json_encode($data);

echo $json;


$myfile = fopen("created.txt", "w") or die("Unable to open file!");

fwrite($myfile, $json);

fclose($myfile);


$dbhost = 'localhost';
$dbuser = 'MahdiRefaideen0819';
$dbpass = '3c2#mahdirefaideen412';

$conn = mysqli_connect($dbhost, $dbuser, $dbpass) ;

if (!$conn)
  {
  die('Could not connect: ' . mysqli_error());
  }
mysqli_select_db( $conn,"MahdiRefaideen0819_db");

$jsondata = file_get_contents('created.txt');

$data = json_decode($jsondata, true);

//$userdata=json_decode($json,true);

$sessionId = $data['sessionId'];
$userId = $data['userId'];
$filterCriteria = $data['name'];

$sql = "INSERT INTO user(sessionId, userId, name)
VALUES ('$sessionId', '$userId', '$filterCriteria')";

if ($conn->query($sql) === TRUE) {
    echo "Record updated successfully";
} else {
    echo "Error updating record: " . $conn->error;
}

$conn->close();


?>
