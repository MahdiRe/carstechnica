<?php

$data = json_decode(file_get_contents('php://input'));

$data->activeStatus = 403;

$json=json_encode($data);

echo $json;


$myfile = fopen("deleted.txt", "w") or die("Unable to open file!");

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

//get user credentials from the text file named "deleted.txt"
$jsondata = file_get_contents('deleted.txt');
//decode the JSON Payload in to a variable
$data = json_decode($jsondata, true);
//extract the value of parameter 'sessionId' and store it in a variable $sessionId
$sessionId = $data ['sessionId'];
//from your table delete the row in which the field parameter 'sessionID' is equql to the value of the variable $sessionId
$sql = "DELETE FROM user WHERE sessionId=$sessionId";
//setting up the response
if ($conn->query($sql) === TRUE) {
    echo "Record deleted successfully";
} else {
    echo "Error deleting record: " . $conn->error;
}

$conn->close();

?>
