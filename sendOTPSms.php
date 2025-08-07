<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'include/header.php'; ?>
</head>
<body style="background-color: #213040;">
    <!-- Your HTML content goes here -->
</body>
</html>
<?php

    include 'database.php';

    $phoneNumber = $_POST['phoneNumber'];
    $otp = rand(1000, 9999);

    $sql = "SELECT * FROM user WHERE phone_number = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $phoneNumber);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->num_rows;

    if ($count > 0) 
    {
        $sql_update_statement = "UPDATE user SET otp = ? WHERE phone_number = ?";
        $statement = $conn->prepare($sql_update_statement);
        $statement->bind_param("ss", $otp, $phoneNumber);
        $statement->execute();

        try {
            require 'smsAPI.php';

            header("location: enterOTP.php?phoneNumber=" . urlencode($phoneNumber));
            exit();

        } catch (Exception $e) {
            echo "An error occurred. The message could not be sent: {$e->getMessage()}";
        }
    } else {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'The phone number you entered does not exist in the system',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = 'forgot_password_sms.php'; // Redirect to forgot password page
                });
            </script>";
    }
?>
