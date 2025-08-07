<?php

    $page = 'employee';

    include 'database.php';

    include "auth_middleware.php";
    checkAuth();
    checkRole('admin');
    
    if(!isset($_SESSION['admin_name'])){
       header('location:index.php');
    }

    $adminID = $_SESSION['adminID'];
    
    $userResult = mysqli_query($conn, "SELECT * FROM user WHERE user_id = $adminID");
    if(mysqli_num_rows($userResult) > 0) 
    {
        $userData = mysqli_fetch_array($userResult);
    }

    $defaultImage = '../images/default.jpg';
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <?php include 'include/header.php' ?>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
            <?php include('include/sidebar.php'); ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content" class="bg-white">

                <!-- Topbar -->
                    <?php include('include/topbar.php'); ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid p-0">

                    <?php include 'employeeInsert.php' ?>

                    <?php include 'employeeList.php' ?>

                </div>
            <!-- End of Main Content -->

            <!-- Footer -->
                <?php include('include/footer.php'); ?>
            <!-- End of Footer -->
        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->


</body>

</html>