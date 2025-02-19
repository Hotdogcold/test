
<?php

$sname = "localhost";
$uname = "root";
$password = "";
$dbname = "inventory";
$conn = new mysqli($sname, $uname, $password, $dbname);

$sql = "SELECT p.id, p.name, p.description, p.price, p.stock, s.quantity 
        FROM products p
        JOIN sale s ON p.id = s.product_id"; 

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="prud.css">
    <title>Document</title>
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
<h2>Sales List</h2>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Category</th>
            <th>Stock</th>
            <th>Price</th>
            <th>Sales Quantity</th>
            <th>Total Price</th>
        </tr>
    </thead>
    <tbody>
    <?php
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            if (isset($row['price']) && isset($row['quantity'])) {
                $totalPrice = $row['price'] * $row['quantity'];
            } else {
                $totalPrice = 0; 
            }

            echo "<tr>";
            echo "<td>".$row['id']."</td>";
            echo "<td>".$row['name']."</td>";
            echo "<td>".$row['description']."</td>";
            echo "<td>".$row['stock']."</td>";
            echo "<td>".$row['price']."</td>";
            echo "<td>".$row['quantity']."</td>";
            echo "<td>".$totalPrice."</td>"; 
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='7'>No sales found</td></tr>";
    }
    ?>
    </tbody>
</table>
</body>
</html>