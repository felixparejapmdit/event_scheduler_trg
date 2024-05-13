<?php
 // Login successful
 if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include '../databases/connection/db.php';

// Check connection
if ($conn->connect_error) {

    die("Connection failed: " . $conn->connect_error);
    header("Location: ../"); // Relative path to sched.php
}
// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $username = $_POST['username'];
    $password = $_POST['password'];
   
    // SQL query to check if the username exists in the database
    $sql = "SELECT * FROM user WHERE username='$username'";
    $result = $conn->query($sql);
      
    if ($result->num_rows == 1) {
        // Username exists, fetch the user data
        $row = $result->fetch_assoc();
        $hashed_password = $row['password'];
        $userid = $row['id'];
        $role = $row['role'];
        $fullname = $row['name'];
        $contact = $row['contact'];
        $sectionid = $row['section'];
        $photo = $row['profile_photo'];
        // Verify the provided password with the hashed password from the database

        if ($password === $row['password']) {
        //if (password_verify($password, $hashed_password)) {
           
            $_SESSION['username'] = $username;
            $_SESSION['fullname'] = $fullname;
            $_SESSION['contact'] = $contact;
            $_SESSION['role'] = $role;
            $_SESSION['photo'] = $photo;
            $_SESSION['userid'] = $userid;
            $_SESSION['sectionid'] = $sectionid;

              // Clear localStorage
    echo "<script>localStorage.clear();</script>";
    echo $username;
            if ($_SESSION['role'] === "1" || $_SESSION['role'] === "3" ) {
                // Redirect to sched.php
                header("Location: dashboard.php"); // Relative path to sched.php
                exit(); // Ensure no further code execution after redirection
            } elseif ($_SESSION['role'] === "2" ) {
                // Redirect to weekly_task.php
                header("Location: sched.php"); // Relative path to weekly_task.php
                exit(); // Ensure no further code execution after redirection
            } elseif ($_SESSION['role'] === "4") {
                // Redirect to weekly_task.php
                header("Location: sched.php"); // Relative path to weekly_task.php
                exit(); // Ensure no further code execution after redirection
            }
            
            
            exit(); // Terminate script execution after redirection
        } else {
            // Password does not match
            header("Location: ../"); // Relative path to sched.php
            echo "Invalid password. Please try again.";
        }
    } else {
        // Username not found
        header("Location: ../"); // Relative path to sched.php
        echo "Invalid username. Please try again.";
    }
    
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Event Tracker - Login</title>
<link rel="icon" href="./images/scheduler.ico" type="image/x-icon">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            width: 300px;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s ease-in-out;
        }

        .container:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        h2 {
            text-align: center;
            color: #03220e;
            margin-bottom: 30px;
        }

        label {
            font-weight: normal;
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="password"] {
            width: calc(100% - 0px);
            padding: 10px 40px 10px 40px;
            margin: 5px 0 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            transition: border-color 0.3s ease-in-out;
            position: relative;
            font-size: 16px;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #4caf50;
            outline: none;
        }

        input[type="submit"] {
            width: 100%;
            background-color: #4caf50;
            color: white;
            padding: 12px 0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .icon {
            position: absolute;
            left: 10px;
            top: 40%;
            transform: translateY(-50%);
            color: #aaa;
            z-index: 1; /* Ensure the icon is displayed above the input text */
        }

        input[type="text"]:focus + .icon,
        input[type="password"]:focus + .icon {
            opacity: 0;
        }

        @media (max-width: 600px) {
            .container {
                width: 90%;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Event Tracker System</h2>
    <form action="./views/index.php" method="post">
        <label for="username" style="display:none;">Username:</label>
        <div style="position: relative;">
            <!-- Font Awesome user icon -->
            <i class="icon fas fa-user"></i>
            <input type="text" id="username" name="username" required>
        </div>

        <label for="password" style="display:none;">Password:</label>
        <div style="position: relative;">
            <!-- Font Awesome lock icon -->
            <i class="icon fas fa-lock"></i>
            <input type="password" id="password" name="password" required>
        </div>

        <input type="submit" value="Login">
    </form>
</div>
<script>
    // Focus on the username input field when the page loads
    window.onload = function() {
        document.getElementById('username').focus();
    };
</script>

<script>
    // Hide icon when input is focused
    document.querySelectorAll('input[type="text"], input[type="password"]').forEach(function(input) {
        input.addEventListener('focus', function() {
            this.nextElementSibling.style.opacity = '0';
        });
        input.addEventListener('blur', function() {
            if (this.value === '') {
                this.nextElementSibling.style.opacity = '1';
            }
        });
    });
</script>
</body>
</html>
