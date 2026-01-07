<?php
session_start();
include "database.php";

// Define the waiting time in seconds
$waitingTime = 30;

// Handle AJAX request to reset login attempts
if (isset($_GET['reset_attempts'])) {
    $_SESSION['login_attempts'] = 0;
    unset($_SESSION['remaining_time']);
    unset($_SESSION['last_login_attempt_time']);
    header("location: index.php");
    exit();
}

// Check if login attempts have exceeded the limit
if (isset($_SESSION['login_attempts']) && $_SESSION['login_attempts'] >= 5) {
    // Calculate the elapsed time since the last login attempt
    $timeElapsed = time() - $_SESSION['last_login_attempt_time'];

    if ($timeElapsed < $waitingTime) {
        // Calculate the remaining waiting time
        $remainingTime = $waitingTime - $timeElapsed;

        // Store the remaining time in the session
        $_SESSION['remaining_time'] = $remainingTime;

        // Display an error message and redirect to the login page
        $_SESSION['login_err_msg'] = "Too many login attempts.";
        $_SESSION['email'] = $_POST['email'] ?? ''; // Ensure the email is set
        header("location: index.php");
        exit();
    } else {
        // Reset the login attempts if the waiting period is over
        $_SESSION['login_attempts'] = 0;
        unset($_SESSION['remaining_time']);
        unset($_SESSION['last_login_attempt_time']);
    }
}

// Handle login process
if (isset($_POST['submit'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $password = md5($password);

    $result = mysqli_query($conn, "SELECT * FROM user WHERE email = '$email' AND password = '$password'");

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
        $id = $row["user_id"];

        // Insert login record into the log table
        $insertQuery = mysqli_query($conn, "INSERT INTO log (description, date, time, user_id) VALUES ('Login', CURDATE(), CURTIME(), '$id')");

        // Create a unique session ID for the user
        $sessionId = session_create_id();
        $_SESSION['session_id'] = $sessionId;

        // Set a cookie with the session ID
        setcookie("session_id", $sessionId, time() + (86400 * 30), "/"); // 30 days expiration

        $_SESSION['login_attempts'] = 0;
        unset($_SESSION['remaining_time']);
        $_SESSION['success'] = "Welcome back {$row['name']}";
        $_SESSION['user_id'] = $row['user_id'];
        $_SESSION['user_type'] = $row['user_type'];

        // Redirect based on user type
        if ($row['user_type'] == 'admin') {
            $_SESSION['adminID'] = $row['user_id'];
            $_SESSION['admin_name'] = $row['name'];
            header('Location: dashboard.php');
            exit();
        } elseif ($row['user_type'] == 'employee') {
            $_SESSION['employeeID'] = $row['user_id'];
            $_SESSION['employee_name'] = $row['name'];
            header('Location: user_dashboard.php');
            exit();
        } elseif ($row['user_type'] == 'customer') {
            $_SESSION['customerID'] = $row['user_id'];
            $_SESSION['customer_name'] = $row['name'];
            header('Location: customer_project.php');
            exit();
        } elseif ($row['user_type'] == 'cashier') {
            $_SESSION['cashierID'] = $row['user_id'];
            $_SESSION['cashier_name'] = $row['name'];
            header('Location: cashier_dashboard.php');
            exit();
        }
    } else {
        // Increment login attempts
        if (!isset($_SESSION['login_attempts'])) {
            $_SESSION['login_attempts'] = 1;
        } else {
            $_SESSION['login_attempts']++;
        }

        if ($_SESSION['login_attempts'] >= 5) {
            $_SESSION['last_login_attempt_time'] = time();
            $_SESSION['login_err_msg'] = "Too many login attempts.";
            $_SESSION['email'] = $email;
            header("location: index.php");
            exit();
        }

        // Set an error message for incorrect login details
        $_SESSION['login_err_msg'] = "Incorrect Email or Password";
        $_SESSION['email'] = $email;
        header("location: index.php");
        exit();
    }
}
?>