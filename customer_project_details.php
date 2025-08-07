<?php
    $page = 'customer_project';

    include 'database.php';
    include "auth_middleware.php";
    checkAuth();
    checkRole('customer');

    if(!isset($_SESSION['customer_name'])){
        header('location:index.php');
    }

    $customerID = $_SESSION['customerID'];

    $userResult = mysqli_query($conn, "SELECT * FROM user WHERE user_id = $customerID");
    if (mysqli_num_rows($userResult) > 0) {
        $userData = mysqli_fetch_array($userResult);
    }

    if(isset($_GET['project_id']))
    {
        $project_id = $_GET['project_id'];
    }

    $defaultImage = '../images/default.jpg';
    $defaultImage1 = '../images/default.jpg';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'include2/header.php' ?>

    <style>
        .statusProgress{
            padding: 0 200px;
            margin-top: 70px;
        }

        @media (max-width: 767px) 
        {
            .statusProgress{
                padding: 0 30px;
                margin-top: 50px;
            }
            
            .stats {
                font-size: 12px !important;
            }
            
            .amt{
                font-size: 12px !important;
            }
        }
        
        .stats {
            font-size: 14px;
        }
    </style>
</head>
<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        <?php include('include2/sidebar.php'); ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content" class="bg-white">
                <!-- Topbar -->
                <?php include('include2/topbar.php'); ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid p-0">
                    <div class="card border-0 mb-4" style="background-color: transparent;">

                    <?php
                        $projResult = mysqli_query($conn, "SELECT project.*, status.*, user.*
                                                            FROM project
                                                            INNER JOIN status ON project.status = status.status_name
                                                            INNER JOIN user ON project.customer_id = user.user_id
                                                            WHERE project.customer_id = '$customerID' AND project_id = '$project_id'
                                                            ORDER BY project.project_id DESC");

                            if(mysqli_num_rows($projResult) > 0) 
                            {
                                while($row = mysqli_fetch_assoc($projResult)) 
                                        {
                                            $project_status = $row["status"];
                                            $status_color = $row["color"];
                                            $projectID = $row['project_id'];
                                            $projectLocation = $row['location'];
                                            $projectCustomer = $row['name'];
                                            $projectContact = $row['phone_number'];
                                            $projectName = $row['project_name'];

                                            // Set button classes and progress width based on project status
                                            $button_class = '';
                                            $button_class1 = '';
                                            $button_class2 = '';
                                            $button_class3 = '';
                                            $progress_width = '';

                                        if ($project_status === "IN PROGRESS") {
                                            $button_class = "btn-primary";
                                            $button_class1 = "btn-secondary";
                                            $button_class2 = "btn-secondary";
                                            $button_class3 = "btn-secondary";
                                            $progress_width = "0%";
                                        } elseif ($project_status === "DELIVERED") {
                                            $button_class = "btn-primary";
                                            $button_class1 = "btn-primary";
                                            $button_class2 = "btn-secondary";
                                            $button_class3 = "btn-secondary";
                                            $progress_width = "33.33%"; 
                                        } elseif ($project_status === "INSTALLATION") {
                                            $button_class = "btn-primary";
                                            $button_class1 = "btn-primary";
                                            $button_class2 = "btn-primary";
                                            $button_class3 = "btn-secondary";
                                            $progress_width = "66.33%";
                                        } elseif ($project_status === "COMPLETED") {
                                            $button_class = "btn-primary";
                                            $button_class1 = "btn-primary";
                                            $button_class2 = "btn-primary";
                                            $button_class3 = "btn-primary";
                                            $progress_width = "100%";
                                        } else {
                                            $button_class = "btn-secondary";
                                            $button_class1 = "btn-secondary";
                                            $button_class2 = "btn-secondary";
                                            $button_class3 = "btn-secondary";
                                            $progress_width = "0%";
                                        }

                        ?>
                            <div class="card-body pb-0">

                                <a href="customer_project.php" class="btn btn-primary"> Back </a>
                                <div class="text-center">
                                    <h3 class="fw-bold pt-4 m-0"><?php echo $projectName ?></h3>
                                </div>

                                <div class="statusProgress">
                                    <div class="position-relative m-4">
                                        <div class="progress" role="progressbar" aria-label="Progress" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="height: 5px;">
                                            <div class="progress-bar" style="width: <?php echo $progress_width; ?>"></div>
                                        </div>
                                        <a type="button" class="position-absolute top-0 start-0 translate-middle d-flex align-items-center justify-content-center btn btn-sm rounded-pill <?php echo $button_class; ?>" style="width: 2rem; height:2rem;">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <i class="fas fa-fw fa-person-walking"></i>
                                            </div>
                                        </a>
                                        <a type="button" class="position-absolute top-0 translate-middle d-flex align-items-center justify-content-center btn btn-sm rounded-pill <?php echo $button_class1; ?>" style="width: 2rem; height:2rem; margin-left: 33.33%;">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <i class="fas fa-fw fa-truck-fast"></i>
                                            </div>
                                        </a>
                                        <a type="button" class="position-absolute top-0 translate-middle d-flex align-items-center justify-content-center btn btn-sm rounded-pill <?php echo $button_class2; ?>" style="width: 2rem; height:2rem; margin-left: 66.33%;">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <i class="fas fa-fw fa-screwdriver-wrench"></i>
                                            </div>
                                        </a>
                                        <a type="button" class="position-absolute top-0 start-100 translate-middle d-flex align-items-center justify-content-center btn btn-sm rounded-pill <?php echo $button_class3; ?>" style="width: 2rem; height:2rem;">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <i class="fas fa-fw fa-check"></i>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="position-relative m-4 pb-4 stats">
                                        <div class="position-absolute top-0 start-0 translate-middle">
                                            <div class="d-flex align-items-center justify-content-center" style="color: #213040; font-weight: 700;">
                                                In Progress
                                            </div>
                                        </div>
                                        <div class="position-absolute top-0 translate-middle" style="margin-left: 33.33%;">
                                            <div class="d-flex align-items-center justify-content-center" style="color: #213040; font-weight: 700;">
                                                Delivered
                                            </div>
                                        </div>
                                        <div class="position-absolute top-0 translate-middle" style="margin-left: 66.33%;">
                                            <div class="d-flex align-items-center justify-content-center" style="color: #213040; font-weight: 700;">
                                                Installation
                                            </div>
                                        </div>
                                        <div class="position-absolute top-0 start-100 translate-middle">
                                            <div class="d-flex align-items-center justify-content-center" style="color: #213040; font-weight: 700;">
                                                Completed
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="d-flex px-3 mt-4 row" style="font-size: 16px;">
                                    <div class="col-lg-7 pt-3 pb-0">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="" style="font-weight: 800;">
                                                    Project
                                                </div>

                                                <div class="mt-2">
                                                    <?php 
                                                        $taskResult = mysqli_query($conn, "SELECT * FROM task WHERE project_id = $projectID");
                                                                    
                                                        $totalAmount = 0;

                                                        if(mysqli_num_rows($taskResult) > 0)
                                                        {
                                                            while($taskData = mysqli_fetch_assoc($taskResult))
                                                            {
                                                                $totalAmount += $taskData['total_price'];
                                                                $canvasID = 'itemCanvas_' . $projectID . '_' . $taskData['task_id'];
                                                            ?>
                                                                <div class="d-flex flex-column flex-md-row justify-content-around align-items-center gap-md-3 gap-0 mb-3">
                                                                    <div class="text-center">
                                                                        <div style="font-weight: 600;">
                                                                            <?php echo $taskData['description']; ?>
                                                                        </div>
                                                                        <canvas id="<?php echo $canvasID; ?>" width="210" height="200"></canvas>
                                                                    </div>
                                                                    <div class="d-flex justify-content-center align-items-center">
                                                                        <span class="fs-5 fw-bold">
                                                                            ₱ <?php echo number_format($taskData['total_price'], 2); ?>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            <?php
                                                            }
                                                        }

                                                        $paymentResult = mysqli_query($conn, "SELECT down_payment, payment_status FROM payment WHERE project_id = $projectID");
                                                        $downpayment = 0;
                                                        $paymentStatus = '';

                                                        if($paymentResult && mysqli_num_rows($paymentResult) > 0)
                                                        {
                                                            $paymentData = mysqli_fetch_assoc($paymentResult);
                                                            $downpayment = $paymentData['down_payment'] ?? 0;
                                                            $paymentStatus = $paymentData['payment_status'] ?? '';
                                                        }

                                                        $balance = $totalAmount - $downpayment;
                                                    ?>
                                                </div>

                                                <?php if(strtolower($paymentStatus) === 'paid'): ?>
                                                    <div class="text-center mt-2 fw-bold text-success">This project is paid</div>
                                                <?php else: ?>
                                                    <div class="d-flex fw-bold amt">
                                                        <div class="col-4 text-center">Total Amount: ₱ <?php echo number_format($totalAmount, 2); ?></div>
                                                        <div class="col-4 text-center">Down Payment: ₱ <?php echo number_format($downpayment, 2); ?></div>
                                                        <div class="col-4 text-center">Balance: ₱ <?php echo number_format($balance, 2); ?></div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-5 p-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="" style="font-weight: 800;">
                                                    Delivery Information
                                                </div>
                                                <div class="d-flex align-items-center mt-3 ms-1">
                                                    <i class="fa-solid fa-location-dot fs-4"></i>
                                                <div class="ms-2">
                                                    <div class="d-flex">
                                                        <div class="me-1" style="font-weight: 600;">
                                                            <?php echo $projectCustomer ?>
                                                        </div>
                                                            <?php $formattedContact = '(+63) ' . ltrim($projectContact, '0'); echo $formattedContact; ?>
                                                        </div>
                                                    <div>
                                                        <?php echo $projectLocation ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card mt-3">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center ms-1">
                                                <i class="fa-solid fa-message"></i>
                                                    <div class="ms-2">
                                                        <?php
                                                            $employeeResult = mysqli_query($conn, "SELECT u.*, p.*
                                                                                                    FROM user u
                                                                                                    JOIN project p ON p.employee_name = u.name
                                                                                                    WHERE p.project_id = $projectID");

                                                            if (mysqli_num_rows($employeeResult) > 0) 
                                                            {
                                                                $row = mysqli_fetch_assoc($employeeResult);
                                                                $employee_name = htmlspecialchars($row['name']);
                                                                $employee_id = htmlspecialchars($row['user_id']);
                                                                ?>
                                                                    <a href="message.php?employee_id=<?php echo urlencode($employee_id); ?>&project_id=<?php echo urlencode($projectID); ?>&customer_id=<?php echo urlencode($customerID); ?>" class="ms-2" style="text-decoration: none;">
                                                                        Contact Employee
                                                                    </a>
                                                                <?php
                                                            } else {
                                                                echo "No employee found for this project.";
                                                            }
                                                        ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                                        <div class="card mt-3">
                                                            <div class="card-body">
                                                                <div class="" style="font-weight: 800;">
                                                                    History
                                                                </div>
                                                                
                                                                <div class="card-body">
                                                                    <?php 
                                                                            $historyProject = mysqli_query($conn, "SELECT * FROM project_history WHERE project_id = $projectID");

                                                                            if(mysqli_num_rows($historyProject) > 0) 
                                                                            {
                                                                                while($historyData = mysqli_fetch_assoc($historyProject)) 
                                                                                {
                                                                                    $status = $historyData['status'];

                                                                                $dateUpdated = new DateTime($historyData['date_updated']);
                                                                                $formattedDate = $dateUpdated->format('m/d/Y');

                                                                                $timeUpdated = new DateTime($historyData['time_updated']);
                                                                                $formattedTime = $timeUpdated->format('h:i A');

                                                                                if ($status === 'IN PROGRESS') {
                                                                            ?>
                                                                                <div class="d-flex gap-2 mb-3">
                                                                                    <div class="mt-1" style="font-size: 10px;">
                                                                                        <i class="fa-solid fa-circle"></i>
                                                                                    </div>
                                                                                    <div><?php echo $historyData['day_updated'] ?> - <?php echo "$formattedDate" ?></div>
                                                                                    <div class="d-flex">
                                                                                        <div class="d-flex flex-column">
                                                                                            <div class="fw-bold"><?php echo "$status" ?></div>
                                                                                            <div style="font-size: 13px;">
                                                                                                Time: <?php echo "$formattedTime" ?>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <?php
                                                                            } elseif ($status === 'DELIVERED') {
                                                                                ?>
                                                                                <div class="d-flex gap-2 mb-3">
                                                                                    <div class="mt-1" style="font-size: 10px;">
                                                                                        <i class="fa-solid fa-circle"></i>
                                                                                    </div>
                                                                                    <div><?php echo $historyData['day_updated'] ?> - <?php echo "$formattedDate" ?></div>
                                                                                    <div class="d-flex">
                                                                                        <div class="d-flex flex-column">
                                                                                            <div class="fw-bold"><?php echo "$status" ?></div>
                                                                                            <div style="font-size: 13px;">
                                                                                                Time: <?php echo "$formattedTime" ?>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <?php
                                                                            } elseif ($status === 'INSTALLATION') {
                                                                                ?>
                                                                                <div class="d-flex gap-2 mb-3">
                                                                                    <div class="mt-1" style="font-size: 10px;">
                                                                                        <i class="fa-solid fa-circle"></i>
                                                                                    </div>
                                                                                    <div><?php echo $historyData['day_updated'] ?> - <?php echo "$formattedDate" ?></div>
                                                                                    <div class="d-flex">
                                                                                        <div class="d-flex flex-column">
                                                                                            <div class="fw-bold"><?php echo "$status" ?></div>
                                                                                            <div style="font-size: 13px;">
                                                                                                Time: <?php echo "$formattedTime" ?>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <?php
                                                                            } elseif ($status === 'COMPLETED') {
                                                                                ?>
                                                                                <div class="d-flex gap-2 mb-3">
                                                                                    <div class="mt-1" style="font-size: 10px;">
                                                                                        <i class="fa-solid fa-circle"></i>
                                                                                    </div>
                                                                                    <div><?php echo $historyData['day_updated'] ?> - <?php echo "$formattedDate" ?></div>
                                                                                    <div class="d-flex">
                                                                                        <div class="d-flex flex-column">
                                                                                            <div class="fw-bold"><?php echo "$status" ?></div>
                                                                                            <div style="font-size: 13px;">
                                                                                                Time: <?php echo "$formattedTime" ?>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <?php
                                                                            }
                                                                        }
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                            </div>
                                </div>

                            </div>
                        <?php
                            }
                        } 
                    ?>
                    </div>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php include('include2/footer.php'); ?>
            <!-- End of Footer -->
        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->
</body>
</html>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        <?php 
            $taskResult = mysqli_query($conn, "SELECT * FROM task WHERE project_id = $project_id");

            if(mysqli_num_rows($taskResult) > 0) 
            {
                while($taskData = mysqli_fetch_assoc($taskResult)) 
                {
                    $canvasID = 'itemCanvas_' . $projectID . '_' . $taskData['task_id'];
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

                        // Draw the length at the top of the canvas
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
                            function drawSliderFrame() {
                                context.strokeStyle = '#000';
                                context.lineWidth = 5;
                                context.strokeRect(20, 20, 160, 160); // Outer frame
                                context.beginPath();
                                context.moveTo(100, 20);
                                context.lineTo(100, 180);
                                context.stroke();
                            }

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

                            function drawTwoPanelSlider() {
                                drawSliderFrame();
                                drawSliderGlass();
                                drawSliderArrowsAndLabels();
                                drawVerticalLine1();
                                drawVerticalLine2();
                            }

                            drawTwoPanelSlider();
                        } 
                        else if ('<?php echo $description; ?>' === 'Awning') {
                            function drawFrame() {
                                context.strokeStyle = "#000";
                                context.lineWidth = 5;
                                context.strokeRect(20, 20, 160, 160); 
                            }

                            function drawGlass() {
                                context.fillStyle = "#87CEEB"; 
                                context.fillRect(23, 113, 154, 65); 
                                context.fillRect(23, 23, 154, 100); 
                            }

                            function drawHinges() {
                                context.strokeStyle = "#000"; 
                                context.lineWidth = 3;
                                context.beginPath();
                                context.moveTo(20, 25);
                                context.lineTo(100, 175);
                                context.lineTo(180, 25);
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

                            function drawAwningWindow() {
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

                            drawAwningWindow();
                        } 
                        else if ('<?php echo $description; ?>' === 'Fixed') {
                            function drawFixedWindow() {
                                context.strokeStyle = '#000';
                                context.lineWidth = 5;
                                context.strokeRect(20, 20, 160, 160); 
                                context.fillStyle = '#87CEEB'; 
                                context.fillRect(23, 23, 154, 154); 
                            }

                            drawFixedWindow();
                        } 
                        else if ('<?php echo $description; ?>' === 'Casement') {
                            function drawCasementWindowFrame() {
                                context.strokeStyle = '#000';
                                context.lineWidth = 5;
                                context.strokeRect(20, 20, 160, 160); 
                            }

                            function drawCasementWindowGlass() {
                                context.fillStyle = '#87CEEB'; 
                                context.fillRect(23, 23, 154, 154);
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

                            // Function to draw a vertical line
                            function drawVerticalLine() {
                                        context.strokeStyle = '#000'; // Line color (black)
                                        context.lineWidth = 7; // Line thickness

                                        // Draw a vertical line
                                        context.beginPath();
                                        context.moveTo(165, 85); // Starting point of the line (x, y)
                                        context.lineTo(165, 115); // Ending point of the line (x, y)
                                        context.stroke(); // Render the line
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