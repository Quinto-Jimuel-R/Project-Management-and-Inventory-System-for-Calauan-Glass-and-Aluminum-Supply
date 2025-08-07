<div class="card border-0 mb-4" style="background-color: transparent;">
    <div class="card-body pb-0">
        <div class="d-flex justify-content-between">
            <div class="d-flex align-items-center gap-1">
                <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCashierModal" style="font-size: 14px;">
                    + Add Cashier
                </a>
            </div>
        </div>
    </div>
    
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="CashierTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th class="text-center d-none">#</th>
                        <th>Cashier Name</th>
                        <th>Address</th>
                        <th>Email</th>
                        <th>Phone Number</th>
                        <th>Controls</th>
                    </tr>
                </thead>
                
                <tbody>
                    <?php
                        $cashierResult = mysqli_query($conn, "SELECT * FROM user WHERE user_type = 'cashier' ORDER BY user_id DESC");

                        $counter = 1;

                        if(mysqli_num_rows($cashierResult) > 0) 
                        {
                            while($cashierData = mysqli_fetch_array($cashierResult)) 
                            {
                    ?>
                            <tr>
                                <td class="text-center d-none"><?php echo $counter++ ?></td>
                                <td><?php echo $cashierData['name'] ?></td>
                                <td><?php echo $cashierData['address'] ?></td>
                                <td><?php echo $cashierData['email'] ?></td>
                                <td><?php echo $cashierData['phone_number'] ?></td>
                                <td>
                                    <div class="text-center d-flex justify-content-evenly">
                                    <a id="editCashier" href="#" class="text-success" data-bs-toggle="modal" data-bs-target="#updateCashierModal"
                                                               data-id="<?php echo $cashierData['user_id']; ?>"
                                                               data-name="<?php echo $cashierData['name']; ?>"
                                                               data-address="<?php echo $cashierData['address']; ?>"
                                                               data-email="<?php echo $cashierData['email']; ?>"
                                                               data-phone="<?php echo $cashierData['phone_number']; ?>">
                                                                <i class="fa-solid fa-pen-to-square" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"></i>
                                                            </a>
                                        <a id="deleteCashier" href="#" class="text-danger" data-id="<?php echo $cashierData['user_id']; ?>" data-admin-id="<?php echo $adminID; ?>"><i class="fa-solid fa-trash" data-bs-toggle="tooltip" data-bs-placement="top" title="Remove"></i></a>
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

<div class="modal fade text-dark animated--grow-in" id="updateCashierModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="transition: 0.5s;">
    <div class="modal-dialog">
        <div class="modal-content">

            <form action="update.php?action=cashier" id="updateCashierForm" name="updateCashierForm" method="POST" class="needs-validation" novalidate>

                <div class="modal-header d-flex align-items-center">
                    <strong class="text-dark">Edit Cashier</strong>
                    <button id="closeModal" type="button" class="btn-close " data-bs-dismiss="modal" aria-label="Close" style="font-size: 12px;"></button>
                </div>

                    <div class="modal-body text-left p-2">                   
                            <div class="text-left px-1">
                                <input type="hidden" id="updateCashierId" name="updateCashierId">
                                <div class="mb-2">
                                    <label class="form-label mx-1 mt-1 mb-0">Name</label>
                                    <input type="text" class="form-control" id="updateCashierName" name="updateCashierName" style="height: 45px;" required>
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
                                    <input type="text" class="form-control" id="updateCashierPN" name="updateCashierPN" maxlength="11" style="height: 45px;" placeholder="Ex. 09xxxxxxxxx" required>
                                    <div class="invalid-feedback">
                                        Please enter phone number.
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label mx-1 mt-1 mb-0">Email</label>
                                    <input type="email" class="form-control" id="updateEmail" name="updateEmail" style="height: 45px;" placeholder="example@gmail.com" required>
                                    <div class="invalid-feedback">
                                        Please enter email.
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="adminID" value="<?php echo $adminID; ?>">
                </div>

                <div class="modal-body text-right pt-0">
                        <button id="updateCashier" type="submit" class="btn btn-success  px-5" disabled>Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() 
    {
        $('#CashierTable').DataTable({
            ordering: false,
            info: false,
            columnDefs: [
                { searchable: true, targets: [1] }, // Make the second column searchable
                { searchable: false, targets: [0, 2, 3, 4, 5] } // Make other columns not searchable
            ],
            language: {
                paginate: {
                    previous: '<i class="fa-solid fa-angle-left"></i>', // Custom text for the 'Previous' button
                    next: '<i class="fa-solid fa-angle-right"></i>', // Custom text for the 'Next' button
                }
            }
        });
    });

    $(document).on('click', '#editCashier', function() 
    {
        var id = $(this).data('id');
        var name = $(this).data('name');
        var address = $(this).data('address');
        var email = $(this).data('email');
        var phone = $(this).data('phone');

        $('#updateCashierId').val(id);
        $('#updateCashierName').val(name);
        $('#updateAddress').val(address);
        $('#updateEmail').val(email);
        $('#updateCashierPN').val(phone);
    });

    $(document).ready(function() 
    {
        var initialData = {};

        function getFormData($form) 
        {
            var unindexedArray = $form.serializeArray();
            var indexedArray = {};

            $.map(unindexedArray, function(n, i) {
                indexedArray[n['name']] = n['value'];
            });

            return indexedArray;
        }

        $('#updateCashierForm').on('input change', function() {
            var currentData = getFormData($('#updateCashierForm'));
            var isChanged = JSON.stringify(initialData) !== JSON.stringify(currentData);
            $('#updateCashier').prop('disabled', !isChanged);
        });

        $(document).on('click', '#updateCashier', function(e) 
        {
            e.preventDefault();

            var form = $('#updateCashierForm')[0];
            
            var phoneNumberField = $('#updateCashierPN')[0];

            phoneNumberField.setCustomValidity('');

            form.classList.add('was-validated');

            if (form.checkValidity() === false) {
                event.stopPropagation();
            } else {

                var phoneNumberValue = phoneNumberField.value;

                if (phoneNumberValue.length !== 11) {

                    phoneNumberField.setCustomValidity('Please enter a valid 11-digit number.');
                    $('#cashierPN').siblings('.invalid-feedback').text('Please enter a valid 11-digit number.');

                    return;
                }

                $.ajax({
                    url: 'update.php?action=cashier',
                    type: 'POST',
                    data: $('#updateCashierForm').serialize(),
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

                        $('#updateCashierForm')[0].reset
                        $('#updateCashierForm')[0].reset();
                        $('#updateCashier').prop('disabled', true); // Disable the 'Save' button after successful update
                        form.classList.remove('was-validated'); // Remove validation classes
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'An error occurred while updating the cashier.',
                            showConfirmButton: true
                        });
                    }
                });
            }
        });

        $('#updateCashierForm').on('submit', function() {
            initialData = getFormData($(this));
        });

        $(document).on('click', '#deleteCashier', function() 
        {
            var cashierId = $(this).data('id');
            var adminID = $(this).data('admin-id');

            Swal.fire({
                title: "Confirmation",
                text: "Are you sure to remove this cashier?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#4e73df",
                cancelButtonColor: "#e74a3b",
                confirmButtonText: "Continue",
                allowOutsideClick: false, 
                preConfirm: () => {
                    return new Promise((resolve) => {
                        Swal.showLoading();
                        setTimeout(() => {
                            resolve(true);
                        }, 3000);
                    });
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'delete.php?action=cashier',
                        type: 'POST',
                        data: { cashierId: cashierId, adminID: adminID }, // Change id to cashierId
                        success: function(response) {
                            Swal.fire({
                                title: "Removed",
                                text: response,
                                icon: "success",
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                location.reload();
                            });
                        }
                    });
                }
            });
        });

    });
</script>
