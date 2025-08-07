<?php

    $page = 'user_dashboard';

    include 'database.php';

    include "auth_middleware.php";
    checkAuth();
    checkRole('employee');
    
    if(!isset($_SESSION['employee_name'])){
       header('location:login.php');
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
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>

    <style>
        #calendar {
            max-width: 900px;
            margin: 0 auto;
        }
        .fc-toolbar .fc-button {
            background-color: #E57373; /* Button background color */
            border: none; /* Remove border */
            color: white; /* Text color */
            padding: 5px 10px; /* Padding */
            border-radius: 5px; /* Rounded corners */
        }

        .fc-toolbar .fc-button:hover {
            background-color: #F44336; /* Darken background on hover */
        }

        .fc-event {
            padding: 5px; /* Adjust the padding as needed */
            font-weight: 700;
        }

        .datetime-display {
            font-size: 1.2rem;
            font-weight: bold;
            color: #007bff;
            padding: 10px;
            border: 1px solid #007bff;
            border-radius: 5px;
            background-color: #f8f9fa;
            text-align: center;
        }
        #timeDisplay {
            font-size: 1.5rem;
        }
        #dateDisplay {
            font-size: 1rem;
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
                            <div class="col-lg-12">
                                <div class="card border-0 mb-4">
                                    <div class="card-body d-flex justify-content-between p-0">
                                        <div class="d-flex align-items-center rounded-3 bordered gap-1 p-3">
                                        </div>
                                        <div class="d-flex justify-content-end">
                                            <div id="dateTimeDisplay" class="datetime-display">
                                                <div id="timeDisplay"></div>
                                                <div id="dateDisplay"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="container-fluid pt-0 px-3 pb-3">
                                <div class="row">
                                    <!-- Left Column -->
                                    <div class="col-lg-6 col-md-12 mb-3">
                                        <!-- Card 1 -->

                                        <div class="card mb-3">
                                            <div class="card-body">
                                                <div class="fs-6 fw-bold mb-1">Deadline This Week:</div>
                                                <div>
                                                    <?php
                                                    // Query to fetch projects with deadlines this week, excluding completed projects
                                                    $query = "
                                                        SELECT *
                                                        FROM project
                                                        WHERE status != 'COMPLETED'
                                                        AND end_date >= DATE_ADD(CURDATE(), INTERVAL 1 - DAYOFWEEK(CURDATE()) DAY)
                                                        AND end_date <= DATE_ADD(CURDATE(), INTERVAL 7 - DAYOFWEEK(CURDATE()) DAY) AND employee_name = '$employeeName'";
                                                    
                                                    $result = $conn->query($query);

                                                    // Check if there are any projects with deadlines this week
                                                    if ($result->num_rows > 0) {
                                                        echo "<ul>";
                                                        while ($row = $result->fetch_assoc()) {
                                                            // Format the date
                                                            $formattedDate = date("F d, Y", strtotime($row['end_date']));
                                                            echo "<li>{$row['project_name']} - {$formattedDate}</li>";
                                                        }
                                                        echo "</ul>";
                                                    } else {
                                                        echo "<p>No projects deadlines this week.</p>";
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card mb-3">
                                            <div class="card-body">
                                                <div class="fs-6 fw-bold mb-1">Deadline Next Week:</div>
                                                <div>
                                                    <?php
                                                    $query = "
                                                        SELECT * 
                                                        FROM project
                                                        WHERE end_date >= DATE_ADD(CURDATE(), INTERVAL 8 - DAYOFWEEK(CURDATE()) DAY) 
                                                        AND end_date <= DATE_ADD(CURDATE(), INTERVAL 14 - DAYOFWEEK(CURDATE()) DAY) AND employee_name = '$employeeName'";
                                                    
                                                    $result = $conn->query($query);

                                                    if ($result->num_rows > 0) {
                                                        while ($row = $result->fetch_assoc()) {
                                                            $formattedDate = date("F d, Y", strtotime($row['end_date']));
                                                            ?>
                                                            <ul>
                                                                <li><?php echo $row['project_name'] ?> - <?php echo $formattedDate ?></li>
                                                            </ul>
                                                            <?php
                                                        }
                                                    } else {
                                                        echo "<p>No projects deadlines next week.</p>";
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Card 2 -->
                                        <div class="card mb-3">
                                            <div class="card-body">
                                                <div class="fs-6 fw-bold mb-1">Completed Projects This Month:</div>
                                                <!-- Logic for Completed Projects -->
                                                <?php
                                                // Query to fetch completed projects in the current month
                                                $query = "
                                                    SELECT *
                                                    FROM project
                                                    WHERE status = 'COMPLETED'
                                                    AND MONTH(end_date) = MONTH(CURDATE())
                                                    AND YEAR(end_date) = YEAR(CURDATE()) AND employee_name = '$employeeName'";

                                                $result = $conn->query($query);

                                                // Check if there are any completed projects
                                                if ($result->num_rows > 0) {
                                                    echo "<ul>";
                                                    while ($row = $result->fetch_assoc()) {
                                                        echo "<li>{$row['project_name']}</li>";
                                                    }
                                                    echo "</ul>";
                                                } else {
                                                    echo "<p>No completed projects this month.</p>";
                                                }
                                                ?>
                                            </div>
                                        </div>

                                        <div class="card rounded">
                                            <div class="card-header text-center border-bottom-0 bg-white fw-bold pb-0" style="font-size: 30px;">
                                                Calendar
                                            </div>
                                            <div class="card-body p-0 py-3">
                                                <div id='calendar'></div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Right Column -->
                                    <div class="col-lg-6 col-md-12">
                                        <div class="card p-3">
                                            <?php 
                                            $sql = "SELECT 
                                                        project.*, status.color 
                                                    FROM 
                                                        project
                                                    INNER JOIN 
                                                        status 
                                                    ON 
                                                        project.status = status.status_name
                                                    WHERE 
                                                        project.status = 'IN PROGRESS' 
                                                        AND project.employee_name = '$employeeName'";
                                            $result = $conn->query($sql);
                                            ?>

                                            <?php if ($result->num_rows > 0): ?>
                                                <?php while ($row = $result->fetch_assoc()): 
                                                    $projectID = $row['project_id'];
                                                ?>
                                                <div class="mb-3">
                                                    <div class="mb-2"><strong>Project Name:</strong> <?= $row['project_name'] ?></div>

                                                    <?php
                                                    $endDate = new DateTime($row['end_date']);
                                                    $formattedDate = $endDate->format('F j, Y');
                                                    $currentDate = new DateTime();
                                                    $interval = $currentDate->diff($endDate);
                                                    $daysDifference = (int)$interval->format('%r%a');
                                                    ?>

                                                    <div class="d-flex align-items-center mb-2">
                                                        <p class="m-0"><strong>Deadline:</strong></p>
                                                        <div class="d-flex align-items-center rounded-1 px-2 py-1 ms-2" 
                                                            style="font-size: 11px; width: fit-content; font-weight: 700;
                                                                <?= $daysDifference <= 0 ? 'background-color: rgba(255, 0, 0, 0.2); color: red;' : 
                                                                ($daysDifference <= 7 ? 'background-color: rgba(255, 255, 0, 0.2); color: orange;' : 
                                                                'background-color: rgba(0, 0, 255, 0.2); color: blue;') ?>">
                                                            <?= $daysDifference == 0 ? 'Today' : ($daysDifference < 0 ? $formattedDate : "$daysDifference day" . ($daysDifference == 1 ? '' : 's') . " left") ?>
                                                        </div>
                                                    </div>

                                                    <div class="d-flex">Status:
                                                                                    <div class="d-flex align-items-center rounded-1 fw-bold px-2 py-1 ms-2" style="background-color: <?php echo htmlspecialchars(string: $row['color']); ?>; font-size: 11px; width: fit-content; font-weight: 700;">
                                                                                    <?php echo htmlspecialchars($row['status']); ?>
                                                                                </div></div>
                                                                            </div>

                                                    <div class="text-center fs-5"><strong>Tasks</strong></div>
                                                    <?php 
                                                    $checkOngoingQuery = "SELECT COUNT(*) AS ongoing_count FROM task WHERE project_id = $projectID AND status = 'ONGOING'";
                                                    $ongoingResult = $conn->query($checkOngoingQuery);
                                                    $ongoingCount = $ongoingResult->fetch_assoc()['ongoing_count'];

                                                    $orderBy = $ongoingCount > 0 
                                                        ? "CASE 
                                                                WHEN status = 'ONGOING' THEN 1
                                                                WHEN status = 'NOT STARTED' THEN 2
                                                                WHEN status = 'COMPLETED' THEN 3
                                                        END"
                                                        : "CASE 
                                                                WHEN status = 'NOT STARTED' THEN 1
                                                                WHEN status = 'COMPLETED' THEN 2
                                                        END";

                                                    $selectTask = "SELECT * FROM task WHERE project_id = $projectID ORDER BY $orderBy";
                                                    $taskResult = $conn->query($selectTask);

                                                    if ($taskResult->num_rows > 0): ?>
                                                        <div class="d-flex flex-wrap justify-content-center">
                                                        <?php while ($taskData = $taskResult->fetch_assoc()): ?>
                                                                                            <div class="task-item m-2 p-3 d-flex flex-column align-items-center" style="border-radius: 8px;">
                                                                                                <div class="text-center" style="font-weight: 600;">
                                                                                                    <?php 
                                                                                                        $canvasID = 'itemCanvas_' . $taskData['task_id'];

                                                                                                        if($taskData['status'] == 'COMPLETED'): ?>
                                                                                                            <i id="editTaskToNS" class="fa-solid fa-fw fa-square-check <?php echo $isInProgress ? '' : 'disabled-icon'; ?>" data-task-id="<?php echo $taskData['task_id']; ?>" style="color: #10f500;"></i>
                                                                                                        <?php elseif($taskData['status'] == 'ONGOING'): ?>
                                                                                                            <i id="editTaskToC" class="fa-solid fa-fw fa-arrows-spin <?php echo $isInProgress ? '' : 'disabled-icon'; ?>" data-task-id="<?php echo $taskData['task_id']; ?>" style="color: #f7a531;"></i>
                                                                                                        <?php else: ?>
                                                                                                            <i id="editTaskToOG" class="fa-solid fa-fw fa-square <?php echo $isInProgress ? '' : 'disabled-icon'; ?>" data-task-id="<?php echo $taskData['task_id']; ?>" style="color: #a8a7a7;"></i>
                                                                                                    <?php endif; ?>

                                                                                                    <div><?php echo $taskData['description']; ?></div>
                                                                                                </div>
                                                                                                <!-- Unique canvas ID -->
                                                                                                <canvas id="<?php echo $canvasID; ?>" width="210" height="210"></canvas>
                                                                                            </div>
                                                                                        <?php endwhile; ?>
                                                        </div>
                                                    <?php else: ?>
                                                        <p class="text-muted">No tasks</p>
                                                    <?php endif; ?>
                                                </div>
                                                <?php endwhile; ?>
                                            <?php else: ?>
                                                <div class="d-flex justify-content-center align-items-center">
                                                    <p class="text-muted fw-bold m-0">NO IN PROGRESS PROJECT</p>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="container-fluid">
                                <div class="col-lg-12 mt-3">
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <!-- End of Main Content -->
            <!-- Footer -->
            <?php include('include1/footer.php'); ?>
            <!-- End of Footer -->
            </div>
        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->

</body>

</html>

<style>
    #calendar {
        max-width: 95%; /* Adjusts the width to be full-screen */
        height: 750px; /* Set the height to be bigger */
        margin: 0 auto; /* Centers the calendar */
    }

    .fc-toolbar .fc-button {
        background-color: rgb(237, 204, 161); /* Button background color */
        border: none; /* Remove border */
        color: white; /* Text color */
        padding: 5px 10px; /* Padding */
        border-radius: 5px; /* Rounded corners */
    }

    .fc-toolbar .fc-button:hover {
        background-color: rgb(237, 191, 130); /* Darken background on hover */
    }

    .fc-event {
        padding: 5px; /* Adjust the padding as needed */
        font-weight: 700;
    }

    .datetime-display {
        font-size: 1.2rem;
        font-weight: bold;
        color: #007bff;
        padding: 10px;
        border: 1px solid #007bff;
        border-radius: 5px;
        background-color: #f8f9fa;
        text-align: center;
    }

    #timeDisplay {
        font-size: 1.5rem;
    }

    #dateDisplay {
        font-size: 1rem;
    }
</style>

<script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,dayGridWeek,dayGridDay'
                },
                eventColor: 'rgb(237, 204, 161)',
                events: [
                    <?php
                        $events = mysqli_query($conn, "SELECT * FROM project WHERE employee_name = '$employeeName' AND status <> 'COMPLETED'");
                        if($events) 
                        {
                            while($eventData = mysqli_fetch_assoc($events)) 
                            {
                                echo "{
                                    title: '" . $eventData['project_name'] . "',
                                    start: '" . $eventData['start_date'] . "',
                                    end: '" . $eventData['end_date'] . "'
                                },";
                            }
                        }
                    ?>
                ]
            });
            calendar.render();
        });
</script>

<script>
    function updateDateTime() 
    {
        const now = new Date();
            
        // Define options for time and date
        const timeOptions = { hour: '2-digit', minute: '2-digit', second: '2-digit' };
        const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            
        // Get formatted time and date
        const formattedTime = now.toLocaleTimeString('en-US', timeOptions);
        const formattedDate = now.toLocaleDateString('en-US', dateOptions);
            
        // Update the time and date display
        $('#timeDisplay').text(formattedTime);
        $('#dateDisplay').text(formattedDate);
    }

    setInterval(updateDateTime, 1000);
    updateDateTime();
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        <?php 
        // Ensure tasks are drawn on canvas after DOM is loaded
        $taskResult = mysqli_query($conn, "SELECT * FROM task WHERE project_id = '$projectID'");

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