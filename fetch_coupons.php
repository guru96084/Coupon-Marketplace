<?php
$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "gurujee";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$category = isset($_GET['category']) ? $_GET['category'] : '';
$currentDate = date('Y-m-d');

$sql = "SELECT item_id, name, category, image, description, expiration_date AS expirationDate, upload_date AS uploadDate FROM coupons WHERE category = ? AND (expiration_date IS NULL OR expiration_date > ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $category, $currentDate);
$stmt->execute();
$result = $stmt->get_result();

$coupons = [];
while ($row = $result->fetch_assoc()) {
    $coupons[] = $row;
}

$stmt->close();
$conn->close();

header('Content-Type: application/json');
echo json_encode($coupons);
?>
