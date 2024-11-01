<?php
session_start();
require_once 'db_connection.php'; // Include your DB connection

// Check if the item_id is provided in the URL
if (!isset($_GET['item_id']) || empty($_GET['item_id'])) {
    echo json_encode([]);
    exit();
}

$item_id = intval($_GET['item_id']);
if ($item_id <= 0) {
    die("Invalid item_id received: $item_id");
}

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit();
}

$sender_id = $_SESSION['user_id']; // Logged-in user is the sender

// Fetch messages between buyer (sender) and seller (receiver)
$query = "
    SELECT m.message, m.created_at, u.id AS sender_id
    FROM messages m
    JOIN users u ON m.sender_id = u.id
    WHERE m.item_id = ?
    ORDER BY m.created_at ASC
";

$stmt = $conn->prepare($query);
$stmt->bind_param('i', $item_id);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

echo json_encode($messages);

$stmt->close();
$conn->close();
?>
