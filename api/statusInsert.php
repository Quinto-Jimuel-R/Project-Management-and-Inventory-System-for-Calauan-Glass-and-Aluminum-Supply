<!-- Modal -->
<div class="modal fade animated--grow-in" id="statusModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">

                    <form action="add.php?action=status" id="createStatusForm" name="createStatusForm" method="POST" class="needs-validation text-dark" novalidate>
                        <div class="modal-header d-flex align-items-center">
                            <strong class="text-dark">New Status</strong>
                            <button id="closeModal" type="button" class="btn-close rounded-0" data-bs-dismiss="modal" aria-label="Close" style="font-size: 12px;"></button>
                        </div>
                        <div class="modal-body p-2 text-left">
                            <div class="mb-2">
                                <label class="form-label mx-1 mt-1 mb-0">Status name</label>
                                <input type="text" class="form-control rounded-0" id="statusName" name="statusName" style="height: 45px;" required>
                                <div class="invalid-feedback">
                                    Please enter status name.
                                </div>
                            </div>

                            <div class="mb-2">
                                <label class="form-label mx-1 mt-1 mb-0">Description</label>
                                <input type="text" class="form-control rounded-0" id="statusDescription" name="statusDescription" style="height: 45px;">
                            </div>

                            <div class="mb-2">
                                <label class="form-label mx-1 mt-1 mb-0">Color</label>
                                <input type="color" class="form-control rounded-0 p-0" id="statusColor" name="statusColor" style="height: 45px;">
                            </div>
                        </div>
                        <div class="modal-body text-right pt-0">
                            <button id="createStatus" type="button" class="btn btn-success rounded-0 px-5">Save</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

<script>
    $(document).on('click', '#closeModal', function (e) 
    {
        e.preventDefault();
        
        $('#createStatusForm')[0].reset();
        $('#createStatusForm').removeClass('was-validated');
        $('#statusModal').modal('hide'); // Close the modal
    });

    $(document).on('input', '#statusName', function(e) 
    {
        e.preventDefault();
        
        $(this).val($(this).val().replace(/[^A-Za-z\s]/g, ''));
    });

    $(document).on('click', '#createStatus', function(e) 
    {
        e.preventDefault();

        var form = $('#createStatusForm')[0];
        form.classList.add('was-validated');

        if (form.checkValidity() === false) {
            event.stopPropagation();
        } else {
                $.ajax({
                type: "POST",
                url: "add.php?action=status",
                data: $('#createStatusForm').serialize(),
                success: function (response) {
                    if (response.includes("already exists")) {
                        $('#createStatusForm')[0].reset();
                        $('#createStatusForm').removeClass('was-validated');
                        $('#statusModal').modal('hide');
                        Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Status already exist!',
                    });
                    } else {
                        $('#createStatusForm')[0].reset();
                        $('#createStatusForm').removeClass('was-validated');
                        $('#statusModal').modal('hide'); // Close the modal

                        Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Status Created!',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                            location.reload();
                        });
                    }
                }
            });
        }
    });
</script>