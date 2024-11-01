<?php
session_start();
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $uploaderId = $_SESSION['user_id'];
    
    $itemName = $_POST['itemName'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $expirationDate = $_POST['expirationDate'];
    $uploadDate = date('Y-m-d H:i:s');

    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["itemImage"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    $check = getimagesize($_FILES["itemImage"]["tmp_name"]);
    if ($check === false) {
        echo json_encode(['success' => false, 'message' => 'File is not an image.']);
        exit; 
    }

    if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
        echo json_encode(['success' => false, 'message' => 'Only JPG, JPEG, PNG & GIF files are allowed.']);
        exit; 
    }

    
    if ($_FILES["itemImage"]["size"] > 500000) {
        echo json_encode(['success' => false, 'message' => 'Sorry, your file is too large.']);
        exit; 
    }

    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["itemImage"]["tmp_name"], $target_file)) {
            
            $stmt = $conn->prepare("INSERT INTO coupons (user_id, name, category, image, description, expiration_date, upload_date) VALUES (?, ?, ?, ?, ?, ?, ?)");
            if ($stmt) {
                
                $stmt->bind_param("issssss", $uploaderId, $itemName, $category, $target_file, $description, $expirationDate, $uploadDate);
                
                if ($stmt->execute()) {
                    $stmt->close();
                    echo json_encode(['success' => true, 'message' => 'Coupon uploaded successfully.']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Error executing the SQL statement: ' . $stmt->error]);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to prepare SQL statement.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Sorry, there was an error uploading your file.']);
        }
    }
}
?>
