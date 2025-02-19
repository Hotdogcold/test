<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="ind.css">
    <title>Document</title>
</head>
<body>
<h1>Tindahan</h1>
<nav class="navbar">
        <ul>
            <li><a href="#">Home</a></li>
            <li><a href="userp.php">Cart</a></li>
            <li><a href="#">Receipt</a></li>
            <li><a href="logout.php">Log Out</a></li>
        </ul>

    <main>
        <div class="card">
            <div class="image">
            <img src="dave.jfif" alt="hih">
            </div>
            <div class="caption">
                <p class="rate">
                </p>
                <p class="product_name">Product Name</p>
                <p class="price">$300</p>
            </div>
            <button class="add">Add to Cart</button>
        </div>
    </main>

</body>
</html>