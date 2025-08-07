<div class="modal fade animated--grow-in" id="createProjectModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <form action="add.php?action=project" id="createProjectForm" name="createProjectForm" method="POST" class="needs-validation text-dark" novalidate>

                <div class="modal-header d-flex align-items-center">
                    <strong class="text-dark">New Project</strong>
                    <button id="closeModal" type="button" class="btn-close " data-bs-dismiss="modal" aria-label="Close" style="font-size: 12px;"></button>
                </div>

                <div class="modal-body text-left p-2">
                    <ul class="nav nav-tabs gap-2" id="projectTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="project-details-tab" data-bs-toggle="tab" href="#project-details" role="tab" aria-controls="project-details" aria-selected="true">Project</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link disabled" id="customer-details-tab" data-bs-toggle="tab" href="#customer-details" role="tab" aria-controls="customer-details" aria-selected="false" tabindex="-1" aria-disabled="true">Customer</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="projectTabsContent">
                        <div class="tab-pane fade show active" id="project-details" role="tabpanel" aria-labelledby="project-details-tab">
                            <fieldset>
                                <div class="px-1">
                                    <div class="text-center fs-5 mx-1 mt-3 mb-2">
                                        <strong>Project</strong>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-12 col-lg-6 mb-2">
                                            <label class="form-label mx-1 mt-1 mb-0">Project name</label>
                                            <input type="text" class="form-control" id="projectName" name="projectName" style="height: 45px;" required>
                                            <div class="invalid-feedback">
                                                Please enter project name.
                                            </div>
                                        </div>
                                        <div class="col-12 col-lg-6 mb-2">
                                            <label class="form-label mx-1 mt-1 mb-0">Location</label>
                                            <input type="text" class="form-control" id="location" name="location" style="height: 45px;" required>
                                            <div class="invalid-feedback">
                                                Please enter location.
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-12 col-lg-4 mb-2">
                                            <label class="form-label mx-1 mt-1 mb-0">Assign Employee</label>
                                            <select class="form-select text-dark" id="employee" name="employee" style="height: 45px;" required>
                                                <option class="d-none" value="" selected>Select Employee</option>
                                                <?php
                                                    $query = "SELECT u.user_id, u.name 
                                                            FROM user u
                                                            LEFT JOIN project p ON u.name = p.employee_name 
                                                                AND p.status IN ('TO DO', 'IN PROGRESS', 'DELIVERED', 'INSTALLATION')
                                                            WHERE u.user_type = 'employee'
                                                            AND p.project_id IS NULL;";
                                                    $result = mysqli_query($conn, $query);
                                                    if ($result) {
                                                        $num_rows = mysqli_num_rows($result);
                                                        if ($num_rows > 0) {
                                                            while ($data = mysqli_fetch_assoc($result)) {
                                                                ?>
                                                                <option value="<?php echo $data['name']; ?>">
                                                                    <?php echo $data['name']; ?>
                                                                </option>
                                                                <?php
                                                            }
                                                        } else {
                                                            ?>
                                                            <option disabled>No available employees.</option>
                                                            <?php
                                                        }
                                                    }
                                                ?>
                                            </select>
                                            <div class="invalid-feedback">
                                                Please select an employee.
                                            </div>
                                        </div>
                                        
                                        <div class="col-12 col-lg-4 mb-2">
                                            <label class="form-label mx-1 mt-1 mb-0">Start Date</label>
                                            <input type="date" class="form-control" id="startDate" name="startDate" style="height: 45px;" required>
                                            <div class="invalid-feedback">
                                                Please select start date.
                                            </div>
                                        </div>
                                        
                                        <div class="col-12 col-lg-4 mb-2">
                                            <label class="form-label mx-1 mt-1 mb-0">Deadline</label>
                                            <input type="date" class="form-control" id="deadline" name="deadline" style="height: 45px;" required>
                                            <div class="invalid-feedback">
                                                Please select deadline.
                                            </div>
                                        </div>
                                    </div>
                            </fieldset>
                            
                            <div class="modal-body text-right pt-0">
                                <button type="button" id="nextButton" class="btn btn-primary  px-5">Next</button>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="customer-details" role="tabpanel" aria-labelledby="customer-details-tab" >
                            <fieldset>
                                <div class="px-1">
                                    <div class="text-center fs-5 mx-1 mt-3 mb-2">
                                        <strong>Customer</strong>
                                    </div>
                                    <div id="notYet" class="">
                                        <div class="row">
                                            <div class="col-12 col-lg-4 mb-2">
                                                <label class="form-label mx-1 mt-1 mb-0">Full Name</label>
                                                <input type="text" class="form-control" id="customerName" name="customerName" style="height: 45px;" required>
                                                <div class="invalid-feedback">
                                                    Please enter full name.
                                                </div>
                                            </div>
                                            
                                            <div class="col-12 col-lg-4 mb-2">
                                                <label class="form-label mx-1 mt-1 mb-0">Address</label>
                                                <input type="text" class="form-control" id="customerAddress" name="customerAddress" style="height: 45px;" required>
                                                <div class="invalid-feedback">
                                                    Please enter address.
                                                </div>
                                            </div>
                                            
                                            <div class="col-12 col-lg-4 mb-2">
                                                <label class="form-label mx-1 mt-1 mb-0">Phone number</label>
                                                <input type="tel" class="form-control" id="customerPN" name="customerPN" maxlength="11" style="height: 45px;" placeholder="Ex. 09xxxxxxxxx" required>
                                                <div class="invalid-feedback">
                                                    Please enter phone number.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-12 col-lg-4 mb-2">
                                                <label class="form-label mx-1 mt-1 mb-0">Email</label>
                                                <input type="email" class="form-control" id="customerEmail" name="customerEmail" style="height: 45px;" required>
                                                <div class="invalid-feedback">
                                                    Please enter email address.
                                                </div>
                                            </div>
                                            
                                            <div class="col-12 col-lg-4 mb-2">
                                                <label class="form-label mx-1 mt-1 mb-0">Password</label>
                                                <input type="password" class="form-control" id="customerPassword" name="customerPassword" style="height: 45px;" required>
                                                <div class="invalid-feedback">
                                                    Please enter password.
                                                </div>
                                            </div>
                                        </div>

                                        <input type="hidden" name="adminID" value="<?php echo $adminID; ?>">

                                        <div class="modal-body text-right pt-0">
                                            <button id="aHaveAccount" class="btn btn-success  px-5">Already have account?</button>
                                            <button id="createProjectDH" type="submit" class="btn btn-success  px-5">Save</button>
                                        </div>
                                    </div>

                                    <div id="haveAccount" class="d-none">
                                        <div class="mb-2">
                                            <label class="form-label mx-1 mt-1 mb-0">Customer</label>
                                            <select class="form-select text-dark " id="customerHA" name="customerHA" style="height: 45px;">
                                            <option class="d-none" value="" selected>Select Customer</option>
                                                <?php
                                                    $query = "SELECT user_id, name FROM user WHERE user_type = 'customer'";
                                                    $result = mysqli_query($conn, $query);

                                                    if ($result) {
                                                        $num_rows = mysqli_num_rows($result);
                                                        if ($num_rows > 0) {
                                                            while ($cdata = mysqli_fetch_assoc($result)) {
                                                                ?>
                                                                <option value="<?php echo $cdata['user_id']; ?>">
                                                                    <?php echo $cdata['name']; ?>
                                                                </option>
                                                                <?php
                                                            }
                                                        } else {
                                                            ?>
                                                            <option disabled>No customer found.</option>
                                                            <?php
                                                        }
                                                    }
                                                ?>
                                            </select>
                                            <div class="invalid-feedback">
                                                Please select a customer.
                                            </div>
                                        </div>

                                        <input type="hidden" name="adminID" value="<?php echo $adminID; ?>">

                                        <div class="modal-body text-right pt-0">
                                            <button id="dHaveAccount" class="btn btn-success  px-5">Don't have account?</button>
                                            <button id="createProjectHA" type="submit" class="btn btn-success px-5">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() 
    {
        var projectFields = ['#projectName', '#location', '#employee', '#startDate', '#deadline'];
        var customerFields = ['#customerName', '#customerAddress', '#customerPN', '#customerEmail', '#customerPassword'];
        var customerFieldsHA = ['#customerHA'];

        $('#aHaveAccount').on('click', function(e) {
            e.preventDefault();

            $('#haveAccount').removeClass('d-none');
            $('#notYet').addClass('d-none');

            $(customerFields.join(', ')).each(function() {
                $(this).removeAttr('required').val('');
            });

            $('#customerHA').attr('required', true);
        });

        $('#dHaveAccount').on('click', function(e) {
            e.preventDefault();

            $('#notYet').removeClass('d-none');
            $('#haveAccount').addClass('d-none');
        
            $(customerFields.join(', ')).each(function() {
                $(this).attr('required', true);
            });

            $('#customerHA').removeAttr('required').val('');
        });

        function checkProjectFields() {
            let allFilled = true;
            projectFields.forEach(function(selector) {
                if ($(selector).val().trim() === '' || $(selector).val() === null || $(selector).val() === 'Select Employee') {
                    allFilled = false;
                }
            });
            return allFilled;
        }

        function validateProjectFields() {
            let allValid = true;
            projectFields.forEach(function(selector) {
                if ($(selector)[0].checkValidity() === false) {
                    allValid = false;
                }
            });
            return allValid;
        }

        function validateCustomerFields() {
            let allValid = true;
            customerFields.forEach(function(selector) {
                if ($(selector)[0].checkValidity() === false) {
                    allValid = false;
                }
            });
            return allValid;
        }

        function validateCustomerHAField() {
            let isValid = true;
            if ($('#customerHA').prop('required') && !$('#customerHA').val()) {
                isValid = false;
                $('#customerHA').addClass('is-invalid');
            } else {
                $('#customerHA').removeClass('is-invalid');
            }

            return isValid;
        }

        projectFields.forEach(function(selector) {
            $(selector).on('input change', function() {
                if (checkProjectFields()) {
                    $('#nextButton').prop('disabled', false);
                    $('#customer-details-tab').removeClass('disabled').attr('aria-disabled', 'false').attr('tabindex', '0');
                } else {
                    $('#nextButton').prop('disabled', true);
                    $('#customer-details-tab').addClass('disabled').attr('aria-disabled', 'true').attr('tabindex', '-1');
                }
            });
        });

        $('#nextButton').prop('disabled', true);
        $('#customer-details-tab').addClass('disabled').attr('aria-disabled', 'true').attr('tabindex', '-1');

        $('#customerHA').on('change', function() {
            if ($(this).val()) {
                $(this).removeClass('is-invalid');
            }
        });

        $('#nextButton').on('click', function() {
            if (checkProjectFields()) {
                $('#customer-details-tab').tab('show');
            }
        });

        $('#createProjectDH').on('click', function(e) {
            e.preventDefault();

            var form = $('#createProjectForm')[0];
            var startDateField = $('#startDate')[0];
            var deadlineField = $('#deadline')[0];
            var phoneNumberField = $('#customerPN')[0];

            startDateField.setCustomValidity('');
            deadlineField.setCustomValidity('');
            phoneNumberField.setCustomValidity('');

            form.classList.add('was-validated');

            let projectTabValid = validateProjectFields();

            if (!projectTabValid) {
                $('#project-details-tab').addClass('bg-danger text-white');
            } else {
                $('#project-details-tab').removeClass('bg-danger text-white');
            }

            if (projectTabValid) {
                $('#customer-details-tab').tab('show');
            }

            if ($('#customer-details-tab').hasClass('active')) {
                let customerTabValid = validateCustomerFields();
                if (!customerTabValid) {
                    $('#customer-details-tab').addClass('bg-danger text-white');
                } else {
                    $('#customer-details-tab').removeClass('bg-danger text-white');
                }
            }

            if (form.checkValidity() === false || !projectTabValid || ($('#customer-details-tab').hasClass('active') && !validateCustomerFields())) {
                e.stopPropagation();
                return;
            } else {
                var startDateValue = new Date(startDateField.value);
                var deadlineValue = new Date(deadlineField.value);
                var phoneNumberValue = phoneNumberField.value;

                var today = new Date();
                today.setHours(0, 0, 0, 0);

                if (startDateValue < today) {
                    startDateField.setCustomValidity('Start date must be today or a future date.');
                    $('#startDate').siblings('.invalid-feedback').text('Start date must be today or a future date.');
                    $('#project-details-tab').addClass('bg-danger text-white');
                    return;
                }

                if (startDateValue > deadlineValue) {
                    deadlineField.setCustomValidity('Deadline must be greater than or equal to start date');
                    $('#deadline').siblings('.invalid-feedback').text('Deadline must be greater than or equal to start date');
                    $('#project-details-tab').addClass('bg-danger text-white');
                    return;
                }

                if (phoneNumberValue.length !== 11) {
                    phoneNumberField.setCustomValidity('Please enter a valid 11-digit number.');
                    $('#customerPN').siblings('.invalid-feedback').text('Please enter a valid 11-digit number.');
                    $('#customer-details-tab').addClass('bg-danger text-white');
                    return;
                }

                // Show SweetAlert confirmation dialog
                Swal.fire({
                    icon: 'question',
                    title: 'Are you sure?',
                    text: 'Do you want to save this project?',
                    showCancelButton: true,
                    confirmButtonText: 'Save',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Proceed with the AJAX request
                        $.ajax({
                            type: 'POST',
                            url: 'add.php?action=project',
                            data: $('#createProjectForm').serialize(),
                            success: function(response) {
                                startDateField.setCustomValidity('');
                                deadlineField.setCustomValidity('');
                                phoneNumberField.setCustomValidity('');

                                $('#startDate').siblings('.invalid-feedback').text('');
                                $('#deadline').siblings('.invalid-feedback').text('');
                                $('#customerPN').siblings('.invalid-feedback').text('');

                                $('#createProjectForm')[0].reset();
                                $('#createProjectForm').removeClass('was-validated');

                                $('#createProjectModal').modal('hide');

                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: response,
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(() => {
                                    location.reload();
                                });
                            },
                        });
                    }
                });
            }

            form.classList.add('was-validated');
        });
        
        $('#createProjectHA').on('click', function(e) {
            e.preventDefault();
        
            var form = $('#createProjectForm')[0];
            var startDateField = $('#startDate')[0];
            var deadlineField = $('#deadline')[0];
            var customerHAField = $('#customerHA')[0];
        
            startDateField.setCustomValidity('');
            deadlineField.setCustomValidity('');
            customerHAField.setCustomValidity('');
        
            form.classList.add('was-validated');
        
            let projectTabValid = validateProjectFields();
            let customerTabValid = validateCustomerHAField();
        
            if (!projectTabValid) {
                $('#project-details-tab').addClass('bg-danger text-white');
            } else {
                $('#project-details-tab').removeClass('bg-danger text-white');
            }
        
            if (projectTabValid) {
                $('#customer-details-tab').tab('show');
            }
        
            if ($('#customer-details-tab').hasClass('active')) {
                if (!customerTabValid) {
                    $('#customer-details-tab').addClass('bg-danger text-white');
                } else {
                    $('#customer-details-tab').removeClass('bg-danger text-white');
                }
            }
        
            if (form.checkValidity() === false || !projectTabValid || ($('#customer-details-tab').hasClass('active') && !validateCustomerHAField())) {
                e.stopPropagation();
                return;
            } else {
                var startDateValue = new Date(startDateField.value);
                var deadlineValue = new Date(deadlineField.value);
        
                var today = new Date();
                today.setHours(0, 0, 0, 0);
        
                if (startDateValue < today) {
                    startDateField.setCustomValidity('Start date must be today or a future date.');
                    $('#startDate').siblings('.invalid-feedback').text('Start date must be today or a future date.');
                    $('#project-details-tab').addClass('bg-danger text-white');
                    return;
                }
        
                if (startDateValue > deadlineValue) {
                    deadlineField.setCustomValidity('Deadline must be greater than or equal to start date');
                    $('#deadline').siblings('.invalid-feedback').text('Deadline must be greater than or equal to start date');
                    $('#project-details-tab').addClass('bg-danger text-white');
                    return;
                }
        
                // Show SweetAlert confirmation dialog
                Swal.fire({
                    icon: 'question',
                    title: 'Are you sure?',
                    text: 'Do you want to save this project?',
                    showCancelButton: true,
                    confirmButtonText: 'Save',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Proceed with the AJAX request
                        $.ajax({
                            type: 'POST',
                            url: 'add.php?action=projectHA',
                            data: $('#createProjectForm').serialize(),
                            success: function(response) {
                                startDateField.setCustomValidity('');
                                deadlineField.setCustomValidity('');
                                customerHAField.setCustomValidity('');
        
                                $('#startDate').siblings('.invalid-feedback').text('');
                                $('#deadline').siblings('.invalid-feedback').text('');
                                $('#customerHA').siblings('.invalid-feedback').text('');
        
                                $('#createProjectForm')[0].reset();
                                $('#createProjectForm').removeClass('was-validated');
        
                                $('#createProjectModal').modal('hide');
        
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: response,
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(() => {
                                    location.reload();
                                });
                            },
                        });
                    }
                });
            }
        
            form.classList.add('was-validated');
        });

        $('#customerPN').on('input', function() {
            $(this).val($(this).val().replace(/\D/g, ''));

            if ($(this).val().length > 11) {
                $(this).val($(this).val().slice(0, 11));
            }
        });

        $('#createProjectModal').on('hidden.bs.modal', function() {
            $('#createProjectForm')[0].reset();
            $('#createProjectForm').removeClass('was-validated');
            $('#project-details-tab').removeClass('bg-danger text-white');
            $('#customer-details-tab').removeClass('bg-danger text-white');
            $('#project-details-tab').tab('show'); // Show the "Project Details" tab
            $('#nextButton').prop('disabled', true);
            $('#customer-details-tab').addClass('disabled').attr('aria-disabled', 'true').attr('tabindex', '-1');
        });
    });
</script>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>