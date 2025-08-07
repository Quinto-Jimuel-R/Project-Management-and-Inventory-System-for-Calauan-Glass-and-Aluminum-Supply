<?php 

    session_start();

    $employeeID = $_SESSION['employeeID'];

    include '../database.php' 

?>
<div id="EmployeeListContainer">
    <?php 
    $statusResult = mysqli_query($conn, "SELECT * FROM status ORDER BY status_name DESC");

    if(mysqli_num_rows($statusResult) > 0) {
        while($status = mysqli_fetch_array($statusResult)) {
            $statusName = $status['status_name'];
    ?>
            <div class="card mb-2">
                <div class="card-header" style="border-top: 5px solid <?php echo $status['color'] ?>;">
                    <div class="rounded fw-bold p-1" style="font-size: 14px;"><?php echo $statusName; ?></div>
                </div>
                <div class="card-body">
                    <div class="accordion accordion-flush">
                        <?php 
                        $projectsResult = mysqli_query($conn, "SELECT * FROM project WHERE employee_id = '$employeeID' AND status = '$statusName' AND active = '1'");
                        if(mysqli_num_rows($projectsResult) > 0) {
                            while($project = mysqli_fetch_array($projectsResult)) {
                                $projectID = $project['project_id'];
                                ?>
                                <div class="accordion-item border rounded mb-2 px-2">
                                    <div class="accordion-header">
                                        <div class="d-flex justify-content-between">
                                            <div class="py-3 d-flex align-items-center">
                                                <a class="nav-link px-2" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fa-solid fa-circle-dot" style="color: <?php echo $status['color'] ?>;"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                                    <?php 
                                                    $stats = mysqli_query($conn, "SELECT * FROM status ORDER BY status_name DESC");
                                                    if(mysqli_num_rows($stats) > 0) {
                                                        while($stat = mysqli_fetch_array($stats)) {
                                                    ?>
                                                            <a class="dropdown-item editable-status" href="#" data-status="<?php echo $stat['status_name'] ?>" data-project-id="<?php echo $projectID; ?>">
                                                                <i class="fa-solid fa-circle-dot fa-fw mr-2" style="color: <?php echo $stat['color'] ?>;"></i>
                                                                <?php echo $stat['status_name'] ?>
                                                            </a>
                                                    <?php
                                                        }
                                                    }
                                                    ?>
                                                </div>
                                                <?php echo $project['project_name']; ?>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <button class="accordion-button rounded collapsed text-center p-0" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse<?php echo $projectID; ?>" aria-expanded="false" aria-controls="flush-collapse<?php echo $projectID; ?>" style="height: 20px;"></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="flush-collapse<?php echo $projectID; ?>" class="accordion-collapse collapse">
                                        <div class="accordion-body d-flex px-3 py-0">
                                            <div class="w-25" style="font-size: 15px;">
                                                <p>Deadline: <?php echo $project['end_date']; ?></p>
                                            </div>
                                            <div class="w-75">
                                                <div class="border-bottom rounded-0 d-flex mb-1">
                                                    <div style="width: 25%; font-size: 13px;">
                                                        <p class="m-0">Name</p>
                                                    </div>
                                                    <div style="width: 25%; font-size: 13px;">
                                                        <p class="m-0">Quantity</p>
                                                    </div>
                                                    <div style="width: 25%; font-size: 13px;">
                                                        <p class="m-0">Dimension</p>
                                                    </div>
                                                    <div style="width: 25%; font-size: 13px;">
                                                        <p class="m-0">Status</p>
                                                    </div>
                                                </div>
                                                <?php 
                                                $tasksResult = mysqli_query($conn, "SELECT * FROM task WHERE project_id = '$projectID' ORDER BY status DESC");

                                                if(mysqli_num_rows($tasksResult) > 0) {
                                                    while($task = mysqli_fetch_array($tasksResult)) {
                                                        $taskStatus = $task['status'];
                                                        $taskID = $task['task_id'];
                                                        ?>
                                                        <div class="border-bottom rounded-0 d-flex ps-3 py-2">
                                                            <div style="width: 25%;">
                                                                <p class="m-0"><?php echo $task['description']; ?></p>
                                                            </div>
                                                            <div style="width: 25%;">
                                                                <p class="m-0"><?php echo $task['quantity']; ?></p>
                                                            </div>
                                                            <div style="width: 25%;">
                                                                <p class="m-0"><?php echo $task['x_coordinate']; ?><sup>x</sup> x <?php echo $task['y_coordinate']; ?><sup>y</sup> x <?php echo $task['depth']; ?><sup>d</sup></p>
                                                            </div>
                                                            <div style="width: 25%;">
                                                                <?php 
                                                                $stat = mysqli_query($conn, "SELECT * FROM status WHERE status_name = '$taskStatus'");

                                                                if(mysqli_num_rows($stat) > 0) {
                                                                    while($data = mysqli_fetch_array($stat)) {
                                                                ?>
                                                                        <div class="dropdown">
                                                                            <a class="nav-link p-0" href="#" id="userDropdown" role="button"
                                                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                <i class="fa-solid fa-circle-dot" style="color: <?php echo $data['color'] ?>;"></i>
                                                                            </a>
                                                                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="statusDropdown">
                                                                                <?php 
                                                                                $allStatus = mysqli_query($conn, "SELECT * FROM status ORDER BY status_name DESC");

                                                                                if(mysqli_num_rows($allStatus) > 0) {
                                                                                    while($statusData = mysqli_fetch_array($allStatus)) {
                                                                                ?>
                                                                                        <a class="dropdown-item editable-statusTask" href="#" data-status="<?php echo $statusData['status_name']; ?>" data-task-id="<?php echo $taskID; ?>">
                                                                                            <i class="fa-solid fa-circle-dot fa-fw mr-2" style="color: <?php echo $statusData['color'] ?>;"></i> <?php echo $statusData['status_name'] ?>
                                                                                        </a>
                                                                                <?php
                                                                                    }
                                                                                }
                                                                                ?>
                                                                            </div>
                                                                        </div>
                                                                <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <div class="d-flex align-items-center justify-content-center py-2">
                                                        <b>No tasks found for this project.</b>
                                                    </div>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                        } else {
                            ?>
                            <div class="d-flex align-items-center justify-content-center py-2">
                                <b>No projects found for this status.</b>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php
        }
    }
    ?>
</div>