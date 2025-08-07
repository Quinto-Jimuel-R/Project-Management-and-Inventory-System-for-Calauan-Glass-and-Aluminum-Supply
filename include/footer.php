<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded mb-5" href="#page-top">
    <i class="fa-solid fa-angle-up"></i>
</a>

<!-- GLOBAL SWEET ALERT-->
    <script>
        <?php
            if (isset($_SESSION['success'])) 
            {
            ?>
                Swal.fire({
                    title: '<?php echo $_SESSION['success']; ?>',
                    showConfirmButton: false,
                    timer: 1500
                });
            <?php
                unset($_SESSION['success']);
            }
        ?>
    </script>
    <!--BOOTSTRAP-->
<script>
    // Example starter JavaScript for disabling form submissions if there are invalid fields
    (() => {
    'use strict'

    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    const forms = document.querySelectorAll('.needs-validation')

    // Loop over them and prevent submission
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
        if (!form.checkValidity()) {
            event.preventDefault()
            event.stopPropagation()
        }

        form.classList.add('was-validated')
        }, false)
    })
    })()
</script>

<style>
    .swal2-title {
        padding-top: 20px !important;
    }
</style>

<script>
    $(document).ready(function() 
    {
        $('.logout-link').click(function(e) {
            e.preventDefault();

            var userId = $(this).data('user-id'); // Get the user ID from data attribute

            Swal.fire({
                title: 'Are you sure you want to logout?',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, logout!'
            }).then((result) => {
                // If the user confirms the logout
                if (result.isConfirmed) {
                    // Make an AJAX request to update last login time
                    $.ajax({
                        type: "POST",
                        url: "add.php?action=logout", // Replace with your PHP script to handle the update
                        data: { userId: userId },
                        success: function(response) {
                            // Show success message using SweetAlert2
                            Swal.fire({
                                title: 'Logout',
                                timer: 2000, // Auto close the modal after 2 seconds
                                showConfirmButton: false // Hide the "OK" button
                            }).then(() => {
                                // Redirect the user after the success message
                                window.location.href = 'index.php'; // Replace with your logout script
                            });
                        },
                    });
                }
            });
        });

        $('.dashboard-link').click(function(e) {
            e.preventDefault();

            var userId = $(this).data('user-id'); // Get the user ID from data attribute

            $.ajax({
                type: "POST",
                url: "add.php?action=dashboard-link",
                data: { userId: userId },
                success: function (response) {
                    window.location.href = 'dashboard.php';
                }
            });
        });

        $('.project-link').click(function(e) {
            e.preventDefault();

            var userId = $(this).data('user-id'); // Get the user ID from data attribute

            $.ajax({
                type: "POST",
                url: "add.php?action=project-link",
                data: { userId: userId },
                success: function (response) {
                    window.location.href = 'project.php';
                }
            });
        });

        $('.inventory-link').click(function(e) {
            e.preventDefault();

            var userId = $(this).data('user-id'); // Get the user ID from data attribute

            $.ajax({
                type: "POST",
                url: "add.php?action=inventory-link",
                data: { userId: userId },
                success: function (response) {
                    window.location.href = 'inventory.php';
                }
            });
        });

        $('.customer-link').click(function(e) {
            e.preventDefault();

            var userId = $(this).data('user-id'); // Get the user ID from data attribute

            $.ajax({
                type: "POST",
                url: "add.php?action=customer-link",
                data: { userId: userId },
                success: function (response) {
                    window.location.href = 'customer.php';
                }
            });
        });

        $('.supplier-link').click(function(e) {
            e.preventDefault();

            var userId = $(this).data('user-id'); // Get the user ID from data attribute

            $.ajax({
                type: "POST",
                url: "add.php?action=supplier-link",
                data: { userId: userId },
                success: function (response) {
                    window.location.href = 'supplier.php';
                }
            });
        });

        $('.employee-link').click(function(e) {
            e.preventDefault();

            var userId = $(this).data('user-id'); // Get the user ID from data attribute

            $.ajax({
                type: "POST",
                url: "add.php?action=employee-link",
                data: { userId: userId },
                success: function (response) {
                    window.location.href = 'employee.php';
                }
            });
        });

        $('.archive-link').click(function(e) {
            e.preventDefault();

            var userId = $(this).data('user-id'); // Get the user ID from data attribute

            $.ajax({
                type: "POST",
                url: "add.php?action=archive-link",
                data: { userId: userId },
                success: function (response) {
                    window.location.href = 'archive.php';
                }
            });
        });

        $('.profile-link').click(function(e) {
            e.preventDefault();

            var userId = $(this).data('user-id'); // Get the user ID from data attribute

            $.ajax({
                type: "POST",
                url: "add.php?action=profile-link",
                data: { userId: userId },
                success: function (response) {
                    window.location.href = 'profile.php';
                }
            });
        });
    });
</script>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>

    <!-- DataTables Buttons JS -->
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.flash.min.js"></script>

    <!-- JSZip library (required for Excel export) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

    <!-- pdfmake library (required for PDF export) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

    