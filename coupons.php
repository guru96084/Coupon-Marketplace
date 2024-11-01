<?php
include 'db_connection.php'; // Ensure this path is correct

$category = $_GET['category'];

$sql = "SELECT * FROM coupons WHERE category = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $category);
$stmt->execute();
$result = $stmt->get_result();

$coupons = [];
while ($row = $result->fetch_assoc()) {
    $coupons[] = $row;
}

echo json_encode($coupons);

$stmt->close();
$conn->close();
?>
