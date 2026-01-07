<?php

    $page = 'user_project';

    include 'database.php';

    include "auth_middleware.php";
    checkAuth();
    checkRole('employee');

    if (!isset($_SESSION['employee_name'])) {
        header('location:index.php');
    }

    $employeeID = $_SESSION['employeeID'];
    $employeeName = $_SESSION['employee_name'];

    $userResult = mysqli_query($conn, "SELECT * FROM user WHERE user_id = $employeeID");
    if (mysqli_num_rows($userResult) > 0) {
        $userData = mysqli_fetch_array($userResult);
    }

    $defaultImage = '../images/default.jpg';
    $defaultImage1 = '../images/default.jpg';
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <?php include 'include1/header.php' ?>

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

        .opt:hover{
            background-color: rgba(128, 128, 128, 0.4);
        }

        #diamond{
            width: 10px;
            height: 10px;
            background-color: black;
            color: white;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            transform: rotate(-45deg);
            transform-origin: 0 100%;
            cursor: pointer;
            margin-top: 7px;
        }
    </style>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
            <?php include('include1/sidebar.php'); ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content" class="bg-white">

                <!-- Topbar -->
                    <?php include('include1/topbar.php'); ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid p-0">
                                        
                    <div class="card border-0 mb-4" style="background-color: transparent;">
                        <div class="card-body pb-0">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="ProjectTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th style="width: 5%;">#</th>
                                            <th style="width: 30%;">Project Name</th>
                                            <th style="width: 30%;">Location</th>
                                            <th style="width: 10%;">Deadline</th>
                                            <th style="width: 20%;">Progress</th>
                                            <th style="width: 5%;">Status</th>
                                            <th style="width: 15%;">Control</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        $projResult = mysqli_query($conn, "SELECT project.*, 
       COUNT(task.task_id) AS total_tasks, 
       SUM(CASE WHEN task.status = 'COMPLETED' THEN 1 ELSE 0 END) AS completed_tasks
FROM project
LEFT JOIN task ON project.project_id = task.project_id 
WHERE project.active = '1' 
  AND project.employee_name = '$employeeName'
  AND project.status <> 'CANCEL'
GROUP BY project.project_id 
ORDER BY project.status DESC;
");

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
                                                $statusColor = $statusRow ? $statusRow['color'] : '#ffffff';


                                                ?>
                                                <tr>
                                                    <td><?php echo $counter++ ?></td>
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

                                                                if ($project == 'COMPLETED'): ?>
                                                                    <div class="rounded-1 fw-bold px-2 py-1" style="background-color: rgba(0, 128, 0, 0.2); font-size: 11px; width: fit-content; color: green; font-weight: 700;">
                                                                        <?= date("M d, Y", $endDate) ?>
                                                                    </div>

                                                                <?php elseif ($daysDifference == 0): ?>
                                                                    <div class="rounded-1 px-2 py-1" style="background-color: rgba(255, 0, 0, 0.2); font-size: 11px; width: fit-content; color: red; font-weight: 700;">
                                                                        Today
                                                                    </div>

                                                                <?php elseif ($currentDate > $endDate): ?>
                                                                    <div class="rounded-1 px-2 py-1" style="background-color: rgba(255, 0, 0, 0.2); font-size: 11px; width: fit-content; color: red; font-weight: 700;">
                                                                        <?= date("M d, Y", $endDate) ?>
                                                                    </div>

                                                                <?php elseif ($daysDifference <= 7): ?>
                                                                    <div class="rounded-1 px-2 py-1" style="background-color: rgba(255, 255, 0, 0.2); font-size: 11px; width: fit-content; color: red; font-weight: 700;">
                                                                        <?= $daysDifference ?> day<?= $daysDifference == 1 ? '' : 's' ?> left
                                                                    </div>

                                                                <?php else: ?>
                                                                    <div class="rounded-1 px-2 py-1" style="background-color: rgba(0, 0, 255, 0.2); font-size: 11px; width: fit-content; font-weight: 700;">
                                                                        <?= date("M d, Y", $endDate) ?>
                                                                    </div>
                                                                <?php endif;
                                                            ?>
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
                                                        <div class="p-1 rounded text-center" style="background-color: <?php echo $statusColor ?>; font-size: 13px; font-weight: 600; width: 200px; opacity: 90%;">
                                                            <?php echo $status ?>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="text-center d-flex justify-content-evenly">
                                                            <a href="user_task.php?projID=<?php echo $projData['project_id'] ?>"><i class="fa-solid fa-eye"></i></a>
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
                <!-- /.container-fluid -->
                </div>

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
                <?php include('include1/footer.php'); ?>
            <!-- End of Footer -->
        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

</body>
</html>

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
    $(document).ready(function() {
        $('#ProjectTable').DataTable({
            ordering: false,
            info: false,
            columnDefs: [
                { searchable: true, targets: [1] }, // Make the first column searchable (index 0)
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
</script>