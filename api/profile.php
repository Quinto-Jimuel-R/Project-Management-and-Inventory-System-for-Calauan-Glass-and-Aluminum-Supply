<?php
    $page = 'profile';

    include 'database.php';

    include "auth_middleware.php";
    checkAuth();
    checkRole('admin');
    
    if(!isset($_SESSION['admin_name'])){
        header('location:index.php');
    }

    $adminID = $_SESSION['adminID'];

    $userResult = mysqli_query($conn, "SELECT * FROM user WHERE user_id = $adminID");

    if(mysqli_num_rows($userResult) > 0) 
    {
        $userData = mysqli_fetch_array($userResult);
    }

    $defaultImage = '../images/default.jpg';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'include/header.php'; ?>
</head>
<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        <?php include('include/sidebar.php'); ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content" class="bg-white">
                <?php include('include/topbar.php'); ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid p-0">
                    <div class="card border-0 mb-4">
                        <div class="card-body pb-0">
                            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                                <h1 class="h3 mb-0 text-dark fw-bold">Manage Account</h1>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="card-body">
                                    <form id="updateProfileForm" name="updateProfileForm" action="update.php?action=updateProfiles" method="post" enctype="multipart/form-data" novalidate>
                                        <div class="form-group">
                                            <label for="username">Username</label>
                                            <input type="text" class="form-control" id="username" name="username" value="<?php echo $userData['name']; ?>" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="address">Address</label>
                                            <input type="text" class="form-control" id="address" name="address" value="<?php echo $userData['address']; ?>" required>
                                            <div class="invalid-feedback">
                                                Please enter address.
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email" class="form-control" id="email" name="email" value="<?php echo $userData['email']; ?>" required">
                                            <div class="invalid-feedback">
                                                Please email.
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="contact">Contact</label>
                                            <input type="text" class="form-control" id="contact" name="contact" value="<?php echo $userData['phone_number']; ?>" required>
                                            <div class="invalid-feedback">
                                                Please enter contact.
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="password">Password</label>
                                            <input type="password" class="form-control" id="password" name="password" data-toggle="tooltip" title="Leave this field blank if you don't want to change password">
                                            <div class="invalid-feedback">
                                                Please choose a username.
                                            </div>
                                        </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card-body">
                                    <!-- Display current image -->
                                    <div class="d-flex align-items-center justify-content-center mb-2" style="height: 350px;">
                                        <div id="imagePreview" style="border-radius: 50%; width: 300px; height: 300px;">
                                            <img src="images/<?php echo $userData['image'] ? $userData['image'] : $defaultImage; ?>" alt="Current Image" style="border-radius: 50%; width: 300px; height: 300px;">
                                        </div>
                                    </div>
                                    <div class="input-group mb-3">
                                        <!-- Allow user to select new image -->
                                        <input type="file" class="form-control" id="image" name="image" accept=".jpg, .jpeg, .png" onchange="previewImage()">
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="adminID" value="<?php echo $adminID; ?>">

                            <div class="card-body text-center">
                                <button type="submit" id="updateProfile" class="btn btn-primary" name="submit" disabled>Update Profile</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Page Content -->
            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php include('include/footer.php'); ?>
            <!-- End of Footer -->
        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->

    <?php
        // Display success or error message
        if(isset($_SESSION['success'])) {
    ?>  
        <script>
            Swal.fire({
                position: "top-end",
                text: '<?php echo $_SESSION['success']; ?>',
                icon: "success",
                showConfirmButton: false,
                timer: 1500
            });  
        </script>
    <?php
            unset($_SESSION['success']);
        }

        if(isset($_SESSION['error'])) {
    ?>  
        <script>
            Swal.fire({
                position: "top-end",
                text: '<?php echo $_SESSION['error']; ?>',
                icon: "error",
                showConfirmButton: false,
                timer: 1500
            });  
        </script>
    <?php
            unset($_SESSION['error']);
        }
    ?>

    

    <script>
        $(document).on('input', '#contact', function(e) 
        {
            e.preventDefault();

            $(this).val($(this).val().replace(/\D/g, ''));

            if ($(this).val().length > 11) {
                $(this).val($(this).val().slice(0, 11));
            }
        });

        $(document).ready(function()
        {
            $('[data-toggle="tooltip"]').tooltip();   

            $('#password').on('input', function() {
                $(this).tooltip('hide');
            });
        });

        $(document).ready(function () {
            var initialData = {};

            function getFormData($form) {
                var unindexedArray = $form.serializeArray();
                var indexedArray = {};

                $.map(unindexedArray, function(n, i) {
                    indexedArray[n['name']] = n['value'];
                });

                return indexedArray;
            }

            $('#updateProfile').prop('disabled', true);

            $('#updateProfileForm').on('input change', function() {
                var currentData = getFormData($('#updateProfileForm'));
                var isChanged = JSON.stringify(initialData) !== JSON.stringify(currentData);
                $('#updateProfile').prop('disabled', !isChanged);
            });

            $('#updateProfile').on('click', function(event) {
                event.preventDefault();

                var form = $('#updateProfileForm')[0];
                var formData = new FormData(form);

                form.classList.add('was-validated');

                if (form.checkValidity() === false) {
                    event.stopPropagation();
                } else {
                    $.ajax({
                        type: "POST",
                        url: "update.php?action=updateProfiles",
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function (response) {
                            Swal.fire({
                                icon: 'success',
                                text: response,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                location.reload();
                            });

                            $('#updateProfileForm')[0].reset();
                            initialData = getFormData($('#updateProfileForm'));
                            $('#updateProfile').prop('disabled', true);
                        },
                    });
                }
            });
        });
    </script>

    <script>
        function previewImage() 
        {
            const fileInput = document.getElementById('image');
            const imagePreview = document.getElementById('imagePreview');

            const file = fileInput.files[0];
            const reader = new FileReader();

            reader.onloadend = function () {
                const img = document.createElement('img');
                img.src = reader.result;
                img.style.width = '100%';
                img.style.height = '100%';
                img.style.borderRadius = '50%';
                imagePreview.innerHTML = '';
                imagePreview.appendChild(img);
            };

            if (file) {
                reader.readAsDataURL(file);
            } else {
                imagePreview.innerHTML = 'Image preview will be shown here after selecting an image.';
            }
        }
    </script>

</body>
</html>
