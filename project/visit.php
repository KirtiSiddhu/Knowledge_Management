<?php
session_start();

// DB connection
$host = 'localhost';
$dbname = 'docrepo';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    $pdo = null;
}

$user_id = $_SESSION['user_id'] ?? 0;

// Get bookmark title and link
$title = $_GET['title'] ?? 'Unknown';
$url = $_GET['url'] ?? 'https://www.w3schools.com/';

// Log activity
$activity = "Visited: " . htmlspecialchars($title);

if ($pdo) {
    $stmt = $pdo->prepare("INSERT INTO recent_activity (user_id, activity) VALUES (?, ?)");
    $stmt->execute([$user_id, $activity]);
}

// Add to session too
$_SESSION['recent_activity'][] = $activity;
$_SESSION['recent_activity'] = array_slice($_SESSION['recent_activity'], 0, 10);

// Redirect to actual URL
header("Location: $url");
exit;
