<?php
session_start();
include "db_conn.php";

if(isset($_POST['uname']) && isset($_POST['password'])) {

    function validate($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

  
    $uname = validate($_POST['uname']);
    $pass = validate($_POST['password']);

    
    if(empty($uname)) {
        header("Location: ind_log.php?error=User Name is required");
        exit();
    }
    else if(empty($pass)) {
        header("Location: ind_log.php?error=Password is required");
        exit();
    }

    
    $sql = "SELECT * FROM users WHERE username=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $uname);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    
    if(mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);

       
        if(password_verify($pass, $row['password'])) {  
            echo "Password is correct!";
        }
            $_SESSION['username'] = $row['username'];
            $_SESSION['id'] = $row['id'];
            $_SESSION['usertype'] = $row['usertype']; 

       
            if($row['usertype'] == 'admin') {
                header("Location: index.php"); 
            } else if($row['usertype'] == 'user')  {
                header("Location: home.php"); 
            }
            exit();
            
        } else {
            header("Location: ind_log.php?error=Incorrect password!");
            exit();
        }
    } else {
        header("Location: ind_log.php?error=User not found!");
        exit();
    }

?>
