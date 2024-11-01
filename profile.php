<?php
include 'connect.php'; 

function getLoggedInUserId() {
    session_start(); 

    if (isset($_SESSION['user_id'])) {
        return $_SESSION['user_id'];
    } else {
        
        header("Location: login.php");
        exit();
    }
}

function getUploadedCoupons() {
    global $conn; 

    $userId = getLoggedInUserId();

    $sql = "SELECT * FROM coupons WHERE user_id = ?";

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("i", $userId);

    $stmt->execute();

    $result = $stmt->get_result();
    $coupons = array();
    while ($row = $result->fetch_assoc()) {
        $coupons[] = $row;
    }

    $stmt->close();
    
    return $coupons; 
}

$coupons = getUploadedCoupons(); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="css/profile-style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <header>
        <div class="header-container">
            <div class="logo">Coupon Marketplace</div>
        </div>
    </header>

    <form id="ProfileForm" action="profile.php" method="post" enctype="multipart/form-data">
        <div class="container light-style flex-grow-1 container-p-y">
            <h4 class="font-weight-bold py-3 mb-4">Account settings</h4>
            <div class="card">
                <div class="row no-gutters row-bordered row-border-light">
                    <div class="col-md-3 pt-0">
                        <div class="list-group list-group-flush account-settings-links">
                            <a class="list-group-item list-group-item-action active" data-toggle="list" href="#account-general">General</a>
                            <a class="list-group-item list-group-item-action" data-toggle="list" href="#account-coupon">My Coupon</a>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="tab-content">
                        <div class="tab-pane fade active show" id="account-general">
                                <div id="profileInfo" class="profile-info">
                                    <img id="profilePhoto" src="uploads/profile_pics/default.png" alt="Profile Picture" class="d-block ui-w-80">
                                    <div>Name: <span id="displayName"></span></div>
                                    <div>Email: <span id="displayEmail"></span></div>
                                    <div>Company: <span id="displayCompany"></span></div>
                                    <p id="displayBio">Bio: [User Bio]</p>
                                    <p id="displayday">Birthday: [User Birthday]</p>
                                    <p id="displayCountry">Country: [User Country]</p>
                                    <p id="displayPhone">Phone: [User Phone]</p>
                                    <p id="displayWebsite">Website: [User Website]</p>
                                    <button id="editButton">Edit</button>
                                </div>

                                <div id="editProfileForm">
                                    <h3>Edit Profile</h3>
                                    <label>First Name: <input type="text" id="firstName"></label><br>
                                    <label>Last Name: <input type="text" id="lastName"></label><br>
                                    <label>Email: <input type="text" id="email"></label><br>
                                    <label>Company: <input type="text" id="company"></label><br>
                                    <label>Profile Picture: 
                                        <input type="file" id="uploadPhoto">
                                    </label><br>

                                    <div class="form-group">
                                        <label class="form-label">Bio</label>
                                        <textarea class="form-control" id="bio" rows="5"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Birthday</label>
                                        <input type="text" class="form-control" id="birthday" value="">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Country</label>
                                        <select class="custom-select" id="country">
                                            <option>USA</option>
                                            <option>India</option>
                                            <option selected>Canada</option>
                                            <option>UK</option>
                                            <option>Germany</option>
                                            <option>France</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Phone</label>
                                        <input type="text" class="form-control" id="phone" value="">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Website</label>
                                        <input type="text" class="form-control" id="website" value="">
                                    </div>
                                    <button id="saveButton">Save</button>
                                </div>

                            </div>
                            <div class="tab-pane fade" id="account-coupon">
                                <div id="account-coupon">
                                    <h5>Your Uploaded Coupons:</h5>
                                    <?php if (!empty($coupons)): ?>
                                        <ul class="list-group">
                                            <?php foreach ($coupons as $coupon): ?>
                                                <li class="list-group-item">
                                                    <strong><?php echo htmlspecialchars($coupon['name']); ?></strong><br>
                                                    Category: <?php echo htmlspecialchars($coupon['category']); ?><br>
                                                    <img src="<?php echo htmlspecialchars($coupon['image']); ?>" alt="Coupon Image" style="max-width: 100px;"><br>
                                                    Description: <?php echo htmlspecialchars($coupon['description']); ?><br>
                                                    Expires on: <?php echo htmlspecialchars($coupon['expiration_date']); ?>
                                                    <?php 
                                                    // Check if the coupon is expired
                                                    $currentDate = date('Y-m-d');
                                                    if ($coupon['expiration_date'] < $currentDate): 
                                                    ?>
                                                        <br><strong style="color: red;">This coupon is expired.</strong>
                                                    <?php endif; ?>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php else: ?>
                                        <p>No coupons uploaded yet.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <footer>
        &copy; 2024 Coupon Marketplace. All rights reserved.
    </footer>
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="profile.js"></script>
</body>
</html>