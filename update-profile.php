<?php
require 'db_connection.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];

    $username = $_POST['username'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $company = $_POST['company'];
    $bio = $_POST['bio'] ?? '';
    $birthday = $_POST['birthday'] ?? '';
    $country = $_POST['country'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $website = $_POST['website'] ?? '';
    $twitter = $_POST['twitter'] ?? '';
    $facebook = $_POST['facebook'] ?? '';
    $googleplus = $_POST['googleplus'] ?? '';
    $linkedin = $_POST['linkedin'] ?? '';
    $instagram = $_POST['instagram'] ?? '';

    $user_photo = '';
    if (isset($_FILES['user_photo']) && $_FILES['user_photo']['error'] == UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        $tmp_name = $_FILES['user_photo']['tmp_name'];
        $name = basename($_FILES['user_photo']['name']);
        $user_photo = $upload_dir . $name;
        move_uploaded_file($tmp_name, $user_photo);
    }

    $sql = "UPDATE users SET username = ?, name = ?, email = ?, company = ?, bio = ?, birthday = ?, country = ?, phone = ?, website = ?, twitter = ?, facebook = ?, googleplus = ?, linkedin = ?, instagram = ?, user_photo = ? WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssssssssi", $username, $name, $email, $company, $bio, $birthday, $country, $phone, $website, $twitter, $facebook, $googleplus, $linkedin, $instagram, $user_photo, $user_id);
    
    if ($stmt->execute()) {
        echo 'success'; // Return success message
    } else {
        echo 'error'; // Return error message
    }
}
?>
