<?php
    $page = 'archive';

    include 'database.php';

    include "auth_middleware.php";
    checkAuth();
    checkRole('admin');

    if (!isset($_SESSION['admin_name'])) {
        header('location:index.php');
    }

    $adminID = $_SESSION['adminID'];

    $userResult = mysqli_query($conn, "SELECT * FROM user WHERE user_id = $adminID");
    if (mysqli_num_rows($userResult) > 0) {
        $userData = mysqli_fetch_array($userResult);
    }

    $defaultImage = '../images/default.jpg';

    // Fetch data from multiple tables and combine into a single array
    $archiveResults = [];
    $tableNames = ['project', 'supplier', 'inventory']; // Replace with your actual table names

    // Define column names for each table
    $tableColumns = [
        'project' => ['id' => 'project_id', 'name' => 'project_name'],
        'supplier' => ['id' => 'supplier_id', 'name' => 'company_name'],
        'inventory' => ['id' => 'item_id', 'name' => 'item_name']
    ];

    foreach ($tableNames as $tableName) 
    {
        $columns = $tableColumns[$tableName];

        $result = mysqli_query($conn, "SELECT {$columns['id']} as id, {$columns['name']} as name FROM $tableName WHERE active = 0");

        if (mysqli_num_rows($result) > 0) 
        {
            while ($row = mysqli_fetch_assoc($result)) 
            {
                $archiveResults[] = array_merge($row, ['table' => $tableName]);
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'include/header.php' ?>
</head>
<body id="page-top">
    <div id="wrapper">
        <?php include('include/sidebar.php'); ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <?php include('include/topbar.php'); ?>
            <div id="content" class="bg-white">
                <div class="container-fluid p-0">
                    <div class="card mb-4 border border-0" style="background-color: transparent;">
                        <div class="card-body">
                            <div class="d-flex mb-2">
                                <div style="width: 50%;">
                                    
                                </div>
                                <div class="d-flex justify-content-end" style="width: 50%;">
                                    <form action="">
                                        <label for="locationFilter" class="form-label mb-0">Filter</label>
                                        <select id="locationFilter" class="form-select py-1 px-2" style="width: 180px; font-size: 14px;">
                                            <option value="">All Locations</option>
                                            <?php
                                                // Fetch distinct locations from the archiveResults
                                                $locations = array_unique(array_column($archiveResults, 'table'));
                                                foreach ($locations as $location) {
                                                    echo '<option value="' . htmlspecialchars($location) . '">' . htmlspecialchars($location) . '</option>';
                                                }
                                            ?>
                                        </select>
                                    </form>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="ArchiveTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th class="d-none">#</th>
                                            <th style="width: 50%;">Name</th>
                                            <th style="width: 30%;">Location</th>
                                            <th style="width: 20%;">Controls</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (count($archiveResults) > 0): ?>
                                            <?php foreach ($archiveResults as $row): ?>
                                                <tr>
                                                    <td class="d-none"><?= htmlspecialchars($row['id']) ?></td>
                                                    <td><?= htmlspecialchars($row['name']) ?></td>
                                                    <td><?= htmlspecialchars($row['table']) ?></td>
                                                    <td class="d-flex justify-content-evenly">
                                                        <i class="fa-solid fa-arrow-rotate-left text-primary restore-btn" data-bs-toggle="tooltip" data-bs-placement="top" style="cursor: pointer;" title="Restore" data-admin-id="<?php echo $adminID ?>" data-id="<?= htmlspecialchars($row['id']) ?>" data-table="<?= htmlspecialchars($row['table']) ?>"></i>
                                                        <i class="fa-solid fa-trash text-danger delete-btn" data-bs-toggle="tooltip" data-bs-placement="top" style="cursor: pointer;" title="Delete" data-admin-id="<?php echo $adminID ?>" data-id="<?= htmlspecialchars($row['id']) ?>" data-table="<?= htmlspecialchars($row['table']) ?>"></i>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include('include/footer.php'); ?>
</body>
</html>

<script>
    $(document).ready(function() {
        var table = $('#ArchiveTable').DataTable({
            ordering: false,
            info: false,
            columnDefs: [
                { searchable: true, targets: [1] },
                { searchable: false, targets: [0, 2, 3] }
            ],
            language: {
                paginate: {
                    previous: '<i class="fas fa-angle-left"></i>',
                    next: '<i class="fas fa-angle-right"></i>'
                }
            }
        });

        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        $('#locationFilter').change(function() {
            var selectedLocation = $(this).val();
            var noMatch = true;

            $('#ArchiveTable tbody tr').each(function() {
                var locationCell = $(this).find('td:nth-child(3)');
                if (selectedLocation === '' || locationCell.text().trim() === selectedLocation) 
                {
                    $(this).show();
                    noMatch = false;
                } else {
                    $(this).hide();
                }
            });

            $('#ArchiveTable tbody .no-records').remove();

            if (noMatch) {
                $('#ArchiveTable tbody').append('<tr class="no-records"><td colspan="4" style="text-align:center;">No matching records found</td></tr>');
            }
        });

        $(document).on('click', '.restore-btn', function(e) {
            e.preventDefault();

            var id = $(this).data('id');
            var table = $(this).data('table');
            var adminID = $(this).data('admin-id');

            Swal.fire({
                title: 'Confirmation',
                text: "Are you sure to restore?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Continue',
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
                        url: 'update.php?action=restore',
                        type: 'POST',
                        data: { id: id, table: table, adminID: adminID },
                        success: function(response) {
                            Swal.fire({
                                title: "Success",
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

        $(document).on('click', '.delete-btn', function(e) {
            e.preventDefault();

            var id = $(this).data('id');
            var table = $(this).data('table');
            var adminID = $(this).data('admin-id');

            Swal.fire({
                title: 'Confirmation',
                text: "Are you sure to delete permanently?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Delete',
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
                        url: 'delete.php?action=delete_perm',
                        type: 'POST',
                        data: { id: id, table: table, adminID: adminID },
                        success: function(response) {
                            Swal.fire({
                                title: "Deleted",
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