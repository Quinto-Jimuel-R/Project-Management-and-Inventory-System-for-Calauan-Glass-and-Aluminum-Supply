<div class="modal fade text-dark animated--grow-in" id="createCashierModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="transition: 0.5s;">
    <div class="modal-dialog">
        <div class="modal-content">

            <form action="add.php?action=inventory" id="createCashierForm" name="createCashierForm" method="POST" class="needs-validation" novalidate>

                <div class="modal-header d-flex align-items-center">
                    <strong class="text-dark">New Cashier</strong>
                    <button id="closeModal" type="button" class="btn-close " data-bs-dismiss="modal" aria-label="Close" style="font-size: 12px;"></button>
                </div>

                <div class="modal-body text-left p-2">                   
                    <div class="text-left px-1">
                        <div class="mb-2">
                            <label class="form-label mx-1 mt-1 mb-0">Name</label>
                            <input type="text" class="form-control" id="cashierName" name="cashierName" style="height: 45px;" required>
                            <div class="invalid-feedback">
                                Please enter name.
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="form-label mx-1 mt-1 mb-0">Address</label>
                            <input type="text" class="form-control" id="address" name="address" style="height: 45px;" required>
                            <div class="invalid-feedback">
                                Please enter address.
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="form-label mx-1 mt-1 mb-0">Phone Number</label>
                            <input type="text" class="form-control" id="cashierPN" name="cashierPN" maxlength="11" style="height: 45px;" placeholder="Ex. 09xxxxxxxxx" required>
                            <div class="invalid-feedback">
                                Please enter phone number.
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="form-label mx-1 mt-1 mb-0">Email</label>
                            <input type="email" class="form-control" id="email" name="email" style="height: 45px;" placeholder="example@gmail.com" required>
                            <div class="invalid-feedback">
                                Please enter email.
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="form-label mx-1 mt-1 mb-0">Password</label>
                            <input type="password" class="form-control" id="password" name="password" style="height: 45px;" required>
                            <div class="invalid-feedback">
                                Please enter password.
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="adminID" value="<?php echo $adminID; ?>">
                </div>

                <div class="modal-body text-right pt-0">
                    <button id="createCashier" type="submit" class="btn btn-success  px-5">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).on('click', '#closeModal', function(e) {
        e.preventDefault();

        $('#createCashierForm')[0].reset();
        $('#createCashierForm').removeClass('was-validated');
        $('#cashierPN').siblings('.invalid-feedback').text('Please enter phone number');
        $("#updateCashier").prop("disabled", true);
    });

    $(document).on('input', '#cashierPN', function(e) {
        e.preventDefault();
            
        $(this).val($(this).val().replace(/\D/g, ''));
    });

    $(document).on('click', '#createCashier', function(e) {
        e.preventDefault();

        var form = $('#createCashierForm')[0];

        var phoneNumberField = $('#cashierPN')[0];

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
                type: "POST",
                url: "add.php?action=cashier",
                data: $("#createCashierForm").serialize(),
                success: function (response)
                {

                    phoneNumberField.setCustomValidity('');

                    $('#cashierPN').siblings('.invalid-feedback').text('Please enter phone number');

                    $('#createCashierForm')[0].reset();
                    $('#createCashierForm').removeClass('was-validated');

                    $('#createCashierModal').modal('hide');

                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function() {
                        window.location.reload();
                    });
                }
            });
        }

        form.classList.add('was-validated');
    });
</script>