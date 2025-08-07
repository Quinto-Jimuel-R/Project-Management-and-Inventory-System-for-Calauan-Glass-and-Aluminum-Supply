<div class="modal fade animated--grow-in" id="createInventoryModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <form action="add.php?action=inventory" id="createInventoryForm" name="createInventoryForm" method="POST" class="needs-validation text-dark" novalidate>

                <div class="modal-header d-flex align-items-center">
                    <strong class="text-dark">New Item</strong>
                    <button id="closeModal" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="font-size: 12px;"></button>
                </div>
                <div class="modal-body text-left p-2">                   
                    <div class="text-left px-1">
                        <div class="mb-2">
                            <label class="form-label mx-1 mt-1 mb-0">Description</label>
                            <input type="text" class="form-control" id="itemName" name="itemName" style="height: 45px;" required>
                            <div class="invalid-feedback">
                                Please enter description.
                            </div>
                        </div>

                        <div class="mb-2">
                            <label class="form-label mx-1 mt-1 mb-0">Supplier</label>
                            <select class="form-select" id="supplier" name="supplier" style="height: 45px;" required>
                                <option class="d-none" selected></option>
                                    <?php
                                        $query = "SELECT supplier_id, company_name, address FROM supplier WHERE active = '1'";
                                        $result = mysqli_query($conn, $query);

                                        if ($result) {
                                            $num_rows = mysqli_num_rows($result);

                                            if ($num_rows > 0) {
                                                while ($data = mysqli_fetch_assoc($result)) {
                                                    ?>
                                                    <option value="<?php echo $data['supplier_id']; ?>">
                                                        <?php echo $data['company_name']; ?> - <?php echo $data['address']; ?>
                                                    </option>
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <option disabled>No suppliers found. Add supplier first.</option>
                                                <?php
                                            }
                                        }
                                    ?>
                            </select>
                            <div class="invalid-feedback">
                                Please select a supplier.
                            </div>
                        </div>

                        <div class="mb-2">
                            <label class="form-label mx-1 mt-1 mb-0">Category</label>
                            <select class="form-select text-dark" id="category" name="category" style="height: 45px;" required>
                                <option value="" class="d-none">Select Category</option>
                                <option value="Glass">Glass</option>
                                <option value="Aluminum">Aluminum</option>
                            </select>
                            <div class="invalid-feedback">
                                Please select a category.
                            </div>
                        </div>

                        <div class="d-none" id="show-field">
                            <div class="row">
                                <div class="col-lg-4 mb-2">
                                    <label class="form-label mx-1 mt-1 mb-0">Dimension</label>
                                    <input type="text" class="form-control" id="dimension" name="dimension" style="height: 45px;" required>
                                    <div class="invalid-feedback">
                                        Please enter dimension.
                                    </div>
                                </div>

                                <div class="col-lg-4 mb-2">
                                    <label class="form-label mx-1 mt-1 mb-0">Color</label>
                                    <input type="text" class="form-control" id="color" name="color" style="height: 45px;" required>
                                    <div class="invalid-feedback">
                                        Please enter color.
                                    </div>
                                </div>

                                <div class="col-lg-4 mb-2" id="inch-field">
                                    <label class="form-label mx-1 mt-1 mb-0">Foot</label>
                                    <input type="text" class="form-control" id="inch" name="inch" style="height: 45px;">
                                    <div class="invalid-feedback">
                                        Please enter foot.
                                    </div>
                                </div>

                                <div class="col-lg-4 mb-2" id="sqft-field">
                                    <label class="form-label mx-1 mt-1 mb-0">Sqft</label>
                                    <input type="text" class="form-control" id="sqft" name="sqft" style="height: 45px;">
                                    <div class="invalid-feedback">
                                        Please enter sqft.
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6 mb-2">
                                    <label class="form-label mx-1 mt-1 mb-0">Stock</label>
                                    <input type="text" class="form-control" id="stock" name="stock" style="height: 45px;" required>
                                    <div class="invalid-feedback">
                                        Please enter stock
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-2">
                                    <label class="form-label mx-1 mt-1 mb-0">Price</label>
                                    <input type="text" class="form-control" id="price" name="price" style="height: 45px;" required>
                                    <div class="invalid-feedback">
                                        Please enter price
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="adminID" value="<?php echo $adminID; ?>">
                </div>

                <div class="modal-body text-right pt-0">
                    <button id="createInventory" type="button" class="btn btn-success px-5">Save</button>
                </div>
            </form>

        </div>
    </div>
</div>

<?php
    $query = "SELECT * FROM inventory_excess";
    $result = mysqli_query($conn, $query);
?>

<div class="modal fade animated--grow-in" id="excessModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <form id="excessInventoryForm" name="excessInventoryForm" method="POST" class="needs-validation text-dark" novalidate>

                <div class="modal-header d-flex align-items-center">
                    <strong class="text-dark">Excess Item</strong>
                    <button id="closeModal" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="font-size: 12px;"></button>
                </div>

                <div class="modal-body text-left p-2">
                    <div class="text-left px-1">
                        <div class="d-flex flex-column">
                            <?php if (mysqli_num_rows($result) > 0) { ?>
                                <?php while($row = mysqli_fetch_assoc($result)) { ?>
                                    <div class="d-flex justify-content-between align-items-center mb-2 p-2">
                                        <span><?php echo $row['item_name']; ?> - <?php echo $row['color'] ?> - <?php echo $row['dimension'] ?> ( <?php echo $row['exc_foot']; ?> ft )</span>
                                        <button type="button" class="btn btn-danger btn-sm deleteExcess-btn" data-id="<?php echo $row['exc_id']; ?>" data-admin-id="<?php echo $adminID ?>">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                <?php } ?>
                            <?php } else { ?>
                                <p class="text-center py-2 m-0">No excess items available.</p>
                            <?php } ?>
                        </div>
                    </div>
                    <input type="hidden" name="adminID" value="<?php echo $adminID; ?>">
                </div>
            </form>

        </div>
    </div>
</div>

<!-- Your HTML code -->

<script>
    $('.deleteExcess-btn').click(function() 
    {
        var excId = $(this).data('id'); // Get the item ID from the button
        var adminID = $(this).data('admin-id');

        // Trigger SweetAlert confirmation
        Swal.fire({
            title: 'Confirmation',
            text: 'Are you sure to dispose this item?',
            icon: 'warning',
            confirmButtonColor: "#4e73df",
            cancelButtonColor: "#e74a3b",
            showCancelButton: true,
            confirmButtonText: 'Continue',
            cancelButtonText: 'Cancel',
            allowOutsideClick: false, // Make the alert modal static
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
                // If confirmed, make an AJAX request to delete.php
                $.ajax({
                    url: 'delete.php?action=excess',
                    type: 'POST',
                    data: { id: excId, adminID: adminID},
                    success: function(response) {
                        Swal.fire({
                            title: "Dispose",
                            text: response,
                            icon: "success",
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            location.reload();
                        });
                    },
                });
            }
        });
    });

    $(document).on('click', '#closeModal', function(e) {
        e.preventDefault();

        $('#createInventoryForm')[0].reset();
        $('#createInventoryForm').removeClass('was-validated');
        $('#price').siblings('.invalid-feedback').text('Please enter price');
        $('#stock').siblings('.invalid-feedback').text('Please enter stock');
        $("#updateInventory").prop("disabled", true);
        $('#show-field').addClass('d-none');
    });

    $(document).on('input', '#color', function(e) {
        e.preventDefault();
            
        $(this).val($(this).val().replace(/[^A-Za-z]/g, ''));
    });


    $(document).on('input', '#inch', function(e) {
        e.preventDefault();
            
        $(this).val($(this).val().replace(/\D/g, ''));
    });

    $(document).on('input', '#sqft', function(e) {
        e.preventDefault();
            
        // Remove non-numeric characters from the input
        $(this).val($(this).val().replace(/\D/g, ''));
    });

    $(document).on('input', '#price', function(e) {
        e.preventDefault();
            
        // Remove non-numeric characters from the input
        $(this).val($(this).val().replace(/\D/g, ''));
    });

    $(document).on('input', '#stock', function(e) {
        e.preventDefault();
            
        // Remove non-numeric characters from the input
        $(this).val($(this).val().replace(/\D/g, ''));
    });

    $(document).on('click', '#createInventory', function(e) {
        e.preventDefault();

        var form = $('#createInventoryForm')[0];

        var priceField = $('#price')[0];
        var stockField = $('#stock')[0];

        priceField.setCustomValidity('');
        stockField.setCustomValidity('');

        form.classList.add('was-validated');

        if (form.checkValidity() === false) {
            event.stopPropagation();
        } else {
            
            var priceValue = priceField.value;
            var stockValue = stockField.value;

            if(priceValue == 0)
            {
                priceField.setCustomValidity('Price must be greater than 0');
                $('#price').siblings('.invalid-feedback').text('Price must be greater than 0');

                return;
            }
            else if(stockValue == 0)
            {
                stockField.setCustomValidity('Stock must be greater than 0');
                $('#stock').siblings('.invalid-feedback').text('Stock must be greater than 0');

                return;
            }

            // Proceed with AJAX request if the form is valid
            $.ajax({
                type: 'POST',
                url: 'add.php?action=inventory', // Make sure the URL is correct
                data: $('#createInventoryForm').serialize(),
                success: function (response) {

                    priceField.setCustomValidity('');
                    stockField.setCustomValidity('');
                    
                    $('#price').siblings('.invalid-feedback').text('Please enter price');
                    $('#stock').siblings('.invalid-feedback').text('Please enter stock');

                    $('#createInventoryModal').modal('hide');

                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        location.reload();
                    });

                    $('#createInventoryForm')[0].reset();
                    $('#createInventoryForm').removeClass('was-validated');

                    $('#createInventoryModal').modal('hide');
                },
            });
        }

        // Trigger Bootstrap validation styles
        form.classList.add('was-validated');
    });
</script>

<script>
    $(document).ready(function () 
    {
        $('#inch-field').hide().find('input').prop('required', false);
        $('#sqft-field').hide().find('input').prop('required', false);

        $('#category').change(function () 
        {
            var selectedCategory = $(this).val();
            
            $('#show-field').removeClass('d-none');

            if (selectedCategory === 'Aluminum') 
            {
                // Show Inch field and set it as required
                $('#inch-field').show().find('input').prop('required', true);
                // Hide Sqft field and remove required attribute
                $('#sqft-field').hide().find('input').prop('required', false);
            } else if (selectedCategory === 'Glass') {
                // Show Sqft field and set it as required
                $('#sqft-field').show().find('input').prop('required', true);
                // Hide Inch field and remove required attribute
                $('#inch-field').hide().find('input').prop('required', false);
            } else {
                // Hide both fields and remove required attributes
                $('#inch-field, #sqft-field').hide().find('input').prop('required', false);
            }
        })
    });
</script>
