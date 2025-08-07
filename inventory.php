<?php

    $page = 'inventory';

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

    <?php include 'include/header.php' ?>

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

                <!-- Topbar -->
                <?php include('include/topbar.php'); ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid p-0">
                        <?php include 'inventoryInsert.php' ?>
                        <?php include 'inventoryList.php' ?>
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
                <?php include('include/footer.php'); ?>
            <!-- End of Footer -->
        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Create Aluminum Modal-->
    <div class="modal animated--grow-in" id="ModalAddItemAluminum" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered px-3">
            <div class="modal-content">

                <form class="needs-validation" action="create_aluminum_item.php" method="post" novalidate>

                    <div class="modal-body d-flex text-start align-items-center justify-content-between pb-1">
                        <h5 class="m-0">Aluminum Inventory</h5>
                        <button type="reset" class="btn-close m-2" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body text-start pt-1 pb-3">
                        <div class="mb-3">
                            <label for="name" class="form-label m-1 ms-0 text-dark">Description</label>
                            <input id="name" type="text" class="form-control" name="name" required>
                            <div class="invalid-feedback">
                                <strong>Field Required</strong>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="color" class="form-label m-1 ms-0 text-dark">Color</label>
                            <input id="color" type="text" class="form-control" name="color">
                            <div class="invalid-feedback">
                                <strong>Field Required</strong>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="price" class="form-label m-1 ms-0 text-dark">Price</label>
                            <input id="price" type="text" class="form-control" name="price" required>
                            <div class="invalid-feedback">
                                <strong>Field Required</strong>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="stock" class="form-label m-1 ms-0 text-dark">Stock</label>
                            <input id="stock" type="text" class="form-control" name="stock" required>
                            <div class="invalid-feedback">
                                <strong>Field Required</strong>
                            </div>
                        </div>

                        <div class="modal-body text-end p-0">
                            <button type="submit" name="save_aluminum" class="btn btn-success w-25">
                                <strong>Save</strong>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End of Aluminum -->

    <!-- Create Glass Modal-->
    <div class="modal animated--grow-in" id="ModalAddItemGlass" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered px-3">
            <div class="modal-content">

                <form class="needs-validation" action="create_glass_item.php" method="post" novalidate>

                    <div class="modal-body d-flex text-start align-items-center justify-content-between pb-1">
                        <h5 class="m-0">Glass Inventory</h5>
                        <button type="reset" class="btn-close m-2" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body text-start pt-1 pb-3">
                        <div class="mb-3">
                            <label for="name" class="form-label m-1 ms-0 text-dark">Description</label>
                            <input id="name" type="text" class="form-control" name="name" required>
                            <div class="invalid-feedback">
                                <strong>Field Required</strong>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="color" class="form-label m-1 ms-0 text-dark">Color</label>
                            <input id="color" type="text" class="form-control" name="color">
                            <div class="invalid-feedback">
                                <strong>Field Required</strong>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="size" class="form-label m-1 ms-0 text-dark">Size</label>
                            <input id="size" type="text" class="form-control" name="size" required>
                            <div class="invalid-feedback">
                                <strong>Field Required</strong>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="price" class="form-label m-1 ms-0 text-dark">Price</label>
                            <input id="price" type="text" class="form-control" name="price" required>
                            <div class="invalid-feedback">
                                <strong>Field Required</strong>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="stock" class="form-label m-1 ms-0 text-dark">Stock</label>
                            <input id="stock" type="text" class="form-control" name="stock" required>
                            <div class="invalid-feedback">
                                <strong>Field Required</strong>
                            </div>
                        </div>

                        <div class="modal-body text-end p-0">
                            <button type="submit" name="save_glass" class="btn btn-success w-25 btn-add-glass">
                                <strong>Save</strong>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End of Glass -->

    <script>
        //btn delete
        $('.btn-delete').on('click', function(e)
        {
            e.preventDefault();
            const href = $(this).attr('href')

            Swal.fire(
            {
                text: 'Are you sure you want to delete?',
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: '#1cc88a',
                cancelButtonColor: '#e74a3b',
                confirmButtonText: 'Delete',
                }).then((result) => 
                {
                    if (result.value)
                    {
                        document.location.href = href;
                    }
            })
        });

        $('.btn-add-glass').on('click', function(e)
        {
            e.preventDefault();
            
            $.ajax({
                type: "POST",
                url: create_glass_item,
                data: data,
                success: funcion(response),
                
            });
        });
    </script>

    <script>
        //js of validation 
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

    <script>
        $(document).ready(function() 
        {
            $('#dataTables-glass').DataTable({
                responsive: true,
                "iDisplayLength": 10,
                "aLengthMenu": [[-1, 10, 20, 30, 40, 50], ["All", 10, 20, 30, 40, 50]]
            });
        });

        $(document).ready(function() 
        {
            $('#dataTables-aluminum').DataTable({
                responsive: true,
                "iDisplayLength": 10,
                "aLengthMenu": [[-1, 10, 20, 30, 40, 50], ["All", 10, 20, 30, 40, 50]]
            });
        });
    </script>


</body>

</html>