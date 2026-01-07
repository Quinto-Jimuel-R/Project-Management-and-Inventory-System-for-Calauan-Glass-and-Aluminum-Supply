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

    $defaultImage = '../images/default.jpg';
    $defaultImage1 = '../images/default.jpg';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'include2/header.php' ?>
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
                        <div class="card-body pb-0">
                            <?php
                                // Fetch all projects from the database
                                $projectResult = mysqli_query($conn, "SELECT * FROM project WHERE customer_id = $customerID");

                                if (mysqli_num_rows($projectResult) > 0) 
                                {
                                    while ($project = mysqli_fetch_assoc($projectResult)) 
                                    {
                                        ?>
                                            <div class="card shadow-sm mb-3">
                                                <a href="customer_project_details.php?project_id=<?php echo $project['project_id']; ?>" class="card-body d-flex" style="text-decoration: none;">
                                                    <p class="m-0 py-1 fw-bold"><?php echo htmlspecialchars($project['project_name']); ?></p>
                                                </a>
                                            </div>
                                        <?php
                                    }
                                } else {
                                    echo "<p>No projects found.</p>";
                                }
                            ?>
                        </div>
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
            $taskResult = mysqli_query($conn, "SELECT * FROM task WHERE project_id = $projectID");

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