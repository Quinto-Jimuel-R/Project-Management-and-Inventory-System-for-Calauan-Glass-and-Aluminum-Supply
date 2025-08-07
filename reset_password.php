<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'include/header.php'; ?>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap');

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

        ::-webkit-scrollbar {
            display: none;
        }

        #password-strength {
            height: 10px;
            margin-top: 5px;
            border-radius: 5px;
        }

        .strength-0 {
            width: 0%;
            background-color: #ccc;
        }

        .strength-1 {
            width: 20%;
            background-color: #ff4d4d;
        }

        .strength-2 {
            width: 40%;
            background-color: #ff9966;
        }

        .strength-3 {
            width: 60%;
            background-color: #ffcc00;
        }

        .strength-4 {
            width: 80%;
            background-color: #66cc66;
        }

        .strength-5 {
            width: 100%;
            background-color: #4caf50;
        }

        .progress-bar {
            transition: 0.5s ease-in-out;
        }
    </style>

    <title>Calauan Glass and Aluminum Supply</title>
</head>
<body class="d-flex align-items-center justify-content-center" style="background-color: #213040; height: 100vh;">

<?php

// Check if OTP is submitted via POST
if(isset($_POST['otp'])) 
{
    $otp = implode("", $_POST['otp']);

    include './database.php';

    $sql = "SELECT * FROM user WHERE otp = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $otp); // Use "s" for a string parameter
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->num_rows;

    if($count > 0) {
?>
        <div class="card mx-3" style="width: 500px;">
            <div class="card-body p-3">
                <div class="text-center mb-4">
                    <h4 class="poppins-medium">Reset Password</h4>
                </div>
                <form id="resetPasswordForm" action="process_reset_password.php" method="POST" class="px-4">
                    <input type="hidden" name="otp" value="<?= $otp ?>" class="d-none">
                    <div class="row mb-2">
                        <label for="password" class="col-md-4 col-form-label p-0">New Password</label>
                        <div class="p-0 position-relative">
                            <input id="password" type="password" class="form-control rounded-0 py-2" name="password">
                            <div class="position-absolute end-0 top-50 translate-middle-y d-flex align-items-center justify-content-center me-3" style="cursor: pointer; width: 25px;">
                                <i id="eyeIcon" class="fa-solid fa-eye text-dark"></i>
                            </div>
                        </div>
                        <div class="d-flex align-items-center progress mt-2 p-0" role="progressbar" aria-label="Password strength" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                            <div id="password-strength" class="progress-bar strength-0 m-0" style="height: 100%;"></div>
                        </div>
                    </div>
                    <div id="password-error" class="row text-danger mb-2">
                        <ul id="password-error-list"></ul>
                    </div>
                    <div class="row mb-2">
                        <label for="password_confirmation" class="col-md-4 col-form-label p-0">Confirm Password</label>
                        <div class="p-0 position-relative">
                            <input id="password_confirmation" type="password" class="form-control rounded-0 py-2" name="password_confirmation">
                            <div class="position-absolute end-0 top-50 translate-middle-y d-flex align-items-center justify-content-center me-3" style="cursor: pointer; width: 25px;">
                                <i id="confirmEyeIcon" class="fa-solid fa-eye text-dark"></i>
                            </div>
                        </div>
                    </div>
                    <div id="confirm-password-error" class="row text-danger mb-2"></div>
                    <div class="row mt-2">
                        <button type="submit" name="submit" class="btn btn-primary rounded-0 w-100 fs-6 mt-2 mb-3 py-2">
                            <strong>Reset Password</strong>
                        </button>
                    </div>
                </form>
            </div>
        </div>
<?php
    } else {
        echo "<script>
                Swal.fire({
                    title: 'Incorrect OTP. Try again...',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#3085d6'
                }).then(() => {
                    window.location.href = 'enterOTP.php'; // Redirect to enterOTP page
                });
            </script>";
    }
} else {
    echo 'OTP is required.';
}
?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Toggle password visibility for New Password field
            $('#eyeIcon').on('click', function() {
                togglePasswordVisibility('#password', '#eyeIcon');
            });

            // Toggle password visibility for Confirm Password field
            $('#confirmEyeIcon').on('click', function() {
                togglePasswordVisibility('#password_confirmation', '#confirmEyeIcon');
            });

            $('#password').on('input', function() {
                var password = $(this).val();
                if (password === '') {
                    // Reset password strength meter
                    updatePasswordStrengthMeter(0);
                } else {
                    var strengthInfo = calculatePasswordStrength(password);
                    updatePasswordStrengthMeter(strengthInfo.strength);
                }
                clearErrors(); // Clear errors when typing
            });

            $('#resetPasswordForm').submit(function(event) {
                var password = $('#password').val();
                var confirmPassword = $('#password_confirmation').val();
                var errors = [];

                // Check if passwords match
                if (password !== confirmPassword) {
                    errors.push("Confirm password does not match");
                }

                // Check password strength
                var strengthInfo = calculatePasswordStrength(password);
                errors = errors.concat(strengthInfo.errors);

                if (errors.length > 0) {
                    displayErrors(errors);
                    event.preventDefault(); // Prevent form submission
                }
            });
        });

        function togglePasswordVisibility(passwordField, eyeIcon) {
            var passwordFieldEl = $(passwordField);
            var eyeIconEl = $(eyeIcon);

            if (passwordFieldEl.attr('type') === 'password') {
                passwordFieldEl.attr('type', 'text');
                eyeIconEl.removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                passwordFieldEl.attr('type', 'password');
                eyeIconEl.removeClass('fa-eye-slash').addClass('fa-eye');
            }
        }

        function calculatePasswordStrength(password) {
            var strength = 5; // Start with the maximum strength
            var errors = [];
            if (password.length >= 8) {
                strength--;
            } else {
                errors.push("Minimum 8 characters");
            }
            if (/[A-Z]/.test(password)) {
                strength--;
            } else {
                errors.push("At least one uppercase letter");
            }
            if (/\d/.test(password)) {
                strength--;
            } else {
                errors.push("At least one number");
            }
            if (/[!@#$%^&*(),.?\":{}|<>]/.test(password)) {
                strength--;
            } else {
                errors.push("At least one special character");
            }
            return { strength: 5 - errors.length, errors: errors };
        }

        function updatePasswordStrengthMeter(strength) {
            var progress = strength * 20; // Convert strength to percentage
            var strengthClass = 'strength-' + strength;
            $('#password-strength').attr('class', 'progress-bar ' + strengthClass).css('width', progress + '%');
        }

        function displayErrors(errors) {
            var errorList = $('#password-error-list');
            var confirmPasswordError = $('#confirm-password-error');
            errorList.empty();
            confirmPasswordError.empty();

            errors.forEach(function(error) {
                if (error === "Confirm password does not match") {
                    confirmPasswordError.text(error);
                } else {
                    errorList.append('<li>' + error + '</li>');
                }
            });
        }

        function clearErrors() {
            $('#password-error-list').empty();
            $('#confirm-password-error').empty();
        }
    </script>
</body>
</html>