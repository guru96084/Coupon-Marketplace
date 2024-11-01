<?php
session_start();
require_once 'db_connection.php'; // Include your DB connection

$item_id = intval($_POST['item_id']);
$sender_id = intval($_POST['sender_id']);
$receiver_id = intval($_POST['receiver_id']);
$message = trim($_POST['message']);

// Insert the message into the database if not empty
if (!empty($message)) {
    $query = "INSERT INTO messages (sender_id, receiver_id, item_id, message, created_at) VALUES (?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('iiis', $sender_id, $receiver_id, $item_id, $message);
    $stmt->execute();
    $stmt->close();
}

$conn->close();
