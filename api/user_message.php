<?php
$page = 'user_message';

include 'database.php';
include "auth_middleware.php";
checkAuth();
checkRole('employee');

if (!isset($_SESSION['employee_name'])) {
    header('location:index.php');
    exit();
}

$employeeID = mysqli_real_escape_string($conn, $_SESSION['employeeID']);
$userResult = mysqli_query($conn, "SELECT * FROM user WHERE user_id = '$employeeID'");

if (mysqli_num_rows($userResult) > 0) {
    $userData = mysqli_fetch_array($userResult);
}

$defaultImage = 'images/default.jpg';
$defaultImage1 = '../images/default.jpg';

// Fetch the latest message for each project
$query = "SELECT m1.*, u.name AS customer_name, u.image AS customer_image, p.project_name, p.project_id, u.user_id AS customer_id
    FROM message m1
    JOIN user u ON (m1.from = u.user_id OR m1.to = u.user_id)
    JOIN project p ON m1.project_id = p.project_id
    WHERE (m1.from = '$employeeID' AND m1.to = u.user_id) 
       OR (m1.to = '$employeeID' AND m1.from = u.user_id)
    ORDER BY m1.sent_date DESC, m1.message_id DESC
";

$messagesResult = mysqli_query($conn, $query);

$messagesByProject = [];

while ($message = mysqli_fetch_assoc($messagesResult)) 
{
    $projectID = $message['project_id'];
    $customerID = $message['customer_id'];

    if (!isset($messagesByProject[$projectID])) 
    {
        $sentDate = new DateTime($message['sent_date']);
        $today = new DateTime();

        if ($sentDate->format('Y-m-d') === $today->format('Y-m-d')) 
        {
            $formattedDate = $sentDate->format('h:i A');
        } 
        else 
        {
            $formattedDate = $sentDate->format('M d, Y h:i A');
        }        

        $messagesByProject[$projectID] = [
            'project_name' => $message['project_name'],
            'customer_name' => $message['customer_name'],
            'customer_image' => $message['customer_image'],
            'message' => $message,
            'sent_date' => $formattedDate,
            'is_employee_sender' => $message['from'] == $employeeID,
            'employee_id' => $employeeID // Store employee_id
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'include1/header.php'; ?>
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
                <?php include('include1/topbar.php'); ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid p-0">
                    <div class="card border-0 mb-4">
                        <div class="card-body">
                            <?php if (!empty($messagesByProject)): ?>
                                <?php foreach ($messagesByProject as $projectID => $projectData): ?>
                                    <a href="message1.php?employee_id=<?php echo urlencode($employeeID); ?>&project_id=<?php echo urlencode($projectID); ?>&customer_id=<?php echo urlencode($projectData['message']['customer_id']); ?>" style="text-decoration: none;">
                                        <div class="project-section mb-3">
                                            <div class="card mb-2 message" 
                                                data-project-id="<?= htmlspecialchars($projectID); ?>" 
                                                data-customer-id="<?= $employeeID; ?>"
                                                style="<?= !$projectData['is_employee_sender'] && $projectData['message']['is_read'] == 0 ? 'font-weight: bold;' : ''; ?>">
                                                <div class="d-flex align-items-center">
                                                    <div class="d-flex align-items-center justify-content-center p-3">
                                                        <img src="<?= isset($projectData['customer_image']) && !empty($projectData['customer_image']) ? 'images/' . htmlspecialchars($projectData['customer_image']) : $defaultImage; ?>" 
                                                            alt="Customer Image" 
                                                            style="border-radius: 50%; width: 45px; height: 45px;">
                                                    </div>
                                                    <div style="width: 100%;">
                                                        <strong>
                                                            <?= htmlspecialchars($projectData['customer_name']); ?> ( <?= htmlspecialchars($projectData['project_name']); ?> )
                                                        </strong>
                                                        <div class="d-flex justify-content-between">
                                                            <p class="m-0" style="<?= !$projectData['is_employee_sender'] && $projectData['message']['is_read'] == 0 ? 'font-weight: bold;' : ''; ?>">
                                                                <?= htmlspecialchars($projectData['message']['message']); ?> 
                                                            </p>
                                                            <p class="d-flex align-items-center mx-3 my-0" style="font-size: 14px;">
                                                                <?= isset($projectData['sent_date']) ? htmlspecialchars($projectData['sent_date']) : 'Unknown date'; ?>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="card d-flex flex-column align-items-center justify-content-center border-0" style="height: 80vh;">
                                    <i class="fa-solid fa-envelope-open-text" style="font-size: 48px; color: #6c757d; margin-bottom: 20px;"></i>
                                    <h3 class="" style="font-size: 24px; font-weight: 700; letter-spacing: 1px; color: #6c757d;">
                                        No Message Yet
                                    </h3>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <!-- End Page Content -->
            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php include('include1/footer.php'); ?>
            <!-- End of Footer -->
        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->

    <?php
        if (isset($_SESSION['success'])) {
    ?>  
        <script>
            Swal.fire({
                position: "top-end",
                text: '<?= $_SESSION['success']; ?>',
                icon: "success",
                showConfirmButton: false,
                timer: 1500
            });  
        </script>
    <?php
            unset($_SESSION['success']);
        }

        if (isset($_SESSION['error'])) {
    ?>  
        <script>
            Swal.fire({
                position: "top-end",
                text: '<?= $_SESSION['error']; ?>',
                icon: "error",
                showConfirmButton: false,
                timer: 1500
            });  
        </script>
    <?php
            unset($_SESSION['error']);
        }
    ?>
</body>
</html>

<script>
    $(document).ready(function() 
    {
        $('.message').on('click', function() 
        {
            var projectID = $(this).data('project-id');
            var customerID = $(this).data('customer-id'); // Change this to employee_id
            
            $.ajax({
                url: 'update.php?action=isReadMessage', // PHP file to handle status update
                type: 'POST',
                data: {
                    project_id: projectID,
                    customer_id: customerID, // Send employee_id
                    status: 1 // Mark as read
                },
                success: function(response) {
                    // Handle response if needed
                },
            });
        });
    });
</script>