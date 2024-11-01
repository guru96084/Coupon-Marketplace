<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gurujee";


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id']; 
    
    $firstName = $conn->real_escape_string($_POST['firstName']);
    $lastName = $conn->real_escape_string($_POST['lastName']);
    $email = $conn->real_escape_string($_POST['email']);
    $company = $conn->real_escape_string($_POST['company']);
    $bio = $conn->real_escape_string($_POST['bio']);
    $birthday = $conn->real_escape_string($_POST['birthday']);
    $country = $conn->real_escape_string($_POST['country']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $website = $conn->real_escape_string($_POST['website']);

    $profilePicPath = '';
    
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
        $targetDir = "uploads/profile_pics/"; 
        $fileName = time() . '_' . basename($_FILES['profile_pic']['name']);
        $targetFilePath = $targetDir . $fileName;
        
        $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
        if (in_array($fileType, ['jpg', 'png', 'gif']) && $_FILES['profile_pic']['size'] <= 800000) {
           
            if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $targetFilePath)) {
                $profilePicPath = $targetFilePath;
            } else {
                echo json_encode(['success' => false, 'error' => 'File upload failed.']);
                exit;
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'Invalid file type or file too large.']);
            exit;
        }
    }

    $sql = "UPDATE users SET 
                firstName = '$firstName', 
                lastName = '$lastName', 
                email = '$email', 
                company = '$company',
                bio = '$bio',
                birthday = '$birthday',
                country = '$country',
                phone = '$phone',
                website = '$website'";
                
    if (!empty($profilePicPath)) {
        $sql .= ", profile_photo = '$profilePicPath'";
    }

    $sql .= " WHERE id = $userId";

    
    if ($conn->query($sql) === TRUE) {
        
        $result = $conn->query("SELECT * FROM users WHERE id = $userId");
        $userData = $result->fetch_assoc();
        
        echo json_encode([
            'success' => true,
            'firstName' => $userData['firstName'],
            'lastName' => $userData['lastName'],
            'email' => $userData['email'],
            'company' => $userData['company'],
            'bio' => $userData['bio'],
            'birthday' => $userData['birthday'],
            'country' => $userData['country'],
            'phone' => $userData['phone'],
            'website' => $userData['website'],
            'profile_photo' => $userData['profile_photo']
        ]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }

    $conn->close();
}
?>
