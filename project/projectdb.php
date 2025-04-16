<?php
$servername = "localhost";
$username = "root";
$password = ""; // if using XAMPP, keep blank
$dbname = "hackathon_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>
