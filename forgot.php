<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'include/header.php' ?>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

        
        .poppins-medium {
            font-family: "Poppins", sans-serif;
            font-weight: 600;
            font-style: normal;
        }

        .poppins-regular {
            font-family: "Poppins", sans-serif;
            font-weight: 400;
            font-style: normal;
        }

        ::-webkit-scrollbar{
            display: none;
        }
    </style>

    <title>Calauan Glass and Aluminum Supply</title>
</head>
<body class="d-flex align-items-center justify-content-center" style="background-color: #213040; height: 100vh;">
    <div class="card mx-3" style="width: 500px;">
        <div class="card-body p-3">
            <div class="text-center mb-4">
                <h4 class="poppins-medium">Reset Your Password </h4>
                <p>How do you want to receive the code to reset your password?</p>
            </div>

            <a href="forgot_password.php" class="btn btn-primary btn-md btn-block mb-3">Send code via Email</a>

            <a href="forgot_password_sms.php" class="btn btn-primary btn-md btn-block">Send code via SMS</a>
        </div>
    </div>
</body>
</html>