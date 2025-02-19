<?php
session_start();

if (isset($_SESSION["user"])) {
    header("Location: ind_log.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Registration Form</title>
</head>
<body>
    <div class="container">
        <?php
        // Initialize errors array
        $errors = [];
        
        if (isset($_POST["submit"])) {
            function validate($data) {
                $data = trim($data);
                $data = str_replace(" ", "", $data);
                $data = stripslashes($data);
                $data = htmlspecialchars($data);
                return $data;
            }
            
            $username = validate($_POST['username']);
            $password = validate($_POST["password"]);
            $usertype = validate($_POST["usertype"]);
            
            if (empty($username) || empty($password)) {
                array_push($errors, "Username and Password cannot be empty.");
            }

            require_once "db_conn.php";
            $sql = "SELECT * FROM users WHERE username = ?";
            $stmt = mysqli_stmt_init($conn);
            if (mysqli_stmt_prepare($stmt, $sql)) {
                mysqli_stmt_bind_param($stmt, "s", $username);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $rowCount = mysqli_num_rows($result);
                if ($rowCount > 0) {
                    array_push($errors, "Email already exists");
                }
            }

            if (count($errors) > 0) {
                foreach ($errors as $error) {
                    echo "<div class='alert alert-danger'>$error</div>";
                }
            } else {

                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                $sql = "INSERT INTO users (username, password, usertype) VALUES (?, ?, ?)";
                $stmt = mysqli_stmt_init($conn);
                if (mysqli_stmt_prepare($stmt, $sql)) {
                    mysqli_stmt_bind_param($stmt, "sss", $username, $hashedPassword, $usertype);
                    mysqli_stmt_execute($stmt);
                    echo "<div class='alert alert-success'>You are registered successfully.</div>";
                } else {
                    die("Something went wrong");
                }
            }
        }
        ?>

        <form action="regs.php" method="post">
            <h2>Registration Form</h2>
            <div class="form-group">
                <input type="text" class="form-control" name="username" placeholder="Username" required>
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="Password" required>
            </div>
            <select name="usertype" required>
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>
            <div class="form-btn">
                <input type="submit" class="btn btn-primary" value="Register" name="submit">
            </div>
            <div class="link">
                <a href="ind_log.php">Login Here</a>
            </div>
        </form>
    </div>
</body>
</html>
