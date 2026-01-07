<?php
    session_start();

    $email = $login_err_msg = "";
    $remaining_time = 0; // Default value

    // Check if 'remaining_time' is set in the session
    if (isset($_SESSION['remaining_time'])) {
        $remaining_time = $_SESSION['remaining_time'];
    }

    if (isset($_SESSION['login_err_msg']) && ($_SESSION['login_err_msg'] != "")) {
        $login_err_msg = $_SESSION['login_err_msg'];
        if (isset($_SESSION['email'])) {
            $email = $_SESSION['email'];
            unset($_SESSION['email']);
        }
        unset($_SESSION['login_err_msg']);
    }
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
        
        .navbar .nav .nav-item .nav-link {
            font-size: 15px;
            color: #213040;
            background-color: transparent; /* Match the default background color */
            text-decoration: none; /* Remove underline on hover */
            width: fit-content;
            transition: 0.1s ease-in; /* Add transition for smooth animation */
        }

        /* Apply zoom effect on hover */
        .navbar .nav-link:hover {
            color: #213040;
            background-color: transparent; /* Match the default background color */
            text-decoration: none; /* Remove underline on hover */
            transform: scale(1.1); /* Zoom in by 10% */
        }

        .navbar .nav-link.active {
            font-weight: 600;
            border-bottom: 3px solid #213040; /* Solid underline with white color */
            padding-bottom: 3px; /* Optional: Add some padding to the underline */
            transform: none;
        }

        /* Inside your CSS */
        .animated-image {
            opacity: 1;
            transition: opacity 0.5s ease;
        }

        .animated-image.animate {
            opacity: 0;
            transition: opacity 0.5s ease;
        }

        .login-button {
            background-color: #213040;
            text-decoration: none;
            font-weight: 600;
            width: 100px; /* Fixed width */
            height: 40px; /* Fixed height */
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
        }

        .brand-name {
            display: inline;
        }

        .small-brand-name {
            display: none;
        }

        @media (max-width: 1024px) {

            .text-justify {
                overflow-y: scroll;
                max-height: 300px;
            }

            .navbar {
                padding: 0;
            }

            .small-brand-name{
                display: inline;
            }

            .brand-name {
                display: none;
            }

            .navbar{
                display: flex;
                justify-content: space-between;
            }
        }

        @media (max-width: 710px) {
            .navbar {
                display: flex;
                justify-content: center;
            }
            
            .nav {
                padding: 1px;
            }
        
            .brand-name {
                display: none;
            }
        
            .text-justify {
                overflow-y: scroll;
                max-height: 300px;
            }
        
            .small-brand-name {
                display: inline;
            }
            
            #Home {
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                padding: 0 10px;
            }
            
            #Home h1 {
                width: 350px;
                margin-top: 100px;
            }
            
            #glass {
                width: 90%;
                margin: 0 auto;
            }
        
            #text{
                width: auto;
                margin: 0 auto;
                word-wrap: break-word;
                text-align: center;
            }
            
            #top {
                width: 100% !important; /* Override earlier styles */
                margin-bottom: 13px;
            }
        }
        
        .carousel-item {
            transition: transform 0.3s ease-in-out; /* Add transition for smooth animation */
        }

        .carousel-item.active {
            transform: translateX(0); /* Set the initial position for the active item */
        }

        .carousel-item-next {
            transform: translateX(100%); /* Move next item to the right */
        }

        .carousel-item-prev {
            transform: translateX(-100%); /* Move previous item to the left */
        }

        .carousel-item-end,
        .active.carousel-item-start {
            transform: translateX(0); /* Reset position for start/end items */
        }

        .img-thumbnail {
            transition: transform 0.5s ease-in-out; /* Add transition for smooth animation */
        }

        .img-thumbnail.active {
            transform: scale(1.2); /* Zoom the active thumbnail to 120% of its original size */
        }

        .img-thumbnail:not(.active) {
            transform: scale(1); /* Reset the scale for inactive thumbnails */
        }
        
        
        #glass {
            font-size: 2rem;
            width: auto;
            text-align: center; /* Optional: Center-align text */
            margin-top: 175px; 
            font-weight: 700; 
            color: #213040; 
            word-spacing: 8px;
        }
        
        #text {
            display: inline-block; /* Makes #text fit its content */
            width: 350px;
            font-weight: bold;
            font-size: 2rem;
            color: black;
        }
        
        .menu-item a {
            font-size: 1rem; /* Adjust font size as needed */
        }
        
        #top{
            width: 50%;
        }
        
        #logins{
            margin-left: 110px;
        }
    </style>

    <title>Calauan Glass and Aluminum Supply</title>
</head>

<body style="background-color: #213040;">

<nav id="navbar-example2" class="navbar shadow-lg bg-white position-sticky top-0 px-2" style="width: 100%; padding: 30px 0px; z-index: 1000; color: #213040;">
    <div id="top" class="d-flex">
        <div id="logos" class="ms-3 d-flex align-items-center brand-container">
            <img src="pictures/logo-removebg-preview.png" alt="" style="width: 50px;">
            <span class="ms-2 poppins-medium brand-name" style="font-weight: 500;"><strong>Calauan Glass and Aluminum Services</strong></span>
            <span class="ms-2 poppins-medium small-brand-name" style="font-weight: 500;"><strong>CGAS</strong></span>
        </div>
        <div class="d-flex align-items-center">
            <div id="logins" class="d-block d-sm-none">
                <a id="login" href="" class="login-button rounded-0 py-0 d-flex align-items-center m-0 text-white px-3" style="background-color: #213040; text-decoration: none; font-weight: 600;" data-bs-toggle="modal" data-bs-target="#user-login">Login</a>
            </div>
        </div>
    </div>
    <div class="">
        <ul class="nav nav-pills poppins-regular">
            <li class="nav-item menu-item">
                <a class="nav-link rounded-0" href="#Home">Home</a>
            </li>
            <li class="nav-item menu-item">
                <a class="nav-link rounded-0" href="#Project">Project</a>
            </li>
            <li class="nav-item menu-item">
                <a class="nav-link rounded-0" href="#About">About</a>
            </li>
            <li class="nav-item menu-item">
                <a class="nav-link rounded-0" href="#Contact">Contact</a>
            </li>
            <div class="ms-2 d-none d-xxl-block d-sm-block">
                <a id="login" href="" class="login-button rounded-0 py-0 d-flex align-items-center m-0 text-white px-3" style="background-color: #213040; text-decoration: none; font-weight: 600;" data-bs-toggle="modal" data-bs-target="#user-login">Login</a>
            </div>
        </ul>
    </div>
</nav>

    <div data-bs-spy="scroll" data-bs-target="#navbar-example2" data-bs-root-margin="0px" data-bs-smooth-scroll="true" class="scrollspy-example gap-1 rounded-2">
        <div style="height: 90vh; background-image: linear-gradient(
          157deg,
          hsl(0deg 0% 100%) 1%,
          hsl(0deg 0% 100%) 34%,
          hsl(0deg 0% 100%) 42%,
          hsl(0deg 0% 100%) 47%,
          hsl(0deg 0% 100%) 49%,
          hsl(216deg 2% 97%) 50%,
          hsl(216deg 3% 86%) 51%,
          hsl(216deg 3% 74%) 51%,
          hsl(215deg 3% 59%) 51%,
          hsl(215deg 7% 37%) 50%,
          hsl(211deg 32% 19%) 50%,
          hsl(211deg 32% 19%) 49%,
          hsl(211deg 32% 19%) 49%,
          hsl(211deg 32% 19%) 49%,
          hsl(211deg 32% 19%) 50%,
          hsl(211deg 32% 19%) 51%,
          hsl(211deg 32% 19%) 53%,
          hsl(211deg 32% 19%) 58%,
          hsl(211deg 32% 19%) 66%,
          hsl(211deg 32% 19%) 99%
        );">
            
        <div id="Home" class="d-flex justify-content-around text-center" style="margin: 0;">
            <div>
                <h1 id="glass" class="poppins-medium">
                    Find The Perfect
                </h1>
                <div id="text" class="poppins-medium"></div>
            </div>
            <img id="animatedImage" src="pictures/Untitled_design-removebg-preview.png" class="d-none d-md-block animated-image pt-5" alt="" style="height: 600px;">
        </div>
    </div>
        
        <div class="my-5 py-5" style="height: fit-content; background-color: #213040;">
            <div id="Project" class="carousel slide px-5 py-2 d-flex flex-column align-items-center justify-content-center" data-bs-ride="carousel">
                <h2 class="mt-2 text-white" style="font-weight: 700;">Project</h2>
                <div class="carousel-inner mt-2" style="width: 100%; max-width: 900px;">
                    <div class="carousel-item active">
                        <img src="pictures/pic1.jpg" class="d-block mx-auto" alt="Slide 1" style="height: 500px; width: 100%; object-fit: cover;">
                    </div>
                    <div class="carousel-item">
                        <img src="pictures/pic2.jpg" class="d-block mx-auto" alt="Slide 2" style="height: 500px; width: 100%; object-fit: cover;">
                    </div>
                    <div class="carousel-item">
                        <img src="pictures/pic3.jpg" class="d-block mx-auto" alt="Slide 3" style="height: 500px; width: 100%; object-fit: cover;">
                    </div>
                    <div class="carousel-item">
                        <img src="pictures/pic4.jpg" class="d-block mx-auto" alt="Slide 4" style="height: 500px; width: 100%; object-fit: cover;">
                    </div>
                    <div class="carousel-item">
                        <img src="pictures/pic5.jpg" class="d-block mx-auto" alt="Slide 5" style="height: 500px; width: 100%; object-fit: cover;">
                    </div>
                    <div class="carousel-item">
                        <img src="pictures/pic6.jpg" class="d-block mx-auto" alt="Slide 6" style="height: 500px; width: 100%; object-fit: cover;">
                    </div>
                </div>

                <button class="carousel-control-prev" type="button" data-bs-target="#Project" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#Project" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>

            <!-- Thumbnails for larger screens -->
            <div class="d-none d-md-flex justify-content-center gap-3 mt-2">
                <div class="text-center">
                    <img src="pictures/pic1.jpg" class="img-thumbnail active" alt="Thumbnail 1" style="width: 100px; height: 70px;" data-bs-target="#Project" data-bs-slide-to="0">
                </div>
                <div class="text-center">
                    <img src="pictures/pic2.jpg" class="img-thumbnail" alt="Thumbnail 2" style="width: 100px; height: 70px;" data-bs-target="#Project" data-bs-slide-to="1">
                </div>
                <div class="text-center">
                    <img src="pictures/pic3.jpg" class="img-thumbnail" alt="Thumbnail 3" style="width: 100px; height: 70px;" data-bs-target="#Project" data-bs-slide-to="2">
                </div>
                <div class="text-center">
                    <img src="pictures/pic4.jpg" class="img-thumbnail" alt="Thumbnail 4" style="width: 100px; height: 70px;" data-bs-target="#Project" data-bs-slide-to="3">
                </div>
                <div class="text-center">
                    <img src="pictures/pic5.jpg" class="img-thumbnail" alt="Thumbnail 5" style="width: 100px; height: 70px;" data-bs-target="#Project" data-bs-slide-to="4">
                </div>
                <div class="text-center">
                    <img src="pictures/pic6.jpg" class="img-thumbnail" alt="Thumbnail 6" style="width: 100px; height: 70px;" data-bs-target="#Project" data-bs-slide-to="5">
                </div>
            </div>
        </div>

        <div class="my-5 pt-5" style="height: fit-content;">
            <div id="About" class="text-white text-center py-2">
                <div class="text-center mb-2" style="font-weight: 700; font-size: 35px;"> CALAUAN GLASS AND ALUMINUM SERVICES </div>
                <div class="d-flex justify-content-center mb-5">
                    <div class="col-12 col-lg-10">
                        Is a privately-owned firm based in Calauan, Laguna with almost two decades of experience in various kinds of aluminum and glass works. We are providing products and services that meet the types of demands along with high standards of quality.
                    </div>
                </div>

                <div class="mt-3 mb-5 px-3 mx-0 px-md-5 row justify-content-around">
                    <div class="col-lg-5 col-md-6 mt-4">
                        <div class="card" style="background-color: #213040; color: #fff;">
                            <div class="card-body">
                                <div class="d-flex justify-content-center">
                                    <i class="fa-solid fa-rocket me-3" style="font-size: 40px;"></i>
                                    <div class="fw-bold fs-2 mb-3">MISSION</div>
                                </div>
                                <div class="text-justify" style="font-size: 16px;">
                                    Our mission is to provide high-quality fabrication and installation services that meet and exceed our clients' expectations. We aim to achieve this by delivering a reliable, durable and aesthetically pleasing product and offering exceptional customer service. We are dedicated to fostering a culture of innovation, commitment to continuous improvement, and sustainability, ensuring every project is completed to the highest standard. We strive to positively impact the communities we serves.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-5 col-md-6 mt-4">
                        <div class="card" style="background-color: #213040; color: #fff;">
                            <div class="card-body">
                                <div class="d-flex justify-content-center">
                                    <i class="fa-solid fa-eye me-3" style="font-size: 40px;"></i>
                                    <div class="fw-bold fs-2 mb-3">VISION</div>
                                </div>
                                <div class="text-justify" style="font-size: 16px;">
                                    To be the leading provider of innovative glass and aluminum products and services known for our precision, reliability, and customer satisfaction while transforming spaces into inspiring environments.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-5">
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3868.7648216906236!2d121.30988917517786!3d14.149919386284811!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33bd5e81def1c643%3A0xb62fc4888ada9c22!2sCALAUAN%20GLASS%20AND%20ALUMINUM%20SUPPLY%20%26%20SERVICES!5e0!3m2!1sen!2sph!4v1717571777337!5m2!1sen!2sph" width="100%" height="500" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>
        </div>

        <div class="mx-2 my-5 py-5" style="height: fit-content;">
            <div id="Contact" class="text-white py-2">
                <div class="text-center">
                    <h2>Hi, How can we Help?</h2>
                </div>

                <div class="row justify-content-center m-0">
                    <div class="col-lg-4 mb-3 mb-lg-0">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="text-center">
                                    <i class="fa-solid fa-message fs-2 mb-4"></i>
                                </div>
                                <div class="text-center mb-3" style="font-weight: 700; font-size: 17px;">Message Us</div>
                                <div class="d-flex align-items-center justify-content-center">
                                    <a href="https://www.facebook.com/aluminumandmetalworks" target="_blank">Calauan Glass & Aluminum Services</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 mb-3 mb-lg-0">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="text-center">
                                    <i class="fa-solid fa-phone-volume fs-2 mb-4"></i>
                                </div>
                                <div class="text-center mb-3" style="font-weight: 700; font-size: 17px;">Call Us</div>
                                <div class="d-flex align-items-center justify-content-center">
                                    <a>09278158215</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 mb-3 mb-lg-0">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="text-center">
                                    <i class="fa-solid fa-envelope fs-2 mb-4"></i>
                                </div>
                                <div class="text-center mb-3" style="font-weight: 700; font-size: 17px;">Email Us</div>
                                <div class="d-flex align-items-center justify-content-center">
                                    <a href="https://mail.google.com/mail/u/0/#inbox?compose=jrjtXGjLxjllSkJvRsKzWHnFDvnTpJkglNhsCbrPtMxtzwBRlwvKxFrWgrSSWsxBqSnRxPst" target="_blank">cgaservices.laguna@gmail.com</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex mt-5">
                    <div class="col-12 text-center mt-5">
                        <p class="fs-5">THANK YOU FOR VISITING</p>
                    </div>
                </div>
            </div>
        </div>

    </div>

<!-- Modal -->
<div class="modal fade animated--fade-in" id="user-login" data-bs-backdrop="static" style="height: 700px;" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body d-flex justify-content-end p-2" style="font-size: 14px;">
        <button id="closeModal" type="button" class="btn-close rounded-0" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="text-center">
            <img src="pictures/logo-removebg-preview.png" alt="" class="mb-2" style="width: 60px;">
            <h4 class="poppins-medium text-dark">Login</h4>
        </div>

        <form id="loginForm" class="px-4" action="log.php" method="post">

            <?php if(!empty($login_err_msg)): ?>
                <div id="invalid" class="invalid row alert alert-danger alert-dismissible fade d-flex justify-content-center show mb-2 p-3" role="alert">
                    <div class="text-center"><?php echo $login_err_msg; ?></div>
                </div>
            <?php endif; ?>

            <div class="row mb-2">
                <label for="email" class="col-md-4 col-form-label p-0 text-dark" style="font-weight: 700;">Email</label>
                <input id="email" type="email" class="form-control rounded-0 py-2 text-dark" style="font-weight: 600;" name="email" value="<?php echo $email; ?>" required>
            </div>

            <div class="row mb-2">
                <label for="password" class="col-md-4 col-form-label p-0 text-dark" style="font-weight: 700;">Password</label>
                <div class="p-0 position-relative">
                    <input id="password" type="password" class="form-control rounded-0 py-2 text-dark" style="font-weight: 600;" name="password" required>
                    <div class="position-absolute end-0 top-50 translate-middle-y d-flex align-items-center justify-content-center me-3" style="cursor: pointer; width: 25px;">
                        <i id="eyeIcon" class="fa-solid fa-eye text-dark"></i>
                    </div>
                </div>
            </div>


            <div class="row text-right">
                <a href="forgot.php" class="p-0" style="text-decoration: none;">Forgot Password?</a>
            </div>

            <div class="row mt-3">
                <button id="loginButton" type="submit" name="submit" class="btn btn-primary rounded-0 w-100 fs-6 mt-2 mb-3 py-2">
                    <strong>Login</strong>
                </button>
            </div>

        </form>
      </div>
    </div>
  </div>
</div>

<script>
    $(document).ready(function() {
        $("#eyeIcon").on("click", function() {
            var passwordField = $("#password");
            var icon = $(this);
            
            if (passwordField.attr("type") === "password") {
                passwordField.attr("type", "text");
                icon.removeClass("fa-eye").addClass("fa-eye-slash");
            } else {
                passwordField.attr("type", "password");
                icon.removeClass("fa-eye-slash").addClass("fa-eye");
            }
        });
    });

    $(document).on('click', '#closeModal', function(e)
    {
        e.preventDefault()

        $('.err-msg').html();
        $('#email').val("");
    });

    $(document).ready(function (){
  	    <?php if ($login_err_msg != "") { ?>
            $("#user-login").modal('show');
        <?php } ?>
    });

    $(document).ready(function() {
        <?php if (!empty($login_err_msg) && strpos($login_err_msg, "Too many login attempts") !== false) { ?>
            var remainingTime = <?php echo isset($_SESSION['remaining_time']) ? $_SESSION['remaining_time'] : 30; ?>;
            var interval;
            
            function updateCountdown() {
                $("#invalid").html("Please try again in " + remainingTime + " seconds.");
                remainingTime--;

                if (remainingTime < 0) {
                    clearInterval(interval);
                    $("#errorContainer").fadeOut();
                    $("#loginButton").prop('disabled', false);

                    // Send AJAX request to reset login attempts
                    $.ajax({
                        url: 'index.php', // Your PHP file that handles resetting
                        type: 'GET',
                        data: { reset_attempts: true },
                        success: function(response) {
                            window.location.href = 'index.php';
                        }
                    });
                }
            }

            interval = setInterval(updateCountdown, 1000);

            $("#loginButton").click(function(e) {
                e.preventDefault(); // Prevent form submission
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'You are not allowed to login right now!',
                });
            });
        <?php } else { ?>
            // Show the "Please try again" message after 3 seconds
            setTimeout(function() {
                $("#errorContainer").fadeOut();
            }, 3000);
        <?php } ?>
    });

        //validation
        (function () {
            'use strict'

            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.querySelectorAll('.needs-validation')

            // Loop over them and prevent submission
            Array.prototype.slice.call(forms)
                .forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                    }

                    form.classList.add('was-validated')
                }, false)
                })
            })()

            var alertList = document.querySelectorAll('.alert')
            alertList.forEach(function (alert) {
            new bootstrap.Alert(alert)
        })
</script>

</body>
</html>

<script>
    $(document).ready(function () {
        var name = "Glass and Aluminum Services";
        var speed = 150;
        var i = 0;
        var isDeleting = false; // To track if we're in the deleting phase

        function autoType() {
            var textElement = $("#text");

            // Check if we're in the typing phase
            if (!isDeleting && i <= name.length) {
                textElement.append(name.charAt(i)); // Add a character
                i++;
                setTimeout(autoType, speed);
            } else if (isDeleting && i >= 0) {
                // If in the deleting phase and i is not negative, remove a character
                textElement.text(name.substring(0, i)); // Display substring from 0 to i
                i--;
                setTimeout(autoType, speed / 1); // Adjust the speed for deleting phase
            } else {
                // If the whole string is typed and deleted, reset and repeat the process
                i = 0;
                isDeleting = false;
                setTimeout(autoType, speed); // Start typing again after a brief pause
            }

            // When reached the end, set isDeleting to true to start deleting
            if (i === name.length) {
                isDeleting = true;
            }
        }

        autoType();
    });

</script>

<script>
    $(document).ready(function() {
        $('#Project').carousel({
            interval: 3000,
            wrap: true
        });
    });

    $(document).ready(function () {
        var thumbnails = $('.img-thumbnail');
        var carousel = $('#Project');

        // Function to toggle active class on thumbnails
        function toggleActiveThumbnail(index) {
            thumbnails.each(function (i) {
                if (i === index) {
                    $(this).addClass('active');
                } else {
                    $(this).removeClass('active');
                }
            });
        }

        // Listen for slide event on carousel
        carousel.on('slide.bs.carousel', function (event) {
            var slideTo = event.to;
            toggleActiveThumbnail(slideTo);
        });

        // Listen for click event on thumbnails
        thumbnails.each(function (index) {
            $(this).on('click', function () {
                toggleActiveThumbnail(index);
            });
        });
    });

</script>
