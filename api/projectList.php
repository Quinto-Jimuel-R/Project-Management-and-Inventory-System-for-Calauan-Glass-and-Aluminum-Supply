<style>
    @keyframes progressBarAnimation {
        from { 
            width: 0; 
        }
        to { 
            width: <?php echo $completionPercentage; ?>; 
        }
    }

    .progress-bar {
        animation: progressBarAnimation 1.5s ease-in-out;
    }

    .dataTables_wrapper .dataTables_paginate {
    float: right; /* Align pagination with SB Admin 2's layout */
}

table.dataTable {
    width: 100% !important; /* Ensure full-width display */
}

@media (max-width: 576px) {
        .status-select-wrapper {
            display: none !important;
        }
    }

    
</style>

<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap4.min.css">
<script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>

<div class="card border border-0 mb-4" style="background-color: transparent;">
    <div class="card-body pb-0">
        <div class="d-flex justify-content-between">
            <div class="d-flex align-items-center gap-1">
                <!--<a type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#statusModal" style="font-size: 14px;">
                    + Add Status
                </a>-->
                <a type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createProjectModal" style="font-size: 14px;">
                    + Add project
                </a>
            </div>
            <div class="d-flex justify-content-end status-select-wrapper" style="width: 50%;">
                <form id="statusFilterForm">
                    <label class="m-0" for="statusFilter" style="font-size: 14px; font-weight: 600;">Status</label>
                    <select class="form-select py-1 px-2" id="statusFilter" name="statusFilter" style="width: 180px; font-size: 14px;">
                        <option value="ALL">All</option>
                        <?php
                            $statusResult = mysqli_query($conn, "SELECT *
                                                                FROM status
                                                                WHERE status_name <> 'CANCEL'
                                                                ORDER BY CASE WHEN status_name = 'COMPLETE' THEN 1 ELSE 0 END, status_name DESC;");
                            if(mysqli_num_rows($statusResult) > 0)
                            {
                                while($statusData = mysqli_fetch_array($statusResult))
                                {
                        ?>
                            <option value="<?php echo $statusData['status_name']; ?>"><?php echo $statusData['status_name']; ?></option>
                        <?php 
                                }
                            } 
                        ?>
                    </select>
                </form>
            </div>
        </div>
        <?php include 'statusInsert.php' ?>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="ProjectTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th class="text-center d-none" style="width: 5%;">#</th>
                        <th style="width: 25%;">Project Name</th>
                        <th style="width: 25%;">Location</th>
                        <th style="width: 10%;">Deadline</th>
                        <th style="width: 20%;">Progress</th>
                        <th style="width: 5%;">Status</th>
                        <th style="width: 10%;">Controls</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $projResult = mysqli_query($conn, "SELECT project.*, COUNT(task.task_id) AS total_tasks, SUM(CASE WHEN task.status = 'COMPLETED' THEN 1 ELSE 0 END) AS completed_tasks
                                    FROM project
                                    LEFT JOIN task ON project.project_id = task.project_id WHERE project.active = '1' 
                                    AND project.status <> 'CANCEL'
                                    GROUP BY project.project_id ORDER BY project.status DESC");
                    $counter = 1;

                    if(mysqli_num_rows($projResult) > 0) 
                    {
                        while($projData = mysqli_fetch_array($projResult)) 
                        {
                            $totalTasks = $projData['total_tasks'];
                            $completedTasks = $projData['completed_tasks'];
                            $completionPercentage = ($totalTasks > 0) ? ($completedTasks / $totalTasks) * 100 : 0;
                            $status = $projData['status'];

                            $statusQuery = mysqli_query($conn, "SELECT color FROM status WHERE status_name = '$status'");
                            $statusRow = mysqli_fetch_array($statusQuery);
                            $statusColor = $statusRow ? $statusRow['color'] : '#ffffff'; // Default color if not found

                            ?>
                            <tr>
                                <td class="text-center d-none"><?php echo $counter++ ?></td>
                                <td><?php echo $projData['project_name'] ?></td>
                                <td><?php echo $projData['location'] ?></td>
                                <td>
                                    <div class="d-flex align-items-center justify-content-center">
                                        <?php
                                            $endDate = strtotime($projData['end_date']);
                                            $project = $projData['status'];
                                            $currentDate = time();

                                            $difference = $endDate - $currentDate;
                                            $daysDifference = floor($difference / (60 * 60 * 24));

                                            // Format for display (e.g., "M d, Y")
                                            $formattedEndDate = date("M d, Y", $endDate);

                                            // Format for date input (e.g., "YYYY-MM-DD")
                                            $inputFormattedDate = date("Y-m-d", $endDate);
                                        ?>

                                        <!-- Conditional Divs with a data-enddate attribute -->
                                        <?php if ($project == 'COMPLETED'): ?>
                                            <div class="rounded-1 fw-bold px-2 py-1" 
                                                style="background-color: rgba(0, 128, 0, 0.2); font-size: 11px; width: fit-content; color: green; font-weight: 700;"
                                                data-bs-toggle="modal">
                                                <?= $formattedEndDate ?>
                                            </div>

                                        <?php elseif ($daysDifference == 0): ?>
                                            <div class="rounded-1 px-2 py-1" 
                                                style="cursor: pointer; background-color: rgba(255, 0, 0, 0.2); font-size: 11px; width: fit-content; color: red; font-weight: 700;"
                                                data-bs-toggle="modal" data-bs-target="#endDateModal" 
                                                data-enddate="<?= $inputFormattedDate ?>" 
                                                data-projectid="<?= $projData['project_id'] ?>"
                                                data-toggle="tooltip" data-bs-placement="top" title="Edit">
                                                Today
                                            </div>

                                        <?php elseif ($currentDate > $endDate): ?>
                                            <div class="rounded-1 px-2 py-1" 
                                                style="cursor: pointer; background-color: rgba(255, 0, 0, 0.2); font-size: 11px; width: fit-content; color: red; font-weight: 700;"
                                                data-bs-toggle="modal" data-bs-target="#endDateModal" 
                                                data-enddate="<?= $inputFormattedDate ?>" 
                                                data-projectid="<?= $projData['project_id'] ?>"
                                                data-toggle="tooltip" data-bs-placement="top" title="Edit">
                                                <?= $formattedEndDate ?>
                                            </div>

                                        <?php elseif ($daysDifference <= 7): ?>
                                            <div class="rounded-1 px-2 py-1" 
                                                style="cursor: pointer; background-color: rgba(255, 255, 0, 0.2); font-size: 11px; width: fit-content; color: red; font-weight: 700;"
                                                data-bs-toggle="modal" data-bs-target="#endDateModal" 
                                                data-enddate="<?= $inputFormattedDate ?>" 
                                                data-projectid="<?= $projData['project_id'] ?>"
                                                data-toggle="tooltip" data-bs-placement="top" title="Edit">
                                                <?= $daysDifference ?> day<?= $daysDifference == 1 ? '' : 's' ?> left
                                            </div>

                                        <?php else: ?>
                                            <div class="rounded-1 px-2 py-1" 
                                                style="cursor: pointer; background-color: rgba(0, 0, 255, 0.2); font-size: 11px; width: fit-content; font-weight: 700;"
                                                data-bs-toggle="modal" data-bs-target="#endDateModal" 
                                                data-enddate="<?= $inputFormattedDate ?>" 
                                                data-projectid="<?= $projData['project_id'] ?>"
                                                data-toggle="tooltip" data-bs-placement="top" title="Edit">
                                                <?= $formattedEndDate ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center justify-content-center mt-1">
                                        <div class="progress w-100 me-2" style="height: 7px;" role="progressbar" aria-label="Progress" aria-valuenow="<?php echo $completionPercentage; ?>" aria-valuemin="0" aria-valuemax="100">
                                            <div class="progress-bar <?php echo getProgressBarColor($completionPercentage); ?>" style="opacity: 90%; width: <?php echo $completionPercentage; ?>%"></div>
                                        </div>
                                        <div style="font-size: 12px;"><?php echo $completedTasks; ?>/<?php echo $totalTasks; ?></div>
                                    </div>
                                </td>
                                <td class="">
                                    <div class="p-1 rounded text-center" style="background-color: <?php echo $statusColor ?>; font-size: 13px; font-weight: 600; width: 150px; opacity: 90%;">
                                        <?php echo $status ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-center d-flex justify-content-around">
                                        <a href="task.php?projID=<?php echo $projData['project_id'] ?>"><i class="fa-solid fa-eye" data-toggle="tooltip" data-bs-placement="top" title="View"></i></a>
                                        <a id="deleteProject" href="" class="text-danger d-none" data-id="<?php echo $projData['project_id'] ?>" data-admin-id="<?php echo $adminID; ?>"><i class="fa-solid fa-trash" data-toggle="tooltip" data-bs-placement="top" title="Remove"></i></a>
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

<!-- Modal for updating the end date -->
<div class="modal fade animated--grow-in" id="endDateModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="endDateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header d-flex align-items-center">
                <strong class="text-dark">Edit Deadline</strong>
                <button id="closeModal" type="button" class="btn-close " data-bs-dismiss="modal" aria-label="Close" style="font-size: 12px;"></button>
            </div>

            <div class="modal-body text-left p-2">
                <!-- Input field for editing the date -->
                <label for="modalEndDateInput" class="form-label mx-1 mt-1 mb-0">Deadline</label>
                <input type="date" id="modalEndDateInput" class="form-control">
                <!-- Hidden field for project ID -->
                <input type="hidden" id="projectIdInput">
            </div>
            <div class="modal-body text-right pt-0">
                <button id="saveEndDate" type="submit" class="btn btn-success px-5">Save</button>
            </div>
        </div>
    </div>
</div>

<?php
    function getProgressBarColor($percentage) {
        if ($percentage == 0) {
            return 'bg-secondary'; // Background color for 0% completion
        } elseif ($percentage < 100) {
            return 'bg-warning'; // Background color for less than 100% completion
        } else {
            return 'bg-success'; // Background color for 100% completion
        }
    }
?>

<script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    $(document).ready(function() {
        $('#ProjectTable').DataTable({
            ordering: false,
            info: false,
            responsive: true,
            columnDefs: [
                { searchable: true, targets: [1] }, // Make the first column searchable (index 0)
                { searchable: false, targets: [0, 2, 3, 4, 5] }, // Make other columns not searchable
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
    $(document).ready(function() {
        var clickedDiv;

        // When any div with data-enddate is clicked
        $('div[data-enddate]').on('click', function() {
            clickedDiv = $(this);

            // Get the end date and project ID from the clicked div
            var endDate = clickedDiv.data('enddate');
            var projectId = clickedDiv.data('projectid');

            // If the endDate is in a readable format like "M d, Y", convert it to YYYY-MM-DD for the date input
            if (isNaN(Date.parse(endDate))) {
                var formattedEndDate = new Date(endDate).toISOString().split('T')[0];
            } else {
                var formattedEndDate = endDate; // If it's already in the correct format
            }

            // Set the end date in the modal input and project ID in hidden input
            $('#modalEndDateInput').val(formattedEndDate);
            $('#projectIdInput').val(projectId);

            // Show the modal
            $('#endDateModal').modal('show');
        });

        // Save the updated date on save button click
        $('#saveEndDate').on('click', function() {
            var updatedEndDate = $('#modalEndDateInput').val();
            var projectId = $('#projectIdInput').val();

            $.ajax({
                url: 'update.php?action=updateEndDate',
                type: 'POST',
                data: {
                    project_id: projectId,
                    end_date: updatedEndDate
                },
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
                }
            });
        });
    });

    $(document).ready(function() {
        $('#statusFilter').change(function() 
        {
            var selectedStatus = $(this).val();
            var noMatch = true;

            $('#ProjectTable tbody tr').each(function() {
                var statusCell = $(this).find('td:nth-child(6)');
                if (selectedStatus === 'ALL' || statusCell.text().trim() === selectedStatus) {
                    $(this).show();
                    noMatch = false;
                } else {
                    $(this).hide();
                }
            });

            $('#ProjectTable tbody .no-records').remove();

            if (noMatch) {
                $('#ProjectTable tbody').append('<tr class="no-records"><td colspan="6" style="text-align:center;">No matching records found</td></tr>');
            }
        });
    });

    $(document).on('click', '#deleteProject', function(e) {
        e.preventDefault();

        var projectId = $(this).data('id');
        var adminID = $(this).data('admin-id');

        let timerInterval;
        Swal.fire({
            title: "Confirmation",
            text: "Are you sure to remove this project?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#4e73df",
            cancelButtonColor: "#e74a3b",
            confirmButtonText: "Continue",
            cancelButtonText: "Close",
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
                $.ajax({
                    type: "POST",
                    url: "delete.php?action=project",
                    data: { projectId: projectId, adminID: adminID },
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