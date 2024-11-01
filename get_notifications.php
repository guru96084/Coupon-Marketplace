<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    exit('Invalid request');
}

$seller_id = $_SESSION['user_id'];

$conn = new mysqli("localhost", "root", "", "gurujee");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve notifications for the seller
$stmt = $conn->prepare("SELECT id, message, item_id, created_at FROM notifications WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $seller_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='notification'>
                <p>" . htmlspecialchars($row['message']) . "</p>
                <small>" . $row['created_at'] . "</small>
                <a href='chat.php?seller_id=" . $seller_id . "&item_id=" . $row['item_id'] . "'>Go to Chat</a>
              </div>";
    }
} else {
    echo "<p>No notifications.</p>";
}

$stmt->close();
$conn->close();
?>
