<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gurujee";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$userId = $_SESSION['user_id'];

$sql = "SELECT firstName, lastName, email, company, profile_photo, bio, birthday, country, phone, website FROM users WHERE id = $userId";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $userData = $result->fetch_assoc();
    echo json_encode($userData);
} else {
    echo json_encode(['error' => 'User not found.']);
}

$conn->close();
?>
