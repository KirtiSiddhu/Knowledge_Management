<?php

$host = "localhost";
$dbname = "library_booking";
$user = "root";        
$pass = "";            

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get and sanitize user inputs
$name = $conn->real_escape_string($_POST['name'] ?? '');
$email = $conn->real_escape_string($_POST['email'] ?? '');
$start_time = $conn->real_escape_string($_POST['start_time'] ?? '');
$end_time = $conn->real_escape_string($_POST['end_time'] ?? '');

// Validate inputs
if (empty($name) || empty($email) || empty($start_time) || empty($end_time)) {
    echo "<script>
        alert('All fields are required.');
        window.location.href = 'booking.html';
    </script>";
    exit;
}

// Check if time slot overlaps with any existing booking
$checkQuery = "SELECT * FROM bookings WHERE 
    (start_time < '$end_time' AND end_time > '$start_time')";

$result = $conn->query($checkQuery);

if ($result->num_rows > 0) {
    echo "<script>
        alert('Time slot from $start_time to $end_time is already booked! Please choose another.');
        window.location.href = 'booking.html';
    </script>";
    exit;
}

// Insert the new booking
$insertQuery = "INSERT INTO bookings (name, email, start_time, end_time) 
                VALUES ('$name', '$email', '$start_time', '$end_time')";

if ($conn->query($insertQuery) === TRUE) {
    echo "<script>
        alert('Booking successful! You have reserved from $start_time to $end_time.');
        window.location.href = 'booking.html';
    </script>";
} else {
    echo "<script>
        alert('Error processing your booking. Please try again.');
        window.location.href = 'booking.html';
    </script>";
}

$conn->close();
?>
