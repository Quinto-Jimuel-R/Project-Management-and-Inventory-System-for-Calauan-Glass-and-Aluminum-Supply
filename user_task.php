<?php

    $page = 'user_project';

    include 'database.php';

    include "auth_middleware.php";
    checkAuth();
    checkRole('employee');
    
    if(!isset($_SESSION['employee_name'])){
       header('location:index.php');
    }
    
    if(isset($_GET['projID']))
    {
        $project_id = $_GET['projID'];
    }

    $employeeID = $_SESSION['employeeID'];
    $employeeName = $_SESSION['employee_name'];

    $userResult = mysqli_query($conn, "SELECT * FROM user WHERE user_id = $employeeID");
    if (mysqli_num_rows($userResult) > 0) {
        $userData = mysqli_fetch_array($userResult);
    }

    $defaultImage = '../images/default.jpg';
    $defaultImage1 = '../images/default.jpg';

    // Fetch the total number of tasks and the number of completed tasks
    $sql = "SELECT COUNT(*) as totalTasks FROM task WHERE project_id = $project_id";
    $result = $conn->query($sql);
    $totalTasks = $result->fetch_assoc()['totalTasks'];

    $sql = "SELECT COUNT(*) as completedTasks FROM task WHERE status = 'Completed' AND project_id = $project_id";
    $result = $conn->query($sql);
    $completedTasks = $result->fetch_assoc()['completedTasks'];

    // Check if all tasks are completed
    $allTasksCompleted = ($totalTasks > 0 && $totalTasks == $completedTasks);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'include1/header.php' ?>

    <style>
        .disabled-icon {
            pointer-events: none; /* Disable click events */
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
                        <div class="card-body pb-0 px-4" style="padding-right: 20px;">
                            <div class="text-end">
                                <a class="btn bg-primary text-white" href="user_project.php"><i class="fa-solid fa-person-walking-arrow-right"></i> Back</a>
                            </div>
                            <div class="row my-3">
                                <div class="col-lg-6 mb-3 mb-lg-0"">
                                    <div class="card">
                                        <div class="card-body d-flex justify-content-between pb-0">
                                            <p class="m-0" style="font-weight: 800;">Project Information</p>
                                            <div class="d-flex align-items-center">
                                                <a href="#" class="rounded text-dark" data-bs-toggle="modal" data-bs-target="#updateStatus" style="background-color: rgb(232, 237, 234);">
                                                    <i class="fa-solid fa-pen-to-square" style="font-size: 20px; cursor: pointer;"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <?php 
                                                // Fetch the current project status
                                                $projectStatusQuery = mysqli_query($conn, "SELECT project.*, user.*, status.*
                                                FROM project
                                                INNER JOIN user ON project.customer_id = user.user_id
                                                INNER JOIN status ON project.status = status.status_name
                                                WHERE project.project_id = '$project_id';");

                                                $projectData = mysqli_fetch_array($projectStatusQuery);
                                                $customerID = $projectData['user_id'];
                                                $status = $projectData['status'];
                                                $isToDo = $status === 'TO DO';
                                                $isInProgress = $status === 'IN PROGRESS';
                                                $isDelivered = $status === 'DELIVERED';
                                                $isInstallation = $status === 'INSTALLATION';
                                                $isCompleted = $status === 'COMPLETED';

                                                // Check if there are any ongoing IN PROGRESS projects for the same employee
                                                $ongoingInProgressQuery = mysqli_query($conn, "SELECT * FROM project WHERE employee_name = '$employeeName' AND status = 'IN PROGRESS'");
                                                $hasOngoingInProgress = mysqli_num_rows($ongoingInProgressQuery) > 0;

                                                // Check if all tasks are done
                                                $tasksStatusQuery = mysqli_query($conn, "SELECT status FROM task WHERE project_id = '$project_id'");
                                                $allTasksDone = true;
                                                while ($taskStatus = mysqli_fetch_array($tasksStatusQuery)) 
                                                {
                                                    if ($taskStatus['status'] !== 'COMPLETED') {
                                                        $allTasksDone = false;
                                                        break;
                                                    }
                                                }
                                            ?>

                                            <input type="hidden" id="allTasksDone" value="<?php echo $allTasksDone ? 'true' : 'false'; ?>">
                                            <input type="hidden" id="isInProgress" value="<?php echo $isInProgress ? 'true' : 'false'; ?>">

                                            <div class="">
                                                <div class="d-flex align-items-center mb-3">
                                                    <p class="m-0">Name: <?php echo htmlspecialchars($projectData['project_name']); ?></p>
                                                </div>
                                                <div class="d-flex align-items-center mb-3">
                                                    <p class="m-0">Location: <?php echo htmlspecialchars($projectData['location']); ?></p>
                                                </div>
                                                <div class="d-flex align-items-center mb-3">
                                                    <p class="m-0">Customer: <?php echo htmlspecialchars($projectData['name']); ?></p>
                                                </div>
                                                <div class="d-flex align-items-center mb-3">
                                                    <p class="m-0">Contact: <?php echo htmlspecialchars($projectData['phone_number']); ?></p>
                                                </div>
                                                <div class="d-flex align-items-center mb-3">
                                                    <p class="m-0">Start Date: <?php echo date('F d, Y', strtotime($projectData['start_date'])); ?></p>
                                                </div>
                                                <?php
                                                    $endDate = new DateTime($projectData['end_date']);
                                                    $formattedDate = $endDate->format('F j, Y');
                                                    $currentDate = new DateTime();
                                                    $interval = $currentDate->diff($endDate);
                                                    $daysDifference = (int)$interval->format('%r%a');

                                                    if ($status != 'COMPLETED'):
                                                ?>
                                                    <div class="d-flex align-items-center mb-3">
                                                        <p class="m-0">Deadline:
                                                        <?php if ($daysDifference == 0): ?>
                                                            <div class="d-flex align-items-center rounded-1 px-2 py-1 ms-2" style="background-color: rgba(255, 0, 0, 0.2); font-size: 11px; width: fit-content; color: red; font-weight: 700;">
                                                                Today
                                                            </div>
                                                        <?php elseif ($daysDifference < 0): ?>
                                                            <div class="d-flex align-items-center rounded-1 px-2 py-1 ms-2" style="background-color: rgba(255, 0, 0, 0.2); font-size: 11px; width: fit-content; color: red; font-weight: 700;">
                                                                <?= $formattedDate ?>
                                                            </div>
                                                        <?php elseif ($daysDifference <= 7): ?>
                                                            <div class="d-flex align-items-center rounded-1 px-2 py-1 ms-2" style="background-color: rgba(255, 255, 0, 0.2); font-size: 11px; width: fit-content; color: red; font-weight: 700;">
                                                                <?= $daysDifference ?> day<?= $daysDifference == 1 ? '' : 's' ?> left
                                                            </div>
                                                        <?php else: ?>
                                                            <div class="d-flex align-items-center rounded-1 px-2 py-1 ms-2" style="background-color: rgba(0, 0, 255, 0.2); font-size: 11px; width: fit-content; font-weight: 700;">
                                                                <?= $formattedDate ?>
                                                            </div>
                                                        <?php endif; ?>
                                                        </p>
                                                    </div>
                                                <?php endif; ?>
                                                <div class="d-flex align-items-center mb-3">
                                                    <p class="m-0">Status: </p>
                                                        <div class="d-flex align-items-center rounded-1 fw-bold px-2 py-1 ms-2" style="background-color: <?php echo htmlspecialchars($projectData['color']); ?>; font-size: 11px; width: fit-content; font-weight: 700;">
                                                            <?php echo htmlspecialchars($projectData['status']); ?>
                                                        </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="card" style="height: 325px;">
                                        <div class="card-body pb-0">
                                            <p class="m-0" style="font-weight: 800;">Task Progress</p>
                                        </div>
                                        <div class="d-flex justify-content-center">
                                            <div class="card-body col-7" style="height: 250px;">
                                                <canvas id="taskProgressChart" width="250" height="250"></canvas>
                                            </div>
                                            <div class="card-body d-flex flex-column justify-content-center gap-3 col-4 ps-0" style="height: 250px; font-weight: 700;">
                                                <div class="d-flex align-items-center mb-2">
                                                    <i class="fa-solid fa-fw fa-square fs-5 me-2" style="color: #a8a7a7;"></i><span>NOT STARTED</span>
                                                </div>
                                                <div class="d-flex align-items-center mb-2">
                                                    <i class="fa-solid fa-fw fa-arrows-spin fs-5 me-2" style="color: #f7a531;"></i><span>ONGOING</span>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <i class="fa-solid fa-fw fa-square-check fs-5 me-2" style="color: #10f500;"></i><span>COMPLETED</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card border-0">
                                        <div class="table-responsive">
                                            <table class="table table-bordered" id="SupplierTable" width="100%" cellspacing="0">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center d-none" style="width: 5%;">#</th>
                                                        <th class="text-center">
                                                            <?php if ($allTasksCompleted && $status === 'IN PROGRESS'): ?>
                                                                <i id="notifyCustomer" class="fa-solid fa-bell text-danger" style="cursor: pointer;" data-project-id="<?php echo $project_id ?>" data-employee-id="<?php echo $employeeID ?>" data-customer-id="<?php echo $customerID ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Notify customer"></i>
                                                            <?php endif; ?>
                                                        </th>
                                                        <th class="text-center">Description</th>
                                                        <th class="text-center">Dimension</th>
                                                        <th class="text-center">Requirements</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php 
                                                        $taskResult = mysqli_query($conn, "SELECT * FROM task WHERE project_id = '$project_id'");

                                                        if(mysqli_num_rows($taskResult) > 0) 
                                                        {
                                                            while($taskData = mysqli_fetch_array($taskResult)) 
                                                            {
                                                                // Generate a unique ID for each canvas based on task_id
                                                                $canvasID = 'itemCanvas_' . $taskData['task_id'];
                                                    ?>
                                                    <tr>
                                                        <td class="text-center d-none"><?php echo $taskData['task_id']; ?></td>
                                                        <td class="text-center" style="vertical-align: middle;">
                                                            <?php 
                                                                if($taskData['status'] == 'COMPLETED'): ?>
                                                                    <i id="editTaskToNS" class="fa-solid fa-fw fa-square-check <?php echo $isInProgress ? '' : 'disabled-icon'; ?>" data-task-id="<?php echo $taskData['task_id']; ?>" style="cursor: pointer; color: #10f500;"></i>
                                                                <?php elseif($taskData['status'] == 'ONGOING'): ?>
                                                                    <i id="editTaskToC" class="fa-solid fa-fw fa-arrows-spin <?php echo $isInProgress ? '' : 'disabled-icon'; ?>" data-task-id="<?php echo $taskData['task_id']; ?>" style="cursor: pointer; color: #f7a531;"></i>
                                                                <?php else: ?>
                                                                    <i id="editTaskToOG" class="fa-solid fa-fw fa-square <?php echo $isInProgress ? '' : 'disabled-icon'; ?>" data-task-id="<?php echo $taskData['task_id']; ?>" style="cursor: pointer; color: #a8a7a7;"></i>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td class="text-center" style="vertical-align: middle;">
                                                            <div style="font-weight: 600;">
                                                                <?php echo $taskData['description']; ?>
                                                            </div>
                                                            <!-- Unique canvas ID -->
                                                            <canvas id="<?php echo $canvasID; ?>" width="210" height="210"></canvas>
                                                        </td>
                                                        <td class="text-center" style="vertical-align: middle;">
                                                            (L) <?php echo $taskData['length'] ?> - (H) <?php echo $taskData['height'] ?>
                                                        </td>
                                                        <td class="text-center" style="vertical-align: middle;">
                                                            <?php 
                                                                echo nl2br(str_replace('|', ' - ', str_replace(',', '<br>', $taskData['items'])));
                                                            ?>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                            }
                                                        } else {
                                                    ?>
                                                    <tr>
                                                        <td colspan="6" class="text-center">No task records found</td>
                                                    </tr>
                                                    <?php
                                                        }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Update Status Modal -->
                        <div class="modal fade animated--grow-in" id="updateStatus" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header d-flex align-items-center">
                                        <strong class="text-dark">Update Status</strong>
                                        <button id="closeModal" type="button" class="btn-close rounded-0" data-bs-dismiss="modal" aria-label="Close" style="font-size: 12px;"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="container">
                                            <div class="row">
                                            <?php 
                                                $statusResult = mysqli_query($conn, "SELECT * FROM status");

                                                if(mysqli_num_rows($statusResult) > 0) 
                                                {
                                                    // Check if the project is completed
                                                    $isProjectCompleted = $isDelivered && $allTasksDone;

                                                    while($statusData = mysqli_fetch_array($statusResult)) 
                                                    {
                                                        $statusName = $statusData['status_name'];
                                                        
                                                        $disabled = '';
                                                        $cursor = 'cursor: pointer;';
                                                        $additionalData = '';

                                                        if ($statusName === 'CANCEL') {
                                                            continue;
                                                        }
                                                        
                                                        if ($isCompleted) 
                                                        {
                                                            $disabled = 'disabled';
                                                            $cursor = 'cursor: not-allowed;';
                                                        } 
                                                        else 
                                                        {
                                                            if ($isDelivered) 
                                                            {
                                                                if ($statusName !== 'INSTALLATION') 
                                                                {
                                                                    $disabled = 'disabled';
                                                                    $cursor = 'cursor: not-allowed;';
                                                                }
                                                            } 
                                                            elseif ($isInstallation) 
                                                            {
                                                                if ($statusName !== 'COMPLETED') {
                                                                    $disabled = 'disabled';
                                                                    $cursor = 'cursor: not-allowed;';
                                                                }
                                                            } 
                                                            elseif ($isToDo) 
                                                            {
                                                                if ($statusName !== 'IN PROGRESS') {
                                                                    $disabled = 'disabled';
                                                                    $cursor = 'cursor: not-allowed;';
                                                                }
                                                            } 
                                                            else {
                                                                if ($statusName === 'IN PROGRESS' && $isInProgress) 
                                                                {
                                                                    $disabled = 'disabled';
                                                                    $cursor = 'cursor: not-allowed;';
                                                                } 
                                                                elseif ($statusName === 'DELIVERED') 
                                                                {
                                                                    $additionalData = 'data-all-tasks-done="' . $allTasksDone . '"';
                                                                } 
                                                                else 
                                                                {
                                                                    if ($isInProgress && $statusName !== 'DELIVERED') 
                                                                    {
                                                                        $disabled = 'disabled';
                                                                        $cursor = 'cursor: not-allowed;';
                                                                    }
                                                                    if ($isToDo && $statusName === 'TO DO') 
                                                                    {
                                                                        $disabled = 'disabled';
                                                                        $cursor = 'cursor: not-allowed;';
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    ?>
                                                        <div class="col-xl-6 col-md-3 mb-3 d-flex justify-content-around">
                                                            <button class="status-option btn border-0 p-4" data-status="<?php echo $statusName; ?>" data-customer-id="<?php echo $customerID; ?>" data-employee-id="<?php echo $employeeID; ?>" style="text-decoration: none; background-color: <?php echo $statusData['color']; ?>; width: 100%; height: 100%; color: black; font-weight: 700; <?php echo $cursor; ?>" <?php echo $disabled; ?> <?php echo $additionalData; ?>>
                                                                <?php echo $statusName ?>
                                                            </button>
                                                        </div>
                                                    <?php
                                                    }
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End of Update Status Modal -->
                    </div>
                </div>
                <!-- End of Page Content -->
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
        if(isset($_SESSION['success']))
        {
    ?>  
        <script>
            Swal.fire({
                position: "top-end",
                text: '<?php echo $_SESSION['success']; ?>',
                icon: "success",
                showConfirmButton: false,
                timer: 1500
            });  
        </script>
    <?php
        unset($_SESSION['success']);
        }
    ?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        <?php 
        // Ensure tasks are drawn on canvas after DOM is loaded
        $taskResult = mysqli_query($conn, "SELECT * FROM task WHERE project_id = '$project_id'");

        if(mysqli_num_rows($taskResult) > 0) 
        {
            while($taskData = mysqli_fetch_array($taskResult)) 
            {
                $canvasID = 'itemCanvas_' . $taskData['task_id'];
                $description = $taskData['description'];
                $length = $taskData['length']; // Assuming this column is available
                $height = $taskData['height']; // Assuming this column is available
                ?>
                
                (function() {
                    var canvas = document.getElementById('<?php echo $canvasID; ?>');
                    var context = canvas.getContext('2d');

                    // Clear the canvas before drawing
                    context.clearRect(0, 0, canvas.width, canvas.height);

                    // Set background color for canvas
                    context.fillStyle = "#ffffff"; // Background color
                    context.fillRect(0, 0, canvas.width, canvas.height);

                    context.font = '15px Arial';
                    context.fillStyle = '#000'; // Text color
                    context.textAlign = 'center';
                    context.fillText('<?php echo $length; ?>', canvas.width / 2, 15); // Display length at the top center

                    // Draw the height on the right side of the canvas
                    context.save(); // Save the current context state
                    context.translate(canvas.width - 11, canvas.height / 2); // Move context to the right side
                    context.rotate(-Math.PI / 0.5); // Rotate the context to draw the text vertically
                    context.fillText('<?php echo $height; ?>', -5, 0); // Display height along the right side
                    context.restore(); // Restore the original context state

                    if ('<?php echo $description; ?>' === 'Two Panel Slider') {
                        // Function to draw the frame
                        function drawSliderFrame() {
                            context.strokeStyle = '#000';
                            context.lineWidth = 5;
                            context.strokeRect(20, 20, 160, 160); // Outer frame

                            // Draw the vertical line in the middle (divider between two panes)
                            context.beginPath();
                            context.moveTo(20 + 80, 20); // Start at the top-middle of the frame
                            context.lineTo(20 + 80, 20 + 160); // End at the bottom-middle of the frame
                            context.stroke();
                        }

                        // Function to draw the glass panes
                        function drawSliderGlass() {
                            context.fillStyle = "#87CEEB"; // Light blue for glass
                            context.fillRect(23, 23, 74, 154); // Left glass pane
                            context.fillRect(103, 23, 74, 154); // Right glass pane
                        }

                        // Function to draw arrows and 'S' labels
                        function drawSliderArrowsAndLabels() {
                            context.font = '50px Arial'; // Large font size
                            context.textAlign = 'center';
                            context.textBaseline = 'middle';
                            context.fillStyle = '#000'; // Color of the text

                            // Calculate the center points for the 'S' text
                            const centerX1 = 20 + 40; // X coordinate of the first 'S'
                            const centerX2 = 20 + 120; // X coordinate of the second 'S'
                            const centerY = 20 + 160 / 2; // Y coordinate between the two panes

                            // Draw arrows below each 'S'
                            const arrowY = centerY + 5; // Y position of the arrows
                            const arrowLength = 30; // Length of the arrows

                            // Function to draw an arrow below an 'S'
                            function drawArrow(centerX) {
                                context.beginPath();
                                context.lineWidth = 1; // Thinner arrows
                                context.moveTo(centerX - arrowLength / 2, arrowY); // Left point of the arrow
                                context.lineTo(centerX + arrowLength / 2, arrowY); // Right point of the arrow

                                // Left arrowhead
                                context.moveTo(centerX - arrowLength / 2, arrowY);
                                context.lineTo(centerX - arrowLength / 2 + 5, arrowY - 5); // Upper left diagonal
                                context.moveTo(centerX - arrowLength / 2, arrowY);
                                context.lineTo(centerX - arrowLength / 2 + 5, arrowY + 5); // Lower left diagonal

                                // Right arrowhead
                                context.moveTo(centerX + arrowLength / 2, arrowY);
                                context.lineTo(centerX + arrowLength / 2 - 5, arrowY - 5); // Upper right diagonal
                                context.moveTo(centerX + arrowLength / 2, arrowY);
                                context.lineTo(centerX + arrowLength / 2 - 5, arrowY + 5); // Lower right diagonal

                                context.stroke();
                            }

                            // Draw arrows for both panes
                            drawArrow(centerX1);
                            drawArrow(centerX2);
                        }

                        // Function to draw vertical lines
                        function drawVerticalLine1() {
                            context.strokeStyle = '#000'; // Line color (black)
                            context.lineWidth = 7; // Line thickness

                            // Draw a vertical line
                            context.beginPath();
                            context.moveTo(165, 90); // Starting point of the line (x, y)
                            context.lineTo(165, 110); // Ending point of the line (x, y)
                            context.stroke(); // Render the line
                        }

                        function drawVerticalLine2() {
                            context.strokeStyle = '#000'; // Line color (black)
                            context.lineWidth = 7; // Line thickness

                            // Draw a vertical line
                            context.beginPath();
                            context.moveTo(35, 90); // Starting point of the line (x, y)
                            context.lineTo(35, 110); // Ending point of the line (x, y)
                            context.stroke(); // Render the line
                        }

                        // Function to draw the entire slider window
                        function drawTwoPanelSlider() {
                            drawSliderFrame();
                            drawSliderGlass();
                            drawSliderArrowsAndLabels();
                            drawVerticalLine1();
                            drawVerticalLine2();
                        }

                        // Call the function to draw the two-panel slider window
                        drawTwoPanelSlider();
                    } else if ('<?php echo $description; ?>' === 'Awning') {
                        // Draw the frame for the awning window
                        function drawFrame() {
                            context.strokeStyle = "#000";
                            context.lineWidth = 5;
                            context.strokeRect(20, 20, 160, 160); // Adjusted to fit the 200x200 canvas
                        }

                        // Draw the glass panes for the awning window
                        function drawGlass() {
                            context.fillStyle = "#87CEEB"; // Light blue for glass
                            context.fillRect(23, 113, 154, 65); // Bottom glass (adjusted size)
                            context.fillRect(23, 23, 154, 100); // Top glass (adjusted size)
                        }

                        // Draw the hinges for the awning window
                        function drawHinges() {
                            context.strokeStyle = "#000"; // Black color for the hinges
                            context.lineWidth = 3;

                            // Left hinge (adjust to make it like the right)
                            context.beginPath();
                            context.moveTo(20, 25); // Start from the bottom-left of the frame
                            context.lineTo(100, 175); // Extend the line downwards
                            context.lineTo(180, 25); // Connect to the bottom-right of the frame
                            context.stroke();
                        }

                        // Draw the arrows
                        function drawArrow(centerX, centerY, direction) {
                            const arrowLength = 35; // Length of the arrow
                            const arrowWidth = 15; // Width of the arrow

                            context.strokeStyle = "#000"; // Color of the arrow
                            context.lineWidth = 1; // Thinner arrow

                            context.beginPath();

                            if (direction === "down") {
                                // Draw the vertical arrow pointing down
                                context.moveTo(centerX, centerY - arrowLength / 2); // Start of the arrow line
                                context.lineTo(centerX, centerY + arrowLength / 2); // End of the arrow line

                                // Arrowhead
                                context.moveTo(centerX - arrowWidth / 2, centerY + arrowLength / 3); // Left diagonal
                                context.lineTo(centerX, centerY + arrowLength / 4 + 10); // Tip of the arrowhead
                                context.lineTo(centerX + arrowWidth / 2, centerY + arrowLength / 3); // Right diagonal
                            } else if (direction === "up") {
                                // Draw the vertical arrow pointing up
                                context.moveTo(centerX, centerY + arrowLength / 2); // Start of the arrow line
                                context.lineTo(centerX, centerY - arrowLength / 2); // End of the arrow line

                                // Arrowhead
                                context.moveTo(centerX - arrowWidth / 2, centerY - arrowLength / 3); // Left diagonal
                                context.lineTo(centerX, centerY - arrowLength / 4 - 10); // Tip of the arrowhead
                                context.lineTo(centerX + arrowWidth / 2, centerY - arrowLength / 3); // Right diagonal
                            }

                            context.stroke();
                        }

                        // Draw the entire awning window
                        function drawWindow() {
                            drawFrame();
                            drawGlass();
                            drawHinges();
                            
                            const frameLeft = 20;
                            const frameTop = 20;
                            const frameWidth = 160;
                            const frameHeight = 160;

                            // Calculate center of the frame
                            const centerX = frameLeft + frameWidth / 2;
                            const centerY = frameTop + frameHeight / 2;

                            // Draw up arrow
                            drawArrow(centerX, frameTop + 80, "up");
                            // Draw down arrow
                            drawArrow(centerX, frameTop + frameHeight - 80, "down");
                        }

                        // Call the function to draw the awning window
                        drawWindow();
                    } else if ('<?php echo $description; ?>' === 'Fixed') {
                        // Draw the fixed window frame
                        function drawFixedWindowFrame() {
                            context.strokeStyle = '#000';
                            context.lineWidth = 5;
                            context.strokeRect(20, 20, 160, 160); // Outer frame
                        }

                        // Draw the glass of the fixed window
                        function drawFixedWindowGlass() {
                            context.fillStyle = '#87CEEB'; // Light blue for glass
                            context.fillRect(23, 23, 154, 154); // Single glass pane
                        }

                        // Draw the entire fixed window
                        function drawFixedWindow() {
                            drawFixedWindowFrame();
                            drawFixedWindowGlass();
                        }

                        drawFixedWindow();
                    } else if ('<?php echo $description; ?>' === 'Casement') {
                        // Function to draw the frame of the casement window
                        function drawCasementWindowFrame() {
                            context.strokeStyle = '#000';
                            context.lineWidth = 5;
                            context.strokeRect(20, 20, 160, 160); // Outer frame
                        }

                        // Function to draw the glass pane of the casement window
                        function drawCasementWindowGlass() {
                            context.fillStyle = '#87CEEB'; // Light blue for glass
                            context.fillRect(23, 23, 154, 154); // Single pane covering the whole area
                        }

                        // Function to draw a return arrow
                        function drawReturnArrow() {
                            context.strokeStyle = '#000'; // Arrow color
                            context.lineWidth = 1; // Line width for the arrow

                            // Draw the straight part of the arrow (horizontal)
                            context.beginPath();
                            context.moveTo(120, 120); // Starting point (left)
                            context.lineTo(130, 120); // Horizontal line (going right)
                            context.stroke();

                            // Draw the curved part of the arrow (down and to the left)
                            context.beginPath();
                            context.arc(130, 100, 20, 0.5 * Math.PI, 1.5 * Math.PI, true); // Left-turning arc
                            context.stroke();

                            // Draw the arrowhead
                            const arrowHeadSize = 7; // Size of the arrowhead
                            context.beginPath();
                            context.moveTo(120, 120); // Point where the arrowhead starts
                            context.lineTo(120 + arrowHeadSize, 120 - arrowHeadSize); // Right side of the arrowhead
                            context.moveTo(120, 120); // Move back to the starting point for the second line
                            context.lineTo(120 + arrowHeadSize, 120 + arrowHeadSize); // Left side of the arrowhead
                            context.stroke(); // Draw the arrowhead

                            const arrowHeadSize1 = 7; // Size of the arrowhead
                            context.beginPath();
                            context.moveTo(130, 80); // Point where the arrowhead starts
                            context.lineTo(130 + arrowHeadSize1, 80 - arrowHeadSize1); // Right side of the arrowhead
                            context.moveTo(130, 80); // Move back to the starting point for the second line
                            context.lineTo(130 + arrowHeadSize1, 80 + arrowHeadSize1); // Left side of the arrowhead
                            context.stroke(); // Draw the arrowhead
                        }

                            function drawVerticalLine() {
                                context.strokeStyle = '#000';
                                context.lineWidth = 7;

                                context.beginPath();
                                context.moveTo(165, 85);
                                context.lineTo(165, 115);
                                context.stroke();
                            }

                        function drawCasementWindow() {
                            drawCasementWindowFrame();
                            drawCasementWindowGlass();
                            drawReturnArrow();
                            drawVerticalLine();
                        }

                        drawCasementWindow();
                    }
                })();
                <?php
            }
        }
        ?>
    });
</script>

<script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    $(document).ready(function () {

        $('#notifyCustomer').on('click', function() 
        {
            var projectId = $(this).data('project-id');
            var employeeId = $(this).data('employee-id');
            var customerId = $(this).data('customer-id');

            $.ajax({
                url: 'add.php?action=notifyCustomer',
                method: 'POST',
                data: {
                    project_id: projectId,
                    employee_id: employeeId,
                    customer_id: customerId
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
                },
            });
        });
    });

    $(document).on('click', '.status-option', function(e) {
        e.preventDefault();

        var projectId = <?php echo json_encode($project_id); ?>;
        var newStatus = $(this).data('status');
        var employeeId = $(this).data('employee-id');
        var customerId = $(this).data('customer-id');

        // Show confirmation before proceeding with the status update
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to change the status to " + newStatus + "?",
            icon: 'warning',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    url: 'update.php?action=updateProjectStatus',
                    data: {
                        projectId: projectId,
                        newStatus: newStatus,
                        employeeId: employeeId,
                        customerId: customerId
                    },
                    success: function(response) {
                        $('#updateStatus').modal('hide');

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
    });

$(document).ready(function() {
    var hasOngoingInProgress = <?php echo json_encode($hasOngoingInProgress); ?>;
    var isInProgress = <?php echo json_encode($isInProgress); ?>;

    $('.status-option').click(function() {
        const statusName = $(this).data('status');
        const disabled = $(this).is(':disabled');
        const allTasksDone = $(this).data('all-tasks-done');

        if (statusName === 'DELIVERED') 
        {
            if (!allTasksDone) 
            {
                Swal.fire({
                    icon: 'warning',
                    title: 'Cannot Change Status',
                    text: 'All tasks must be completed to mark the project as delivered.',
                    confirmButtonText: 'OK'
                });
                return false;
            }
        } 
        else if (statusName === 'IN PROGRESS') 
        {
            if (hasOngoingInProgress) 
            {
                Swal.fire({
                    icon: 'warning',
                    title: 'Cannot Change Status',
                    text: 'You have a project that is currently "IN PROGRESS".',
                    confirmButtonText: 'OK'
                });
                return false;
            }
        } 
        else if (disabled) 
        {
            Swal.fire({
                icon: 'warning',
                title: 'Cannot Change Status',
                text: 'This status change is not allowed.',
                confirmButtonText: 'OK'
            });
        }
    });

});

$(document).on('click', '#editTaskToNS', function(e)
    {
        e.preventDefault();

        var taskId = $(this).data('task-id');

        $.ajax({
            type: "POST",
            url: "update.php?action=updateTaskToNS",
            data: { taskId:taskId },
            success: function (response) {

                window.location.reload()

            },
        }); 
    });

    
    $(document).on('click', '#editTaskToOG', function(e)
    {
        e.preventDefault();

        var taskId = $(this).data('task-id');

        $.ajax({
            type: "POST",
            url: "update.php?action=updateTaskToOG",
            data: { taskId:taskId },
            success: function (response) {

                window.location.reload()

            },
        }); 
    });

    $(document).on('click', '#editTaskToC', function(e)
    {
        e.preventDefault();

        var taskId = $(this).data('task-id');

        $.ajax({
            type: "POST",
            url: "update.php?action=updateTaskToC",
            data: { taskId:taskId },
            success: function (response) {

                window.location.reload()

            },
        }); 
    });
</script>

<?php
    $completedPercentage = 0;
    $ongoingPercentage = 0;
    $notStartedPercentage = 0;
    // Fetch the count of tasks with different statuses for the project
    $completedTaskResult = mysqli_query($conn, "SELECT COUNT(*) AS total FROM task WHERE project_id = '$project_id' AND status = 'COMPLETED'");
    $ongoingTaskResult = mysqli_query($conn, "SELECT COUNT(*) AS total FROM task WHERE project_id = '$project_id' AND status = 'ONGOING'");
    $notStartedTaskResult = mysqli_query($conn, "SELECT COUNT(*) AS total FROM task WHERE project_id = '$project_id' AND status = 'NOT STARTED'");

    $completedTaskCount = mysqli_fetch_assoc($completedTaskResult)['total'];
    $ongoingTaskCount = mysqli_fetch_assoc($ongoingTaskResult)['total'];
    $notStartedTaskCount = mysqli_fetch_assoc($notStartedTaskResult)['total'];

    // Calculate total tasks
    $totalTasks = $completedTaskCount + $ongoingTaskCount + $notStartedTaskCount;

    // Each task contributes equally to the total progress (100% divided by the total number of tasks)
    if ($totalTasks > 0) {
        $taskContribution = 100 / $totalTasks;
        $completedPercentage = $completedTaskCount * $taskContribution; // Each completed task contributes its full weight
        $ongoingPercentage = $ongoingTaskCount * ($taskContribution / 2); // Each ongoing task contributes 50% of its potential weight
        $notStartedPercentage = $notStartedTaskCount * 0; // Not started tasks contribute 0%
        
        $progressPercentage = $completedPercentage + $ongoingPercentage;
    } else {
        $progressPercentage = 0; // Handle case where there are no tasks
    }
    
    // Prepare data for the chart
    $taskLabels = ['Not Started', 'Ongoing', 'Completed'];
    $taskProgress = [100 - $progressPercentage, $ongoingPercentage, $completedPercentage];
?>

<script>
    var ctx = document.getElementById('taskProgressChart').getContext('2d');
    var taskProgress = <?php echo json_encode($taskProgress); ?>;
    var taskLabels = <?php echo json_encode($taskLabels); ?>;
        
    var myChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: taskLabels,
            datasets: [{
                data: taskProgress,
                backgroundColor: [
                    'rgb(174, 181, 177)', // Not Started
                    'rgb(255, 205, 86)',  // Ongoing
                    'rgb(60, 240, 126)'   // Completed
                ],
                borderColor: [
                    'rgb(174, 181, 177)',
                    'rgb(255, 205, 86)',
                    'rgb(60, 240, 126)'
                ],
                borderWidth: 0
            }]
        },
        options: {
            rotation: -57.5 * Math.PI, // Rotate the chart to start from the left
            responsive: true,
            maintainAspectRatio: false,
            cutout: '65%', // Adjust the inner radius
            plugins: {
                legend: {
                    display: false // Hide the legend
                },
                tooltip: {
                    enabled: false // Disable tooltips
                },
                centerText: {
                    display: true,
                    text: '<?php echo round($progressPercentage); ?>%',
                    color: '#000',
                    fontStyle: 'normal',
                    fontFamily: "'Helvetica Neue', 'Helvetica', 'Arial', sans-serif",
                    sidePadding: 20,
                    minFontSize: 12,
                    maxFontSize: 24,
                    showText: true
                }
            },
            layout: {
                padding: {
                    left: 0,
                    right: 0,
                    top: 0,
                    bottom: 0
                }
            },
            hover: {
                mode: null // Disable hover effect
            }
        },
        plugins: [{
            beforeDraw: function(chart) {
                var chartArea = chart.chartArea;
                var ctx = chart.ctx;
                if (ctx) {
                    var centerConfig = chart.config.options.plugins.centerText;
                    var fontStyle = centerConfig.fontStyle || 'Arial';
                    var txt = centerConfig.text;
                    var color = centerConfig.color || '#000';
                    var sidePadding = centerConfig.sidePadding || 20;
                    var fontSize = centerConfig.maxFontSize;
                    var fontFamily = centerConfig.fontFamily || "'Helvetica Neue', 'Helvetica', 'Arial', sans-serif";
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';
                    var centerX = (chartArea.left + chartArea.right) / 2;
                    var centerY = (chartArea.top + chartArea.bottom) / 2;
                    ctx.font = fontStyle + ' ' + fontSize + 'px ' + fontFamily;
                    ctx.fillStyle = color;
                    ctx.fillText(txt, centerX, centerY); // Center the text
                }
            }
        }]
    });
</script>