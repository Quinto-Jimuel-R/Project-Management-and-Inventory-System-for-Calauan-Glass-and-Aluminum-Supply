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
                <h4 class="poppins-medium">Forgot Password</h4>
                <p>Please enter the email address linked with your account.</p>
            </div>

            <form action="sendOTPMail.php" method="POST" class="px-4">
                <div class="row mb-2">
                    <label for="email" class="col-md-4 col-form-label p-0">Email</label>
                    <input id="email" type="email" class="form-control rounded-0 py-2" name="email" required>
                </div>
                <div class="row mt-2">
                    <button type="submit" name="submit" class="btn btn-primary rounded-0 w-100 fs-6 mt-2 mb-3 py-2">
                        <strong>Send Code</strong>
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>