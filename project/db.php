
<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "discussion_room";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>