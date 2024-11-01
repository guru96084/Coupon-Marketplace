<?php
session_start();
require_once 'db_connection.php'; 


if (!isset($_GET['item_id']) || empty($_GET['item_id'])) {
    echo "<p>Unable to start chat. Please try again from the item page.</p>";
    exit();
}

$item_id = intval($_GET['item_id']);
if ($item_id <= 0) {
    die("Invalid item_id received: $item_id");
}


$query = "SELECT user_id FROM coupons WHERE item_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $item_id);
$stmt->execute();
$stmt->bind_result($receiver_id);
$stmt->fetch();
$stmt->close();


if (!isset($_SESSION['user_id'])) {
    die("You need to log in to chat.");
}

$sender_id = $_SESSION['user_id']; 


$query = "SELECT firstName, lastName FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $receiver_id);
$stmt->execute();
$stmt->bind_result($receiver_first_name, $receiver_last_name);
$stmt->fetch();
$receiver_name = htmlspecialchars($receiver_first_name . ' ' . $receiver_last_name);
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $message = trim($_POST['message']);
    
    
    if (!empty($message)) {
        
        $query = "INSERT INTO messages (sender_id, receiver_id, item_id, message, created_at) VALUES (?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('iiis', $sender_id, $receiver_id, $item_id, $message);
        if (!$stmt->execute()) {
            die("Error inserting message: " . $stmt->error);
        }
        $stmt->close();
    }

    header("Location: chat.php?item_id=$item_id");
    exit();
}

$query = "
    SELECT m.message, m.created_at, u.id AS sender_id, u.firstName, u.lastName
    FROM messages m
    JOIN users u ON m.sender_id = u.id
    WHERE m.item_id = ?
    ORDER BY m.created_at ASC
";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $item_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat with Seller</title>
    <link rel="stylesheet" href="css/chat-style.css">
</head>
<body>
<div class="chat-box">
    <h3>Chat with <?= $receiver_name ?></h3>

    <div id="chat-messages">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="message <?php echo $row['sender_id'] == $sender_id ? 'sent' : 'received'; ?>">
                <p><?php echo htmlspecialchars($row['message']); ?></p>
                <small><?php echo htmlspecialchars($row['created_at']); ?></small>
            </div>
        <?php endwhile; ?>
    </div>

    <form method="POST" id="message-form">
        <textarea name="message" required></textarea>
        <button type="submit">Send</button>
    </form>
</div>

<script>
    const chatMessages = document.getElementById('chat-messages');

    setInterval(() => {
        fetch('fetch_messages.php?item_id=<?= $item_id; ?>')
            .then(response => response.json())
            .then(data => {
                chatMessages.innerHTML = '';
                data.forEach(message => {
                    const msgDiv = document.createElement('div');
                    msgDiv.classList.add('message', message.sender_id == <?= $sender_id; ?> ? 'sent' : 'received');
                    msgDiv.innerHTML = `<p>${message.message}</p> <small>${message.created_at}</small>`;
                    chatMessages.appendChild(msgDiv);
                });
                chatMessages.scrollTop = chatMessages.scrollHeight; 
            });
    }, 2000);
    
    const messageForm = document.getElementById('message-form');
    messageForm.addEventListener('submit', function(event) {
        event.preventDefault(); 
        const formData = new FormData(messageForm);
        fetch('chat.php?item_id=<?= $item_id; ?>', {
            method: 'POST',
            body: formData
        })
        .then(() => {
            messageForm.reset(); 
        });
    });
</script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
