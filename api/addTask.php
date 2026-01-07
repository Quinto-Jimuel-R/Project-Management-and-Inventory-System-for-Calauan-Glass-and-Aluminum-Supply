<?php

    $page = 'project';

    include 'database.php';

    include "auth_middleware.php";
    checkAuth();
    checkRole('admin');

    if(!isset($_SESSION['admin_name'])){
        header('location:index.php');
    }

    if(isset($_GET['projID'])) {
        $project_id = $_GET['projID'];
    }

    $adminID = $_SESSION['adminID'];

    $userResult = mysqli_query($conn, "SELECT * FROM user WHERE user_id = $adminID");
    if(mysqli_num_rows($userResult) > 0) {
        $userData = mysqli_fetch_array($userResult);
    }

    $defaultImage = '../images/default.jpg';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'include/header.php' ?>
    <style>
        .canvas-container {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Apply the same styles to mobile and tablet devices */
        @media (max-width: 1024px) {
            .canvas-container {
                width: 450px;
                height: 450px;
            }
        }

        /* Larger screens (desktops) */
        @media (min-width: 1025px) {
            .canvas-container {
                width: 600px;
                height: 600px;
            }
        }

        /* Base button styles */
        .btns {
            transition: transform 0.3s ease; /* Smooth transition for the zoom effect */
            opacity: 80%;
        }

        /* Zoom effect on hover */
        .btns:hover {
            transform: scale(1.1); /* Scale the button to 110% of its original size */
        }
    </style>
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
            <?php include('include/topbar.php') ?>
            <!-- End of Topbar -->

            <!-- Begin Page Content -->
            <div class="container-fluid p-0">
                <div class="card border-0 mb-4" style="background-color: transparent;">
                    <div class="card-body">
                        <div id="taskButton">
                            <div class="d-flex justify-content-center row gap-4">
                                <div class="d-flex fs-5 mb-3" style="font-weight: 800;">
                                    <div class="col-lg-4">
                                        <a href="task.php?projID=<?php echo $project_id ?>" class="btn btn-primary"> Back </a>
                                    </div>
                                    <div class="col-lg-4 text-center">
                                        Task
                                    </div>
                                    <div class="col-lg-4"></div>
                                </div>

                                <button id="twoPS" class="btn btns border border-5 border-secondary pb-2 bg-primary text-white" style="width: 325px;">
                                    <div class="py-2">
                                        Two-Panel Slider
                                    </div>

                                    <div class="canvas-containers bg-white">
                                        <canvas id="twoPanelSlider" width="200" height="200"></canvas>
                                    </div>
                                </button>

                                <button id="awningS" class="btn btns border border-5 border-secondary pb-2 bg-primary text-white" style="width: 325px;">
                                    <div class="py-2">
                                        Awning
                                    </div>

                                    <div class="canvas-containers bg-white">
                                        <canvas id="awningWindow" width="200" height="200"></canvas>
                                    </div>
                                </button>

                                <button id="fixedS" class="btn btns border border-5 border-secondary pb-2 bg-primary text-white" style="width: 325px;">
                                    <div class="py-2">
                                        Fixed
                                    </div>

                                    <div class="canvas-containers bg-white">
                                        <canvas id="fixedWindow" width="200" height="200"></canvas>
                                    </div>
                                </button>

                                <button id="casementS" class="btn btns border border-5 border-secondary pb-2 bg-primary text-white" style="width: 325px;">
                                    <div class="py-2">
                                        Casement
                                    </div>

                                    <div class="canvas-containers bg-white">
                                        <canvas id="casementWindow" width="200" height="200"></canvas>
                                    </div>
                                </button>
                            </div>
                        </div>
                
                        <!-- TwoPanelSlider -->
                        <div id="twoPSlider" class="row d-none">
                            <div class="">
                                <button id="twoPSBack" class="btn btn-primary">
                                    Back
                                </button>
                            </div>

                            <div class="col-md-6 d-flex justify-content-center align-items-center mb-3">
                                <div class="canvas-container">
                                    <canvas id="twoPanelSliderss"></canvas>
                                </div>
                            </div>

                            <div class="col-md-6">
                                    
                                <form action="add.php?action=task" id="createTaskForm" name="createTaskForm" method="POST" class="needs-validation" novalidate>
                                    <!-- Add form elements here -->
                                    <div class="d-flex row">
                                        <div class="col-6 mb-2">
                                            <label class="form-label mx-1 mt-1 mb-0">Length</label>
                                            <input class="form-control" id="length" name="length" style="height: 45px;" placeholder="inch" required>
                                            <div class="invalid-feedback">
                                                Please enter length.
                                            </div>

                                            <div class="col-4 d-none">
                                                <label class="form-label mx-1 mt-1 mb-0">Length Foot</label>
                                                <input class="form-control" id="lFoot" name="lFoot" style="height: 45px;">
                                            </div>
                                            <div class="col-4 d-none">
                                                <label class="form-label mx-1 mt-1 mb-0">Length Foot x 2</label>
                                                <input class="form-control" id="lFootX2" name="lFootX2" style="height: 45px;">
                                            </div>
                                        </div>

                                        <div class="col-6 mb-2">
                                            <label class="form-label mx-1 mt-1 mb-0">Height</label>
                                            <input class="form-control" id="height" name="height" style="height: 45px;" placeholder="inch" required>
                                            <div class="invalid-feedback">
                                                Please enter height.
                                            </div>

                                            <div class="col-4 d-none">
                                                <label class="form-label mx-1 mt-1 mb-0">Height Foot</label>
                                                <input class="form-control" id="hFoot" name="hFoot" style="height: 45px;">
                                            </div>
                                            <div class="col-4 d-none">
                                                <label class="form-label mx-1 mt-1 mb-0">Height Foot x 2</label>
                                                <input class="form-control" id="hFootX2" name="hFootX2" style="height: 45px;">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- TOP HEAD -->
                                    <div class="d-flex mb-2">
                                        <div class="col-7 ps-0">
                                            <label class="form-label mx-1 mt-1 mb-0">Head</label>
                                            <select class="form-select text-dark " id="topHead" name="topHead" style="height: 45px;" required>
                                                <option class="d-none" value="" selected>Select Item</option>
                                                <?php
                                                    $query = "SELECT * FROM inventory WHERE category = 'Aluminum'";
                                                    $result = mysqli_query($conn, $query);

                                                    if ($result) 
                                                    {
                                                        $num_rows = mysqli_num_rows($result);
                                                        if ($num_rows > 0) 
                                                        {
                                                            while ($data = mysqli_fetch_assoc($result)) 
                                                            {
                                                                // Check if stock is 0, if yes, disable the option
                                                                $disabled = ($data['stock'] == 0) ? 'disabled' : '';
                                                            ?>
                                                                <option value="<?php echo $data['item_name'] . '|' . $data['dimension'] . '|' . $data['color']; ?>" <?php echo $disabled; ?>>
                                                                    <?php echo $data['item_name']; ?> - <?php echo $data['dimension']; ?> - <?php echo $data['color']; ?> 
                                                                    <?php if ($data['stock'] == 0) { echo "( No Stock) ❌️"; } ?>
                                                                </option>
                                                            <?php
                                                            }
                                                        } 
                                                        else 
                                                        {
                                                            ?>
                                                                <option disabled>No item found. Add item first.</option>
                                                            <?php
                                                        }
                                                    }
                                                ?>
                                            </select>
                                            <div class="invalid-feedback">
                                                Please select an item.
                                            </div>
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">ID</label>
                                            <input class=" form-control" id="thItemID" name="thItemID" style="height: 45px;" >
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">EID</label>
                                            <input class=" form-control" id="thEItemID" name="thEItemID" style="height: 45px;" >
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Color</label>
                                            <input class=" form-control" id="thColor" name="thColor" style="height: 45px;" >
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Dimension</label>
                                            <input class=" form-control" id="thDimension" name="thDimension" style="height: 45px;" >
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Stock</label>
                                            <input class=" form-control" id="thStock" name="thStock" style="height: 45px;" >
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">SRP(PF)</label>
                                            <input class="form-control" id="thSellPrice" name="thSellPrice" style="height: 45px;" >
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Foot</label>
                                            <input class="form-control" id="thFootExcess" name="thFootExcess" style="height: 45px;" >
                                        </div>

                                        <div class="col-5 pr-0">
                                            <label class="form-label mx-1 mb-0" style="margin-top: 21px;"></label>
                                            <div class="input-group">
                                                <span class="input-group-text">₱</span>
                                                <input class="form-control" id="thPrice" name="thPrice" style="height: 45px;" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- TOP HEAD -->

                                    <!-- BOTTOM SILL -->
                                    <div class="d-flex mb-2">
                                        <div class="col-7 ps-0">
                                            <label class="form-label mx-1 mt-1 mb-0">Sill</label>
                                            <select class="form-select text-dark" id="bottomSill" name="bottomSill" style="height: 45px;" required>
                                                <option class="d-none" value="" selected>Select Item</option>
                                                <?php
                                                    $query = "SELECT * FROM inventory WHERE category = 'Aluminum'";
                                                    $result = mysqli_query($conn, $query);

                                                    if ($result) 
                                                    {
                                                        $num_rows = mysqli_num_rows($result);
                                                        if ($num_rows > 0) 
                                                        {
                                                            while ($data = mysqli_fetch_assoc($result)) 
                                                            {
                                                                // Check if stock is 0, if yes, disable the option
                                                                $disabled = ($data['stock'] == 0) ? 'disabled' : '';
                                                            ?>
                                                                <option value="<?php echo $data['item_name'] . '|' . $data['dimension'] . '|' . $data['color']; ?>" <?php echo $disabled; ?>>
                                                                    <?php echo $data['item_name']; ?> - <?php echo $data['dimension']; ?> - <?php echo $data['color']; ?> 
                                                                    <?php if ($data['stock'] == 0) { echo "( No Stock) ❌️"; } ?>
                                                                </option>
                                                            <?php
                                                            }
                                                        } 
                                                        else 
                                                        {
                                                            ?>
                                                                <option disabled>No item found. Add item first.</option>
                                                            <?php
                                                        }
                                                    }
                                                ?>
                                            </select>
                                            <div class="invalid-feedback">
                                                Please select an item.
                                            </div>
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">ID</label>
                                            <input class=" form-control" id="bsItemID" name="bsItemID" style="height: 45px;" >
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">EID</label>
                                            <input class=" form-control" id="bsEItemID" name="bsEItemID" style="height: 45px;" >
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Color</label>
                                            <input class=" form-control" id="bsColor" name="bsColor" style="height: 45px;" >
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Dimension</label>
                                            <input class=" form-control" id="bsDimension" name="bsDimension" style="height: 45px;" >
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Stock</label>
                                            <input class="form-control" id="bsStock" name="bsStock" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">SRP(PF)</label>
                                            <input class="form-control" id="bsSellPrice" name="bsSellPrice" style="height: 45px;" >
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Foot</label>
                                            <input class="form-control" id="bsFootExcess" name="bsFootExcess" style="height: 45px;" >
                                        </div>

                                        <div class="col-5 pr-0">
                                            <label class="form-label mx-1 mb-0" style="margin-top: 21px;"></label>
                                            <div class="input-group">
                                                <span class="input-group-text">₱</span>
                                                <input class="form-control" id="bsPrice" name="bsPrice" style="height: 45px;" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- BOTTOM SILL -->

                                    <!-- RAIL -->
                                    <div class="d-flex mb-2">
                                        <div class="col-7 ps-0">
                                            <label class="form-label mx-1 mt-1 mb-0">Rails</label>
                                            <select class="form-select text-dark" id="rail" name="rail" style="height: 45px;" required>
                                                <option class="d-none" value="" selected>Select Item</option>
                                                <?php
                                                    $query = "SELECT * FROM inventory WHERE category = 'Aluminum'";
                                                    $result = mysqli_query($conn, $query);

                                                    if ($result) 
                                                    {
                                                        $num_rows = mysqli_num_rows($result);
                                                        if ($num_rows > 0) 
                                                        {
                                                            while ($data = mysqli_fetch_assoc($result)) 
                                                            {
                                                                // Check if stock is 0, if yes, disable the option
                                                                $disabled = ($data['stock'] == 0) ? 'disabled' : '';
                                                            ?>
                                                                <option value="<?php echo $data['item_name'] . '|' . $data['dimension'] . '|' . $data['color']; ?>" <?php echo $disabled; ?>>
                                                                    <?php echo $data['item_name']; ?> - <?php echo $data['dimension']; ?> - <?php echo $data['color']; ?> 
                                                                    <?php if ($data['stock'] == 0) { echo "( No Stock) ❌️"; } ?>
                                                                </option>
                                                            <?php
                                                            }
                                                        } 
                                                        else 
                                                        {
                                                            ?>
                                                                <option disabled>No item found. Add item first.</option>
                                                            <?php
                                                        }
                                                    }
                                                ?>
                                            </select>
                                            <div class="invalid-feedback">
                                                Please select an item.
                                            </div>
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">ID</label>
                                            <input class=" form-control" id="rItemID" name="rItemID" style="height: 45px;" >
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Foot</label>
                                            <input class="form-control" id="rFoot" name="rFoot" style="height: 45px;" >
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Color</label>
                                            <input class=" form-control" id="rColor" name="rColor" style="height: 45px;" >
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Dimension</label>
                                            <input class=" form-control" id="rDimension" name="rDimension" style="height: 45px;" >
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Stock</label>
                                            <input class="form-control" id="rStock" name="rStock" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">SRP(PF)</label>
                                            <input class="form-control" id="rSellPrice" name="rSellPrice" style="height: 45px;" >
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">EID</label>
                                            <input class=" form-control" id="rEItemID" name="rEItemID" style="height: 45px;" >
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Foot EXC</label>
                                            <input class="form-control" id="rFootExcess" name="rFootExcess" style="height: 45px;" >
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">UEI</label>
                                            <input class=" form-control" id="rUEI" name="rUEI" style="height: 45px;" >
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">UNI</label>
                                            <input class=" form-control" id="rUNI" name="rUNI" style="height: 45px;" >
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">FEID</label>
                                            <input class="form-control" id="rFEID" name="rFEID" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">FH</label>
                                            <input class="form-control" id="rFH" name="rFH" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">FEID1</label>
                                            <input class="form-control" id="rFEID1" name="rFEID1" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">SH</label>
                                            <input class="form-control" id="rSH" name="rSH" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Half Foot</label>
                                            <input class="form-control" id="rHFoot" name="rHFoot" style="height: 45px;">
                                        </div>

                                        <div class="col-5 pr-0">
                                            <label class="form-label mx-1 mb-0" style="margin-top: 21px;"></label>
                                            <div class="input-group">
                                                <span class="input-group-text">₱</span>
                                                <input class="form-control" id="rPrice" name="rPrice" style="height: 45px;" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- RAIL -->

                                    <!-- SIDE JAMB -->
                                    <div class="d-flex mb-2">
                                        <div class="col-7 ps-0">
                                            <label class="form-label mx-1 mt-1 mb-0">Jamb</label>
                                            <select class="form-select text-dark" id="sideJamb" name="sideJamb" style="height: 45px;" required>
                                                <option class="d-none" value="" selected>Select Item</option>
                                                <?php
                                                    $query = "SELECT * FROM inventory WHERE category = 'Aluminum'";
                                                    $result = mysqli_query($conn, $query);

                                                    if ($result) 
                                                    {
                                                        $num_rows = mysqli_num_rows($result);
                                                        if ($num_rows > 0) 
                                                        {
                                                            while ($data = mysqli_fetch_assoc($result)) 
                                                            {
                                                                // Check if stock is 0, if yes, disable the option
                                                                $disabled = ($data['stock'] == 0) ? 'disabled' : '';
                                                            ?>
                                                                <option value="<?php echo $data['item_name'] . '|' . $data['dimension'] . '|' . $data['color']; ?>" <?php echo $disabled; ?>>
                                                                    <?php echo $data['item_name']; ?> - <?php echo $data['dimension']; ?> - <?php echo $data['color']; ?> 
                                                                    <?php if ($data['stock'] == 0) { echo "( No Stock) ❌️"; } ?>
                                                                </option>
                                                            <?php
                                                            }
                                                        } 
                                                        else 
                                                        {
                                                            ?>
                                                                <option disabled>No item found. Add item first.</option>
                                                            <?php
                                                        }
                                                    }
                                                ?>
                                            </select>
                                            <div class="invalid-feedback">
                                                Please select an item.
                                            </div>
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">ID</label>
                                            <input class="form-control" id="sjItemID" name="sjItemID" style="height: 45px;" >
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Foot</label>
                                            <input class="form-control" id="sjFoot" name="sjFoot" style="height: 45px;" >
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Color</label>
                                            <input class="form-control" id="sjColor" name="sjColor" style="height: 45px;" >
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Dimension</label>
                                            <input class="form-control" id="sjDimension" name="sjDimension" style="height: 45px;" >
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Stock</label>
                                            <input class="form-control" id="sjStock" name="sjStock" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">SRP(PF)</label>
                                            <input class="form-control" id="sjSellPrice" name="sjSellPrice" style="height: 45px;" >
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">EID</label>
                                            <input class="form-control" id="sjEItemID" name="sjEItemID" style="height: 45px;" >
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Foot EXC</label>
                                            <input class="form-control" id="sjFootExcess" name="sjFootExcess" style="height: 45px;" >
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">UEI</label>
                                            <input class="form-control" id="sjUEI" name="sjUEI" style="height: 45px;" >
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">UNI</label>
                                            <input class="form-control" id="sjUNI" name="sjUNI" style="height: 45px;" >
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">FEID</label>
                                            <input class="form-control" id="sjFEID" name="sjFEID" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">FH</label>
                                            <input class="form-control" id="sjFH" name="sjFH" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">FEID1</label>
                                            <input class="form-control" id="sjFEID1" name="sjFEID1" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">SH</label>
                                            <input class="form-control" id="sjSH" name="sjSH" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Half Foot</label>
                                            <input class="form-control" id="sjHFoot" name="sjHFoot" style="height: 45px;">
                                        </div>

                                        <div class="col-5 pr-0">
                                            <label class="form-label mx-1 mb-0" style="margin-top: 21px;"></label>
                                            <div class="input-group">
                                                <span class="input-group-text">₱</span>
                                                <input class="form-control" id="sjPrice" name="sjPrice" style="height: 45px;" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- SIDE JAMB -->

                                    <!-- LOCK STILE -->
                                    <div class="d-flex mb-2">
                                        <div class="col-7 ps-0">
                                            <label class="form-label mx-1 mt-1 mb-0">Stiles</label>
                                            <select class="form-select text-dark" id="lockStile" name="lockStile" style="height: 45px;" required>
                                                <option class="d-none" value="" selected>Select Item</option>
                                                <?php
                                                    $query = "SELECT * FROM inventory WHERE category = 'Aluminum'";
                                                    $result = mysqli_query($conn, $query);

                                                    if ($result) 
                                                    {
                                                        $num_rows = mysqli_num_rows($result);
                                                        if ($num_rows > 0) 
                                                        {
                                                            while ($data = mysqli_fetch_assoc($result)) 
                                                            {
                                                                // Check if stock is 0, if yes, disable the option
                                                                $disabled = ($data['stock'] == 0) ? 'disabled' : '';
                                                            ?>
                                                                <option value="<?php echo $data['item_name'] . '|' . $data['dimension'] . '|' . $data['color']; ?>" <?php echo $disabled; ?>>
                                                                    <?php echo $data['item_name']; ?> - <?php echo $data['dimension']; ?> - <?php echo $data['color']; ?> 
                                                                    <?php if ($data['stock'] == 0) { echo "( No Stock)"; } ?>
                                                                </option>
                                                            <?php
                                                            }
                                                        } 
                                                        else 
                                                        {
                                                            ?>
                                                                <option disabled>No item found. Add item first.</option>
                                                            <?php
                                                        }
                                                    }
                                                ?>
                                            </select>
                                            <div class="invalid-feedback">
                                                Please select an item.
                                            </div>
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">ID</label>
                                            <input class="form-control" id="lsItemID" name="lsItemID" style="height: 45px;" >
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Foot</label>
                                            <input class="form-control" id="lsFoot" name="lsFoot" style="height: 45px;" >
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Color</label>
                                            <input class="form-control" id="lsColor" name="lsColor" style="height: 45px;" >
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Dimension</label>
                                            <input class="form-control" id="lsDimension" name="lsDimension" style="height: 45px;" >
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Stock</label>
                                            <input class="form-control" id="lsStock" name="lsStock" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">SRP(PF)</label>
                                            <input class="form-control" id="lsSellPrice" name="lsSellPrice" style="height: 45px;" >
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">EID</label>
                                            <input class="form-control" id="lsEItemID" name="lsEItemID" style="height: 45px;" >
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Foot EXC</label>
                                            <input class="form-control" id="lsFootExcess" name="lsFootExcess" style="height: 45px;" >
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">UEI</label>
                                            <input class="form-control" id="lsUEI" name="lsUEI" style="height: 45px;" >
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">UNI</label>
                                            <input class="form-control" id="lsUNI" name="lsUNI" style="height: 45px;" >
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">FEID</label>
                                            <input class="form-control" id="lsFEID" name="lsFEID" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">FH</label>
                                            <input class="form-control" id="lsFH" name="lsFH" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">FEID1</label>
                                            <input class="form-control" id="lsFEID1" name="lsFEID1" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">SH</label>
                                            <input class="form-control" id="lsSH" name="lsSH" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Half Foot</label>
                                            <input class="form-control" id="lsHFoot" name="lsHFoot" style="height: 45px;">
                                        </div>

                                        <div class="col-5 pr-0">
                                            <label class="form-label mx-1 mb-0" style="margin-top: 21px;"></label>
                                            <div class="input-group">
                                                <span class="input-group-text">₱</span>
                                                <input class="form-control" id="lsPrice" name="lsPrice" style="height: 45px;" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- LOCK STILE -->

                                    <!-- INTERLOCKER -->
                                    <div class="d-flex mb-2">
                                        <div class="col-7 ps-0">
                                            <label class="form-label mx-1 mt-1 mb-0">Interlocker</label>
                                            <select class="form-select text-dark" id="interlocker" name="interlocker" style="height: 45px;" required>
                                                <option class="d-none" value="" selected>Select Item</option>
                                                <?php
                                                    $query = "SELECT * FROM inventory WHERE category = 'Aluminum'";
                                                    $result = mysqli_query($conn, $query);

                                                    if ($result) 
                                                    {
                                                        $num_rows = mysqli_num_rows($result);
                                                        if ($num_rows > 0) 
                                                        {
                                                            while ($data = mysqli_fetch_assoc($result)) 
                                                            {
                                                                // Check if stock is 0, if yes, disable the option
                                                                $disabled = ($data['stock'] == 0) ? 'disabled' : '';
                                                            ?>
                                                                <option value="<?php echo $data['item_name'] . '|' . $data['dimension'] . '|' . $data['color']; ?>" <?php echo $disabled; ?>>
                                                                    <?php echo $data['item_name']; ?> - <?php echo $data['dimension']; ?> - <?php echo $data['color']; ?> 
                                                                    <?php if ($data['stock'] == 0) { echo "( No Stock)"; } ?>
                                                                </option>
                                                            <?php
                                                            }
                                                        } 
                                                        else 
                                                        {
                                                            ?>
                                                                <option disabled>No item found. Add item first.</option>
                                                            <?php
                                                        }
                                                    }
                                                ?>
                                            </select>
                                            <div class="invalid-feedback">
                                                Please select an item.
                                            </div>
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">ID</label>
                                            <input class="form-control" id="ilItemID" name="ilItemID" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Foot</label>
                                            <input class="form-control" id="ilFoot" name="ilFoot" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Color</label>
                                            <input class="form-control" id="ilColor" name="ilColor" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Dimension</label>
                                            <input class="form-control" id="ilDimension" name="ilDimension" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Stock</label>
                                            <input class="form-control" id="ilStock" name="ilStock" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">SRP(PF)</label>
                                            <input class="form-control" id="ilSellPrice" name="ilSellPrice" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">EID</label>
                                            <input class="form-control" id="ilEItemID" name="ilEItemID" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Foot EXC</label>
                                            <input class="form-control" id="ilFootExcess" name="ilFootExcess" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">UEI</label>
                                            <input class="form-control" id="ilUEI" name="ilUEI" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">UNI</label>
                                            <input class="form-control" id="ilUNI" name="ilUNI" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">FEID</label>
                                            <input class="form-control" id="ilFEID" name="ilFEID" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">FH</label>
                                            <input class="form-control" id="ilFH" name="ilFH" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">FEID1</label>
                                            <input class="form-control" id="ilFEID1" name="ilFEID1" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">SH</label>
                                            <input class="form-control" id="ilSH" name="ilSH" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Half Foot</label>
                                            <input class="form-control" id="ilHFoot" name="ilHFoot" style="height: 45px;">
                                        </div>

                                        <div class="col-5 pr-0">
                                            <label class="form-label mx-1 mb-0" style="margin-top: 21px;"></label>
                                            <div class="input-group">
                                                <span class="input-group-text">₱</span>
                                                <input class="form-control" id="ilPrice" name="ilPrice" style="height: 45px;" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- INTERLOCKER -->

                                    <!-- GLASS -->
                                    <div class="d-flex mb-2">
                                        <div class="col-7 ps-0">
                                            <label class="form-label mx-1 mt-1 mb-0">Glass</label>
                                            <select class="form-select text-dark" id="glass" name="glass" style="height: 45px;" required>
                                                <option class="d-none" value="" selected>Select Item</option>
                                                <?php
                                                    $query = "SELECT * FROM inventory WHERE category = 'Glass' AND stock != 1";
                                                    $result = mysqli_query($conn, $query);
                                                    
                                                    if ($result) 
                                                    {
                                                        $num_rows = mysqli_num_rows($result);
                                                        if ($num_rows > 0) 
                                                        {
                                                            while ($data = mysqli_fetch_assoc($result)) 
                                                            {
                                                        ?>
                                                            <option value="<?php echo $data['item_name']; ?>">
                                                                <?php echo $data['color']; ?> - <?php echo $data['dimension']; ?> - <?php echo $data['item_name']; ?>
                                                            </option>
                                                        <?php
                                                        }
                                                    }
                                                    else 
                                                    {
                                                        ?>
                                                            <option disabled>No item found. Add item first.</option>
                                                        <?php
                                                        }
                                                    }
                                                ?>
                                            </select>
                                            <div class="invalid-feedback">
                                                Please select an item.
                                            </div>
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">ID</label>
                                            <input class="form-control" id="gItemID" name="gItemID" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">EID</label>
                                            <input class="form-control" id="gEItemID" name="gEItemID" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Color</label>
                                            <input class="form-control" id="gColor" name="gColor" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Dimension</label>
                                            <input class="form-control" id="gDimension" name="gDimension" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Stock</label>
                                            <input class="form-control" id="gStock" name="gStock" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">SRP(PF)</label>
                                            <input class="form-control" id="gSellPrice" name="gSellPrice" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Foot</label>
                                            <input class="form-control" id="gFootExcess" name="gFootExcess" style="height: 45px;">
                                        </div>

                                        <div class="col-5 pr-0">
                                            <label class="form-label mx-1 mb-0" style="margin-top: 21px;"></label>
                                            <div class="input-group">
                                                <span class="input-group-text">₱</span>
                                                <input class="form-control" id="gPrice" name="gPrice" style="height: 45px;" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- GLASS -->

                                    <input type="hidden" name="projectID" value="<?php echo $project_id; ?>">

                                    <button id="createTask" type="submit" class="btn btn-primary w-100 mt-2">Save</button>
                                </form>
                            </div>
                        </div>
                        <!-- -->

                        <!-- Awning -->
                        <div id="Awning" class="row d-none">
                            <div class="">
                                <button id="awningBack" class="btn btn-primary">
                                    Back
                                </button>
                            </div>

                            <div class="col-md-6 d-flex justify-content-center align-items-center mb-3">
                                <div class="canvas-container">
                                    <canvas id="awningss"></canvas>
                                </div>
                            </div>

                            <div class="col-md-6">
                                    
                                <form action="add.php?action=taskAwning" id="createTaskFormAwning" name="createTaskFormAwning" method="POST" class="needs-validation" novalidate>
                                    <!-- Add form elements here -->
                                    <div class="d-flex row">
                                        <div class="col-6 mb-2">
                                            <label class="form-label mx-1 mt-1 mb-0">Length</label>
                                            <input class="form-control" id="lengthAwning" name="lengthAwning" style="height: 45px;" placeholder="inch" required>
                                            <div class="invalid-feedback">
                                                Please enter length.
                                            </div>

                                            <div class="col-4 d-none">
                                                <label class="form-label mx-1 mt-1 mb-0">Length Foot</label>
                                                <input class="form-control" id="lFootAwning" name="lFootAwning" style="height: 45px;">
                                            </div>
                                            <div class="col-4 d-none">
                                                <label class="form-label mx-1 mt-1 mb-0">Length Foot x 2</label>
                                                <input class="form-control" id="lFootX2Awning" name="lFootX2Awning" style="height: 45px;">
                                            </div>
                                        </div>

                                        <div class="col-6 mb-2">
                                            <label class="form-label mx-1 mt-1 mb-0">Height</label>
                                            <input class="form-control" id="heightAwning" name="heightAwning" style="height: 45px;" placeholder="inch" required>
                                            <div class="invalid-feedback">
                                                Please enter height.
                                            </div>

                                            <div class="col-4 d-none">
                                                <label class="form-label mx-1 mt-1 mb-0">Height Foot</label>
                                                <input class="form-control" id="hFootAwning" name="hFootAwning" style="height: 45px;">
                                            </div>
                                            <div class="col-4 d-none">
                                                <label class="form-label mx-1 mt-1 mb-0">Height Foot x 2</label>
                                                <input class="form-control" id="hFootX2Awning" name="hFootX2Awning" style="height: 45px;">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- TOP HEAD -->
                                    <div class="d-flex mb-2">
                                        <div class="col-7 ps-0">
                                            <label class="form-label mx-1 mt-1 mb-0">Head</label>
                                            <select class="form-select text-dark " id="HeadAwning" name="HeadAwning" style="height: 45px;" required>
                                                <option class="d-none" value="" selected>Select Item</option>
                                                <?php
                                                    $query = "SELECT * FROM inventory WHERE category = 'Aluminum'";
                                                    $result = mysqli_query($conn, $query);

                                                    if ($result) 
                                                    {
                                                        $num_rows = mysqli_num_rows($result);
                                                        if ($num_rows > 0) 
                                                        {
                                                            while ($data = mysqli_fetch_assoc($result)) 
                                                            {
                                                                // Check if stock is 0, if yes, disable the option
                                                                $disabled = ($data['stock'] == 0) ? 'disabled' : '';
                                                            ?>
                                                                <option value="<?php echo $data['item_name'] . '|' . $data['dimension'] . '|' . $data['color']; ?>" <?php echo $disabled; ?>>
                                                                    <?php echo $data['item_name']; ?> - <?php echo $data['dimension']; ?> - <?php echo $data['color']; ?> 
                                                                    <?php if ($data['stock'] == 0) { echo "( No Stock) ❌️"; } ?>
                                                                </option>
                                                            <?php
                                                            }
                                                        } 
                                                        else 
                                                        {
                                                            ?>
                                                                <option disabled>No item found. Add item first.</option>
                                                            <?php
                                                        }
                                                    }
                                                ?>
                                            </select>
                                            <div class="invalid-feedback">
                                                Please select an item.
                                            </div>
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">ID</label>
                                            <input class=" form-control" id="hItemIDAwning" name="hItemIDAwning" style="height: 45px;" >
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">EID</label>
                                            <input class=" form-control" id="hEItemIDAwning" name="hEItemIDAwning" style="height: 45px;" >
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Color</label>
                                            <input class=" form-control" id="hColorAwning" name="hColorAwning" style="height: 45px;" >
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Dimension</label>
                                            <input class=" form-control" id="hDimensionAwning" name="hDimensionAwning" style="height: 45px;" >
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Stock</label>
                                            <input class=" form-control" id="hStockAwning" name="hStockAwning" style="height: 45px;" >
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">SRP(PF)</label>
                                            <input class="form-control" id="hSellPriceAwning" name="hSellPriceAwning" style="height: 45px;" >
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Foot</label>
                                            <input class="form-control" id="hFootExcessAwning" name="hFootExcessAwning" style="height: 45px;" >
                                        </div>

                                        <div class="col-5 pr-0">
                                            <label class="form-label mx-1 mb-0" style="margin-top: 21px;"></label>
                                            <div class="input-group">
                                                <span class="input-group-text">₱</span>
                                                <input class="form-control" id="hPriceAwning" name="hPriceAwning" style="height: 45px;" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- TOP HEAD -->

                                    <!-- BOTTOM SILL -->
                                    <div class="d-flex mb-2">
                                        <div class="col-7 ps-0">
                                            <label class="form-label mx-1 mt-1 mb-0">Sill</label>
                                            <select class="form-select text-dark" id="SillAwning" name="SillAwning" style="height: 45px;" required>
                                                <option class="d-none" value="" selected>Select Item</option>
                                                <?php
                                                    $query = "SELECT * FROM inventory WHERE category = 'Aluminum'";
                                                    $result = mysqli_query($conn, $query);

                                                    if ($result) 
                                                    {
                                                        $num_rows = mysqli_num_rows($result);
                                                        if ($num_rows > 0) 
                                                        {
                                                            while ($data = mysqli_fetch_assoc($result)) 
                                                            {
                                                                // Check if stock is 0, if yes, disable the option
                                                                $disabled = ($data['stock'] == 0) ? 'disabled' : '';
                                                            ?>
                                                                <option value="<?php echo $data['item_name'] . '|' . $data['dimension'] . '|' . $data['color']; ?>" <?php echo $disabled; ?>>
                                                                    <?php echo $data['item_name']; ?> - <?php echo $data['dimension']; ?> - <?php echo $data['color']; ?> 
                                                                    <?php if ($data['stock'] == 0) { echo "( No Stock) ❌️"; } ?>
                                                                </option>
                                                            <?php
                                                            }
                                                        } 
                                                        else 
                                                        {
                                                            ?>
                                                                <option disabled>No item found. Add item first.</option>
                                                            <?php
                                                        }
                                                    }
                                                ?>
                                            </select>
                                            <div class="invalid-feedback">
                                                Please select an item.
                                            </div>
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">ID</label>
                                            <input class=" form-control" id="sItemIDAwning" name="sItemIDAwning" style="height: 45px;" >
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">EID</label>
                                            <input class=" form-control" id="sEItemIDAwning" name="sEItemIDAwning" style="height: 45px;" >
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Color</label>
                                            <input class=" form-control" id="sColorAwning" name="sColorAwning" style="height: 45px;" >
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Dimension</label>
                                            <input class=" form-control" id="sDimensionAwning" name="sDimensionAwning" style="height: 45px;" >
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Stock</label>
                                            <input class="form-control" id="sStockAwning" name="sStockAwning" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">SRP(PF)</label>
                                            <input class="form-control" id="sSellPriceAwning" name="sSellPriceAwning" style="height: 45px;" >
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Foot</label>
                                            <input class="form-control" id="sFootExcessAwning" name="sFootExcessAwning" style="height: 45px;" >
                                        </div>

                                        <div class="col-5 pr-0">
                                            <label class="form-label mx-1 mb-0" style="margin-top: 21px;"></label>
                                            <div class="input-group">
                                                <span class="input-group-text">₱</span>
                                                <input class="form-control" id="sPriceAwning" name="sPriceAwning" style="height: 45px;" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- BOTTOM SILL -->

                                    <!-- SIDE JAMB -->
                                    <div class="d-flex mb-2">
                                        <div class="col-7 ps-0">
                                            <label class="form-label mx-1 mt-1 mb-0">Jamb</label>
                                            <select class="form-select text-dark" id="JambAwning" name="JambAwning" style="height: 45px;" required>
                                                <option class="d-none" value="" selected>Select Item</option>

                                                <?php
                                                    $query = "SELECT * FROM inventory WHERE category = 'Aluminum'";
                                                    $result = mysqli_query($conn, $query);

                                                    if ($result) 
                                                    {
                                                        $num_rows = mysqli_num_rows($result);
                                                        if ($num_rows > 0) 
                                                        {
                                                            while ($data = mysqli_fetch_assoc($result)) 
                                                            {
                                                        ?>
                                                            <option value="<?php echo $data['item_name'] . '|' . $data['dimension'] . '|' . $data['color']; ?>" <?php echo $disabled; ?>>
                                                                    <?php echo $data['item_name']; ?> - <?php echo $data['dimension']; ?> - <?php echo $data['color']; ?> 
                                                                    <?php if ($data['stock'] == 0) { echo "( No Stock) ❌️"; } ?>
                                                                </option>
                                                        <?php
                                                        }
                                                    } 
                                                    else 
                                                    {
                                                        ?>
                                                            <option disabled>No item found. Add item first.</option>
                                                        <?php
                                                        }
                                                    }
                                                ?>
                                            </select>
                                            <div class="invalid-feedback">
                                                Please select an item.
                                            </div>
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">ID</label>
                                            <input class="form-control" id="sjItemIDAwning" name="sjItemIDAwning" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Foot</label>
                                            <input class="form-control" id="sjFootAwning" name="sjFootAwning" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Color</label>
                                            <input class="form-control" id="sjColorAwning" name="sjColorAwning" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Dimension</label>
                                            <input class="form-control" id="sjDimensionAwning" name="sjDimensionAwning" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Stock</label>
                                            <input class="form-control" id="sjStockAwning" name="sjStockAwning" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">SRP(PF)</label>
                                            <input class="form-control" id="sjSellPriceAwning" name="sjSellPriceAwning" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">EID</label>
                                            <input class="form-control" id="sjEItemIDAwning" name="sjEItemIDAwning" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Foot EXC</label>
                                            <input class="form-control" id="sjFootExcessAwning" name="sjFootExcessAwning" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">UEI</label>
                                            <input class="form-control" id="sjUEIAwning" name="sjUEIAwning" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">UNI</label>
                                            <input class="form-control" id="sjUNIAwning" name="sjUNIAwning" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">FEID</label>
                                            <input class="form-control" id="sjFEIDAwning" name="sjFEIDAwning" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">FH</label>
                                            <input class="form-control" id="sjFHAwning" name="sjFHAwning" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">FEID1</label>
                                            <input class="form-control" id="sjFEID1Awning" name="sjFEID1Awning" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">SH</label>
                                            <input class="form-control" id="sjSHAwning" name="sjSHAwning" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Half Foot</label>
                                            <input class="form-control" id="sjHFootAwning" name="sjHFootAwning" style="height: 45px;">
                                        </div>

                                        <div class="col-5 pr-0">
                                            <label class="form-label mx-1 mb-0" style="margin-top: 21px;"></label>
                                            <div class="input-group">
                                                <span class="input-group-text">₱</span>
                                                <input class="form-control" id="sjPriceAwning" name="sjPriceAwning" style="height: 45px;" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- SIDE JAMB -->

                                    <!-- GLASS -->
                                    <div class="d-flex mb-2">
                                        <div class="col-7 ps-0">
                                            <label class="form-label mx-1 mt-1 mb-0">Glass</label>
                                            <select class="form-select text-dark" id="GlassAwning" name="GlassAwning" style="height: 45px;" required>
                                                <option class="d-none" value="" selected>Select Item</option>
                                                <?php
                                                    $query = "SELECT * FROM inventory WHERE category = 'Glass' AND stock != 1";
                                                    $result = mysqli_query($conn, $query);
                                                    
                                                    if ($result) 
                                                    {
                                                        $num_rows = mysqli_num_rows($result);
                                                        if ($num_rows > 0) 
                                                        {
                                                            while ($data = mysqli_fetch_assoc($result)) 
                                                            {
                                                        ?>
                                                            <option value="<?php echo $data['item_name']; ?>">
                                                                <?php echo $data['color']; ?> - <?php echo $data['dimension']; ?> - <?php echo $data['item_name']; ?>
                                                            </option>
                                                        <?php
                                                        }
                                                    }
                                                    else 
                                                    {
                                                        ?>
                                                            <option disabled>No item found. Add item first.</option>
                                                        <?php
                                                        }
                                                    }
                                                ?>
                                            </select>
                                            <div class="invalid-feedback">
                                                Please select an item.
                                            </div>
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">ID</label>
                                            <input class="form-control" id="gItemIDAwning" name="gItemIDAwning" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">EID</label>
                                            <input class="form-control" id="gEItemIDAwning" name="gEItemIDAwning" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Color</label>
                                            <input class="form-control" id="gColorAwning" name="gColorAwning" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Dimension</label>
                                            <input class="form-control" id="gDimensionAwning" name="gDimensionAwning" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Stock</label>
                                            <input class="form-control" id="gStockAwning" name="gStockAwning" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">SRP(PF)</label>
                                            <input class="form-control" id="gSellPriceAwning" name="gSellPriceAwning" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Foot</label>
                                            <input class="form-control" id="gFootExcessAwning" name="gFootExcessAwning" style="height: 45px;">
                                        </div>

                                        <div class="col-5 pr-0">
                                            <label class="form-label mx-1 mb-0" style="margin-top: 21px;"></label>
                                            <div class="input-group">
                                                <span class="input-group-text">₱</span>
                                                <input class="form-control" id="gPriceAwning" name="gPriceAwning" style="height: 45px;" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- GLASS -->

                                    <input type="hidden" name="projectID" value="<?php echo $project_id; ?>">

                                    <button id="createTaskAwning" type="submit" class="btn btn-primary w-100 mt-2">Save</button>
                                </form>
                            </div>
                        </div>
                        <!-- -->

                        <div id="Fixed" class="row d-none">
                            <div class="">
                                <button id="fixedBack" class="btn btn-primary">
                                    Back
                                </button>
                            </div>

                            <div class="col-md-6 d-flex justify-content-center align-items-center mb-3">
                                <div class="canvas-container">
                                    <canvas id="fixedss"></canvas>
                                </div>
                            </div>

                            <div class="col-md-6">
                                    
                                <form action="add.php?action=task" id="createTaskFormFixed" name="createTaskFormFixed" method="POST" class="needs-validation" novalidate>
                                    <!-- Add form elements here -->
                                    <div class="d-flex row">
                                        <div class="col-6 mb-2">
                                            <label class="form-label mx-1 mt-1 mb-0">Length</label>
                                            <input class="form-control" id="lengthFixed" name="lengthFixed" style="height: 45px;" placeholder="inch" required>
                                            <div class="invalid-feedback">
                                                Please enter length.
                                            </div>

                                            <div class="col-4 d-none">
                                                <label class="form-label mx-1 mt-1 mb-0">Length Foot</label>
                                                <input class="form-control" id="lFootFixed" name="lFootFixed" style="height: 45px;">
                                            </div>
                                            <div class="col-4 d-none">
                                                <label class="form-label mx-1 mt-1 mb-0">Length Foot x 2</label>
                                                <input class="form-control" id="lFootX2Fixed" name="lFootX2Fixed" style="height: 45px;">
                                            </div>
                                        </div>

                                        <div class="col-6 mb-2">
                                            <label class="form-label mx-1 mt-1 mb-0">Height</label>
                                            <input class="form-control" id="heightFixed" name="heightFixed" style="height: 45px;" placeholder="inch" required>
                                            <div class="invalid-feedback">
                                                Please enter height.
                                            </div>

                                            <div class="col-4 d-none">
                                                <label class="form-label mx-1 mt-1 mb-0">Height Foot</label>
                                                <input class="form-control" id="hFootFixed" name="hFootFixed" style="height: 45px;">
                                            </div>
                                            <div class="col-4 d-none">
                                                <label class="form-label mx-1 mt-1 mb-0">Height Foot x 2</label>
                                                <input class="form-control" id="hFootX2Fixed" name="hFootX2Fixed" style="height: 45px;">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- TOP HEAD -->
                                    <div class="d-flex mb-2">
                                        <div class="col-7 ps-0">
                                            <label class="form-label mx-1 mt-1 mb-0">Head</label>
                                            <select class="form-select text-dark " id="HeadFixed" name="HeadFixed" style="height: 45px;" required>
                                                <option class="d-none" value="" selected>Select Item</option>
                                                <?php
                                                    $query = "SELECT * FROM inventory WHERE category = 'Aluminum'";
                                                    $result = mysqli_query($conn, $query);

                                                    if ($result) 
                                                    {
                                                        $num_rows = mysqli_num_rows($result);
                                                        if ($num_rows > 0) 
                                                        {
                                                            while ($data = mysqli_fetch_assoc($result)) 
                                                            {
                                                                // Check if stock is 0, if yes, disable the option
                                                                $disabled = ($data['stock'] == 0) ? 'disabled' : '';
                                                            ?>
                                                                <option value="<?php echo $data['item_name'] . '|' . $data['dimension'] . '|' . $data['color']; ?>" <?php echo $disabled; ?>>
                                                                    <?php echo $data['item_name']; ?> - <?php echo $data['dimension']; ?> - <?php echo $data['color']; ?> 
                                                                    <?php if ($data['stock'] == 0) { echo "( No Stock) ❌️"; } ?>
                                                                </option>
                                                            <?php
                                                            }
                                                        } 
                                                        else 
                                                        {
                                                            ?>
                                                                <option disabled>No item found. Add item first.</option>
                                                            <?php
                                                        }
                                                    }
                                                ?>
                                            </select>
                                            <div class="invalid-feedback">
                                                Please select an item.
                                            </div>
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">ID</label>
                                            <input class=" form-control" id="hItemIDFixed" name="hItemIDFixed" style="height: 45px;" >
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">EID</label>
                                            <input class=" form-control" id="hEItemIDFixed" name="hEItemIDFixed" style="height: 45px;" >
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Color</label>
                                            <input class=" form-control" id="hColorFixed" name="hColorFixed" style="height: 45px;" >
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Dimension</label>
                                            <input class=" form-control" id="hDimensionFixed" name="hDimensionFixed" style="height: 45px;" >
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Stock</label>
                                            <input class=" form-control" id="hStockFixed" name="hStockFixed" style="height: 45px;" >
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">SRP(PF)</label>
                                            <input class="form-control" id="hSellPriceFixed" name="hSellPriceFixed" style="height: 45px;" >
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Foot</label>
                                            <input class="form-control" id="hFootExcessFixed" name="hFootExcessFixed" style="height: 45px;" >
                                        </div>

                                        <div class="col-5 pr-0">
                                            <label class="form-label mx-1 mb-0" style="margin-top: 21px;"></label>
                                            <div class="input-group">
                                                <span class="input-group-text">₱</span>
                                                <input class="form-control" id="hPriceFixed" name="hPriceFixed" style="height: 45px;" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- TOP HEAD -->

                                    <!-- BOTTOM SILL -->
                                    <div class="d-flex mb-2">
                                        <div class="col-7 ps-0">
                                            <label class="form-label mx-1 mt-1 mb-0">Sill</label>
                                            <select class="form-select text-dark" id="SillFixed" name="SillFixed" style="height: 45px;" required>
                                                <option class="d-none" value="" selected>Select Item</option>
                                                    <?php
                                                                                        $query = "SELECT * FROM inventory WHERE category = 'Aluminum'";
                                                                                        $result = mysqli_query($conn, $query);

                                                                                        if ($result) 
                                                                                        {
                                                                                            $num_rows = mysqli_num_rows($result);
                                                                                            if ($num_rows > 0) 
                                                                                            {
                                                                                                while ($data = mysqli_fetch_assoc($result)) 
                                                                                                {
                                                                                                    // Check if stock is 0, if yes, disable the option
                                                                                                    $disabled = ($data['stock'] == 0) ? 'disabled' : '';
                                                                                                ?>
                                                                                                    <option value="<?php echo $data['item_name'] . '|' . $data['dimension'] . '|' . $data['color']; ?>" <?php echo $disabled; ?>>
                                                                                                        <?php echo $data['item_name']; ?> - <?php echo $data['dimension']; ?> - <?php echo $data['color']; ?> 
                                                                                                        <?php if ($data['stock'] == 0) { echo "( No Stock) ❌️"; } ?>
                                                                                                    </option>
                                                                                                <?php
                                                                                                }
                                                                                            } 
                                                                                            else 
                                                                                            {
                                                                                                ?>
                                                                                                    <option disabled>No item found. Add item first.</option>
                                                                                                <?php
                                                                                            }
                                                                                        }
                                                                                    ?>
                                            </select>
                                            <div class="invalid-feedback">
                                                Please select an item.
                                            </div>
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">ID</label>
                                            <input class="form-control" id="sItemIDFixed" name="sItemIDFixed" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">EID</label>
                                            <input class="form-control" id="sEItemIDFixed" name="sEItemIDFixed" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Color</label>
                                            <input class="form-control" id="sColorFixed" name="sColorFixed" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Dimension</label>
                                            <input class="form-control" id="sDimensionFixed" name="sDimensionFixed" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Stock</label>
                                            <input class="form-control" id="sStockFixed" name="sStockFixed" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">SRP(PF)</label>
                                            <input class="form-control" id="sSellPriceFixed" name="sSellPriceFixed" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Foot</label>
                                            <input class="form-control" id="sFootExcessFixed" name="sFootExcessFixed" style="height: 45px;">
                                        </div>

                                        <div class="col-5 pr-0">
                                            <label class="form-label mx-1 mb-0" style="margin-top: 21px;"></label>
                                            <div class="input-group">
                                                <span class="input-group-text">₱</span>
                                                <input class="form-control" id="sPriceFixed" name="sPriceFixed" style="height: 45px;" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- BOTTOM SILL -->

                                    <!-- SIDE JAMB -->
                                    <div class="d-flex mb-2">
                                        <div class="col-7 ps-0">
                                            <label class="form-label mx-1 mt-1 mb-0">Side Jamb</label>
                                            <select class="form-select text-dark" id="JambFixed" name="JambFixed" style="height: 45px;" required>
                                                <option class="d-none" value="" selected>Select Item</option>
                                                <?php
                                                    $query = "SELECT * FROM inventory WHERE category = 'Aluminum'";
                                                    $result = mysqli_query($conn, $query);

                                                    if ($result) {
                                                        $num_rows = mysqli_num_rows($result);
                                                        if ($num_rows > 0) {
                                                            while ($data = mysqli_fetch_assoc($result)) {
                                                                // Check if stock is 0, if yes, disable the option
                                                                $disabled = ($data['stock'] == 0) ? 'disabled' : '';
                                                ?>
                                                                <option value="<?php echo $data['item_name'] . '|' . $data['dimension'] . '|' . $data['color']; ?>" <?php echo $disabled; ?>>
                                                                    <?php echo $data['item_name']; ?> - <?php echo $data['dimension']; ?> - <?php echo $data['color']; ?> 
                                                                    <?php if ($data['stock'] == 0) { echo "( No Stock) ❌️"; } ?>
                                                                </option>
                                                <?php
                                                            }
                                                        } else {
                                                ?>
                                                            <option disabled>No item found. Add item first.</option>
                                                <?php
                                                        }
                                                    }
                                                ?>
                                            </select>
                                            <div class="invalid-feedback">
                                                Please select an item.
                                            </div>
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">ID</label>
                                            <input class="form-control" id="sjItemIDFixed" name="sjItemIDFixed" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Foot</label>
                                            <input class="form-control" id="sjFootFixed" name="sjFootFixed" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Color</label>
                                            <input class="form-control" id="sjColorFixed" name="sjColorFixed" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Dimension</label>
                                            <input class="form-control" id="sjDimensionFixed" name="sjDimensionFixed" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Stock</label>
                                            <input class="form-control" id="sjStockFixed" name="sjStockFixed" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">SRP(PF)</label>
                                            <input class="form-control" id="sjSellPriceFixed" name="sjSellPriceFixed" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">EID</label>
                                            <input class="form-control" id="sjEItemIDFixed" name="sjEItemIDFixed" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Foot EXC</label>
                                            <input class="form-control" id="sjFootExcessFixed" name="sjFootExcessFixed" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">UEI</label>
                                            <input class="form-control" id="sjUEIFixed" name="sjUEIFixed" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">UNI</label>
                                            <input class="form-control" id="sjUNIFixed" name="sjUNIFixed" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">FEID</label>
                                            <input class="form-control" id="sjFEIDFixed" name="sjFEIDFixed" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">FH</label>
                                            <input class="form-control" id="sjFHFixed" name="sjFHFixed" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">FEID1</label>
                                            <input class="form-control" id="sjFEID1Fixed" name="sjFEID1Fixed" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">SH</label>
                                            <input class="form-control" id="sjSHFixed" name="sjSHFixed" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Half Foot</label>
                                            <input class="form-control" id="sjHFootFixed" name="sjHFootFixed" style="height: 45px;">
                                        </div>

                                        <div class="col-5 pr-0">
                                            <label class="form-label mx-1 mb-0" style="margin-top: 21px;"></label>
                                            <div class="input-group">
                                                <span class="input-group-text">₱</span>
                                                <input class="form-control" id="sjPriceFixed" name="sjPriceFixed" style="height: 45px;" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- SIDE JAMB -->

                                    <!-- GLASS -->
                                    <div class="d-flex mb-2">
                                        <div class="col-7 ps-0">
                                            <label class="form-label mx-1 mt-1 mb-0">Glass</label>
                                            <select class="form-select text-dark" id="GlassFixed" name="GlassFixed" style="height: 45px;" required>
                                                <option class="d-none" value="" selected>Select Item</option>
                                                <?php
                                                    $query = "SELECT * FROM inventory WHERE category = 'Glass'";
                                                    $result = mysqli_query($conn, $query);
                                                    
                                                    if ($result) 
                                                    {
                                                        $num_rows = mysqli_num_rows($result);
                                                        if ($num_rows > 0) 
                                                        {
                                                            while ($data = mysqli_fetch_assoc($result)) 
                                                            {
                                                        ?>
                                                            <option value="<?php echo $data['item_name']; ?>">
                                                                <?php echo $data['color']; ?> - <?php echo $data['dimension']; ?> - <?php echo $data['item_name']; ?>
                                                            </option>
                                                        <?php
                                                        }
                                                    }
                                                    else 
                                                    {
                                                        ?>
                                                            <option disabled>No item found. Add item first.</option>
                                                        <?php
                                                        }
                                                    }
                                                ?>
                                            </select>
                                            <div class="invalid-feedback">
                                                Please select an item.
                                            </div>
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">ID</label>
                                            <input class="form-control" id="gItemIDFixed" name="gItemIDFixed" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">EID</label>
                                            <input class="form-control" id="gEItemIDFixed" name="gEItemIDFixed" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Color</label>
                                            <input class="form-control" id="gColorFixed" name="gColorFixed" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Dimension</label>
                                            <input class="form-control" id="gDimensionFixed" name="gDimensionFixed" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Stock</label>
                                            <input class="form-control" id="gStockFixed" name="gStockFixed" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">SRP(PF)</label>
                                            <input class="form-control" id="gSellPriceFixed" name="gSellPriceFixed" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Foot</label>
                                            <input class="form-control" id="gFootExcessFixed" name="gFootExcessFixed" style="height: 45px;">
                                        </div>

                                        <div class="col-5 pr-0">
                                            <label class="form-label mx-1 mb-0" style="margin-top: 21px;"></label>
                                            <div class="input-group">
                                                <span class="input-group-text">₱</span>
                                                <input class="form-control" id="gPriceFixed" name="gPriceFixed" style="height: 45px;" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- GLASS -->

                                    <input type="hidden" name="projectID" value="<?php echo $project_id; ?>">

                                    <button id="createTaskFixed" type="submit" class="btn btn-primary w-100 mt-2">Save</button>
                                </form>
                            </div>
                        </div>

                        <div id="Casement" class="row d-none">
                            <div class="">
                                <button id="casementBack" class="btn btn-primary">
                                    Back
                                </button>
                            </div>

                            <div class="col-md-6 d-flex justify-content-center align-items-center mb-3">
                                <div class="canvas-container">
                                    <canvas id="casementss"></canvas>
                                </div>
                            </div>

                            <div class="col-md-6">
                                    
                                <form action="add.php?action=task" id="createTaskFormCasement" name="createTaskFormCasement" method="POST" class="needs-validation" novalidate>
                                    <!-- Add form elements here -->
                                    <div class="d-flex row">
                                        <div class="col-6 mb-2">
                                            <label class="form-label mx-1 mt-1 mb-0">Length</label>
                                            <input class="form-control" id="lengthCasement" name="lengthCasement" style="height: 45px;" placeholder="inch" required>
                                            <div class="invalid-feedback">
                                                Please enter length.
                                            </div>

                                            <div class="col-4 d-none">
                                                <label class="form-label mx-1 mt-1 mb-0">Length Foot</label>
                                                <input class="form-control" id="lFootCasement" name="lFootCasement" style="height: 45px;">
                                            </div>
                                            <div class="col-4 d-none">
                                                <label class="form-label mx-1 mt-1 mb-0">Length Foot x 2</label>
                                                <input class="form-control" id="lFootX2Casement" name="lFootX2Casement" style="height: 45px;">
                                            </div>
                                        </div>

                                        <div class="col-6 mb-2">
                                            <label class="form-label mx-1 mt-1 mb-0">Height</label>
                                            <input class="form-control" id="heightCasement" name="heightCasement" style="height: 45px;" placeholder="inch" required>
                                            <div class="invalid-feedback">
                                                Please enter height.
                                            </div>

                                            <div class="col-4 d-none">
                                                <label class="form-label mx-1 mt-1 mb-0">Height Foot</label>
                                                <input class="form-control" id="hFootCasement" name="hFootCasement" style="height: 45px;">
                                            </div>
                                            <div class="col-4 d-none">
                                                <label class="form-label mx-1 mt-1 mb-0">Height Foot x 2</label>
                                                <input class="form-control" id="hFootX2Casement" name="hFootX2Casement" style="height: 45px;">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- TOP HEAD -->
                                    <div class="d-flex mb-2">
                                        <div class="col-7 ps-0">
                                            <label class="form-label mx-1 mt-1 mb-0">Head</label>
                                            <select class="form-select text-dark " id="HeadCasement" name="HeadCasement" style="height: 45px;" required>
                                                <option class="d-none" value="" selected>Select Item</option>
                                                <?php
                                                    $query = "SELECT * FROM inventory WHERE category = 'Aluminum'";
                                                    $result = mysqli_query($conn, $query);

                                                    if ($result) 
                                                    {
                                                        $num_rows = mysqli_num_rows($result);
                                                        if ($num_rows > 0) 
                                                        {
                                                            while ($data = mysqli_fetch_assoc($result)) 
                                                            {
                                                                // Check if stock is 0, if yes, disable the option
                                                                $disabled = ($data['stock'] == 0) ? 'disabled' : '';
                                                            ?>
                                                                <option value="<?php echo $data['item_name'] . '|' . $data['dimension'] . '|' . $data['color']; ?>" <?php echo $disabled; ?>>
                                                                    <?php echo $data['item_name']; ?> - <?php echo $data['dimension']; ?> - <?php echo $data['color']; ?> 
                                                                    <?php if ($data['stock'] == 0) { echo "( No Stock) ❌️"; } ?>
                                                                </option>
                                                            <?php
                                                            }
                                                        } 
                                                        else 
                                                        {
                                                            ?>
                                                                <option disabled>No item found. Add item first.</option>
                                                            <?php
                                                        }
                                                    }
                                                ?>
                                            </select>
                                            <div class="invalid-feedback">
                                                Please select an item.
                                            </div>
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">ID</label>
                                            <input class="form-control" id="hItemIDCasement" name="hItemIDCasement" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">EID</label>
                                            <input class="form-control" id="hEItemIDCasement" name="hEItemIDCasement" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Color</label>
                                            <input class="form-control" id="hColorCasement" name="hColorCasement" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Dimension</label>
                                            <input class="form-control" id="hDimensionCasement" name="hDimensionCasement" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Stock</label>
                                            <input class="form-control" id="hStockCasement" name="hStockCasement" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">SRP(PF)</label>
                                            <input class="form-control" id="hSellPriceCasement" name="hSellPriceCasement" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Foot</label>
                                            <input class="form-control" id="hFootExcessCasement" name="hFootExcessCasement" style="height: 45px;">
                                        </div>

                                        <div class="col-5 pr-0">
                                            <label class="form-label mx-1 mb-0" style="margin-top: 21px;"></label>
                                            <div class="input-group">
                                                <span class="input-group-text">₱</span>
                                                <input class="form-control" id="hPriceCasement" name="hPriceCasement" style="height: 45px;" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- TOP HEAD -->

                                    <!-- BOTTOM SILL -->
                                    <div class="d-flex mb-2">
                                        <div class="col-7 ps-0">
                                            <label class="form-label mx-1 mt-1 mb-0">Sill</label>
                                            <select class="form-select text-dark" id="SillCasement" name="SillCasement" style="height: 45px;" required>
                                                <option class="d-none" value="" selected>Select Item</option>
                                                <?php
                                                    $query = "SELECT * FROM inventory WHERE category = 'Aluminum'";
                                                    $result = mysqli_query($conn, $query);

                                                    if ($result) 
                                                    {
                                                        $num_rows = mysqli_num_rows($result);
                                                        if ($num_rows > 0) 
                                                        {
                                                            while ($data = mysqli_fetch_assoc($result)) 
                                                            {
                                                                // Check if stock is 0, if yes, disable the option
                                                                $disabled = ($data['stock'] == 0) ? 'disabled' : '';
                                                            ?>
                                                                <option value="<?php echo $data['item_name'] . '|' . $data['dimension'] . '|' . $data['color']; ?>" <?php echo $disabled; ?>>
                                                                    <?php echo $data['item_name']; ?> - <?php echo $data['dimension']; ?> - <?php echo $data['color']; ?> 
                                                                    <?php if ($data['stock'] == 0) { echo "( No Stock) ❌️"; } ?>
                                                                </option>
                                                            <?php
                                                            }
                                                        } 
                                                        else 
                                                        {
                                                            ?>
                                                                <option disabled>No item found. Add item first.</option>
                                                            <?php
                                                        }
                                                    }
                                                ?>
                                            </select>
                                            <div class="invalid-feedback">
                                                Please select an item.
                                            </div>
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">ID</label>
                                            <input class="form-control" id="sItemIDCasement" name="sItemIDCasement" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">EID</label>
                                            <input class="form-control" id="sEItemIDCasement" name="sEItemIDCasement" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Color</label>
                                            <input class="form-control" id="sColorCasement" name="sColorCasement" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Dimension</label>
                                            <input class="form-control" id="sDimensionCasement" name="sDimensionCasement" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Stock</label>
                                            <input class="form-control" id="sStockCasement" name="sStockCasement" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">SRP(PF)</label>
                                            <input class="form-control" id="sSellPriceCasement" name="sSellPriceCasement" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Foot</label>
                                            <input class="form-control" id="sFootExcessCasement" name="sFootExcessCasement" style="height: 45px;">
                                        </div>

                                        <div class="col-5 pr-0">
                                            <label class="form-label mx-1 mb-0" style="margin-top: 21px;"></label>
                                            <div class="input-group">
                                                <span class="input-group-text">₱</span>
                                                <input class="form-control" id="sPriceCasement" name="sPriceCasement" style="height: 45px;" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- BOTTOM SILL -->

                                    <!-- SIDE JAMB -->
                                    <div class="d-flex mb-2">
                                        <div class="col-7 ps-0">
                                            <label class="form-label mx-1 mt-1 mb-0">Jamb</label>
                                            <select class="form-select text-dark" id="JambCasement" name="JambCasement" style="height: 45px;" required>
                                                <option class="d-none" value="" selected>Select Item</option>
                                                <?php
                                                    $query = "SELECT * FROM inventory WHERE category = 'Aluminum'";
                                                    $result = mysqli_query($conn, $query);

                                                    if ($result) 
                                                    {
                                                        $num_rows = mysqli_num_rows($result);
                                                        if ($num_rows > 0) 
                                                        {
                                                            while ($data = mysqli_fetch_assoc($result)) 
                                                            {
                                                                // Check if stock is 0, if yes, disable the option
                                                                $disabled = ($data['stock'] == 0) ? 'disabled' : '';
                                                            ?>
                                                                <option value="<?php echo $data['item_name'] . '|' . $data['dimension'] . '|' . $data['color']; ?>" <?php echo $disabled; ?>>
                                                                    <?php echo $data['item_name']; ?> - <?php echo $data['dimension']; ?> - <?php echo $data['color']; ?> 
                                                                    <?php if ($data['stock'] == 0) { echo "( No Stock) ❌️"; } ?>
                                                                </option>
                                                            <?php
                                                            }
                                                        } 
                                                        else 
                                                        {
                                                            ?>
                                                                <option disabled>No item found. Add item first.</option>
                                                            <?php
                                                        }
                                                    }
                                                ?>
                                            </select>
                                            <div class="invalid-feedback">
                                                Please select an item.
                                            </div>
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">ID</label>
                                            <input class="form-control" id="sjItemIDCasement" name="sjItemIDCasement" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Foot</label>
                                            <input class="form-control" id="sjFootCasement" name="sjFootCasement" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Color</label>
                                            <input class="form-control" id="sjColorCasement" name="sjColorCasement" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Dimension</label>
                                            <input class="form-control" id="sjDimensionCasement" name="sjDimensionCasement" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Stock</label>
                                            <input class="form-control" id="sjStockCasement" name="sjStockCasement" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">SRP(PF)</label>
                                            <input class="form-control" id="sjSellPriceCasement" name="sjSellPriceCasement" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">EID</label>
                                            <input class="form-control" id="sjEItemIDCasement" name="sjEItemIDCasement" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Foot EXC</label>
                                            <input class="form-control" id="sjFootExcessCasement" name="sjFootExcessCasement" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">UEI</label>
                                            <input class="form-control" id="sjUEICasement" name="sjUEICasement" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">UNI</label>
                                            <input class="form-control" id="sjUNICasement" name="sjUNICasement" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">FEID</label>
                                            <input class="form-control" id="sjFEIDCasement" name="sjFEIDCasement" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">FH</label>
                                            <input class="form-control" id="sjFHCasement" name="sjFHCasement" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">FEID1</label>
                                            <input class="form-control" id="sjFEID1Casement" name="sjFEID1Casement" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">SH</label>
                                            <input class="form-control" id="sjSHCasement" name="sjSHCasement" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Half Foot</label>
                                            <input class="form-control" id="sjHFootCasement" name="sjHFootCasement" style="height: 45px;">
                                        </div>

                                        <div class="col-5 pr-0">
                                            <label class="form-label mx-1 mb-0" style="margin-top: 21px;"></label>
                                            <div class="input-group">
                                                <span class="input-group-text">₱</span>
                                                <input class="form-control" id="sjPriceCasement" name="sjPriceCasement" style="height: 45px;" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- SIDE JAMB -->

                                    <!-- GLASS -->
                                    <div class="d-flex mb-2">
                                        <div class="col-7 ps-0">
                                            <label class="form-label mx-1 mt-1 mb-0">Glass</label>
                                            <select class="form-select text-dark" id="GlassCasement" name="GlassCasement" style="height: 45px;" required>
                                                <option class="d-none" value="" selected>Select Item</option>
                                                <?php
                                                    $query = "SELECT * FROM inventory WHERE category = 'Glass'";
                                                    $result = mysqli_query($conn, $query);
                                                    
                                                    if ($result) 
                                                    {
                                                        $num_rows = mysqli_num_rows($result);
                                                        if ($num_rows > 0) 
                                                        {
                                                            while ($data = mysqli_fetch_assoc($result)) 
                                                            {
                                                        ?>
                                                            <option value="<?php echo $data['item_name']; ?>">
                                                                <?php echo $data['color']; ?> - <?php echo $data['dimension']; ?> - <?php echo $data['item_name']; ?>
                                                            </option>
                                                        <?php
                                                        }
                                                    }
                                                    else 
                                                    {
                                                        ?>
                                                            <option disabled>No item found. Add item first.</option>
                                                        <?php
                                                        }
                                                    }
                                                ?>
                                            </select>
                                            <div class="invalid-feedback">
                                                Please select an item.
                                            </div>
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">ID</label>
                                            <input class="form-control" id="gItemIDCasement" name="gItemIDCasement" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">EID</label>
                                            <input class="form-control" id="gEItemIDCasement" name="gEItemIDCasement" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Color</label>
                                            <input class="form-control" id="gColorCasement" name="gColorCasement" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Dimension</label>
                                            <input class="form-control" id="gDimensionCasement" name="gDimensionCasement" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Stock</label>
                                            <input class="form-control" id="gStockCasement" name="gStockCasement" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">SRP(PF)</label>
                                            <input class="form-control" id="gSellPriceCasement" name="gSellPriceCasement" style="height: 45px;">
                                        </div>

                                        <div class="col-2 d-none">
                                            <label class="form-label mx-1 mt-1 mb-0">Foot</label>
                                            <input class="form-control" id="gFootExcessCasement" name="gFootExcessCasement" style="height: 45px;">
                                        </div>

                                        <div class="col-5 pr-0">
                                            <label class="form-label mx-1 mb-0" style="margin-top: 21px;"></label>
                                            <div class="input-group">
                                                <span class="input-group-text">₱</span>
                                                <input class="form-control" id="gPriceCasement" name="gPriceCasement" style="height: 45px;" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- GLASS -->

                                    <input type="hidden" name="projectID" value="<?php echo $project_id; ?>">

                                    <button id="createTaskCasement" type="submit" class="btn btn-primary w-100 mt-2">Save</button>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>

        <!-- End of Main Content -->

        <!-- Footer -->
            <?php include('include/footer.php'); ?>
        <!-- End of Footer -->
    </div>
    <!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->

<style>
    #twoPSlider {
        opacity: 0;
        transform: scale(0.5);
        transition: opacity 0.5s ease, transform 0.5s ease;
    }

    #twoPSlider.show {
        opacity: 1;
        transform: scale(1);
        transition: opacity 0.5s ease, transform 0.5s ease;
    }

    #twoPSlider.zoom-out {
        opacity: 1;
        transform: scale(0.5);
        transition: opacity 0.5s ease, transform 1s ease;
    }

    #Awning {
        opacity: 0;
        transform: scale(0.5);
        transition: opacity 0.5s ease, transform 0.5s ease;
    }

    #Awning.show {
        opacity: 1;
        transform: scale(1);
        transition: opacity 0.5s ease, transform 0.5s ease;
    }

    #Awning.zoom-out {
        opacity: 1;
        transform: scale(0.5);
        transition: opacity 0.5s ease, transform 1s ease;
    }

    #Fixed {
        opacity: 0;
        transform: scale(0.5);
        transition: opacity 0.5s ease, transform 0.5s ease;
    }

    #Fixed.show {
        opacity: 1;
        transform: scale(1);
        transition: opacity 0.5s ease, transform 0.5s ease;
    }

    #Fixed.zoom-out {
        opacity: 1;
        transform: scale(0.5);
        transition: opacity 0.5s ease, transform 1s ease;
    }

    #Casement {
        opacity: 0;
        transform: scale(0.5);
        transition: opacity 0.5s ease, transform 0.5s ease;
    }

    #Casement.show {
        opacity: 1;
        transform: scale(1);
        transition: opacity 0.5s ease, transform 0.5s ease;
    }

    #Casement.zoom-out {
        opacity: 1;
        transform: scale(0.5);
        transition: opacity 0.5s ease, transform 1s ease;
    }
</style>

<script>
    $('#twoPSBack').on('click', function() {
        var slider = $('#twoPSlider');
        var taskButton = $('#taskButton');
        var createTaskForm = $('#createTaskForm');

        // Trigger the zoom-out effect on the #twoPSlider
        slider.removeClass('show').addClass('zoom-out');

        // After animation ends, hide #twoPSlider and show #taskButton
        setTimeout(function() {
            slider.addClass('d-none'); // Hide #twoPSlider
            slider.removeClass('zoom-out'); // Reset zoom-out class

            // Show the #taskButton by removing the d-none class
            taskButton.removeClass('d-none').addClass('show');

            // Reset the form fields
            if (createTaskForm.length > 0) {
                createTaskForm[0].reset();  // Use raw DOM reset method
                createTaskForm.removeClass('was-validated');
            }

        }, 500); // Matches the transition time of the zoom-out animation
    });

    $('#twoPS').on('click', function() {
        var slider = $('#twoPSlider');
        var taskButton = $('#taskButton');
        
        // Toggle visibility with zoom animation
        if (slider.hasClass('show')) {
            slider.removeClass('show');
            setTimeout(function() {
                slider.addClass('d-none');
            }, 100); // Wait for animation to finish before hiding
        } else {
            slider.removeClass('d-none');
            setTimeout(function() {
                slider.addClass('show');
            }, 100); // Add a small delay for smoother transition

            $('#taskButton').addClass('d-none');

            setTimeout(function() {
                resizeCanvas();
            }, 100); // Ensure canvas resizes after transition
        }
    });

    $('#awningBack').on('click', function() {
        var slider = $('#Awning');
        var taskButton = $('#taskButton');
        var createTaskForm = $('#createTaskFormAwning');

        // Trigger the zoom-out effect on the #twoPSlider
        slider.removeClass('show').addClass('zoom-out');

        // After animation ends, hide #twoPSlider and show #taskButton
        setTimeout(function() {
            slider.addClass('d-none'); // Hide #twoPSlider
            slider.removeClass('zoom-out'); // Reset zoom-out class

            // Show the #taskButton by removing the d-none class
            taskButton.removeClass('d-none').addClass('show');

            if (createTaskForm.length > 0) {
                createTaskForm[0].reset();  // Use raw DOM reset method
                createTaskForm.removeClass('was-validated');
            }
        }, 500); // Matches the transition time of the zoom-out animation
    });

    $('#awningS').on('click', function() {
        var slider = $('#Awning');
        var taskButton = $('#taskButton');
        
        // Toggle visibility with zoom animation
        if (slider.hasClass('show')) {
            slider.removeClass('show');
            setTimeout(function() {
                slider.addClass('d-none');
            }, 100); // Wait for animation to finish before hiding
        } else {
            slider.removeClass('d-none');
            setTimeout(function() {
                slider.addClass('show');
            }, 100); // Add a small delay for smoother transition

            $('#taskButton').addClass('d-none');

            setTimeout(function() {
                resizeCanvas1();
            }, 100); // Ensure canvas resizes after transition
        }
    });

    $('#fixedBack').on('click', function() {
        var slider = $('#Fixed');
        var taskButton = $('#taskButton');
        var createTaskForm = $('#createTaskFormFixed');

        // Trigger the zoom-out effect on the #twoPSlider
        slider.removeClass('show').addClass('zoom-out');

        // After animation ends, hide #twoPSlider and show #taskButton
        setTimeout(function() {
            slider.addClass('d-none'); // Hide #twoPSlider
            slider.removeClass('zoom-out'); // Reset zoom-out class

            // Show the #taskButton by removing the d-none class
            taskButton.removeClass('d-none').addClass('show');

            if (createTaskForm.length > 0) {
                createTaskForm[0].reset();  // Use raw DOM reset method
                createTaskForm.removeClass('was-validated');
            }
        }, 500); // Matches the transition time of the zoom-out animation
    });

    $('#fixedS').on('click', function() {
        var slider = $('#Fixed');
        var taskButton = $('#taskButton');
        
        // Toggle visibility with zoom animation
        if (slider.hasClass('show')) {
            slider.removeClass('show');
            setTimeout(function() {
                slider.addClass('d-none');
            }, 100); // Wait for animation to finish before hiding
        } else {
            slider.removeClass('d-none');
            setTimeout(function() {
                slider.addClass('show');
            }, 100); // Add a small delay for smoother transition

            $('#taskButton').addClass('d-none');

            setTimeout(function() {
                resizeCanvas2();
            }, 100); // Ensure canvas resizes after transition
        }
    });

    $('#casementBack').on('click', function() {
        var slider = $('#Casement');
        var taskButton = $('#taskButton');
        var createTaskForm = $('#createTaskFormCasement');

        // Trigger the zoom-out effect on the #twoPSlider
        slider.removeClass('show').addClass('zoom-out');

        // After animation ends, hide #twoPSlider and show #taskButton
        setTimeout(function() {
            slider.addClass('d-none'); // Hide #twoPSlider
            slider.removeClass('zoom-out'); // Reset zoom-out class

            // Show the #taskButton by removing the d-none class
            taskButton.removeClass('d-none').addClass('show');

            if (createTaskForm.length > 0) {
                createTaskForm[0].reset();  // Use raw DOM reset method
                createTaskForm.removeClass('was-validated');
            }
        }, 500); // Matches the transition time of the zoom-out animation
    });

    $('#casementS').on('click', function() {
        var slider = $('#Casement');
        var taskButton = $('#taskButton');
        
        // Toggle visibility with zoom animation
        if (slider.hasClass('show')) {
            slider.removeClass('show');
            setTimeout(function() {
                slider.addClass('d-none');
            }, 100); // Wait for animation to finish before hiding
        } else {
            slider.removeClass('d-none');
            setTimeout(function() {
                slider.addClass('show');
            }, 100); // Add a small delay for smoother transition

            $('#taskButton').addClass('d-none');

            setTimeout(function() {
                resizeCanvas3();
            }, 100); // Ensure canvas resizes after transition
        }
    });

    document.getElementById('length').addEventListener('input', drawtwopanel);
    document.getElementById('height').addEventListener('input', drawtwopanel);
    document.getElementById('lengthAwning').addEventListener('input', drawawning);
    document.getElementById('heightAwning').addEventListener('input', drawawning);
    document.getElementById('lengthFixed').addEventListener('input', drawfixed);
    document.getElementById('heightFixed').addEventListener('input', drawfixed);
    document.getElementById('lengthCasement').addEventListener('input', drawcasement);
    document.getElementById('heightCasement').addEventListener('input', drawcasement);

    function drawtwopanel() {
        const lengthInput = document.getElementById("length").value;
        const heightInput = document.getElementById("height").value;
        
        const canvas = document.getElementById('twoPanelSliderss');
        const ctx = canvas.getContext('2d');
        
        // Check if both inputs are provided and valid
        const inchesLength = parseFloat(lengthInput);
        const inchesHeight = parseFloat(heightInput);

        if (!inchesLength || !inchesHeight) {
            // If inputs are empty or invalid, draw the default two-panel design
            drawTwoPanel(ctx, canvas.width, canvas.height);
            return; // Exit the function
        }

        // Convert inches to pixels
        const pixelsPerInch = 5; // Adjustable scale
        const rectLength = inchesLength * pixelsPerInch;
        const rectHeight = inchesHeight * pixelsPerInch;

        // Clear previous drawings
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        
        // Calculate the position to center the rectangle
        const startX = (canvas.width - rectLength) / 2;
        const startY = (canvas.height - rectHeight) / 2;

        // Fill the rectangle with blue color (representing the glass)**
        ctx.fillStyle = '#87CEEB'; // Light blue with 20% opacity
        ctx.fillRect(startX, startY, rectLength, rectHeight);

        // Draw the rectangle (representing a window/door)
        ctx.beginPath();
        ctx.rect(startX, startY, rectLength, rectHeight);
        ctx.strokeStyle = 'black';
        ctx.lineWidth = 2;
        ctx.stroke();

        // Add a vertical line in the middle of the rectangle
        const centerX = startX + rectLength / 2;
        ctx.beginPath();
        ctx.moveTo(centerX, startY);          // Starting point of the vertical line
        ctx.lineTo(centerX, startY + rectHeight);  // Ending point of the vertical line
        ctx.strokeStyle = 'black';
        ctx.lineWidth = 2;
        ctx.stroke();

        // Draw the length at the top center of the rectangle
        ctx.font = "16px Arial";
        ctx.fillStyle = "black";
        ctx.textAlign = "center";
        ctx.fillText(`${inchesLength}`, startX + rectLength / 2, startY - 10);

        // Draw the height at the right center of the rectangle
        ctx.save();
        ctx.translate(startX + rectLength + 20, startY + rectHeight / 2);
        ctx.rotate(-Math.PI / 0.5);  // Rotate the text for height vertically
        ctx.fillText(`${inchesHeight}`, 0, 0);
        ctx.restore();
    }

    function drawTwoPanel(ctx, width, height) {
        ctx.clearRect(0, 0, width, height);

        // Calculate scale and center offset
        const scale = Math.min(width / 600, height / 600);
        const offsetX = (width - 600 * scale) / 2;
        const offsetY = (height - 600 * scale) / 2;

        ctx.save(); // Save the current state
        ctx.translate(offsetX, offsetY); // Translate to center the drawing
        ctx.scale(scale, scale); // Scale the drawing

        // Draw the outer frame
        ctx.strokeRect(50, 50, 500, 500);

        // Draw the inner frame
        ctx.strokeRect(60, 60, 480, 480);

        // Draw the middle dividing line
        ctx.beginPath();
        ctx.moveTo(300, 60);
        ctx.lineTo(300, 540);
        ctx.stroke();

        // Draw the top length indicator
        ctx.beginPath();
        ctx.moveTo(60, 20); // Move this higher
        ctx.lineTo(60, 30);
        ctx.moveTo(540, 20); // Move this higher
        ctx.lineTo(540, 30);
        ctx.moveTo(60, 25); // Move this higher
        ctx.lineTo(540, 25);
        ctx.stroke();

        // Draw arrow heads for length
        ctx.beginPath();
        ctx.moveTo(60, 20);
        ctx.lineTo(50, 25);
        ctx.lineTo(60, 30);
        ctx.moveTo(540, 20);
        ctx.lineTo(550, 25);
        ctx.lineTo(540, 30);
        ctx.fill();

        // Add "Length" text above the length indicator
        ctx.font = '14px Arial';
        ctx.fillText('Length', 280, 15);

        // Draw the right height indicator
        ctx.beginPath();
        ctx.moveTo(570, 60); // Left point of arrow
        ctx.lineTo(580, 60); // Top point of vertical line
        ctx.lineTo(575, 50); // Left point of arrow
        ctx.moveTo(575, 60); // Top point of vertical line
        ctx.lineTo(575, 540); // Bottom point of vertical line
        ctx.moveTo(580, 540); // Bottom point of vertical line
        ctx.lineTo(570, 540); // Bottom point of arrow
        ctx.moveTo(575, 540); // Bottom point of vertical line
        ctx.lineTo(575, 550); // Bottom point of arrow
        ctx.stroke();

        // Draw arrow heads for height
        ctx.beginPath();
        ctx.moveTo(570, 60);
        ctx.lineTo(575, 50);
        ctx.lineTo(580, 60);
        ctx.moveTo(570, 540);
        ctx.lineTo(575, 550);
        ctx.lineTo(580, 540);
        ctx.fill();

        // Add "Height" text beside the height indicator
        ctx.save();
        ctx.translate(585, 280);
        ctx.rotate(Math.PI / 2);
        ctx.fillText('Height', 0, 0);
        ctx.restore();

        // Add text for the different parts
        ctx.font = '14px Arial';
        ctx.fillText('Head', 280, 45);
        ctx.fillText('Sill', 290, 565);
        ctx.fillText('Rails', 170, 535);

        // Rotate and add "Side Jamb" text
        ctx.save();
        ctx.translate(45, 320);
        ctx.rotate(-Math.PI / 2);
        ctx.fillText('Jamb', 0, 0);
        ctx.restore();

        ctx.save();
        ctx.translate(295, 320);
        ctx.rotate(-Math.PI / 2);
        ctx.fillText('Interlocker', 0, 0);
        ctx.restore();

        ctx.save();
        ctx.translate(535, 320);
        ctx.rotate(-Math.PI / 2);
        ctx.fillText('Stiles', 0, 0);
        ctx.restore();

        // Add 'S' and 'Glass' text
        ctx.font = '100px Arial';
        ctx.fillText('S', 150, 330);
        ctx.fillText('S', 380, 330);

        ctx.restore(); // Restore the previous state
    }

    function drawawning() {
        const lengthInput = document.getElementById("lengthAwning").value;
        const heightInput = document.getElementById("heightAwning").value;
        
        const canvas = document.getElementById('awningss');
        const ctx = canvas.getContext('2d');
        
        // Check if both inputs are provided and valid
        const inchesLength = parseFloat(lengthInput);
        const inchesHeight = parseFloat(heightInput);

        if (!inchesLength || !inchesHeight) {
            // If inputs are empty or invalid, draw the default two-panel design
            drawAwning(ctx, canvas.width, canvas.height);
            return; // Exit the function
        }

        // Convert inches to pixels
        const pixelsPerInch = 5; // Adjustable scale
        const rectLength = inchesLength * pixelsPerInch;
        const rectHeight = inchesHeight * pixelsPerInch;

        // Clear previous drawings
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        
        // Calculate the position to center the rectangle
        const startX = (canvas.width - rectLength) / 2;
        const startY = (canvas.height - rectHeight) / 2;

        // Fill the rectangle with blue color (representing the glass)
        ctx.fillStyle = '#87CEEB'; // Light blue with 20% opacity
        ctx.fillRect(startX, startY, rectLength, rectHeight);

        // Draw the rectangle (representing a window/door)
        ctx.beginPath();
        ctx.rect(startX, startY, rectLength, rectHeight);
        ctx.strokeStyle = 'black';
        ctx.lineWidth = 2;
        ctx.stroke();

        // Add a flipped "V" shape inside the window
        const centerX = startX + rectLength / 2;
        const centerY = startY + rectHeight;  // Bottom center of the rectangle

        // Draw the flipped "V" shape with arms extending to the top corners
        ctx.beginPath();
        ctx.moveTo(centerX, centerY);  // Bottom point of the "V"
        ctx.lineTo(startX, startY);  // Top-left corner of the rectangle
        ctx.moveTo(centerX, centerY);  // Reset to bottom point of the "V"
        ctx.lineTo(startX + rectLength, startY);  // Top-right corner of the rectangle
        ctx.strokeStyle = 'black';
        ctx.lineWidth = 2;
        ctx.stroke();
        // Draw the length at the top center of the rectangle
        ctx.font = "16px Arial";
        ctx.fillStyle = "black";
        ctx.textAlign = "center";
        ctx.fillText(`${inchesLength}`, startX + rectLength / 2, startY - 10);

        // Draw the height at the right center of the rectangle
        ctx.save();
        ctx.translate(startX + rectLength + 20, startY + rectHeight / 2);
        ctx.rotate(-Math.PI / 0.5);  // Rotate the text for height vertically
        ctx.fillText(`${inchesHeight}`, 0, 0);
        ctx.restore();
    }

    function drawAwning(ctx, width, height) {
        ctx.clearRect(0, 0, width, height);

        // Calculate scale and center offset
        const scale = Math.min(width / 600, height / 600);
        const offsetX = (width - 600 * scale) / 2;
        const offsetY = (height - 600 * scale) / 2;

        ctx.save(); // Save the current state
        ctx.translate(offsetX, offsetY); // Translate to center the drawing
        ctx.scale(scale, scale); // Scale the drawing

        // Draw the outer frame
        ctx.strokeRect(50, 50, 500, 500);

        // Draw the inner frame
        ctx.strokeRect(60, 60, 480, 480);

        // Draw the top length indicator
        ctx.beginPath();
        ctx.moveTo(60, 20);
        ctx.lineTo(60, 30);
        ctx.moveTo(540, 20);
        ctx.lineTo(540, 30);
        ctx.moveTo(60, 25);
        ctx.lineTo(540, 25);
        ctx.stroke();

        // Draw arrow heads for length
        ctx.beginPath();
        ctx.moveTo(60, 20);
        ctx.lineTo(50, 25);
        ctx.lineTo(60, 30);
        ctx.moveTo(540, 20);
        ctx.lineTo(550, 25);
        ctx.lineTo(540, 30);
        ctx.fill();

        // Add "Length" text above the length indicator
        ctx.font = '14px Arial';
        ctx.fillText('Length', 280, 15);

        // Draw the right height indicator
        ctx.beginPath();
        ctx.moveTo(570, 60);
        ctx.lineTo(580, 60);
        ctx.lineTo(575, 50);
        ctx.moveTo(575, 60);
        ctx.lineTo(575, 540);
        ctx.moveTo(580, 540);
        ctx.lineTo(570, 540);
        ctx.moveTo(575, 540);
        ctx.lineTo(575, 550);
        ctx.stroke();

        // Draw arrow heads for height
        ctx.beginPath();
        ctx.moveTo(570, 60);
        ctx.lineTo(575, 50);
        ctx.lineTo(580, 60);
        ctx.moveTo(570, 540);
        ctx.lineTo(575, 550);
        ctx.lineTo(580, 540);
        ctx.fill();

        // Add "Height" text beside the height indicator
        ctx.save();
        ctx.translate(585, 280);
        ctx.rotate(Math.PI / 2);
        ctx.fillText('Height', 0, 0);
        ctx.restore();

        // Add text for the different parts
        ctx.font = '14px Arial';
        ctx.fillText('Head', 280, 45);
        ctx.fillText('Sill', 290, 565);

        // Rotate and add "Side Jamb" text
        ctx.save();
        ctx.translate(45, 320);
        ctx.rotate(-Math.PI / 2);
        ctx.fillText('Jamb', 0, 0);
        ctx.restore();

        // Draw a proper "V" shape in the center
        ctx.beginPath();
        ctx.moveTo(60, 60); // Top of the "V"
        ctx.lineTo(310, 540); // Left leg of the "V"
        ctx.moveTo(540, 60); // Top of the "V"
        ctx.lineTo(310, 540); // Right leg of the "V"
        ctx.stroke();

        ctx.font = '100px Arial';
        ctx.fillText('U', 275, 330);

        ctx.restore(); // Restore the previous state
    }

    function drawfixed() {
        const lengthInput = document.getElementById("lengthFixed").value;
        const heightInput = document.getElementById("heightFixed").value;
        
        const canvas = document.getElementById('fixedss');
        const ctx = canvas.getContext('2d');
        
        // Check if both inputs are provided and valid
        const inchesLength = parseFloat(lengthInput);
        const inchesHeight = parseFloat(heightInput);

        if (!inchesLength || !inchesHeight) {
            // If inputs are empty or invalid, draw the default two-panel design
            drawFixed(ctx, canvas.width, canvas.height);
            return; // Exit the function
        }

        // Convert inches to pixels
        const pixelsPerInch = 5; // Adjustable scale
        const rectLength = inchesLength * pixelsPerInch;
        const rectHeight = inchesHeight * pixelsPerInch;

        // Clear previous drawings
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        
        // Calculate the position to center the rectangle
        const startX = (canvas.width - rectLength) / 2;
        const startY = (canvas.height - rectHeight) / 2;

        // Fill the rectangle with blue color (representing the glass)
        ctx.fillStyle = '#87CEEB'; // Light blue with 20% opacity
        ctx.fillRect(startX, startY, rectLength, rectHeight);

        // Draw the rectangle (representing a window/door)
        ctx.beginPath();
        ctx.rect(startX, startY, rectLength, rectHeight);
        ctx.strokeStyle = 'black';
        ctx.lineWidth = 2;
        ctx.stroke();

        
        // Draw the length at the top center of the rectangle
        ctx.font = "16px Arial";
        ctx.fillStyle = "black";
        ctx.textAlign = "center";
        ctx.fillText(`${inchesLength}`, startX + rectLength / 2, startY - 10);

        // Draw the height at the right center of the rectangle
        ctx.save();
        ctx.translate(startX + rectLength + 20, startY + rectHeight / 2);
        ctx.rotate(-Math.PI / 0.5);  // Rotate the text for height vertically
        ctx.fillText(`${inchesHeight}`, 0, 0);
        ctx.restore();
    }

    function drawFixed(ctx, width, height) {
        ctx.clearRect(0, 0, width, height);

        // Calculate scale and center offset
        const scale = Math.min(width / 600, height / 600);
        const offsetX = (width - 600 * scale) / 2;
        const offsetY = (height - 600 * scale) / 2;

        ctx.save(); // Save the current state
        ctx.translate(offsetX, offsetY); // Translate to center the drawing
        ctx.scale(scale, scale); // Scale the drawing

        // Draw the outer frame
        ctx.strokeRect(50, 50, 500, 500);

        // Draw the inner frame
        ctx.strokeRect(60, 60, 480, 480);

        // Draw the top length indicator
        ctx.beginPath();
        ctx.moveTo(60, 20);
        ctx.lineTo(60, 30);
        ctx.moveTo(540, 20);
        ctx.lineTo(540, 30);
        ctx.moveTo(60, 25);
        ctx.lineTo(540, 25);
        ctx.stroke();

        // Draw arrow heads for length
        ctx.beginPath();
        ctx.moveTo(60, 20);
        ctx.lineTo(50, 25);
        ctx.lineTo(60, 30);
        ctx.moveTo(540, 20);
        ctx.lineTo(550, 25);
        ctx.lineTo(540, 30);
        ctx.fill();

        // Add "Length" text above the length indicator
        ctx.font = '14px Arial';
        ctx.fillText('Length', 280, 15);

        // Draw the right height indicator
        ctx.beginPath();
        ctx.moveTo(570, 60);
        ctx.lineTo(580, 60);
        ctx.lineTo(575, 50);
        ctx.moveTo(575, 60);
        ctx.lineTo(575, 540);
        ctx.moveTo(580, 540);
        ctx.lineTo(570, 540);
        ctx.moveTo(575, 540);
        ctx.lineTo(575, 550);
        ctx.stroke();

        // Draw arrow heads for height
        ctx.beginPath();
        ctx.moveTo(570, 60);
        ctx.lineTo(575, 50);
        ctx.lineTo(580, 60);
        ctx.moveTo(570, 540);
        ctx.lineTo(575, 550);
        ctx.lineTo(580, 540);
        ctx.fill();

        // Add "Height" text beside the height indicator
        ctx.save();
        ctx.translate(585, 280);
        ctx.rotate(Math.PI / 2);
        ctx.fillText('Height', 0, 0);
        ctx.restore();

        // Add text for the different parts
        ctx.font = '14px Arial';
        ctx.fillText('Head', 280, 45);
        ctx.fillText('Sill', 290, 565);

        // Rotate and add "Side Jamb" text
        ctx.save();
        ctx.translate(45, 320);
        ctx.rotate(-Math.PI / 2);
        ctx.fillText('Jamb', 0, 0);
        ctx.restore();

            // Add 'S' and 'Glass' text
        ctx.font = '100px Arial';
        ctx.fillText('F', 275, 330);

        ctx.restore(); // Restore the previous state
    }

    function drawcasement() {
        const lengthInput = document.getElementById("lengthCasement").value;
        const heightInput = document.getElementById("heightCasement").value;
        
        const canvas = document.getElementById('casementss');
        const ctx = canvas.getContext('2d');
        
        // Check if both inputs are provided and valid
        const inchesLength = parseFloat(lengthInput);
        const inchesHeight = parseFloat(heightInput);

        if (!inchesLength || !inchesHeight) {
            // If inputs are empty or invalid, draw the default two-panel design
            drawCasement(ctx, canvas.width, canvas.height);
            return; // Exit the function
        }

        // Convert inches to pixels
        const pixelsPerInch = 5; // Adjustable scale
        const rectLength = inchesLength * pixelsPerInch;
        const rectHeight = inchesHeight * pixelsPerInch;

        // Clear previous drawings
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        
        // Calculate the position to center the rectangle
        const startX = (canvas.width - rectLength) / 2;
        const startY = (canvas.height - rectHeight) / 2;

        // Fill the rectangle with blue color (representing the glass)
        ctx.fillStyle = '#87CEEB'; // Light blue with 20% opacity
        ctx.fillRect(startX, startY, rectLength, rectHeight);

        // Draw the rectangle (representing a window/door)
        ctx.beginPath();
        ctx.rect(startX, startY, rectLength, rectHeight);
        ctx.strokeStyle = 'black';
        ctx.lineWidth = 2;
        ctx.stroke();

        
        // Draw the length at the top center of the rectangle
        ctx.font = "16px Arial";
        ctx.fillStyle = "black";
        ctx.textAlign = "center";
        ctx.fillText(`${inchesLength}`, startX + rectLength / 2, startY - 10);

        // Draw the height at the right center of the rectangle
        ctx.save();
        ctx.translate(startX + rectLength + 20, startY + rectHeight / 2);
        ctx.rotate(-Math.PI / 0.5);  // Rotate the text for height vertically
        ctx.fillText(`${inchesHeight}`, 0, 0);
        ctx.restore();
    }

    function drawCasement(ctx, width, height) {
        ctx.clearRect(0, 0, width, height);

        // Calculate scale and center offset
        const scale = Math.min(width / 600, height / 600);
        const offsetX = (width - 600 * scale) / 2;
        const offsetY = (height - 600 * scale) / 2;

        ctx.save(); // Save the current state
        ctx.translate(offsetX, offsetY); // Translate to center the drawing
        ctx.scale(scale, scale); // Scale the drawing

        // Draw the outer frame
        ctx.strokeRect(50, 50, 500, 500);

        // Draw the inner frame
        ctx.strokeRect(60, 60, 480, 480);

        // Draw the top length indicator
        ctx.beginPath();
        ctx.moveTo(60, 20);
        ctx.lineTo(60, 30);
        ctx.moveTo(540, 20);
        ctx.lineTo(540, 30);
        ctx.moveTo(60, 25);
        ctx.lineTo(540, 25);
        ctx.stroke();

        // Draw arrow heads for length
        ctx.beginPath();
        ctx.moveTo(60, 20);
        ctx.lineTo(50, 25);
        ctx.lineTo(60, 30);
        ctx.moveTo(540, 20);
        ctx.lineTo(550, 25);
        ctx.lineTo(540, 30);
        ctx.fill();

        // Add "Length" text above the length indicator
        ctx.font = '14px Arial';
        ctx.fillText('Length', 280, 15);

        // Draw the right height indicator
        ctx.beginPath();
        ctx.moveTo(570, 60);
        ctx.lineTo(580, 60);
        ctx.lineTo(575, 50);
        ctx.moveTo(575, 60);
        ctx.lineTo(575, 540);
        ctx.moveTo(580, 540);
        ctx.lineTo(570, 540);
        ctx.moveTo(575, 540);
        ctx.lineTo(575, 550);
        ctx.stroke();

        // Draw arrow heads for height
        ctx.beginPath();
        ctx.moveTo(570, 60);
        ctx.lineTo(575, 50);
        ctx.lineTo(580, 60);
        ctx.moveTo(570, 540);
        ctx.lineTo(575, 550);
        ctx.lineTo(580, 540);
        ctx.fill();

        // Add "Height" text beside the height indicator
        ctx.save();
        ctx.translate(585, 280);
        ctx.rotate(Math.PI / 2);
        ctx.fillText('Height', 0, 0);
        ctx.restore();

        // Add text for the different parts
        ctx.font = '14px Arial';
        ctx.fillText('Head', 280, 45);
        ctx.fillText('Sill', 290, 565);

        // Rotate and add "Side Jamb" text
        ctx.save();
        ctx.translate(45, 320);
        ctx.rotate(-Math.PI / 2);
        ctx.fillText('Jamb', 0, 0);
        ctx.restore();

        // Add 'S' and 'Glass' text
        ctx.font = '100px Arial';
        ctx.fillText('SG', 245, 330);

        ctx.restore(); // Restore the previous state
    }

    function resizeCanvas() {
        var canvas = document.getElementById('twoPanelSliderss');
        var container = canvas.parentElement;
        canvas.width = container.clientWidth;
        canvas.height = container.clientHeight;

        // Get the context and redraw the two-panel drawing
        var ctx = canvas.getContext('2d');
        drawTwoPanel(ctx, canvas.width, canvas.height);

        // If valid length and height are entered, redraw with those values
        const lengthInput = document.getElementById("length").value;
        const heightInput = document.getElementById("height").value;

        if (lengthInput && heightInput) {
            drawtwopanel();  // Redraw the panel with user-provided dimensions
        }
    }

    function resizeCanvas1() {
        var canvas = document.getElementById('awningss');
        var container = canvas.parentElement;
        canvas.width = container.clientWidth;
        canvas.height = container.clientHeight;

        var ctx = canvas.getContext('2d');
        drawAwning(ctx, canvas.width, canvas.height);

        // If valid length and height are entered, redraw with those values
        const lengthInput = document.getElementById("lengthAwning").value;
        const heightInput = document.getElementById("heightAwning").value;

        if (lengthInput && heightInput) {
            drawawning();  // Redraw the panel with user-provided dimensions
        }
    }

    function resizeCanvas2() {
        var canvas = document.getElementById('fixedss');
        var container = canvas.parentElement;
        canvas.width = container.clientWidth;
        canvas.height = container.clientHeight;

        var ctx = canvas.getContext('2d');
        drawFixed(ctx, canvas.width, canvas.height);

        // If valid length and height are entered, redraw with those values
        const lengthInput = document.getElementById("lengthFixed").value;
        const heightInput = document.getElementById("heightFixed").value;

        if (lengthInput && heightInput) {
            drawfixed();  // Redraw the panel with user-provided dimensions
        }
    }

    function resizeCanvas3() {
        var canvas = document.getElementById('casementss');
        var container = canvas.parentElement;
        canvas.width = container.clientWidth;
        canvas.height = container.clientHeight;

        var ctx = canvas.getContext('2d');
        drawCasement(ctx, canvas.width, canvas.height);

        // If valid length and height are entered, redraw with those values
        const lengthInput = document.getElementById("lengthCasement").value;
        const heightInput = document.getElementById("heightCasement").value;

        if (lengthInput && heightInput) {
            drawcasement();  // Redraw the panel with user-provided dimensions
        }
    }

    window.onload = function() {
        resizeCanvas();  // Call the first canvas resize
        resizeCanvas1(); // Call the second canvas resize
        resizeCanvas2(); // Call the second canvas resize
        resizeCanvas3();

        // Add event listeners for window resizing
        window.addEventListener('resize', function() {
            resizeCanvas();
            resizeCanvas1();
            resizeCanvas2();
            resizeCanvas3();
        });
    }
</script>

<script>
    const canvas1 = document.getElementById('twoPanelSlider');
    const ctx1 = canvas1.getContext('2d');

    // Function to draw the frame
    function drawSliderFrame() {
        // Draw the window frame (two-panel slider)
        ctx1.strokeStyle = '#000';
        ctx1.lineWidth = 5;
        ctx1.strokeRect(20, 20, 160, 160); // Outer frame

        // Draw the vertical line in the middle (divider between two panes)
        ctx1.beginPath();
        ctx1.moveTo(20 + 80, 20); // Start at the top-middle of the frame
        ctx1.lineTo(20 + 80, 20 + 160); // End at the bottom-middle of the frame
        ctx1.stroke();
    }

    // Function to draw the glass panes
    function drawSliderGlass() {
        // Glass panes for the slider (left and right)
        ctx1.fillStyle = "#87CEEB"; // Light blue for glass
        ctx1.fillRect(23, 23, 74, 154); // Left glass pane
        ctx1.fillRect(103, 23, 74, 154); // Right glass pane
    }

    // Function to draw arrows and 'S' labels
    function drawSliderArrowsAndLabels() {
        // Set font style for the 'S' text
        ctx1.font = '50px Arial'; // Large font size
        ctx1.textAlign = 'center';
        ctx1.textBaseline = 'middle';
        ctx1.fillStyle = '#000'; // Color of the text

        // Calculate the center points for the 'S' text
        const centerX1 = 20 + 40; // X coordinate of the first 'S'
        const centerX2 = 20 + 120; // X coordinate of the second 'S'
        const centerY = 20 + 160 / 2; // Y coordinate between the two panes

        // Draw arrows below each 'S'
        const arrowY = centerY + 5; // Y position of the arrows
        const arrowLength = 30; // Length of the arrows

        // Function to draw an arrow below an 'S'
        function drawArrow(centerX) {
            ctx1.beginPath();
            ctx1.lineWidth = 1; // Thinner arrows
            ctx1.moveTo(centerX - arrowLength / 2, arrowY); // Left point of the arrow
            ctx1.lineTo(centerX + arrowLength / 2, arrowY); // Right point of the arrow

            // Left arrowhead
            ctx1.moveTo(centerX - arrowLength / 2, arrowY);
            ctx1.lineTo(centerX - arrowLength / 2 + 5, arrowY - 5); // Upper left diagonal
            ctx1.moveTo(centerX - arrowLength / 2, arrowY);
            ctx1.lineTo(centerX - arrowLength / 2 + 5, arrowY + 5); // Lower left diagonal

            // Right arrowhead
            ctx1.moveTo(centerX + arrowLength / 2, arrowY);
            ctx1.lineTo(centerX + arrowLength / 2 - 5, arrowY - 5); // Upper right diagonal
            ctx1.moveTo(centerX + arrowLength / 2, arrowY);
            ctx1.lineTo(centerX + arrowLength / 2 - 5, arrowY + 5); // Lower right diagonal

            ctx1.stroke();
        }

        // Draw arrows for both panes
        drawArrow(centerX1);
        drawArrow(centerX2);
    }

    // Function to draw a vertical line
    function drawVerticalLine1() {
        ctx1.strokeStyle = '#000'; // Line color (black)
        ctx1.lineWidth = 7; // Line thickness

        // Draw a vertical line
        ctx1.beginPath();
        ctx1.moveTo(165, 90); // Starting point of the line (x, y)
        ctx1.lineTo(165, 110); // Ending point of the line (x, y)
        ctx1.stroke(); // Render the line
    }

    // Function to draw a vertical line
    function drawVerticalLine2() {
        ctx1.strokeStyle = '#000'; // Line color (black)
        ctx1.lineWidth = 7; // Line thickness

        // Draw a vertical line
        ctx1.beginPath();
        ctx1.moveTo(35, 90); // Starting point of the line (x, y)
        ctx1.lineTo(35, 110); // Ending point of the line (x, y)
        ctx1.stroke(); // Render the line
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


    const canvas2 = document.getElementById("awningWindow");
    const ctx2 = canvas2.getContext("2d");

    function drawFrame() {
        // Window frame for smaller canvas
        ctx2.strokeStyle = "#000";
        ctx2.lineWidth = 5;
        ctx2.strokeRect(20, 20, 160, 160); // Adjusted to fit the 200x200 canvas
    }

    function drawGlass() {
        // Glass panes (bottom part opens, top part fixed)
        ctx2.fillStyle = "#87CEEB"; // Light blue for glass
        ctx2.fillRect(23, 113, 154, 65); // Bottom glass (adjusted size)
        ctx2.fillRect(23, 23, 154, 100); // Top glass (adjusted size)
    }

    function drawHinges() {
        // Hinges on the bottom for the awning effect
        ctx2.strokeStyle = "#000"; // Black color for the hinges
        ctx2.lineWidth = 3;

        // Left hinge (adjust to make it like the right)
        ctx2.beginPath();
        ctx2.moveTo(20, 25); // Start from the bottom-left of the frame
        ctx2.lineTo(100, 175); // Extend the line downwards
        ctx2.lineTo(180, 25); // Connect to the bottom-right of the frame
        ctx2.stroke();
    }

    function drawArrow(centerX, centerY, direction) {
        const arrowLength = 35; // Length of the arrow
        const arrowWidth = 15; // Width of the arrow

        ctx2.strokeStyle = "#000"; // Color of the arrow
        ctx2.lineWidth = 1; // Thinner arrow

        ctx2.beginPath();

        if (direction === "down") {
            // Draw the vertical arrow pointing down
            ctx2.moveTo(centerX, centerY - arrowLength / 1.7); // Start of the arrow line
            ctx2.lineTo(centerX, centerY + arrowLength / 2); // End of the arrow line

            // Arrowhead
            ctx2.moveTo(centerX - arrowWidth / 2, centerY + arrowLength / 2); // Left diagonal
            ctx2.lineTo(centerX, centerY + arrowLength / 2 + 5); // Tip of the arrowhead
            ctx2.lineTo(centerX + arrowWidth / 2, centerY + arrowLength / 2); // Right diagonal
        } else if (direction === "up") {
            // Draw the vertical arrow pointing up
            ctx2.moveTo(centerX, centerY + arrowLength / 1.7); // Start of the arrow line
            ctx2.lineTo(centerX, centerY - arrowLength / 2); // End of the arrow line

            // Arrowhead
            ctx2.moveTo(centerX - arrowWidth / 2, centerY - arrowLength / 2); // Left diagonal
            ctx2.lineTo(centerX, centerY - arrowLength / 2 - 5); // Tip of the arrowhead
            ctx2.lineTo(centerX + arrowWidth / 2, centerY - arrowLength / 2); // Right diagonal
        }

        ctx2.stroke();
    }

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

        // Draw up and down arrows in the center
        drawArrow(centerX, centerY, "down");
        drawArrow(centerX, centerY, "up");
    }

    drawWindow();

    const canvas3 = document.getElementById('fixedWindow');
    const ctx3 = canvas3.getContext('2d');

    // Function to draw the frame of the fixed window
    function drawFixedWindowFrame() {
        // Draw the window frame
        ctx3.strokeStyle = '#000';
        ctx3.lineWidth = 5;
        ctx3.strokeRect(20, 20, 160, 160); // Outer frame
    }

    // Function to draw the glass of the fixed window
    function drawFixedWindowGlass() {
        // Glass panes (fixed)
        ctx3.fillStyle = '#87CEEB'; // Light blue for glass
        ctx3.fillRect(23, 23, 154, 154); // Single glass pane
    }

    // Function to draw the entire fixed window
    function drawFixedWindow() {
        drawFixedWindowFrame();
        drawFixedWindowGlass();
    }

    // Call the function to draw the fixed window
    drawFixedWindow();

    const canvas4 = document.getElementById('casementWindow');
    const ctx4 = canvas4.getContext('2d');

    // Function to draw the frame of the casement window
    function drawCasementWindowFrame() {
        ctx4.strokeStyle = '#000';
        ctx4.lineWidth = 5;
        ctx4.strokeRect(20, 20, 160, 160); // Outer frame
    }

    // Function to draw the glass pane of the casement window
    function drawCasementWindowGlass() {
        ctx4.fillStyle = '#87CEEB'; // Light blue for glass
        ctx4.fillRect(23, 23, 154, 154); // Single pane covering the whole area
    }

    function drawReturnArrow() {
        ctx4.strokeStyle = '#000'; // Arrow color
        ctx4.lineWidth = 1; // Line width for the arrow

        // Draw the straight part of the arrow (horizontal)
        ctx4.beginPath();
        ctx4.moveTo(120, 120); // Starting point (left)
        ctx4.lineTo(130, 120); // Horizontal line (going right)
        ctx4.stroke();

        // Draw the curved part of the arrow (down and to the left)
        ctx4.beginPath();
        ctx4.arc(130, 100, 20, 0.5 * Math.PI, 1.5 * Math.PI, true); // Left-turning arc
        ctx4.stroke();

        // Draw the arrowhead
        const arrowHeadSize = 7; // Size of the arrowhead
        ctx4.beginPath();
        ctx4.moveTo(120, 120); // Point where the arrowhead starts
        ctx4.lineTo(120 + arrowHeadSize, 120 - arrowHeadSize); // Right side of the arrowhead
        ctx4.moveTo(120, 120); // Move back to the starting point for the second line
        ctx4.lineTo(120 + arrowHeadSize, 120 + arrowHeadSize); // Left side of the arrowhead
        ctx4.stroke(); // Draw the arrowhead

        const arrowHeadSize1 = 7; // Size of the arrowhead
        ctx4.beginPath();
        ctx4.moveTo(130, 80); // Point where the arrowhead starts
        ctx4.lineTo(130 + arrowHeadSize1, 80 - arrowHeadSize1); // Right side of the arrowhead
        ctx4.moveTo(130, 80); // Move back to the starting point for the second line
        ctx4.lineTo(130 + arrowHeadSize1, 80 + arrowHeadSize1); // Left side of the arrowhead
        ctx4.stroke(); // Draw the arrowhead
    }

    // Function to draw a vertical line
    function drawVerticalLine() {
        ctx4.strokeStyle = '#000'; // Line color (black)
        ctx4.lineWidth = 7; // Line thickness

        // Draw a vertical line
        ctx4.beginPath();
        ctx4.moveTo(165, 85); // Starting point of the line (x, y)
        ctx4.lineTo(165, 115); // Ending point of the line (x, y)
        ctx4.stroke(); // Render the line
    }


    // Function to draw the entire casement window
    function drawCasementWindow() {
        drawCasementWindowFrame();
        drawCasementWindowGlass();
        drawReturnArrow(); // Add the return arrow
        drawVerticalLine();
    }

    // Call the function to draw the casement window
    drawCasementWindow();
</script>

<?php
    if(isset($_SESSION['success'])) {
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
    $(document).on('click', '#createTask', function (e) {
        e.preventDefault();
    
        var form = $('#createTaskForm')[0];
        form.classList.add('was-validated');
    
        if (form.checkValidity() === false) {
            e.stopPropagation();
        } else {
            // Show confirmation alert before submitting
            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to save this task?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Save',
                cancelButtonText: 'Cancel',
            }).then((result) => {
                if (result.isConfirmed) {
                    // Proceed with AJAX submission
                    $.ajax({
                        type: "POST",
                        url: "add.php?action=task",
                        data: $("#createTaskForm").serialize(),
                        success: function (response) {
                            $('#createTaskForm')[0].reset();
                            $('#createTaskForm').removeClass('was-validated');
    
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response, // Show the success message
                                showConfirmButton: false,
                                timer: 1500
                            }).then(function () {
                                window.location.reload();
                            });
                        },
                    });
                }
            });
        }
    
        form.classList.add('was-validated');
    });

    $(document).on('click', '#createTaskAwning', function (e) {
        e.preventDefault();
    
        var form = $('#createTaskFormAwning')[0];
        form.classList.add('was-validated');
    
        if (form.checkValidity() === false) {
            e.stopPropagation();
        } else {
            // Show confirmation alert before submitting
            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to save this task?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Save',
                cancelButtonText: 'Cancel',
            }).then((result) => {
                if (result.isConfirmed) {
                    // Proceed with AJAX submission
                    $.ajax({
                        type: "POST",
                        url: "add.php?action=taskAwning",
                        data: $("#createTaskFormAwning").serialize(),
                        success: function (response) {
                            $('#createTaskFormAwning')[0].reset();
                            $('#createTaskFormAwning').removeClass('was-validated');
    
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response, // Show the success message
                                showConfirmButton: false,
                                timer: 1500
                            }).then(function () {
                                window.location.reload();
                            });
                        },
                    });
                }
            });
        }
    
        form.classList.add('was-validated');
    });

    $(document).on('click', '#createTaskFixed', function (e) {
        e.preventDefault();
    
        var form = $('#createTaskFormFixed')[0];
        form.classList.add('was-validated');
    
        if (form.checkValidity() === false) {
            e.stopPropagation();
        } else {
            // Show confirmation alert before submitting
            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to save this task?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Save',
                cancelButtonText: 'Cancel',
            }).then((result) => {
                if (result.isConfirmed) {
                    // Proceed with AJAX submission
                    $.ajax({
                        type: "POST",
                        url: "add.php?action=taskFixed",
                        data: $("#createTaskFormFixed").serialize(),
                        success: function (response) {
                            $('#createTaskFormFixed')[0].reset();
                            $('#createTaskFormFixed').removeClass('was-validated');
    
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response, // Show the success message
                                showConfirmButton: false,
                                timer: 1500
                            }).then(function () {
                                window.location.reload();
                            });
                        },
                    });
                }
            });
        }
    
        form.classList.add('was-validated');
    });


    $(document).on('click', '#createTaskCasement', function (e) {
    e.preventDefault();

    var form = $('#createTaskFormCasement')[0];
    form.classList.add('was-validated');

    if (form.checkValidity() === false) {
        e.stopPropagation();
    } else {
        // Show confirmation alert before submitting
        Swal.fire({
            title: 'Are you sure?',
            text: 'Do you want to save this task?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Save',
            cancelButtonText: 'Cancel',
        }).then((result) => {
            if (result.isConfirmed) {
                // Proceed with AJAX submission
                $.ajax({
                    type: "POST",
                    url: "add.php?action=taskCasement",
                    data: $("#createTaskFormCasement").serialize(),
                    success: function (response) {
                        $('#createTaskFormCasement')[0].reset();
                        $('#createTaskFormCasement').removeClass('was-validated');

                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response, // Show the success message
                            showConfirmButton: false,
                            timer: 1500
                        }).then(function () {
                            window.location.reload();
                        });
                    },
                });
            }
        });
    }

    form.classList.add('was-validated');
});


    $(document).ready(function() {

        function handleSelectChangeForGlass(){
            var selectedItemName = $('#glass').val();

            if(selectedItemName)
            {
                $.ajax({
                    url: "checkInventory.php?action=glass",
                    type: 'POST',
                    data: { item_name: selectedItemName},
                    dataType: 'json',
                    success: function (response) {

                        if (response.status === 'found')
                        {
                            $('#gItemID').val(response.item_id);
                            $('#gStock').val(response.stock);

                            var totalPrice = parseFloat(response.price);

                            $('#gPrice').val(totalPrice.toFixed(2));
                        }
                    }
                });
            }
        }

        function handleSelectChangeForTopHead() {
            var selectedItemName = $('#topHead').val();
            var length = $('#length').val();

            if (length && selectedItemName) {
                // Split the selected value to get item_name, dimension, and color
                var selectedItemParts = selectedItemName.split('|');
                var itemName = selectedItemParts[0]; // item_name
                var dimension = selectedItemParts[1]; // dimension
                var color = selectedItemParts[2]; // color
                var lFoot = $('#lFoot').val();

                $.ajax({
                    url: 'check_inventory_excess.php',
                    type: 'POST',
                    data: { 
                        item_name: itemName, 
                        lFoot: lFoot,
                        dimension: dimension, // Include dimension in the data
                        color: color // Include color in the data
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'found') {
                            console.log('Response from check_inventory_excess.php:', response);

                            $('#thEItemID').val(response.exc_id);
                            $('#thItemID').val('');
                            $('#thSellPrice').val(response.price);
                            $('#thFootExcess').val(response.exc_foot);
                            $('#thColor').val(color); // Set the color from the selected item
                            $('#thDimension').val(dimension); // Set the dimension from the selected item
                            $('#thStock').val('');

                            var totalPrice = lFoot * parseFloat(response.price);
                            $('#thPrice').val(totalPrice.toFixed(2));
                        } else {
                            $.ajax({
                                url: 'fetch_item_data.php',
                                type: 'POST',
                                data: { 
                                    item_name: itemName, 
                                    dimension: dimension, // Include dimension in the data
                                    color: color // Include color in the data
                                },
                                dataType: 'json',
                                success: function(response) {
                                    if (response.status !== 'not_found') {
                                        console.log('Response from fetch_item_data.php:', response);

                                        $('#thEItemID').val('');
                                        $('#thItemID').val(response.item_id);
                                        $('#thSellPrice').val(response.price);
                                        $('#thFootExcess').val(response.foot);
                                        $('#thColor').val(color); // Set the color from the selected item
                                        $('#thDimension').val(dimension); // Set the dimension from the selected item
                                        $('#thStock').val(response.stock);

                                        var totalPrice = lFoot * parseFloat(response.price);
                                        $('#thPrice').val(totalPrice.toFixed(2));
                                    }
                                },
                            });
                        }
                    },
                });
            }
        }

        function handleSelectChangeForBottomSill() {
            var selectedItemName = $('#bottomSill').val();
            var length = $('#length').val();

            if (length && selectedItemName) {
                // Split the selected value to get item_name, dimension, and color
                var selectedItemParts = selectedItemName.split('|');
                var itemName = selectedItemParts[0]; // item_name
                var dimension = selectedItemParts[1]; // dimension
                var color = selectedItemParts[2]; // color
                var lFoot = $('#lFoot').val();

                $.ajax({
                    url: 'check_inventory_excess.php',
                    type: 'POST',
                    data: { 
                        item_name: itemName, 
                        lFoot: lFoot,
                        dimension: dimension, // Include dimension in the data
                        color: color // Include color in the data
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'found') {
                            console.log('Response from check_inventory_excess.php:', response);

                            $('#bsEItemID').val(response.exc_id);
                            $('#bsItemID').val('');
                            $('#bsSellPrice').val(response.price);
                            $('#bsFootExcess').val(response.exc_foot);
                            $('#bsColor').val(response.color);
                            $('#bsDimension').val(response.dimension);
                            $('#bsStock').val('');

                            var totalPrice = lFoot * parseFloat(response.price);
                            $('#bsPrice').val(totalPrice.toFixed(2));
                        } else {
                            // If item is not found in inventory excess, fetch from main inventory
                            $.ajax({
                                url: 'fetch_item_data.php',
                                type: 'POST',
                                data: { 
                                    item_name: itemName, 
                                    dimension: dimension, // Include dimension in the data
                                    color: color // Include color in the data
                                },
                                dataType: 'json',
                                success: function(response) {
                                    if (response.status !== 'not_found') {
                                        console.log('Response from fetch_item_data.php:', response);

                                        $('#bsEItemID').val('');
                                        $('#bsItemID').val(response.item_id);
                                        $('#bsSellPrice').val(response.price);
                                        $('#bsFootExcess').val(response.foot);
                                        $('#bsColor').val(response.color);
                                        $('#bsDimension').val(response.dimension);
                                        $('#bsStock').val(response.stock);

                                        var totalPrice = lFoot * parseFloat(response.price);
                                        $('#bsPrice').val(totalPrice.toFixed(2));
                                    }
                                },
                            });
                        }
                    },
                });
            }
        }

        function handleSelectChangeForRail() {
            var selectedItemName = $('#rail').val();
            var length = $('#length').val();

            if (length && selectedItemName) {
                // Split the selected value to get item_name, dimension, and color
                var selectedItemParts = selectedItemName.split('|');
                var itemName = selectedItemParts[0]; // item_name
                var dimension = selectedItemParts[1]; // dimension
                var color = selectedItemParts[2]; // color
                var lFoot = $('#lFootX2').val();
                // Perform an AJAX request to fetch the item details based on the selected item name
                $.ajax({
                    url: 'checkInventory.php?action=rail', // Replace with the actual URL to your server-side script
                    type: 'POST',
                    data: { 
                        item_name: itemName, 
                        lFoot: lFoot,
                        dimension: dimension, // Include dimension in the data
                        color: color // Include color in the data
                    },
                    dataType: 'json',
                    success: function(response) {
                        console.log('Response from checkInventory1.php:', response);

                        if (response.status === 'found') 
                        {
                            //always need
                            $('#rColor').val(response.color);   
                            $('#rDimension').val(response.dimension);
                            $('#rSellPrice').val(response.price);

                            // Update the form fields with the data from the response
                            $('#rEItemID').val(response.exc_id);
                            $('#rFootExcess').val(response.exc_foot);

                            $('#rItemID').val('');
                            $('#rFoot').val('');
                            $('#rStock').val('');
                            $('#rHFoot').val('');
                            $('#rUNI').val('');
                            $('#rUEI').val('');
                            $('#rFEID').val('');
                            $('#rFEID1').val('');
                            $('#rFH').val('');
                            $('#rSH').val('');

                            var totalPrice = lFoot * parseFloat(response.price);

                            $('#rPrice').val(totalPrice.toFixed(2));
                        }
                        else if (response.status === 'found_full')
                        {
                            //always need
                            $('#rColor').val(response.color);   
                            $('#rDimension').val(response.dimension);
                            $('#rSellPrice').val(response.price);

                            $('#rFEID').val(response.exc_id_first_half);
                            $('#rFEID1').val(response.exc_id_second_half);
                            $('#rFH').val(response.first_half_exc_foot);
                            $('#rSH').val(response.second_half_exc_foot);
                            $('#rHFoot').val(response.half_foot);

                            $('#rItemID').val('');
                            $('#rEItemID').val('');
                            $('#rFoot').val('');
                            $('#rFootExcess').val('');
                            $('#rStock').val('');
                            $('#rUNI').val('');
                            $('#rUEI').val('');

                            var totalPrice = lFoot * parseFloat(response.price);

                            $('#rPrice').val(totalPrice.toFixed(2));
                        }
                        else if (response.status === 'found_half') 
                        {
                            //always need
                            $('#rColor').val(response.remaining_color); 
                            $('#rDimension').val(response.remaining_dimension);
                            $('#rSellPrice').val(response.remaining_price);

                            $('#rStock').val(response.remaining_stock);
                            $('#rFoot').val(response.remaining_foot);
                            $('#rFootExcess').val(response.half_exc_foot);
                            $('#rUNI').val(response.remaining_item_id);
                            $('#rUEI').val(response.exc_id);
                            $('#rHFoot').val(response.half_foot);

                            $('#rItemID').val('');
                            $('#rEItemID').val('');
                            $('#rFEID').val('');
                            $('#rFEID1').val('');
                            $('#rFH').val('');
                            $('#rSH').val('');

                            var totalPrice = lFoot * parseFloat(response.remaining_price);

                            $('#rPrice').val(totalPrice.toFixed(2));
                        }
                        else if (response.status === 'found_new')
                        {
                            //always need
                            $('#rColor').val(response.color); 
                            $('#rDimension').val(response.dimension);
                            $('#rSellPrice').val(response.price);

                            $('#rItemID').val(response.item_id);
                            $('#rStock').val(response.stock);
                            $('#rFoot').val(response.foot);

                            $('#rEItemID').val('');
                            $('#rFootExcess').val('');
                            $('#rHFoot').val('');
                            $('#rUNI').val('');
                            $('#rUEI').val('');
                            $('#rFEID').val('');
                            $('#rFEID1').val('');
                            $('#rFH').val('');
                            $('#rSH').val('');

                            var totalPrice = lFoot * parseFloat(response.price);

                            $('#rPrice').val(totalPrice.toFixed(2));
                        }
                    },
                });
            }
        }
        
        function handleSelectChangeForSideJamb() {
            var selectedItemName = $('#sideJamb').val();
            var height = $('#height').val();

            if (height && selectedItemName) 
            {
                var selectedItemParts = selectedItemName.split('|');
                var itemName = selectedItemParts[0]; // item_name
                var dimension = selectedItemParts[1]; // dimension
                var color = selectedItemParts[2]; // color
                var hFoot = $('#hFootX2').val();

                $.ajax({
                    url: 'checkInventory.php?action=sidejamb', // Replace with the actual URL to your server-side script
                    type: 'POST',
                    data: { 
                            item_name: itemName, 
                            hFoot: hFoot,
                            dimension: dimension, // Include dimension in the data
                            color: color // Include color in the data
                        },
                    dataType: 'json',
                    success: function(response) {
                        console.log('Response from checkInventory.php:', response);

                        if (response.status === 'found') {
                            // Update the form fields with the data from the response
                            $('#sjColor').val(response.color);
                            $('#sjDimension').val(response.dimension);
                            $('#sjSellPrice').val(response.price);

                            $('#sjEItemID').val(response.exc_id);
                            $('#sjFootExcess').val(response.exc_foot);

                            // Clearing other fields
                            $('#sjItemID').val('');
                            $('#sjFoot').val('');
                            $('#sjStock').val('');
                            $('#sjHFoot').val('');
                            $('#sjUNI').val('');
                            $('#sjUEI').val('');
                            $('#sjFEID').val('');
                            $('#sjFEID1').val('');
                            $('#sjFH').val('');
                            $('#sjSH').val('');

                            var totalPrice = hFoot * parseFloat(response.price);
                            $('#sjPrice').val(totalPrice.toFixed(2));
                        } else if (response.status === 'found_full') {
                            $('#sjColor').val(response.color);
                            $('#sjDimension').val(response.dimension);
                            $('#sjSellPrice').val(response.price);

                            $('#sjFEID').val(response.exc_id_first_half);
                            $('#sjFEID1').val(response.exc_id_second_half);
                            $('#sjFH').val(response.first_half_exc_foot);
                            $('#sjSH').val(response.second_half_exc_foot);
                            $('#sjHFoot').val(response.half_foot);

                            $('#sjItemID').val('');
                            $('#sjEItemID').val('');
                            $('#sjFoot').val('');
                            $('#sjFootExcess').val('');
                            $('#sjStock').val('');
                            $('#sjUNI').val('');
                            $('#sjUEI').val('');

                            var totalPrice = hFoot * parseFloat(response.price);
                            $('#sjPrice').val(totalPrice.toFixed(2));
                        } else if (response.status === 'found_half') {
                            $('#sjColor').val(response.remaining_color);
                            $('#sjDimension').val(response.remaining_dimension);
                            $('#sjSellPrice').val(response.remaining_price);

                            $('#sjStock').val(response.remaining_stock);
                            $('#sjFoot').val(response.remaining_foot);
                            $('#sjFootExcess').val(response.half_exc_foot);
                            $('#sjUNI').val(response.remaining_item_id);
                            $('#sjUEI').val(response.exc_id);
                            $('#sjHFoot').val(response.half_foot);

                            $('#sjItemID').val('');
                            $('#sjEItemID').val('');
                            $('#sjFEID').val('');
                            $('#sjFEID1').val('');
                            $('#sjFH').val('');
                            $('#sjSH').val('');

                            var totalPrice = hFoot * parseFloat(response.remaining_price);
                            $('#sjPrice').val(totalPrice.toFixed(2));
                        } else if (response.status === 'found_new') {
                            $('#sjColor').val(response.color);
                            $('#sjDimension').val(response.dimension);
                            $('#sjSellPrice').val(response.price);

                            $('#sjItemID').val(response.item_id);
                            $('#sjStock').val(response.stock);
                            $('#sjFoot').val(response.foot);

                            $('#sjEItemID').val('');
                            $('#sjFootExcess').val('');
                            $('#sjHFoot').val('');
                            $('#sjUNI').val('');
                            $('#sjUEI').val('');
                            $('#sjFEID').val('');
                            $('#sjFEID1').val('');
                            $('#sjFH').val('');
                            $('#sjSH').val('');

                            var totalPrice = hFoot * parseFloat(response.price);
                            $('#sjPrice').val(totalPrice.toFixed(2));
                        }
                    },
                });
            }
        }

        function handleSelectChangeForLockstile() {
            var selectedItemName = $('#lockStile').val();
            var height = $('#height').val();

            if (height && selectedItemName) {
                var selectedItemParts = selectedItemName.split('|');
                var itemName = selectedItemParts[0]; // item_name
                var dimension = selectedItemParts[1]; // dimension
                var color = selectedItemParts[2]; // color
                var hFoot = $('#hFootX2').val();

                $.ajax({
                    url: 'checkInventory.php?action=lockstile', // Adjust to actual URL
                    type: 'POST',
                    data: { 
                            item_name: itemName, 
                            hFoot: hFoot,
                            dimension: dimension, // Include dimension in the data
                            color: color // Include color in the data
                        },
                    dataType: 'json',
                    success: function(response) {
                        console.log('Response from checkInventory.php:', response);

                        if (response.status === 'found') {
                            // Update form fields with response data
                            $('#lsColor').val(response.color);
                            $('#lsDimension').val(response.dimension);
                            $('#lsSellPrice').val(response.price);
                            $('#lsEItemID').val(response.exc_id);
                            $('#lsFootExcess').val(response.exc_foot);

                            // Clear other fields
                            $('#lsItemID').val('');
                            $('#lsFoot').val('');
                            $('#lsStock').val('');
                            $('#lsHFoot').val('');
                            $('#lsUNI').val('');
                            $('#lsUEI').val('');
                            $('#lsFEID').val('');
                            $('#lsFEID1').val('');
                            $('#lsFH').val('');
                            $('#lsSH').val('');

                            var totalPrice = hFoot * parseFloat(response.price);
                            $('#lsPrice').val(totalPrice.toFixed(2));
                        } else if (response.status === 'found_full') {
                            $('#lsColor').val(response.color);
                            $('#lsDimension').val(response.dimension);
                            $('#lsSellPrice').val(response.price);

                            $('#lsFEID').val(response.exc_id_first_half);
                            $('#lsFEID1').val(response.exc_id_second_half);
                            $('#lsFH').val(response.first_half_exc_foot);
                            $('#lsSH').val(response.second_half_exc_foot);
                            $('#lsHFoot').val(response.half_foot);

                            // Clear other fields
                            $('#lsItemID').val('');
                            $('#lsEItemID').val('');
                            $('#lsFoot').val('');
                            $('#lsFootExcess').val('');
                            $('#lsStock').val('');
                            $('#lsUNI').val('');
                            $('#lsUEI').val('');

                            var totalPrice = hFoot * parseFloat(response.price);
                            $('#lsPrice').val(totalPrice.toFixed(2));
                        } else if (response.status === 'found_half') {
                            $('#lsColor').val(response.remaining_color);
                            $('#lsDimension').val(response.remaining_dimension);
                            $('#lsSellPrice').val(response.remaining_price);

                            $('#lsStock').val(response.remaining_stock);
                            $('#lsFoot').val(response.remaining_foot);
                            $('#lsFootExcess').val(response.half_exc_foot);
                            $('#lsUNI').val(response.remaining_item_id);
                            $('#lsUEI').val(response.exc_id);
                            $('#lsHFoot').val(response.half_foot);

                            // Clear other fields
                            $('#lsItemID').val('');
                            $('#lsEItemID').val('');
                            $('#lsFEID').val('');
                            $('#lsFEID1').val('');
                            $('#lsFH').val('');
                            $('#lsSH').val('');

                            var totalPrice = hFoot * parseFloat(response.remaining_price);
                            $('#lsPrice').val(totalPrice.toFixed(2));
                        } else if (response.status === 'found_new') {
                            $('#lsColor').val(response.color);
                            $('#lsDimension').val(response.dimension);
                            $('#lsSellPrice').val(response.price);

                            $('#lsItemID').val(response.item_id);
                            $('#lsStock').val(response.stock);
                            $('#lsFoot').val(response.foot);

                            // Clear other fields
                            $('#lsEItemID').val('');
                            $('#lsFootExcess').val('');
                            $('#lsHFoot').val('');
                            $('#lsUNI').val('');
                            $('#lsUEI').val('');
                            $('#lsFEID').val('');
                            $('#lsFEID1').val('');
                            $('#lsFH').val('');
                            $('#lsSH').val('');

                            var totalPrice = hFoot * parseFloat(response.price);
                            $('#lsPrice').val(totalPrice.toFixed(2));
                        }
                    }
                });
            }
        }

        function handleSelectChangeForInterlocker() {
            var selectedItemName = $('#interlocker').val();
            var height = $('#height').val();

            if (height && selectedItemName) {
                var selectedItemParts = selectedItemName.split('|');
                var itemName = selectedItemParts[0]; // item_name
                var dimension = selectedItemParts[1]; // dimension
                var color = selectedItemParts[2]; // color
                var hFoot = $('#hFootX2').val();

                $.ajax({
                    url: 'checkInventory.php?action=interlocker', // Adjust to actual URL
                    type: 'POST',
                    data: { 
                            item_name: itemName, 
                            hFoot: hFoot,
                            dimension: dimension, // Include dimension in the data
                            color: color // Include color in the data
                        },
                    dataType: 'json',
                    success: function(response) {
                        console.log('Response from checkInventory.php:', response);

                        if (response.status === 'found') {
                            // Update form fields with response data
                            $('#ilColor').val(response.color);
                            $('#ilDimension').val(response.dimension);
                            $('#ilSellPrice').val(response.price);
                            $('#ilEItemID').val(response.exc_id);
                            $('#ilFootExcess').val(response.exc_foot);

                            // Clear other fields
                            $('#ilItemID').val('');
                            $('#ilFoot').val('');
                            $('#ilStock').val('');
                            $('#ilHFoot').val('');
                            $('#ilUNI').val('');
                            $('#ilUEI').val('');
                            $('#ilFEID').val('');
                            $('#ilFEID1').val('');
                            $('#ilFH').val('');
                            $('#ilSH').val('');

                            var totalPrice = hFoot * parseFloat(response.price);
                            $('#ilPrice').val(totalPrice.toFixed(2));
                        } else if (response.status === 'found_full') {
                            $('#ilColor').val(response.color);
                            $('#ilDimension').val(response.dimension);
                            $('#ilSellPrice').val(response.price);

                            $('#ilFEID').val(response.exc_id_first_half);
                            $('#ilFEID1').val(response.exc_id_second_half);
                            $('#ilFH').val(response.first_half_exc_foot);
                            $('#ilSH').val(response.second_half_exc_foot);
                            $('#ilHFoot').val(response.half_foot);

                            // Clear other fields
                            $('#ilItemID').val('');
                            $('#ilEItemID').val('');
                            $('#ilFoot').val('');
                            $('#ilFootExcess').val('');
                            $('#ilStock').val('');
                            $('#ilUNI').val('');
                            $('#ilUEI').val('');

                            var totalPrice = hFoot * parseFloat(response.price);
                            $('#ilPrice').val(totalPrice.toFixed(2));
                        } else if (response.status === 'found_half') {
                            $('#ilColor').val(response.remaining_color);
                            $('#ilDimension').val(response.remaining_dimension);
                            $('#ilSellPrice').val(response.remaining_price);

                            $('#ilStock').val(response.remaining_stock);
                            $('#ilFoot').val(response.remaining_foot);
                            $('#ilFootExcess').val(response.half_exc_foot);
                            $('#ilUNI').val(response.remaining_item_id);
                            $('#ilUEI').val(response.exc_id);
                            $('#ilHFoot').val(response.half_foot);

                            // Clear other fields
                            $('#ilItemID').val('');
                            $('#ilEItemID').val('');
                            $('#ilFEID').val('');
                            $('#ilFEID1').val('');
                            $('#ilFH').val('');
                            $('#ilSH').val('');

                            var totalPrice = hFoot * parseFloat(response.remaining_price);
                            $('#ilPrice').val(totalPrice.toFixed(2));
                        } else if (response.status === 'found_new') {
                            $('#ilColor').val(response.color);
                            $('#ilDimension').val(response.dimension);
                            $('#ilSellPrice').val(response.price);

                            $('#ilItemID').val(response.item_id);
                            $('#ilStock').val(response.stock);
                            $('#ilFoot').val(response.foot);

                            // Clear other fields
                            $('#ilEItemID').val('');
                            $('#ilFootExcess').val('');
                            $('#ilHFoot').val('');
                            $('#ilUNI').val('');
                            $('#ilUEI').val('');
                            $('#ilFEID').val('');
                            $('#ilFEID1').val('');
                            $('#ilFH').val('');
                            $('#ilSH').val('');

                            var totalPrice = hFoot * parseFloat(response.price);
                            $('#ilPrice').val(totalPrice.toFixed(2));
                        }
                    }
                });
            }
        }

        function handleSelectChangeForGlassAwning(){
            var selectedItemName = $('#GlassAwning').val();

            if(selectedItemName)
            {
                $.ajax({
                    url: "checkInventory.php?action=GlassAwning",
                    type: 'POST',
                    data: { item_name: selectedItemName},
                    dataType: 'json',
                    success: function (response) {

                        if (response.status === 'found')
                        {
                            $('#gItemIDAwning').val(response.item_id);
                            $('#gStockAwning').val(response.stock);

                            var totalPrice = parseFloat(response.price);

                            $('#gPriceAwning').val(totalPrice.toFixed(2));
                        }
                    }
                });
            }
        }

        function handleSelectChangeForHeadAwning() {
            var selectedItemName = $('#HeadAwning').val();
            var length = $('#lengthAwning').val();

            if (length && selectedItemName) {
                // Split the selected value to get item_name, dimension, and color
                var selectedItemParts = selectedItemName.split('|');
                var itemName = selectedItemParts[0]; // item_name
                var dimension = selectedItemParts[1]; // dimension
                var color = selectedItemParts[2]; // color
                var lFoot = $('#lFootAwning').val();

                console.log("Selected Item: ", selectedItemParts);
                console.log("Length: ", length);
                console.log("lFoot: ", lFoot);

                $.ajax({
                    url: 'checkInventory.php?action=HeadAwning',
                    type: 'POST',
                    data: {
                        item_name: itemName,
                        lFoot: lFoot,
                        dimension: dimension, // Include dimension in the data
                        color: color // Include color in the data
                    },
                    dataType: 'json',
                    success: function(response) {
                        console.log("Response from check_inventory_excess.php:", response);

                        if (response.status === 'found') {
                            $('#hEItemIDAwning').val(response.exc_id);
                            $('#hItemIDAwning').val('');
                            $('#hSellPriceAwning').val(response.price);
                            $('#hFootExcessAwning').val(response.exc_foot);
                            $('#hColorAwning').val(color);
                            $('#hDimensionAwning').val(dimension);
                            $('#hStockAwning').val('');

                            var totalPrice = lFoot * parseFloat(response.price);
                            $('#hPriceAwning').val(totalPrice.toFixed(2));

                            console.log("Total Price (found): ", totalPrice);
                        } else if (response.status === 'not_found') {
                            $('#hEItemIDAwning').val('');
                            $('#hItemIDAwning').val(response.item_id);
                            $('#hSellPriceAwning').val(response.price);
                            $('#hFootExcessAwning').val(response.foot);
                            $('#hColorAwning').val(color);
                            $('#hDimensionAwning').val(dimension);
                            $('#hStockAwning').val(response.stock);

                            var totalPrice = lFoot * parseFloat(response.price);
                            $('#hPriceAwning').val(totalPrice.toFixed(2));

                            console.log("Total Price (not found): ", totalPrice);
                        }
                    },
                });
            }
        }

        function handleSelectChangeForSillAwning() {
            var selectedItemName = $('#SillAwning').val();
            var length = $('#lengthAwning').val();

            if (length && selectedItemName) {
                // Split the selected value to get item_name, dimension, and color
                var selectedItemParts = selectedItemName.split('|');
                var itemName = selectedItemParts[0]; // item_name
                var dimension = selectedItemParts[1]; // dimension
                var color = selectedItemParts[2]; // color
                var lFoot = $('#lFootAwning').val();

                console.log("Selected Item: ", selectedItemParts);
                console.log("Length: ", length);
                console.log("lFoot: ", lFoot);

                $.ajax({
                    url: 'checkInventory.php?action=SillAwning',
                    type: 'POST',
                    data: {
                        item_name: itemName,
                        lFoot: lFoot,
                        dimension: dimension, // Include dimension in the data
                        color: color // Include color in the data
                    },
                    dataType: 'json',
                    success: function(response) {
                        console.log("Response from check_inventory_excess.php:", response);

                        if (response.status === 'found') {
                            $('#sEItemIDAwning').val(response.exc_id);
                            $('#sItemIDAwning').val('');
                            $('#sSellPriceAwning').val(response.price);
                            $('#sFootExcessAwning').val(response.exc_foot);
                            $('#sColorAwning').val(color);
                            $('#sDimensionAwning').val(dimension);
                            $('#sStockAwning').val('');

                            var totalPrice = lFoot * parseFloat(response.price);
                            $('#sPriceAwning').val(totalPrice.toFixed(2));

                            console.log("Total Price (found): ", totalPrice);
                        } else if (response.status === 'not_found') {
                            $('#sEItemIDAwning').val('');
                            $('#sItemIDAwning').val(response.item_id);
                            $('#sSellPriceAwning').val(response.price);
                            $('#sFootExcessAwning').val(response.foot);
                            $('#sColorAwning').val(color);
                            $('#sDimensionAwning').val(dimension);
                            $('#sStockAwning').val(response.stock);

                            var totalPrice = lFoot * parseFloat(response.price);
                            $('#sPriceAwning').val(totalPrice.toFixed(2));
                        }
                    },
                });
            }
        }

        function handleSelectChangeForJambAwning() {
            var selectedItemName = $('#JambAwning').val();
            var height = $('#heightAwning').val();

            if (height && selectedItemName) {
                var selectedItemParts = selectedItemName.split('|');
                var itemName = selectedItemParts[0]; // item_name
                var dimension = selectedItemParts[1]; // dimension
                var color = selectedItemParts[2]; // color
                var hFoot = $('#hFootX2Awning').val();

                $.ajax({
                    url: 'checkInventory.php?action=JambAwning', // Adjust to actual URL
                    type: 'POST',
                    data: { 
                            item_name: itemName, 
                            hFoot: hFoot,
                            dimension: dimension, // Include dimension in the data
                            color: color // Include color in the data
                        },
                    dataType: 'json',
                    success: function(response) {
                        console.log('Response from checkInventory.php:', response);

                        if (response.status === 'found') {
                            // Update form fields with response data
                            $('#sjColorAwning').val(response.color);
                            $('#sjDimensionAwning').val(response.dimension);
                            $('#sjSellPriceAwning').val(response.price);
                            $('#sjEItemIDAwning').val(response.exc_id);
                            $('#sjFootExcessAwning').val(response.exc_foot);

                            // Clear other fields
                            $('#sjItemIDAwning').val('');
                            $('#sjFootAwning').val('');
                            $('#sjStockAwning').val('');
                            $('#sjHFootAwning').val('');
                            $('#sjUNIAwning').val('');
                            $('#sjUEIAwning').val('');
                            $('#sjFEIDAwning').val('');
                            $('#sjFEID1Awning').val('');
                            $('#sjFHAwning').val('');
                            $('#sjSHAwning').val('');

                            var totalPrice = hFoot * parseFloat(response.price);
                            $('#sjPriceAwning').val(totalPrice.toFixed(2));
                        } else if (response.status === 'found_full') {
                            $('#sjColorAwning').val(response.color);
                            $('#sjDimensionAwning').val(response.dimension);
                            $('#sjSellPriceAwning').val(response.price);

                            $('#sjFEIDAwning').val(response.exc_id_first_half);
                            $('#sjFEID1Awning').val(response.exc_id_second_half);
                            $('#sjFHAwning').val(response.first_half_exc_foot);
                            $('#sjSHAwning').val(response.second_half_exc_foot);
                            $('#sjHFootAwning').val(response.half_foot);

                            // Clear other fields
                            $('#sjItemIDAwning').val('');
                            $('#sjEItemIDAwning').val('');
                            $('#sjFootAwning').val('');
                            $('#sjFootExcessAwning').val('');
                            $('#sjStockAwning').val('');
                            $('#sjUNIAwning').val('');
                            $('#sjUEIAwning').val('');

                            var totalPrice = hFoot * parseFloat(response.price);
                            $('#sjPriceAwning').val(totalPrice.toFixed(2));
                        } else if (response.status === 'found_half') {
                            $('#sjColorAwning').val(response.remaining_color);
                            $('#sjDimensionAwning').val(response.remaining_dimension);
                            $('#sjSellPriceAwning').val(response.remaining_price);

                            $('#sjStockAwning').val(response.remaining_stock);
                            $('#sjFootAwning').val(response.remaining_foot);
                            $('#sjFootExcessAwning').val(response.half_exc_foot);
                            $('#sjUNIAwning').val(response.remaining_item_id);
                            $('#sjUEIAwning').val(response.exc_id);
                            $('#sjHFootAwning').val(response.half_foot);

                            // Clear other fields
                            $('#sjItemIDAwning').val('');
                            $('#sjEItemIDAwning').val('');
                            $('#sjFEIDAwning').val('');
                            $('#sjFEID1Awning').val('');
                            $('#sjFHAwning').val('');
                            $('#sjSHAwning').val('');

                            var totalPrice = hFoot * parseFloat(response.remaining_price);
                            $('#sjPriceAwning').val(totalPrice.toFixed(2));
                        } else if (response.status === 'found_new') {
                            $('#sjColorAwning').val(response.color);
                            $('#sjDimensionAwning').val(response.dimension);
                            $('#sjSellPriceAwning').val(response.price);

                            $('#sjItemIDAwning').val(response.item_id);
                            $('#sjStockAwning').val(response.stock);
                            $('#sjFootAwning').val(response.foot);

                            // Clear other fields
                            $('#sjEItemIDAwning').val('');
                            $('#sjFootExcessAwning').val('');
                            $('#sjHFootAwning').val('');
                            $('#sjUNIAwning').val('');
                            $('#sjUEIAwning').val('');
                            $('#sjFEIDAwning').val('');
                            $('#sjFEID1Awning').val('');
                            $('#sjFHAwning').val('');
                            $('#sjSHAwning').val('');

                            var totalPrice = hFoot * parseFloat(response.price);
                            $('#sjPriceAwning').val(totalPrice.toFixed(2));
                        }
                    }
                });
            }
        }

        function handleSelectChangeForGlassFixed(){
            var selectedItemName = $('#GlassFixed').val();

            if(selectedItemName)
            {
                $.ajax({
                    url: "checkInventory.php?action=GlassFixed",
                    type: 'POST',
                    data: { item_name: selectedItemName},
                    dataType: 'json',
                    success: function (response) {

                        if (response.status === 'found')
                        {
                            $('#gItemIDFixed').val(response.item_id);
                            $('#gStockFixed').val(response.stock);

                            var totalPrice = parseFloat(response.price);

                            $('#gPriceFixed').val(totalPrice.toFixed(2));
                        }
                    }
                });
            }
        }

        function handleSelectChangeForHeadFixed() {
            var selectedItemName = $('#HeadFixed').val();
            var length = $('#lengthFixed').val();

            if (length && selectedItemName) {
                // Split the selected value to get item_name, dimension, and color
                var selectedItemParts = selectedItemName.split('|');
                var itemName = selectedItemParts[0]; // item_name
                var dimension = selectedItemParts[1]; // dimension
                var color = selectedItemParts[2]; // color
                var lFoot = $('#lFootFixed').val();

                console.log("Selected Item: ", selectedItemParts);
                console.log("Length: ", length);
                console.log("lFoot: ", lFoot);

                $.ajax({
                    url: 'checkInventory.php?action=HeadFixed',
                    type: 'POST',
                    data: {
                        item_name: itemName,
                        lFoot: lFoot,
                        dimension: dimension, // Include dimension in the data
                        color: color // Include color in the data
                    },
                    dataType: 'json',
                    success: function(response) {
                        console.log("Response from check_inventory_excess.php:", response);

                        if (response.status === 'found') {
                            $('#hEItemIDFixed').val(response.exc_id);
                            $('#hItemIDFixed').val('');
                            $('#hSellPriceFixed').val(response.price);
                            $('#hFootExcessFixed').val(response.exc_foot);
                            $('#hColorFixed').val(color);
                            $('#hDimensionFixed').val(dimension);
                            $('#hStockFixed').val('');

                            var totalPrice = lFoot * parseFloat(response.price);
                            $('#hPriceFixed').val(totalPrice.toFixed(2));

                            console.log("Total Price (found): ", totalPrice);
                        } else if (response.status === 'not_found') {
                            $('#hEItemIDFixed').val('');
                            $('#hItemIDFixed').val(response.item_id);
                            $('#hSellPriceFixed').val(response.price);
                            $('#hFootExcessFixed').val(response.foot);
                            $('#hColorFixed').val(color);
                            $('#hDimensionFixed').val(dimension);
                            $('#hStockFixed').val(response.stock);

                            var totalPrice = lFoot * parseFloat(response.price);
                            $('#hPriceFixed').val(totalPrice.toFixed(2));

                            console.log("Total Price (not found): ", totalPrice);
                        }
                    },
                });
            }
        }

        function handleSelectChangeForSillFixed() {
            var selectedItemName = $('#SillFixed').val();
            var length = $('#lengthFixed').val();

            if (length && selectedItemName) {
                // Split the selected value to get item_name, dimension, and color
                var selectedItemParts = selectedItemName.split('|');
                var itemName = selectedItemParts[0]; // item_name
                var dimension = selectedItemParts[1]; // dimension
                var color = selectedItemParts[2]; // color
                var lFoot = $('#lFootFixed').val();

                console.log("Selected Item: ", selectedItemParts);
                console.log("Length: ", length);
                console.log("lFoot: ", lFoot);

                $.ajax({
                    url: 'checkInventory.php?action=SillFixed',
                    type: 'POST',
                    data: {
                        item_name: itemName,
                        lFoot: lFoot,
                        dimension: dimension, // Include dimension in the data
                        color: color // Include color in the data
                    },
                    dataType: 'json',
                    success: function(response) {
                        console.log("Response from check_inventory_excess.php:", response);

                        if (response.status === 'found') {
                            $('#sEItemIDFixed').val(response.exc_id);
                            $('#sItemIDFixed').val('');
                            $('#sSellPriceFixed').val(response.price);
                            $('#sFootExcessFixed').val(response.exc_foot);
                            $('#sColorFixed').val(color);
                            $('#sDimensionFixed').val(dimension);
                            $('#sStockFixed').val('');

                            var totalPrice = lFoot * parseFloat(response.price);
                            $('#sPriceFixed').val(totalPrice.toFixed(2));

                            console.log("Total Price (found): ", totalPrice);
                        } else if (response.status === 'not_found') {
                            $('#sEItemIDFixed').val('');
                            $('#sItemIDFixed').val(response.item_id);
                            $('#sSellPriceFixed').val(response.price);
                            $('#sFootExcessFixed').val(response.foot);
                            $('#sColorFixed').val(color);
                            $('#sDimensionFixed').val(dimension);
                            $('#sStockFixed').val(response.stock);

                            var totalPrice = lFoot * parseFloat(response.price);
                            $('#sPriceFixed').val(totalPrice.toFixed(2));
                        }
                    },
                });
            }
        }

        function handleSelectChangeForJambFixed() {
            var selectedItemName = $('#JambFixed').val();
            var height = $('#heightFixed').val();

            if (height && selectedItemName) {
                var selectedItemParts = selectedItemName.split('|');
                var itemName = selectedItemParts[0]; // item_name
                var dimension = selectedItemParts[1]; // dimension
                var color = selectedItemParts[2]; // color
                var hFoot = $('#hFootX2Fixed').val();

                $.ajax({
                    url: 'checkInventory.php?action=JambFixed', // Adjust to actual URL
                    type: 'POST',
                    data: { 
                        item_name: itemName, 
                        hFoot: hFoot,
                        dimension: dimension,
                        color: color
                    },
                    dataType: 'json',
                    success: function(response) {
                        console.log('Response from checkInventory.php:', response);

                        if (response.status === 'found') {
                            // Update form fields with response data
                            $('#sjColorFixed').val(response.color);
                            $('#sjDimensionFixed').val(response.dimension);
                            $('#sjSellPriceFixed').val(response.price);
                            $('#sjEItemIDFixed').val(response.exc_id);
                            $('#sjFootExcessFixed').val(response.exc_foot);

                            // Clear other fields
                            $('#sjItemIDFixed').val('');
                            $('#sjFootFixed').val('');
                            $('#sjStockFixed').val('');
                            $('#sjHFootFixed').val('');
                            $('#sjUNIFixed').val('');
                            $('#sjUEIFixed').val('');
                            $('#sjFEIDFixed').val('');
                            $('#sjFEID1Fixed').val('');
                            $('#sjFHFixed').val('');
                            $('#sjSHFixed').val('');

                            var totalPrice = hFoot * parseFloat(response.price);
                            $('#sjPriceFixed').val(totalPrice.toFixed(2));
                        } else if (response.status === 'found_full') {
                            $('#sjColorFixed').val(response.color);
                            $('#sjDimensionFixed').val(response.dimension);
                            $('#sjSellPriceFixed').val(response.price);

                            $('#sjFEIDFixed').val(response.exc_id_first_half);
                            $('#sjFEID1Fixed').val(response.exc_id_second_half);
                            $('#sjFHFixed').val(response.first_half_exc_foot);
                            $('#sjSHFixed').val(response.second_half_exc_foot);
                            $('#sjHFootFixed').val(response.half_foot);

                            // Clear other fields
                            $('#sjItemIDFixed').val('');
                            $('#sjEItemIDFixed').val('');
                            $('#sjFootFixed').val('');
                            $('#sjFootExcessFixed').val('');
                            $('#sjStockFixed').val('');
                            $('#sjUNIFixed').val('');
                            $('#sjUEIFixed').val('');

                            var totalPrice = hFoot * parseFloat(response.price);
                            $('#sjPriceFixed').val(totalPrice.toFixed(2));
                        } else if (response.status === 'found_half') {
                            $('#sjColorFixed').val(response.remaining_color);
                            $('#sjDimensionFixed').val(response.remaining_dimension);
                            $('#sjSellPriceFixed').val(response.remaining_price);

                            $('#sjStockFixed').val(response.remaining_stock);
                            $('#sjFootFixed').val(response.remaining_foot);
                            $('#sjFootExcessFixed').val(response.half_exc_foot);
                            $('#sjUNIFixed').val(response.remaining_item_id);
                            $('#sjUEIFixed').val(response.exc_id);
                            $('#sjHFootFixed').val(response.half_foot);

                            // Clear other fields
                            $('#sjItemIDFixed').val('');
                            $('#sjEItemIDFixed').val('');
                            $('#sjFEIDFixed').val('');
                            $('#sjFEID1Fixed').val('');
                            $('#sjFHFixed').val('');
                            $('#sjSHFixed').val('');

                            var totalPrice = hFoot * parseFloat(response.remaining_price);
                            $('#sjPriceFixed').val(totalPrice.toFixed(2));
                        } else if (response.status === 'found_new') {
                            $('#sjColorFixed').val(response.color);
                            $('#sjDimensionFixed').val(response.dimension);
                            $('#sjSellPriceFixed').val(response.price);

                            $('#sjItemIDFixed').val(response.item_id);
                            $('#sjStockFixed').val(response.stock);
                            $('#sjFootFixed').val(response.foot);

                            // Clear other fields
                            $('#sjEItemIDFixed').val('');
                            $('#sjFootExcessFixed').val('');
                            $('#sjHFootFixed').val('');
                            $('#sjUNIFixed').val('');
                            $('#sjUEIFixed').val('');
                            $('#sjFEIDFixed').val('');
                            $('#sjFEID1Fixed').val('');
                            $('#sjFHFixed').val('');
                            $('#sjSHFixed').val('');

                            var totalPrice = hFoot * parseFloat(response.price);
                            $('#sjPriceFixed').val(totalPrice.toFixed(2));
                        }
                    }
                });
            }
        }

        function handleSelectChangeForGlassCasement(){
            var selectedItemName = $('#GlassCasement').val();

            if(selectedItemName)
            {
                $.ajax({
                    url: "checkInventory.php?action=GlassCasement",
                    type: 'POST',
                    data: { item_name: selectedItemName},
                    dataType: 'json',
                    success: function (response) {

                        if (response.status === 'found')
                        {
                            $('#gItemIDCasement').val(response.item_id);
                            $('#gStockCasement').val(response.stock);

                            var totalPrice = parseFloat(response.price);

                            $('#gPriceCasement').val(totalPrice.toFixed(2));
                        }
                    }
                });
            }
        }

        function handleSelectChangeForHeadCasement() {
            var selectedItemName = $('#HeadCasement').val();
            var length = $('#lengthCasement').val();

            if (length && selectedItemName) {
                // Split the selected value to get item_name, dimension, and color
                var selectedItemParts = selectedItemName.split('|');
                var itemName = selectedItemParts[0]; // item_name
                var dimension = selectedItemParts[1]; // dimension
                var color = selectedItemParts[2]; // color
                var lFoot = $('#lFootCasement').val();

                console.log("Selected Item: ", selectedItemParts);
                console.log("Length: ", length);
                console.log("lFoot: ", lFoot);

                $.ajax({
                    url: 'checkInventory.php?action=HeadCasement',
                    type: 'POST',
                    data: {
                        item_name: itemName,
                        lFoot: lFoot,
                        dimension: dimension, // Include dimension in the data
                        color: color // Include color in the data
                    },
                    dataType: 'json',
                    success: function(response) {
                        console.log("Response from check_inventory_excess.php:", response);

                        if (response.status === 'found') {
                            $('#hEItemIDCasement').val(response.exc_id);
                            $('#hItemIDCasement').val('');
                            $('#hSellPriceCasement').val(response.price);
                            $('#hFootExcessCasement').val(response.exc_foot);
                            $('#hColorCasement').val(color);
                            $('#hDimensionCasement').val(dimension);
                            $('#hStockCasement').val('');

                            var totalPrice = lFoot * parseFloat(response.price);
                            $('#hPriceCasement').val(totalPrice.toFixed(2));

                            console.log("Total Price (found): ", totalPrice);
                        } else if (response.status === 'not_found') {
                            $('#hEItemIDCasement').val('');
                            $('#hItemIDCasement').val(response.item_id);
                            $('#hSellPriceCasement').val(response.price);
                            $('#hFootExcessCasement').val(response.foot);
                            $('#hColorCasement').val(color);
                            $('#hDimensionCasement').val(dimension);
                            $('#hStockCasement').val(response.stock);

                            var totalPrice = lFoot * parseFloat(response.price);
                            $('#hPriceCasement').val(totalPrice.toFixed(2));

                            console.log("Total Price (not found): ", totalPrice);
                        }
                    },
                });
            }
        }

        function handleSelectChangeForSillCasement() {
            var selectedItemName = $('#SillCasement').val();
            var length = $('#lengthCasement').val();

            if (length && selectedItemName) {
                // Split the selected value to get item_name, dimension, and color
                var selectedItemParts = selectedItemName.split('|');
                var itemName = selectedItemParts[0]; // item_name
                var dimension = selectedItemParts[1]; // dimension
                var color = selectedItemParts[2]; // color
                var lFoot = $('#lFootCasement').val();

                console.log("Selected Item: ", selectedItemParts);
                console.log("Length: ", length);
                console.log("lFoot: ", lFoot);

                $.ajax({
                    url: 'checkInventory.php?action=SillCasement',
                    type: 'POST',
                    data: {
                        item_name: itemName,
                        lFoot: lFoot,
                        dimension: dimension, // Include dimension in the data
                        color: color // Include color in the data
                    },
                    dataType: 'json',
                    success: function(response) {
                        console.log("Response from check_inventory_excess.php:", response);

                        if (response.status === 'found') {
                            $('#sEItemIDCasement').val(response.exc_id);
                            $('#sItemIDCasement').val('');
                            $('#sSellPriceCasement').val(response.price);
                            $('#sFootExcessCasement').val(response.exc_foot);
                            $('#sColorCasement').val(color);
                            $('#sDimensionCasement').val(dimension);
                            $('#sStockCasement').val('');

                            var totalPrice = lFoot * parseFloat(response.price);
                            $('#sPriceCasement').val(totalPrice.toFixed(2));

                            console.log("Total Price (found): ", totalPrice);
                        } else if (response.status === 'not_found') {
                            $('#sEItemIDCasement').val('');
                            $('#sItemIDCasement').val(response.item_id);
                            $('#sSellPriceCasement').val(response.price);
                            $('#sFootExcessCasement').val(response.foot);
                            $('#sColorCasement').val(color);
                            $('#sDimensionCasement').val(dimension);
                            $('#sStockCasement').val(response.stock);

                            var totalPrice = lFoot * parseFloat(response.price);
                            $('#sPriceCasement').val(totalPrice.toFixed(2));
                        }
                    },
                });
            }
        }

        function handleSelectChangeForJambCasement() {
            var selectedItemName = $('#JambCasement').val();
            var height = $('#heightCasement').val();

            if (height && selectedItemName) {
                var selectedItemParts = selectedItemName.split('|');
                var itemName = selectedItemParts[0]; // item_name
                var dimension = selectedItemParts[1]; // dimension
                var color = selectedItemParts[2]; // color
                var hFoot = $('#hFootX2Casement').val();

                $.ajax({
                    url: 'checkInventory.php?action=JambCasement', // Adjust to actual URL
                    type: 'POST',
                    data: { 
                        item_name: itemName, 
                        hFoot: hFoot,
                        dimension: dimension,
                        color: color
                    },
                    dataType: 'json',
                    success: function(response) {
                        console.log('Response from checkInventory.php:', response);

                        if (response.status === 'found') {
                            // Update form fields with response data
                            $('#sjColorCasement').val(response.color);
                            $('#sjDimensionCasement').val(response.dimension);
                            $('#sjSellPriceCasement').val(response.price);
                            $('#sjEItemIDCasement').val(response.exc_id);
                            $('#sjFootExcessCasement').val(response.exc_foot);

                            // Clear other fields
                            $('#sjItemIDCasement').val('');
                            $('#sjFootCasement').val('');
                            $('#sjStockCasement').val('');
                            $('#sjHFootCasement').val('');
                            $('#sjUNICasement').val('');
                            $('#sjUEICasement').val('');
                            $('#sjFEIDCasement').val('');
                            $('#sjFEID1Casement').val('');
                            $('#sjFHCasement').val('');
                            $('#sjSHCasement').val('');

                            var totalPrice = hFoot * parseFloat(response.price);
                            $('#sjPriceCasement').val(totalPrice.toFixed(2));
                        } else if (response.status === 'found_full') {
                            $('#sjColorCasement').val(response.color);
                            $('#sjDimensionCasement').val(response.dimension);
                            $('#sjSellPriceCasement').val(response.price);

                            $('#sjFEIDCasement').val(response.exc_id_first_half);
                            $('#sjFEID1Casement').val(response.exc_id_second_half);
                            $('#sjFHCasement').val(response.first_half_exc_foot);
                            $('#sjSHCasement').val(response.second_half_exc_foot);
                            $('#sjHFootCasement').val(response.half_foot);

                            // Clear other fields
                            $('#sjItemIDCasement').val('');
                            $('#sjEItemIDCasement').val('');
                            $('#sjFootCasement').val('');
                            $('#sjFootExcessCasement').val('');
                            $('#sjStockCasement').val('');
                            $('#sjUNICasement').val('');
                            $('#sjUEICasement').val('');

                            var totalPrice = hFoot * parseFloat(response.price);
                            $('#sjPriceCasement').val(totalPrice.toFixed(2));
                        } else if (response.status === 'found_half') {
                            $('#sjColorCasement').val(response.remaining_color);
                            $('#sjDimensionCasement').val(response.remaining_dimension);
                            $('#sjSellPriceCasement').val(response.remaining_price);

                            $('#sjStockCasement').val(response.remaining_stock);
                            $('#sjFootCasement').val(response.remaining_foot);
                            $('#sjFootExcessCasement').val(response.half_exc_foot);
                            $('#sjUNICasement').val(response.remaining_item_id);
                            $('#sjUEICasement').val(response.exc_id);
                            $('#sjHFootCasement').val(response.half_foot);

                            // Clear other fields
                            $('#sjItemIDCasement').val('');
                            $('#sjEItemIDCasement').val('');
                            $('#sjFEIDCasement').val('');
                            $('#sjFEID1Casement').val('');
                            $('#sjFHCasement').val('');
                            $('#sjSHCasement').val('');

                            var totalPrice = hFoot * parseFloat(response.remaining_price);
                            $('#sjPriceCasement').val(totalPrice.toFixed(2));
                        } else if (response.status === 'found_new') {
                            $('#sjColorCasement').val(response.color);
                            $('#sjDimensionCasement').val(response.dimension);
                            $('#sjSellPriceCasement').val(response.price);

                            $('#sjItemIDCasement').val(response.item_id);
                            $('#sjStockCasement').val(response.stock);
                            $('#sjFootCasement').val(response.foot);

                            // Clear other fields
                            $('#sjEItemIDCasement').val('');
                            $('#sjFootExcessCasement').val('');
                            $('#sjHFootCasement').val('');
                            $('#sjUNICasement').val('');
                            $('#sjUEICasement').val('');
                            $('#sjFEIDCasement').val('');
                            $('#sjFEID1Casement').val('');
                            $('#sjFHCasement').val('');
                            $('#sjSHCasement').val('');

                            var totalPrice = hFoot * parseFloat(response.price);
                            $('#sjPriceCasement').val(totalPrice.toFixed(2));
                        }
                    }
                });
            }
        }

        function checkInputs() {
            const length = $('#length').val();
            const height = $('#height').val();
            const lengthAwning = $('#lengthAwning').val();
            const heightAwning = $('#heightAwning').val();
            const lengthFixed = $('#lengthFixed').val();
            const heightFixed = $('#heightFixed').val();
            const lengthCasement = $('#lengthCasement').val();
            const heightCasement = $('#heightCasement').val();

            if (length && height) {
                $('#topHead').prop('disabled', false);
                $('#bottomSill').prop('disabled', false);
                $('#rail').prop('disabled', false);
                $('#sideJamb').prop('disabled', false);
                $('#lockStile').prop('disabled', false);
                $('#interlocker').prop('disabled', false);
                $('#glass').prop('disabled', false);
            } else {
                $('#topHead').prop('disabled', true);
                $('#bottomSill').prop('disabled', true);
                $('#rail').prop('disabled', true);
                $('#sideJamb').prop('disabled', true);
                $('#lockStile').prop('disabled', true);
                $('#interlocker').prop('disabled', true);
                $('#glass').prop('disabled', true);
            }

            if (lengthAwning && heightAwning) {
                $('#HeadAwning').prop('disabled', false);
                $('#SillAwning').prop('disabled', false);
                $('#JambAwning').prop('disabled', false);
                $('#GlassAwning').prop('disabled', false);
            } else {
                $('#HeadAwning').prop('disabled', true);
                $('#SillAwning').prop('disabled', true);
                $('#JambAwning').prop('disabled', true);
                $('#GlassAwning').prop('disabled', true);            
            }

            if (lengthFixed && heightFixed) {
                $('#HeadFixed').prop('disabled', false);
                $('#SillFixed').prop('disabled', false);
                $('#JambFixed').prop('disabled', false);
                $('#GlassFixed').prop('disabled', false);
            } else {
                $('#HeadFixed').prop('disabled', true);
                $('#SillFixed').prop('disabled', true);
                $('#JambFixed').prop('disabled', true);
                $('#GlassFixed').prop('disabled', true);
            }

            if (lengthCasement && heightCasement) {
                $('#HeadCasement').prop('disabled', false);
                $('#SillCasement').prop('disabled', false);
                $('#JambCasement').prop('disabled', false);
                $('#GlassCasement').prop('disabled', false);
            } else {
                $('#HeadCasement').prop('disabled', true);
                $('#SillCasement').prop('disabled', true);
                $('#JambCasement').prop('disabled', true);
                $('#GlassCasement').prop('disabled', true);
            }
        }

        function updateFeetFromInches() {
            const lengthInInches = parseFloat($('#length').val());
            const heightInInches = parseFloat($('#height').val());
            const lengthInInchesAwning = parseFloat($('#lengthAwning').val());
            const heightInInchesAwning = parseFloat($('#heightAwning').val());
            const lengthInInchesFixed = parseFloat($('#lengthFixed').val());
            const heightInInchesFixed = parseFloat($('#heightFixed').val());
            const lengthInInchesCasement = parseFloat($('#lengthCasement').val());
            const heightInInchesCasement = parseFloat($('#heightCasement').val());

            if (!isNaN(lengthInInches)) {
                const lengthFeet = Math.ceil(lengthInInches / 12);
                $('#lFoot').val(lengthFeet);

                // Calculate Length Foot x 2 and update the corresponding field
                const lengthX2 = lengthFeet * 2;
                $('#lFootX2').val(lengthX2);
            } else {
                $('#lFoot').val('');
                $('#lFootX2').val(''); // Clear the Length Foot x 2 field if input is invalid
            }

            if (!isNaN(heightInInches)) {
                const heightFeet = Math.ceil(heightInInches / 12);
                $('#hFoot').val(heightFeet);

                // Calculate Height Foot x 2 and update the corresponding field
                const heightX2 = heightFeet * 2;
                $('#hFootX2').val(heightX2);
            } else {
                $('#hFoot').val('');
                $('#hFootX2').val(''); // Clear the Height Foot x 2 field if input is invalid
            }
            
            //Awning
            if (!isNaN(lengthInInchesAwning)) {
                const lengthFeet = Math.ceil(lengthInInchesAwning / 12);
                $('#lFootAwning').val(lengthFeet);

                // Calculate Length Foot x 2 and update the corresponding field
                const lengthX2 = lengthFeet * 2;
                $('#lFootX2Awning').val(lengthX2);
            } else {
                $('#lFootAwning').val('');
                $('#lFootX2Awning').val(''); // Clear the Length Foot x 2 field if input is invalid
            }

            if (!isNaN(heightInInchesAwning)) {
                const heightFeet = Math.ceil(heightInInchesAwning / 12);
                $('#hFootAwning').val(heightFeet);

                // Calculate Height Foot x 2 and update the corresponding field
                const heightX2 = heightFeet * 2;
                $('#hFootX2Awning').val(heightX2);
            } else {
                $('#hFootAwning').val('');
                $('#hFootX2Awning').val(''); // Clear the Height Foot x 2 field if input is invalid
            }

            //Fixed
            if (!isNaN(lengthInInchesFixed)) {
                const lengthFeet = Math.ceil(lengthInInchesFixed / 12);
                $('#lFootFixed').val(lengthFeet);

                // Calculate Length Foot x 2 and update the corresponding field
                const lengthX2 = lengthFeet * 2;
                $('#lFootX2Fixed').val(lengthX2);
            } else {
                $('#lFootFixed').val('');
                $('#lFootX2Fixed').val(''); // Clear the Length Foot x 2 field if input is invalid
            }

            if (!isNaN(heightInInchesFixed)) {
                const heightFeet = Math.ceil(heightInInchesFixed / 12);
                $('#hFootFixed').val(heightFeet);

                // Calculate Height Foot x 2 and update the corresponding field
                const heightX2 = heightFeet * 2;
                $('#hFootX2Fixed').val(heightX2);
            } else {
                $('#hFootFixed').val('');
                $('#hFootX2Fixed').val(''); // Clear the Height Foot x 2 field if input is invalid
            }

            //Casement
            if (!isNaN(lengthInInchesCasement)) {
                const lengthFeet = Math.ceil(lengthInInchesCasement / 12);
                $('#lFootCasement').val(lengthFeet);

                // Calculate Length Foot x 2 and update the corresponding field
                const lengthX2 = lengthFeet * 2;
                $('#lFootX2Casement').val(lengthX2);
            } else {
                $('#lFootCasement').val('');
                $('#lFootX2Casement').val(''); // Clear the Length Foot x 2 field if input is invalid
            }

            if (!isNaN(heightInInchesCasement)) {
                const heightFeet = Math.ceil(heightInInchesCasement / 12);
                $('#hFootCasement').val(heightFeet);

                // Calculate Height Foot x 2 and update the corresponding field
                const heightX2 = heightFeet * 2;
                $('#hFootX2Casement').val(heightX2);
            } else {
                $('#hFootCasement').val('');
                $('#hFootX2Casement').val(''); // Clear the Height Foot x 2 field if input is invalid
            }
        }

        // Event listeners for Top Head, Bottom Sill, and Rail
        $('#topHead').on('change', function() {
            handleSelectChangeForTopHead();
        });

        $('#bottomSill').on('change', function() {
            handleSelectChangeForBottomSill();
        });

        $('#rail').on('change', function() {
            handleSelectChangeForRail();
        });

        $('#sideJamb').on('change', function() {
            handleSelectChangeForSideJamb();
        });

        $('#lockStile').on('change', function() {
            handleSelectChangeForLockstile();
        });

        $('#interlocker').on('change', function() {
            handleSelectChangeForInterlocker();
        });

        $('#glass').on('change', function() {
            handleSelectChangeForGlass();
        });
        //TopHead

        $('#HeadAwning').on('change', function() {
            handleSelectChangeForHeadAwning();
        });

        $('#SillAwning').on('change', function() {
            handleSelectChangeForSillAwning();
        });
        
        $('#JambAwning').on('change', function() {
            handleSelectChangeForJambAwning();
        });

        $('#GlassAwning').on('change', function() {
            handleSelectChangeForGlassAwning();
        });

        $('#HeadFixed').on('change', function() {
            handleSelectChangeForHeadFixed();
        });

        $('#SillFixed').on('change', function() {
            handleSelectChangeForSillFixed();
        });

        $('#JambFixed').on('change', function() {
            handleSelectChangeForJambFixed();
        });

        $('#GlassFixed').on('change', function() {
            handleSelectChangeForGlassFixed();
        });

        $('#HeadCasement').on('change', function() {
            handleSelectChangeForHeadCasement();
        });

        $('#SillCasement').on('change', function() {
            handleSelectChangeForSillCasement();
        });

        $('#JambCasement').on('change', function() {
            handleSelectChangeForJambCasement();
        });

        $('#GlassCasement').on('change', function() {
            handleSelectChangeForGlassCasement();
        });
        //Awning

        $('#length, #height').on('input', function(event) {
            $('#topHead').val('');
            $('#topHead').prop('selectedIndex', 0);
            $('#bottomSill').val('');
            $('#bottomSill').prop('selectedIndex', 0);
            $('#rail').val('');
            $('#rail').prop('selectedIndex', 0);
            $('#sideJamb').val('');
            $('#sideJamb').prop('selectedIndex', 0);
            $('#lockStile').val('');
            $('#lockStile').prop('selectedIndex', 0);
            $('#interlocker').val('');
            $('#interlocker').prop('selectedIndex', 0);
            $('#glass').val('');
            $('#glass').prop('selectedIndex', 0);

            $('#thPrice').val('');
            $('#bsPrice').val('');
            $('#rPrice').val('');
            $('#sjPrice').val('');
            $('#lsPrice').val('');
            $('#ilPrice').val('');
            $('#gPrice').val('');
        });

        $('#lengthAwning, #heightAwning').on('input', function(event) {
            $('#HeadAwning').val('');
            $('#HeadAwning').prop('selectedIndex', 0);
            $('#SillAwning').val('');
            $('#SillAwning').prop('selectedIndex', 0);
            $('#JambAwning').val('');
            $('#JambAwning').prop('selectedIndex', 0);
            $('#GlassAwning').val('');
            $('#GlassAwning').prop('selectedIndex', 0);

            $('#hPriceAwning').val('');
            $('#sPriceAwning').val('');
            $('#sjPriceAwning').val('');
            $('#gPriceAwning').val('');
        });

        $('#lengthFixed, #heightFixed').on('input', function(event) {
            $('#HeadFixed').val('');
            $('#HeadFixed').prop('selectedIndex', 0);
            $('#SillFixed').val('');
            $('#SillFixed').prop('selectedIndex', 0);
            $('#JambFixed').val('');
            $('#JambFixed').prop('selectedIndex', 0);
            $('#GlassFixed').val('');
            $('#GlassFixed').prop('selectedIndex', 0);

            $('#hPriceFixed').val('');
            $('#sPriceFixed').val('');
            $('#sjPriceFixed').val('');
            $('#gPriceFixed').val('');
        });

        $('#lengthCasement, #heightCasement').on('input', function(event) {
            $('#HeadCasement').val('');
            $('#HeadCasement').prop('selectedIndex', 0);
            $('#SillCasement').val('');
            $('#SillCasement').prop('selectedIndex', 0);
            $('#JambCasement').val('');
            $('#JambCasement').prop('selectedIndex', 0);
            $('#GlassCasement').val('');
            $('#GlassCasement').prop('selectedIndex', 0);

            $('#hPriceCasement').val('');
            $('#sPriceCasement').val('');
            $('#sjPriceCasement').val('');
            $('#gPriceCasement').val('');
        });

        // Trigger updates on length and height input changes
        $('#length, #height').on('input', function() {
            checkInputs();
            updateFeetFromInches();
            handleSelectChangeForTopHead(); // Ensure it runs after feet conversion and input validation
            handleSelectChangeForBottomSill();
            handleSelectChangeForRail();
            handleSelectChangeForSideJamb();
            handleSelectChangeForLockstile();
            handleSelectChangeForInterlocker();
            handleSelectChangeForGlass();
        });

        $('#lengthAwning, #heightAwning').on('input', function() {
            checkInputs();
            updateFeetFromInches();
            handleSelectChangeForHeadAwning();
            handleSelectChangeForSillAwning();
            handleSelectChangeForJambAwning();
            handleSelectChangeForGlassAwning();
        });

        $('#lengthFixed, #heightFixed').on('input', function() {
            checkInputs();
            updateFeetFromInches();
            handleSelectChangeForHeadFixed();
            handleSelectChangeForSillFixed();
            handleSelectChangeForJambFixed();
            handleSelectChangeForGlassFixed();
        });

        $('#lengthCasement, #heightCasement').on('input', function() {
            checkInputs();
            updateFeetFromInches();
            handleSelectChangeForHeadCasement();
            handleSelectChangeForSillCasement();
            handleSelectChangeForJambCasement();
            handleSelectChangeForGlassCasement();
        });

        // Disable dropdowns initially
        $('#topHead').prop('disabled', true);
        $('#bottomSill').prop('disabled', true);
        $('#rail').prop('disabled', true);
        $('#sideJamb').prop('disabled', true);
        $('#lockStile').prop('disabled', true);
        $('#interlocker').prop('disabled', true);
        $('#glass').prop('disabled', true);

        $('#HeadAwning').prop('disabled', true);
        $('#SillAwning').prop('disabled', true);
        $('#JambAwning').prop('disabled', true);
        $('#GlassAwning').prop('disabled', true);

        $('#HeadFixed').prop('disabled', true);
        $('#SillFixed').prop('disabled', true);
        $('#JambFixed').prop('disabled', true);
        $('#GlassFixed').prop('disabled', true);

        $('#HeadCasement').prop('disabled', true);
        $('#SillCasement').prop('disabled', true);
        $('#JambCasement').prop('disabled', true);
        $('#GlassCasement').prop('disabled', true);

        // Remove non-numeric characters from length and height inputs
        $('#length, #height, #lengthAwning, #heightAwning, #lengthFixed, #heightFixed, #lengthCasement, #heightCasement').on('keydown', function(event) {
            // Allow: backspace (key code 8) and F5 (key code 116)
            if (event.keyCode === 8 || event.keyCode === 116) {
                // Allow the keypress for Backspace and F5
                return;
            }

            // Ensure that it's a number (0-9), otherwise prevent the input
            if ((event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105)) {
                event.preventDefault();
            }
        });

        $('#length, #height, #lengthAwning, #heightAwning, #lengthFixed, #heightFixed, #lengthCasement, #heightCasement').on('input', function() {
            // Replace anything that's not a number
            $(this).val($(this).val().replace(/[^0-9]/g, ''));
        });
    });
</script>

</body>

</html>