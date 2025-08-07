<?php

    $page = 'user_inventory';

    include 'database.php';

    session_start();
    
    if(!isset($_SESSION['user_name'])){
       header('location:index.php');
    }

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <?php include 'include1/header.php' ?>

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
            <div id="content">

                <!-- Topbar -->
                    <?php include('include1/topbar.php'); ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                        <!-- Page Heading -->
                        <div class="d-sm-flex align-items-center justify-content-between mb-4">
                            <h1 class="h3 mb-0 text-dark fw-bold">Dashboard</h1>
                        </div>

                        <!-- Content Row -->
                        <div class="row">
                            <div class="col-md-3 mb-3">
								<div class="card card-stats bg-secondary">
									<div class="card-body ">
										<div class="row">
											<div class="col-5 d-flex justify-content-center align-items-center">
												<div class="icon-big text-center">
                                                    <i class="fa-solid fa-list-ul fs-2"></i>
												</div>
											</div>
											<div class="col-7 d-flex align-items-center">
												<div class="numbers">
													<p class="card-category fw-bold">Todo</p>
                                                    <?php 
                                                        $countQuery = mysqli_query($conn, "SELECT COUNT(*) as total FROM project WHERE status = 'Todo' AND active = '1'");
                                                        
                                                        if ($countQuery) {
                                                            $countData = mysqli_fetch_assoc($countQuery);
                                                            $totalItems = $countData['total'];

                                                            if ($totalItems > 0) {
                                                    ?>
                                                                <h4 class="card-title"><?php echo $totalItems; ?></h4>
                                                    <?php
                                                            } else {
                                                    ?>
                                                                <h4 class="card-title">0</h4>
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

							<div class="col-md-3 mb-3">
								<div class="card card-stats bg-primary">
									<div class="card-body ">
										<div class="row">
											<div class="col-5 d-flex justify-content-center align-items-center">
												<div class="icon-big text-center">
                                                    <i class="fa-solid fa-rotate fs-2"></i>
												</div>
											</div>
											<div class="col-7 d-flex align-items-center">
												<div class="numbers">
													<p class="card-category fw-bold">In Progress</p>
                                                    <?php 
                                                        $countQuery = mysqli_query($conn, "SELECT COUNT(*) as total FROM project WHERE status = 'Inprogress' AND active = '1'");
                                                        
                                                        if ($countQuery) {
                                                            $countData = mysqli_fetch_assoc($countQuery);
                                                            $totalItems = $countData['total'];

                                                            if ($totalItems > 0) {
                                                    ?>
                                                                <h4 class="card-title"><?php echo $totalItems; ?></h4>
                                                    <?php
                                                            } else {
                                                    ?>
                                                                <h4 class="card-title">0</h4>
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

                            <div class="col-md-3 mb-3">
								<div class="card card-stats bg-success">
									<div class="card-body ">
										<div class="row">
											<div class="col-5 d-flex justify-content-center align-items-center">
												<div class="icon-big text-center">
                                                    <i class="fa-solid fa-circle-check fs-2"></i>
												</div>
											</div>
											<div class="col-7 d-flex align-items-center">
												<div class="numbers">
													<p class="card-category fw-bold">Completed</p>
                                                    <?php 
                                                        $countQuery = mysqli_query($conn, "SELECT COUNT(*) as total FROM project WHERE status = 'Completed' AND active = '1'");
                                                        
                                                        if ($countQuery) {
                                                            $countData = mysqli_fetch_assoc($countQuery);
                                                            $totalItems = $countData['total'];

                                                            if ($totalItems > 0) {
                                                    ?>
                                                                <h4 class="card-title"><?php echo $totalItems; ?></h4>
                                                    <?php
                                                            } else {
                                                    ?>
                                                                <h4 class="card-title">0</h4>
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
						</div>

                <!-- /.container-fluid -->

                <div class="container-fluid">
                    <div class="row">

                    </div>
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