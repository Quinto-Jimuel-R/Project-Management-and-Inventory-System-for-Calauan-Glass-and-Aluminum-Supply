<?php

include 'database.php';

$email = isset($_GET['email']) ? $_GET['email'] : '';
$phoneNumber = isset($_GET['phoneNumber']) ? $_GET['phoneNumber'] : '';

?>

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
                <h4 class="poppins-medium mb-3">OTP Verification</h4>
                <p class="mb-2">Enter the verification code we sent to <br> 
                    <span class="m-0" style="font-weight: 700;">
                        <?php echo $email ?>

                        <?php
$phoneNumber;

// Extract country code and number
$countryCode = substr($phoneNumber, 0, 3); // +63
$number = substr($phoneNumber, 3); // 9512444315

// Format the number
$formattedNumber = $countryCode . " " . substr($number, 0, 3) . " " . substr($number, 3, 4) . " " . substr($number, 7);

// Output the formatted number
echo $formattedNumber; // Output: +63 951 2444 315
?>


                    </span>
                </p>
            </div>
            <form action="reset_password.php" method="POST" class="px-4">
                <div class="row">
                    <label for="otp" class="col-md-6 col-form-label p-0 mb-2">Type 4 digit security code</label>
                </div>
                <div class="d-flex flex-row mb-2" style="margin: 0 -12px;">
                    <!-- Create four input boxes -->
                    <input id="otp1" type="text" style="height: 75px; font-size: 25px;" class="form-control text-center rounded-0 py-2 mr-1" name="otp[]" maxlength="1" pattern="[0-9]" inputmode="numeric" required>
                    <input id="otp2" type="text" style="height: 75px; font-size: 25px;" class="form-control text-center rounded-0 py-2 mr-1" name="otp[]" maxlength="1" pattern="[0-9]" inputmode="numeric" required>
                    <input id="otp3" type="text" style="height: 75px; font-size: 25px;" class="form-control text-center rounded-0 py-2 mr-1" name="otp[]" maxlength="1" pattern="[0-9]" inputmode="numeric" required>
                    <input id="otp4" type="text" style="height: 75px; font-size: 25px;" class="form-control text-center rounded-0 py-2" name="otp[]" maxlength="1" pattern="[0-9]" inputmode="numeric" required>

                </div>
                <div class="row mt-2">
                    <button type="submit" name="submit" class="btn btn-primary rounded-0 w-100 fs-6 mt-2 mb-3 py-2">
                        <strong>Verify</strong>
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

<script>
    $(document).ready(function() {
        // When a digit is entered into an OTP input field
        $('input[name^="otp"]').on('input', function() {
            // Remove non-numeric characters
            $(this).val($(this).val().replace(/\D/g, ''));

            // Move focus to the next input field if the current field is not empty
            if ($(this).val().length > 0) {
                $(this).next('input[name^="otp"]').prop('disabled', false).focus();
            }
        });

        // When the backspace key is pressed, move focus to the previous input field if the current field is empty
        $('input[name^="otp"]').on('keydown', function(e) {
            if (e.keyCode == 8 && $(this).val().length === 0) {
                $(this).prev('input[name^="otp"]').focus();
            }
        });

        // Disable input into subsequent fields if the previous field is empty
        $('input[name^="otp"]').slice(1).prop('disabled', true);
    });
</script>

