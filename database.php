<?php

    // Use environment variables when available (works on Vercel and other hosts)
    $db_host = getenv('DB_HOST') ?: 'localhost';
    $db_user = getenv('DB_USER') ?: 'root';
    $db_pass = getenv('DB_PASS') ?: '';
    $db_name = getenv('DB_NAME') ?: 'project_management';

    $conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

    if(!$conn)
    {
        die('Connection Failed: ' . mysqli_connect_error());
    }
    return $conn;
?>