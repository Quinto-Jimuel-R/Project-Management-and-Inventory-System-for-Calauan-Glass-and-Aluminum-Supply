<div class="modal fade animated--grow-in" id="createSupplierModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="transition: 0.5s;">
    <div class="modal-dialog modal-centered">
        <div class="modal-content">
            <form action="add.php?action=supplier" id="createSupplierForm" name="createSupplierForm" method="POST" class="needs-validation" novalidate>

                <div class="modal-header d-flex align-items-center">
                    <strong class="text-dark">New Supplier</strong>
                    <button id="closeModal" type="button" class="btn-close " data-bs-dismiss="modal" aria-label="Close" style="font-size: 12px;"></button>
                </div>

                <div class="modal-body text-left p-2">
                    <div class="mb-2">
                        <label class="form-label mx-1 mt-1 mb-0">Company name</label>
                        <input type="text" class="form-control " id="companyName" name="companyName" style="height: 45px;" required>
                        <div class="invalid-feedback">
                            Please enter company name.
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label mx-1 mt-1 mb-0">Contact Person</label>
                        <input type="text" class="form-control " id="contactPerson" name="contactPerson" style="height: 45px;" required>
                        <div class="invalid-feedback">
                            Please enter contact person.
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label mx-1 mt-1 mb-0">Address</label>
                        <input type="text" class="form-control " id="address" name="address" style="height: 45px;" required>
                        <div class="invalid-feedback">
                            Please enter address.
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label mx-1 mt-1 mb-0">Phone Number</label>
                        <input type="text" class="form-control " id="supplierPN" name="supplierPN" style="height: 45px;" placeholder="Ex. 09xxxxxxxxx" required>
                        <div class="invalid-feedback">
                            Please enter phone number.
                        </div>
                    </div>

                    <input type="hidden" name="adminID" value="<?php echo $adminID; ?>">
                </div>

                <div class="modal-body text-right pt-0">
                    <button id="createSupplier" type="submit" class="btn btn-success  px-5">Save</button>
                </div>

            </form>

        </div>
    </div>
</div>

<script>
    $(document).on('click', '#closeModal', function (e) {
        e.preventDefault();

        $('#createSupplierForm')[0].reset();
        $('#createSupplierForm').removeClass('was-validated');
        $('#supplierPN').siblings('.invalid-feedback').text('Please enter phone number.');
        $("#updateSupplier").prop("disabled", true);
    });

    $(document).on('input', '#supplierPN', function (e) 
    {
        e.preventDefault();

        $(this).val($(this).val().replace(/\D/g, ''));

        if ($(this).val().length > 11) {
            $(this).val($(this).val().slice(0, 11));
        }
    });

    $(document).on('click', '#createSupplier', function (e) 
    {
        e.preventDefault();

        var form = $('#createSupplierForm')[0];
        var phoneNumberField = $('#supplierPN')[0];

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
                type: 'POST',
                url: 'add.php?action=supplier',
                data: $('#createSupplierForm').serialize(),
                success: function (response) {
                    if (response.startsWith('success:')) {
                        phoneNumberField.setCustomValidity('');
                        $('#createSupplierForm')[0].reset();
                        $('#createSupplierForm').removeClass('was-validated');
                        $('#supplierPN').siblings('.invalid-feedback').text('Please enter phone number');
                        $('#createSupplierModal').modal('hide');

                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.substring(8), // Remove "success:" prefix
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            location.reload();
                        });
                    } else if (response.startsWith('error:')) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.substring(6), // Remove "error:" prefix
                            showConfirmButton: true,
                        }).then(() => {
                            location.reload();
                        });
                    }
                },
            });
        }

        form.classList.add('was-validated');
    });
</script>

