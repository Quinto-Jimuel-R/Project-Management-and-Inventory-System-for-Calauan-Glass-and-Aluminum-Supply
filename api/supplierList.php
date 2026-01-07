<div class="card border border-0 mb-4" style="background-color: transparent;">
    <div class="card-body pb-0">
        <div class="d-flex align-items-center">
            <a type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createSupplierModal" style="font-size: 14px;">
                + Add Supplier
            </a>
        </div>
    </div>
    <div class="card-body">
    <div class="table-responsive">
        <table class="table table-bordered" id="SupplierTable" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th class="text-center d-none" style="width: 5%;">#</th>
                    <th>Company Name</th>
                    <th>Contact Person</th>
                    <th>Address</th>
                    <th>Phone Number</th>
                    <th>Item</th>
                    <th>Controls</th>
                </tr>
            </thead>
            <tbody>
            <?php
                $result = mysqli_query($conn, "SELECT * FROM supplier WHERE active = '1' ORDER BY supplier_id DESC");

                $counter = 1;

                if (mysqli_num_rows($result) > 0) {
                    while ($data = mysqli_fetch_array($result)) {
                        $supID = $data['supplier_id'];
                        $modalID = "itemsModal" . $supID;  // Unique ID for each modal
                        ?>
                        <tr>
                            <td class="text-center d-none"><?php echo $counter++ ?></td>
                            <td><?php echo $data['company_name']; ?></td>
                            <td><?php echo $data['contact_person']; ?></td>
                            <td><?php echo $data['address']; ?></td>
                            <td><?php echo $data['phone_number']; ?></td>
                            <td class="text-center">
                                <i class="fa-solid fa-eye text-primary" data-bs-toggle="modal" data-bs-target="#<?php echo $modalID; ?>" style="cursor: pointer;" data-toggle="tooltip" data-bs-placement="top" title="View Details"></i>
                            
                                <!-- Items Modal -->
                                <div class="modal fade animated--grow-in" id="<?php echo $modalID; ?>" tabindex="-1" aria-labelledby="<?php echo $modalID; ?>Label" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header d-flex align-items-center">
                                                <strong class="text-dark">Item</strong>
                                                <button id="closeModal" type="button" class="btn-close " data-bs-dismiss="modal" aria-label="Close" style="font-size: 12px;"></button>
                                            </div>
                                            <div class="modal-body">
                                                <?php 
                                                    $supInventory = mysqli_query($conn, "SELECT * FROM inventory WHERE supplier_id = $supID");

                                                    if (mysqli_num_rows($supInventory) > 0) 
                                                    {
                                                        while ($item = mysqli_fetch_assoc($supInventory)) {
                                                            ?>
                                                                <div class="d-flex">
                                                                    <div style="width: 33.33%; text-align: start;" class="mb-2"><?php echo $item['item_name'] ?></div> - <div style="width: 33.33%;" class="mb-2"><?php echo $item['dimension'] ?></div> - <div style="width: 33.33%; text-align: end;" class="mb-2"><?php echo $item['color'] ?></div>
                                                                </div>
                                                            <?php
                                                        }
                                                    } else {
                                                        echo "<p class='mb-2'>No items found.</p>";
                                                    }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </td>
                            <td>
                                <div class="d-flex justify-content-evenly">
                                    <a id="editSupplier" href="#" class="text-success" data-bs-toggle="modal" data-bs-target="#updateSupplierModal" data-id="<?php echo $data['supplier_id']; ?>"><i class="fa-solid fa-pen-to-square" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"></i></a>
                                    <a id="deleteSupplier" href="#" class="text-danger" data-id="<?php echo $data['supplier_id']; ?>" data-admin-id="<?php echo $adminID; ?>"><i class="fa-solid fa-trash" data-bs-toggle="tooltip" data-bs-placement="top" title="Remove"></i></a>
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

<div class="modal fade text-dark animated--grow-in" id="updateSupplierModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="transition: 0.5s;">
    <div class="modal-dialog modal-centered">
        <div class="modal-content">

            <form action="update.php?action=supplier" id="updateSupplierForm" name="updateSupplierForm" method="POST" class="needs-validation" novalidate>

                <div class="modal-header d-flex align-items-center">
                    <strong class="text-dark">Edit Supplier</strong>
                    <button id="closeModal" type="button" class="btn-close " data-bs-dismiss="modal" aria-label="Close" style="font-size: 12px;"></button>
                </div>

                <div class="modal-body text-left p-2">
                    <div class="mb-2">
                        <label class="form-label mx-1 mt-1 mb-0">Company name</label>
                        <input type="text" class="form-control " id="updateCompanyName" name="updateCompanyName" style="height: 45px;" required>
                        <div class="invalid-feedback">
                            Please enter company name.
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label mx-1 mt-1 mb-0">Contact Person</label>
                        <input type="text" class="form-control " id="updateContactPerson" name="updateContactPerson" style="height: 45px;" required>
                        <div class="invalid-feedback">
                            Please enter contact person.
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label mx-1 mt-1 mb-0">Address</label>
                        <input type="text" class="form-control " id="updateAddress" name="updateAddress" style="height: 45px;" required>
                        <div class="invalid-feedback">
                            Please enter address.
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label mx-1 mt-1 mb-0">Phone Number</label>
                        <input type="text" class="form-control " id="updateSupplierPN" name="updateSupplierPN" style="height: 45px;" required>
                        <div class="invalid-feedback">
                            Please enter phone number.
                        </div>
                    </div>
                </div>

                <input type="hidden" id="updateSupplierId" name="updateSupplierId">
                <input type="hidden" name="adminID" value="<?php echo $adminID; ?>">

                <div class="modal-body text-right pt-0">
                        <button id="updateSupplier" type="submit" class="btn btn-success  px-5" disabled>Save</button>
                </div>

            </form>

        </div>
    </div>
</div>

<script>
    $(document).ready(function() 
    {
        $('#SupplierTable').DataTable({
            ordering: false,
            info: false,
            columnDefs: [
                { searchable: true, targets: [1] }, // Make the first column searchable (index 0)
                { searchable: false, targets: [0, 2, 3, 4] } // Make other columns not searchable
            ],
            language: {
                paginate: {
                    previous: '<i class="fa-solid fa-angle-left"></i>', // Custom text for the 'Previous' button
                    next: '<i class="fa-solid fa-angle-right"></i>', // Custom text for the 'Next' button
                }
            }
        });
    });
</script>


<script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    $(document).on('click', '#closeModal', function(e) {
        e.preventDefault();

        $('#updateSupplierForm')[0].reset();
        $('#updateSupplierForm').removeClass('was-validated');
         // Clear custom validation messages and state for specific fields
        $('#updateSupplierPN')[0].setCustomValidity('');
        
    });

    $(document).on('click', '#editSupplier', function(e)
    {
        e.preventDefault();

        var supplierId = $(this).data('id');
        
        $.ajax({
            type: "POST",
            url: "fetch.php?action=supplier",
            data: { supplierId:supplierId },
            dataType: "json",
            success: function (data) {

                $('#updateSupplierId').val(data.supplier_id);
                
                $('#updateCompanyName').val(data.company_name);
                $('#updateContactPerson').val(data.contact_person);
                $('#updateAddress').val(data.address);
                $('#updateSupplierPN').val(data.phone_number);
                
                // Show the update modal
                $('#updateSupplierModal').modal('show');
            }
        });
    });

    $(document).on('input', '#updateSupplierPN', function(e) {
        e.preventDefault();
            // Remove non-numeric characters from the input
            $(this).val($(this).val().replace(/\D/g, ''));

            // You can also enforce a maximum length if needed
            if ($(this).val().length > 11) {
                $(this).val($(this).val().slice(0, 11));
            }
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

        $('#updateSupplierForm').on('input change', function() 
        {
            var currentData = getFormData($('#updateSupplierForm'));
            var isChanged = JSON.stringify(initialData) !== JSON.stringify(currentData);
            $('#updateSupplier').prop('disabled', !isChanged);
        });

        $(document).on('click', '#updateSupplier', function(e) {
            e.preventDefault();

            var form = $('#updateSupplierForm')[0];
            
            var phoneNumberField = $('#updateSupplierPN')[0];

            phoneNumberField.setCustomValidity('');

            form.classList.add('was-validated');

            if (form.checkValidity() === false) {
                e.stopPropagation();
            } else {

                var phoneNumberValue = phoneNumberField.value;

                if (phoneNumberValue.length !== 11) {

                    phoneNumberField.setCustomValidity('Please enter a valid 11-digit number.');
                    $('#supplierPN').siblings('.invalid-feedback').text('Please enter a valid 11-digit number.');

                    return;
                }

                $.ajax({
                    url: 'update.php?action=supplier',
                    type: 'POST',
                    data: $('#updateSupplierForm').serialize(),
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

                        $('#updateSupplierForm')[0].reset();
                        $('#updateSupplierForm').removeClass('was-validated');
                        $('#updateSupplierModal').modal('hide');

                        initialData = getFormData($('#updateSupplierForm'));
                        $('#updateSupplier').prop('disabled', true);
                    },
                });
            }
        });
        form.classList.add('was-validated');
    });

    $(document).on('click', '#deleteSupplier', function(e) 
    {
        e.preventDefault();

        var supplierId = $(this).data('id');
        var adminID = $(this).data('admin-id');

        let timerInterval;
        Swal.fire({
            title: "Confirmation",
            text: "Are you sure to remove this supplier?",
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
                    type: "POST",
                    url: "delete.php?action=supplier",
                    data: { supplierId: supplierId, adminID: adminID },
                    success: function(response) {

                        Swal.fire({
                            title: "Remove",
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
</script>