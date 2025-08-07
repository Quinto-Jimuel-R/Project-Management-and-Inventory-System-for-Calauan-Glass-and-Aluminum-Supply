<?php

    session_start();

    include "database.php";

    function logEvent($userId, $description) {
        global $conn;
        $insertQuery = mysqli_query($conn, "INSERT INTO log (description, date, time, user_id) VALUES ('$description', CURDATE(), CURTIME(), '$userId')");
    }

    function checkAuth() 
    {
        if (!isset($_SESSION['user_id']) || !isset($_COOKIE['session_id']) || $_SESSION['session_id'] !== $_COOKIE['session_id']) 
        {
            if (isset($_SESSION['user_id'])) 
            {
                logEvent($_SESSION['user_id'], 'Logout');
            }

            session_destroy();

            $_SESSION['login_err_msg'] = "Please login to access this page.";
            header("location: index.php");
            exit();
        }
    }

    function checkRole($requiredRole) 
    {
        if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== $requiredRole) 
        {
            if (isset($_SESSION['user_id'])) 
            {
                logEvent($_SESSION['user_id'], 'Logout');
            }

            session_destroy();
            
            $_SESSION['login_err_msg'] = "You do not have access to this page.";
            header("location: index.php");
            exit();
        }
    }

?>
