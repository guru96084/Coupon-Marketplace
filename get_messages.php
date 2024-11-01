<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401); // Unauthorized
    die(json_encode(["error" => "Unauthorized access."]));
}

$sender_id = $_SESSION['user_id']; // Sender is the logged-in user
$receiver_id = isset($_POST['receiver_id']) ? intval($_POST['receiver_id']) : null;
$item_id = isset($_POST['item_id']) ? intval($_POST['item_id']) : null;

// Validate the receiver_id and item_id
if ($receiver_id === null || $item_id === null) {
    http_response_code(400); // Bad Request
    die(json_encode(["error" => "Invalid request. Missing required parameters."]));
}

// Database connection
$conn = new mysqli("localhost", "root", "", "gurujee");
if ($conn->connect_error) {
    http_response_code(500); // Internal Server Error
    die(json_encode(["error" => "Database connection failed: " . $conn->connect_error]));
}

// Query to fetch all messages exchanged between sender and receiver for the specific item
$sql = "
    SELECT * 
    FROM messages 
    WHERE (sender_id = ? AND receiver_id = ? AND item_id = ?) 
    OR (sender_id = ? AND receiver_id = ? AND item_id = ?) 
    ORDER BY created_at ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iiiiii", $sender_id, $receiver_id, $item_id, $receiver_id, $sender_id, $item_id);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $messages[] = [
            "message" => htmlspecialchars($row['message']),
            "created_at" => htmlspecialchars($row['created_at']),
            "sender_id" => $row['sender_id'],
            "receiver_id" => $row['receiver_id']
        ];
    }
}

// Return the messages as a JSON response
header('Content-Type: application/json');
echo json_encode($messages);

// Close the statement and connection
$stmt->close();
$conn->close();
?>
