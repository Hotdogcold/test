<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="prud.css">
</head>
<body>
    <h1>Inventory</h1>
<nav class="navbar">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="user.php">User_info</a></li>
            <li><a href="prod.php">Product</a></li>
            <li><a href="sale.php">Sales</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="logout.php">Log Out</a></li>
        </ul>
    </nav>

    <main>
    <section class="grid-container">
        <article class="grid-item">
            <h2>Lorem Ipsum</h2>
            Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
            Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, 
            when an unknown printer took a galley of type and scrambled it to make a type specimen 
            book. It has survived not only five centuries, but also the leap into electronic 
            typesetting, remaining essentially unchanged. It was popularised in the 1960s with the 
            release of Letraset sheets containing Lorem Ipsum passages, and more recently with 
            desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
        </article>
    </main>
</body>
</html>