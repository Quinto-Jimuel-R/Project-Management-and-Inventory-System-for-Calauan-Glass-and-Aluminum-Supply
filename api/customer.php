<?php

    $page = 'customer';

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
    <script type="text/javascript">
        function preventBack(){window.history.forward()};
        setTimeout("preventBack()", 0);
            window.onunload=function(){null;}
    </script>
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

                    <div class="card mb-4 border-0 mb-4" style="background-color: transparent;">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="CustomerTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th class="text-center d-none">#</th>
                                            <th>Customer Name</th>
                                            <th>Address</th>
                                            <th>Email</th>
                                            <th>Phone Number</th>
                                            <th>Controls</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        $customerResult = mysqli_query($conn, "SELECT * FROM user WHERE user_type = 'customer'");

                                        $counter = 1;

                                        if(mysqli_num_rows($customerResult) > 0) 
                                        {
                                            while($customerData = mysqli_fetch_array($customerResult)) 
                                            {
                                    ?>
                                                <tr>
                                                    <td class="text-center d-none"><?php echo $counter++ ?></td>
                                                    <td><?php echo $customerData['name'] ?></td>
                                                    <td><?php echo $customerData['address'] ?></td>
                                                    <td><?php echo $customerData['email'] ?></td>
                                                    <td><?php echo $customerData['phone_number'] ?></td>
                                                    <td>
                                                        <div class="text-center d-flex justify-content-evenly">
                                                            <a id="editCustomer" href="#" class="text-success" data-bs-toggle="modal" data-bs-target="#updateCustomerModal"
                                                               data-id="<?php echo $customerData['user_id']; ?>"
                                                               data-name="<?php echo $customerData['name']; ?>"
                                                               data-address="<?php echo $customerData['address']; ?>"
                                                               data-email="<?php echo $customerData['email']; ?>"
                                                               data-phone="<?php echo $customerData['phone_number']; ?>">
                                                                <i class="fa-solid fa-pen-to-square" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                        ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                <div>
            <!-- End of Main Content -->

            <!-- Footer -->
                <?php include('include/footer.php'); ?>
            <!-- End of Footer -->
        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <div class="modal fade animated--grow-in" id="updateCustomerModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="update.php" id="updateCustomerForm" name="updateCustomerForm" method="POST" class="needs-validation text-dark" novalidate>
                    <div class="modal-header d-flex align-items-center">
                        <strong class="text-dark">Edit Customer</strong>
                        <button id="closeModal" type="button" class="btn-close rounded-0" data-bs-dismiss="modal" aria-label="Close" style="font-size: 12px;"></button>
                    </div>
                    <div class="modal-body text-left p-2">
                        <input type="hidden" id="updateId" name="updateId">
                        <div class="text-left px-1">
                            <div class="mb-2">
                                <label class="form-label mx-1 mt-1 mb-0">Name</label>
                                <input type="text" class="form-control" id="updateName" name="updateName" style="height: 45px;" required>
                                <div class="invalid-feedback">
                                    Please enter name.
                                </div>
                            </div>

                            <div class="mb-2">
                                <label class="form-label mx-1 mt-1 mb-0">Address</label>
                                <input type="text" class="form-control" id="updateAddress" name="updateAddress" style="height: 45px;" required>
                                <div class="invalid-feedback">
                                    Please enter address.
                                </div>
                            </div>

                            <div class="mb-2">
                                <label class="form-label mx-1 mt-1 mb-0">Phone Number</label>
                                <input type="text" class="form-control" id="updatePN" name="updatePN" style="height: 45px;" required>
                                <div class="invalid-feedback">
                                    Please enter phone number.
                                </div>
                            </div>

                            <div class="mb-2">
                                <label class="form-label mx-1 mt-1 mb-0">Email</label>
                                <input type="email" class="form-control" id="updateEmail" name="updateEmail" style="height: 45px;" required>
                                <div class="invalid-feedback">
                                    Please enter email.
                                </div>
                            </div>
                            
                        </div>
                        <input type="hidden" name="adminID" value="<?php echo $adminID; ?>">
                    </div>

                    <div class="modal-body text-right pt-0">
                        <button id="updateCustomer" type="submit" class="btn btn-success rounded-0 px-5" disabled>Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        $(document).on('click', '#closeModal', function (e) {
            e.preventDefault();

            $("#updateCustomer").prop("disabled", true);
        });

        $(document).ready(function() {
        var initialData = {};

        function getFormData($form) {
            var unindexedArray = $form.serializeArray();
            var indexedArray = {};

            $.map(unindexedArray, function(n, i) {
                indexedArray[n['name']] = n['value'];
            });

            return indexedArray;
        }

        $('#updateCustomerForm').on('input change', function() {
            var currentData = getFormData($('#updateCustomerForm'));
            var isChanged = JSON.stringify(initialData) !== JSON.stringify(currentData);
            $('#updateCustomer').prop('disabled', !isChanged);
        });

        $(document).on('click', '#updateCustomer', function(e) {
            e.preventDefault();

            var form = $('#updateCustomerForm')[0];
            form.classList.add('was-validated');

            if (form.checkValidity() === false) {
                event.stopPropagation();
            } else {
                $.ajax({
                    type: "POST",
                    url: "update.php?action=customer",
                    data: $('#updateCustomerForm').serialize(),
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response,
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            location.reload();
                        });

                        // Reset form and modal after successful submission
                        $('#updateCustomerForm')[0].reset();
                        $('#updateCustomerForm').removeClass('was-validated');
                        $('#updateCustomerModal').modal('hide');

                        $('#updateCustomer').prop('disabled', true);
                    }
                });
            }
        });
    });
    </script>

    <script>
        $(document).ready(function() {
            $('#CustomerTable').DataTable({
                ordering: false,
                info: false,
                columnDefs: [
                    { searchable: true, targets: [1] }, // Make the first column searchable (index 0)
                    { searchable: false, targets: [0, 2, 3, 4, 5] } // Make other columns not searchable
                ],
                language: {
                    paginate: {
                        previous: '<i class="fa-solid fa-angle-left"></i>', // Custom text for the 'Previous' button
                        next: '<i class="fa-solid fa-angle-right"></i>', // Custom text for the 'Next' button
                    }
                }
            });

            $(document).on('input', '#updatePN', function(e) {
                e.preventDefault();
                // Remove non-numeric characters from the input
                $(this).val($(this).val().replace(/\D/g, ''));

                // You can also enforce a maximum length if needed
                if ($(this).val().length > 11) {
                    $(this).val($(this).val().slice(0, 11));
                }
            });

            $(document).on('click', '#editCustomer', function() {
                var id = $(this).data('id');
                var name = $(this).data('name');
                var address = $(this).data('address');
                var email = $(this).data('email');
                var phone = $(this).data('phone');

                // Populate the modal fields
                $('#updateId').val(id);
                $('#updateName').val(name);
                $('#updateAddress').val(address);
                $('#updateEmail').val(email);
                $('#updatePN').val(phone);
            });

            // Bootstrap validation
            (function () {
                'use strict'

                var forms = document.querySelectorAll('.needs-validation')

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
        });
    </script>
</body>

</html>