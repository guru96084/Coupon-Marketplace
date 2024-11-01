<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "gurujee");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start(); // Start session
$current_user_id = $_SESSION['user_id']; // Current logged-in user ID

// Fetch distinct users that the current user has messaged or received messages from
$sql = "
    SELECT DISTINCT u.id AS user_id, u.firstName, u.lastName, u.profile_photo, m.item_id
    FROM users u
    JOIN messages m ON (
        (m.sender_id = u.id AND m.receiver_id = $current_user_id) OR 
        (m.receiver_id = u.id AND m.sender_id = $current_user_id)
    )
    WHERE u.id != $current_user_id
";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat List</title>
    <link href="css/chat_list_style.css" rel="stylesheet">
</head>
<body>
    <h2>Coupon Marketplace Messages</h2>

    <div class="user-list">
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="user-item">
                    <img src="<?= htmlspecialchars($row['profile_photo']) ?>" alt="Profile Picture" style="width: 50px; height: 50px; border-radius: 50%;">
                    <span><?= htmlspecialchars($row['firstName'] . ' ' . $row['lastName']) ?></span>
                    <form method="GET" action="chat.php" style="margin-left: auto;">
                        <input type="hidden" name="item_id" value="<?= $row['item_id'] ?>"> <!-- Ensure item_id is passed -->
                        <button type="submit">Chat</button>
                    </form>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No previous conversations found.</p>
        <?php endif; ?>
    </div>
</body>
</html>

<?php
$conn->close();
?>
