<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'include/header.php' ?>
</head>
<body class="d-flex align-items-center justify-content-center" style="background-color: #213040; height: 100vh;">
<?php
    include 'database.php';
        
    if ($_SERVER["REQUEST_METHOD"] == "POST") 
    {
        $otp = $_POST['otp'];
        $password = $_POST['password'];

        $query = mysqli_query($conn, "SELECT * FROM user WHERE otp = '$otp'");

        if (mysqli_num_rows($query) == 1)
        {
            
            $row = mysqli_fetch_assoc($query);
            $user_id = $row['user_id'];

            $hashedPassword = md5($password);

            $update_query = mysqli_query($conn, "UPDATE user SET password = '$hashedPassword', otp = NULL WHERE user_id = '$user_id'");
            if ($update_query) 
            {
                echo "<script>
                        Swal.fire({
                            icon: 'success',
                            text: 'Password updated successfully',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true,
                            allowOutsideClick: false
                        }).then(() => {
                            window.location.href = 'index.php';
                        });
                    </script>";
            }
        } else {
            $row = mysqli_fetch_assoc($query);
            $user_id = $row['user_id'];

            $hashedPassword = md5($password);

            $update_query = mysqli_query($conn, "UPDATE user SET password = '$hashedPassword', otp = NULL WHERE user_id = '$user_id'");
            if ($update_query) 
            {
                echo "<script>
                        Swal.fire({
                            icon: 'success',
                            text: 'Password updated successfully',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true,
                            allowOutsideClick: false
                        }).then(() => {
                            window.location.href = 'index.php';
                        });
                    </script>";
            }
        }
    } 
    else 
    {
        // If not a POST request, redirect to reset_password.php
        header("Location: reset_password.php");
        exit();
    }
?>

</body>
</html>