<?php

    $page = 'cashier_dashboard';

    include 'database.php';

    include "auth_middleware.php";
    checkAuth();
    checkRole('cashier');
    
    if(!isset($_SESSION['cashier_name'])){
       header('location:index.php');
    }

    $cashierID = $_SESSION['cashierID'];
    
    $userResult = mysqli_query($conn, "SELECT * FROM user WHERE user_id = $cashierID");
    if(mysqli_num_rows($userResult) > 0) 
    {
        $userData = mysqli_fetch_array($userResult);
    }

    $defaultImage = '../images/default.jpg';
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <?php include 'include3/header.php' ?>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
            <?php include('include3/sidebar.php'); ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content" class="bg-white">

                <!-- Topbar -->
                    <?php include('include3/topbar.php'); ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid p-0">

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="ProjectTaskPaymentTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th class="text-center d-none">#</th>
                                    <th>Project Name</th>
                                    <th>Total Payment</th>
                                    <th>Down Payment</th>
                                    <th>Balance</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            
                            <tbody>

                            <?php
    // Fetch all projects with total_price greater than zero
    $projectResult = mysqli_query($conn, "
        SELECT p.project_id, p.project_name
        FROM project p
        INNER JOIN task t ON p.project_id = t.project_id
        GROUP BY p.project_id, p.project_name
        HAVING SUM(t.total_price) > 0
    ");
    
    $counter = 1;

    if (mysqli_num_rows($projectResult) > 0) 
    {
        while ($row = mysqli_fetch_array($projectResult)) 
        {
            $projectID = $row['project_id'];

            // Fetch total price for tasks associated with the current project_id
            $taskResult = mysqli_query($conn, "
                SELECT SUM(total_price) AS total_price_sum 
                FROM task 
                WHERE project_id = $projectID
            ");
            $taskRow = mysqli_fetch_array($taskResult);
            $totalPrice = $taskRow ? ($taskRow['total_price_sum'] ? number_format($taskRow['total_price_sum'], 2) : '0.00') : '0.00';

            // Fetch downpayment and payment_status from payments table
            $paymentResult = mysqli_query($conn, "
                SELECT down_payment, payment_status 
                FROM payment 
                WHERE project_id = $projectID
                LIMIT 1
            ");
            $paymentRow = mysqli_fetch_array($paymentResult);
            $downPayment = $paymentRow ? ($paymentRow['down_payment'] ? number_format($paymentRow['down_payment'], 2) : '0.00') : '0.00';
            $paymentStatus = $paymentRow ? $paymentRow['payment_status'] : ''; // Default value if no status is found

            // Calculate the difference between total_price and down_payment
            $downPaymentFloat = (float)str_replace(['₱', ','], '', $downPayment);
            $totalPriceFloat = (float)str_replace(['₱', ','], '', $totalPrice);
            $difference = number_format($totalPriceFloat - $downPaymentFloat, 2);

            // Set the text color based on payment status (green if 'PAID', red if not)
            $statusColor = ($paymentStatus === 'PAID') ? '#10f500' : '#fc2f21'; // Set color based on status
            $displayStatus = ($paymentStatus === 'PAID') ? 'PAID' : 'NOT PAID'; // Display 'NOT PAID' if not paid
?>
            <tr>
                <td class="text-center d-none"><?php echo $counter++; ?></td>
                <td><?php echo $row['project_name']; ?></td>
                <td>₱ <?php echo $totalPrice; ?></td>
                <td>₱ <?php echo $downPayment; ?></td>
                <td class="d-flex justify-content-between">
                    <?php if ($paymentStatus !== 'PAID') { ?>
                        ₱ <?php echo $difference; ?>
                        <div class="d-flex align-items-center justify-content-center">
                            <i id="updatePaymentStatus" class="fa-solid fa-circle-check text-warning" data-project-id=<?php echo $projectID ?> style="cursor: pointer;"></i>
                        </div>
                    <?php } else { ?>
                        ₱ <?php echo $difference; ?>
                    <?php } ?>
                </td>
                <td>
                    <div class="p-1 rounded text-center" style="background-color: <?php echo $statusColor ?>; font-size: 13px; font-weight: 600; opacity: 90%;">
                        <?php echo $displayStatus; ?>
                    </div>
                </td>
            </tr>
<?php
        }
    } else {
        // Display a message if no data is found
        echo "<tr><td colspan='7' class='text-center'>No data found for the selected project.</td></tr>";
    }
?>

                            </tbody>
                        </table>
                    </div>
                </div>

                </div>
            <!-- End of Main Content -->

            <!-- Footer -->
                <?php include('include3/footer.php'); ?>
            <!-- End of Footer -->
        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->


</body>

</html>

<script>
    $(document).ready(function () {
        $('#updatePaymentStatus').on('click', function (){  

            var projectId = $(this).data('project-id');

            // Send an AJAX POST request
            $.ajax({
                url: 'update.php?action=paymentStatus', // PHP file that handles the update
                type: 'POST',
                data: { project_id: projectId },
                success: function (response) {

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

    $(document).ready(function() 
    {
        $('#ProjectTaskPaymentTable').DataTable({
            ordering: false,
            info: false,
            columnDefs: [
                { searchable: true, targets: [1] }, // Make the second column searchable
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