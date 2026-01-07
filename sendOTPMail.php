<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'include/header.php' ?>
</head>
<body style="background-color: #213040;">
    
</body>
</html>

<?php
 
    include './database.php';

    $email = $_POST['email'];
    $otp = rand(1000, 9999);

    $sql = "SELECT * FROM user WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->num_rows;

    if ($count > 0) {
        $sql_update_statement = "UPDATE user SET otp = ? WHERE email = ?";
        $statement = $conn->prepare($sql_update_statement);
        $statement->bind_param("ss", $otp, $email);
        $statement->execute();

        try {
            require 'emailAPI.php';
            
            header("location: enterOTP.php?email=$email");
            exit();

        } catch (Exception $e) {
            echo "An error occurred. The message could not be sent: {$mail->ErrorInfo}";
        }

    } else {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'The email address you entered does not exist in the system',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = 'forgot_password.php'; // Redirect to forgot password page
                });
            </script>";
    }
?>
