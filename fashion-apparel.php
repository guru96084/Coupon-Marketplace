<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fashion & Apparel - Coupon Marketplace</title>
    <link rel="stylesheet" href="css/category.css">
</head>
<body>
    <header>
        <nav>
            <div class="logo">Coupon Marketplace</div>
            <ul class="nav-links">
                <li><a href="index.html">Home</a></li>
            </ul>
        </nav>
    </header>
    
    <section class="category-items">
            <h1>Fashion & Apperel</h1>
            <div id="item-list">
            <div class="item">
                <img src="path/to/image.jpg" alt="Item Name">
                <h3>Item Name</h3>
                <p>Item Description</p>
                <p>Expires on: Date</p>
                <p>Uploaded on: Date</p>
                <a href="chat.php?item_id=1">Chat with Seller</a>
            </div>
        </div>

    </section>
    
    <footer>
        &copy; 2024 Coupon Marketplace. All rights reserved.
    </footer>

    <script src="categories.js"></script>
</body>
</html>
