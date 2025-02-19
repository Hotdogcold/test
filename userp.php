<?php
session_start(); 

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "inventory";


$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];

    $sql = "INSERT INTO products (name, Stock, price) VALUES ('$name', $quantity, $price)";
    if ($conn->query($sql) === TRUE) {
        echo "New product added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

if (isset($_GET['id'])) {
    $id = $_GET['id']; 
    $sql = "DELETE FROM products WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        echo "Product deleted successfully!";
    } else {
        echo "Error deleting product: " . $conn->error;
    }
}

if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $Stock = $_POST['Stock'];

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['Stock'] += $Stock; 
    } else {
        $_SESSION['cart'][$product_id] = ['Stock' => $Stock]; 

    }

    echo "<p>Product added to cart!</p>";

}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['purchase_product'])) {
    if (!empty($_SESSION['cart'])) {
        $purchase_success = true;
        foreach ($_SESSION['cart'] as $product_id => $cart_item) {
            $purchase_Stock = $cart_item['Stock'];
            $sql = "SELECT * FROM products WHERE id = $product_id";
            $result = $conn->query($sql);
            $product = $result->fetch_assoc();
            if ($product['Stock'] >= $purchase_Stock) {
                $new_Stock = $product['Stock'] - $purchase_Stock;
                $total_cost = $product['price'] * $purchase_Stock; 
                $sql_update = "UPDATE products SET Stock = $new_Stock WHERE id = $product_id";
                if ($conn->query($sql_update) === TRUE) {

                    $sql_sale = "INSERT INTO sale (product_id, quantity, total_cost) VALUES ($product_id, $purchase_Stock, $total_cost)";
                    if (!$conn->query($sql_sale)) {
                        $purchase_success = false;
                        echo "Error logging the sale: " . $conn->error;
                    }
                } else {
                    $purchase_success = false;
                    echo "Error purchasing product: " . $conn->error;
                }
            } else {
                $purchase_success = false;
                echo "Not enough stock available for product: " . $product['name'] . ". Only " . $product['Stock'] . " items are available.";
            }

        }
        if ($purchase_success) {
            generate_receipt();
        }
        unset($_SESSION['cart']);
    } else {
        echo "<p>Your cart is empty.</p>";
    }
}

if (isset($_POST['remove_from_cart'])) {
    $product_id = $_POST['product_id'];
    unset($_SESSION['cart'][$product_id]);
    echo "<p>Product removed from your cart!</p>";

    header("Location: userp.php");
    exit();
}

$sql = "SELECT * FROM products";
$result = $conn->query($sql);

function generate_receipt() {
    echo "<h2>Receipt</h2>";

    $total_amount = 0;
    foreach ($_SESSION['cart'] as $product_id => $cart_item) {
        global $conn;
        $sql = "SELECT * FROM products WHERE id = $product_id";
        $product_result = $conn->query($sql);
        $product = $product_result->fetch_assoc();

        $quantity = $cart_item['Stock']; // Correct variable for cart
        $total_cost = $product['price'] * $quantity;
        $total_amount += $total_cost;

        echo "<p>Product: " . $product['name'] . "</p>";
        echo "<p>Quantity: " . $quantity . "</p>";
        echo "<p>Price per Item: PHP " . number_format($product['price'], 2) . "</p>";
        echo "<p>Total Cost: PHP " . number_format($total_cost, 2) . "</p>";
    }

    echo "<p><strong>Total Purchase Amount: PHP " . number_format($total_amount, 2) . "</strong></p>";
    echo "<p>Date of Purchase: " . date("Y-m-d H:i:s") . "</p>";
    echo "<p>Thank you for your purchase!</p>";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="prud.css">
    <title>Inventory Management</title>
</head>
<body>
<h1>Tindahan</h1>
<nav class="navbar">
        <ul>
            <li><a href="home.php">Home</a></li>
            <li><a href="userp.php">Cart</a></li>
            <li><a href="#">Receipt</a></li>
            <li><a href="logout.php">Log Out</a></li>
        </ul>
    <h2>Product List</h2>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Cart</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['name'] . "</td>";
                    echo "<td>" . $row['price'] . "</td>";
                    echo "<td>" . $row['Stock'] . "</td>";
                    echo "<td>
                            <form action='userp.php' method='POST'>
                                <input type='hidden' name='product_id' value='" . $row['id'] . "'>
                                <label for='Stock'>Quantity:</label>
                                <input type='number' name='Stock' min='1' max='" . $row['Stock'] . "' required>
                                <button type='submit' name='add_to_cart'>Add to Cart</button>
                            </form>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No products found</td></tr>";
            }
            ?>
        </tbody>
    </table>
    <h3>Your Cart</h3>
    <?php
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        echo "<table border='1'>";
        echo "<thead><tr><th>Product Name</th><th>Stock</th><th>Action</th></tr></thead>";
        echo "<tbody>";
        foreach ($_SESSION['cart'] as $product_id => $cart_item) {
            $sql = "SELECT * FROM products WHERE id = $product_id";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                $product = $result->fetch_assoc(); 
                echo "<tr>";
                echo "<td>" . $product['name'] . "</td>";
                echo "<td>" . $cart_item['Stock'] . "</td>";
                echo "<td>
                        <form action='userp.php' method='POST'>
                            <input type='hidden' name='product_id' value='" . $product['id'] . "'>
                            <button type='submit' name='remove_from_cart'>Remove</button>
                        </form>
                      </td>";
                echo "</tr>";
            }
        }
        echo "</tbody></table>";
        echo "<form action='userp.php' method='POST'><button type='submit' name='purchase_product'>Complete Purchase</button></form>";
    } else {
        echo "<p>Your cart is empty.</p>";
    }
    ?>
</body>
</html>
<?php
$conn->close();
?>
