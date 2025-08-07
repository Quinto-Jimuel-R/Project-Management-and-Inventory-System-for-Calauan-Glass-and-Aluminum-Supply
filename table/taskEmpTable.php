<? include '../database.php' ?>
<div class="accordionTaskContainer">
                                                        <div id="collapse<?php echo $projectId; ?>" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                                                            <div class="accordion-body border-top p-2">
                                                                <h6 class="m-0">Task</h6>
                                                                <div class="card-body d-flex py-2 px-0">
                                                                    <div class="" style="width: 50%; font-size: 15px;">
                                                                        Description
                                                                    </div>
                                                                    <div class="text-center" style="width: 25%; font-size: 15px;">
                                                                        Quantity
                                                                    </div>
                                                                    <div class="text-center" style="width: 25%; font-size: 15px;">
                                                                        Dimension
                                                                        </div>
                                                                    </div>
                                                                    <!-- Your existing project and task display code here -->

                                                                    <?php
                                                                        while ($taskData = mysqli_fetch_assoc($taskResultCompleted)) 
                                                                        {
                                                                            ?>
                                                                            <div class="card-body d-flex p-0">
                                                                                <div class="dropdown text-right p-0" style="width: 7%; font-size: 15px;">
                                                                                    <i id="dropdownMenuButton" class="fa-solid fa-circle-check text-success mx-2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;"></i>
                                                                                    <div class="dropdown-menu shadow animated--grow-in" aria-labelledby="dropdownMenuButton">
                                                                                        <a class="dropdown-item editable-statusTask" href="#" data-status="Completed" data-task-id="<?php echo $taskData['task_id']; ?>"><i class="fa-solid fa-circle-check fa-fw text-success"></i> Completed</a>
                                                                                        <a class="dropdown-item editable-statusTask" href="#" data-status="Inprogress" data-task-id="<?php echo $taskData['task_id']; ?>"><i class="fa-solid fa-circle-dot fa-fw text-primary"></i> In Progress</a>
                                                                                        <a class="dropdown-item editable-statusTask" href="#" data-status="Todo" data-task-id="<?php echo $taskData['task_id']; ?>"><i class="fa-solid fa-circle-dot fa-fw text-secondary"></i> To Do</a>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="" style="width: 43%; font-size: 15px;"><?php echo $taskData['description']; ?></div>
                                                                                <div class="text-center" style="width: 25%; font-size: 15px;"><?php echo $taskData['quantity']; ?></div>
                                                                                <div class="text-center" style="width: 25%; font-size: 15px;"><?php echo $taskData['x_coordinate']; ?><sup>x</sup> x <?php echo $taskData['y_coordinate']; ?><sup>y</sup> x <?php echo $taskData['depth']; ?><sup>d</sup></div>
                                                                            </div>
                                                                            <hr class="my-1">
                                                                            <?php
                                                                        }
                                                                    ?>

                                                                    <?php
                                                                        while ($taskData = mysqli_fetch_assoc($taskResultInprogress)) 
                                                                        {
                                                                            ?>
                                                                            <div class="card-body d-flex p-0">
                                                                                <div class="dropdown text-right p-0" style="width: 7%; font-size: 15px;">
                                                                                    <i id="dropdownMenuButton" class="fa-solid fa-circle-dot text-primary mx-2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;"></i>
                                                                                    <div class="dropdown-menu shadow animated--grow-in" aria-labelledby="dropdownMenuButton">
                                                                                        <a class="dropdown-item editable-statusTask" href="#" data-status="Completed" data-task-id="<?php echo $taskData['task_id']; ?>"><i class="fa-solid fa-circle-check fa-fw text-success"></i> Completed</a>
                                                                                        <a class="dropdown-item editable-statusTask" href="#" data-status="Inprogress" data-task-id="<?php echo $taskData['task_id']; ?>"><i class="fa-solid fa-circle-dot fa-fw text-primary"></i> In Progress</a>
                                                                                        <a class="dropdown-item editable-statusTask" href="#" data-status="Todo" data-task-id="<?php echo $taskData['task_id']; ?>"><i class="fa-solid fa-circle-dot fa-fw text-secondary"></i> To Do</a>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="" style="width: 43%; font-size: 15px;"><?php echo $taskData['description']; ?></div>
                                                                                <div class="text-center" style="width: 25%; font-size: 15px;"><?php echo $taskData['quantity']; ?></div>
                                                                                <div class="text-center" style="width: 25%; font-size: 15px;"><?php echo $taskData['x_coordinate']; ?><sup>x</sup> x <?php echo $taskData['y_coordinate']; ?><sup>y</sup> x <?php echo $taskData['depth']; ?><sup>d</sup></div>
                                                                            </div>
                                                                            <hr class="my-1">
                                                                            <?php
                                                                        }
                                                                    ?>
                                                                    
                                                                    <?php
                                                                        while ($taskData = mysqli_fetch_assoc($taskResultTodo)) 
                                                                        {
                                                                            ?>
                                                                            <div class="card-body d-flex p-0">
                                                                                <div class="dropdown text-right p-0" style="width: 7%; font-size: 15px;">
                                                                                    <i id="dropdownMenuButton" class="fa-solid fa-circle-dot text-secondary mx-2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;"></i>
                                                                                    <div class="dropdown-menu shadow animated--grow-in" aria-labelledby="dropdownMenuButton">
                                                                                        <a class="dropdown-item editable-statusTask" href="#" data-status="Completed" data-task-id="<?php echo $taskData['task_id']; ?>"><i class="fa-solid fa-circle-check fa-fw text-success"></i> Completed</a>
                                                                                        <a class="dropdown-item editable-statusTask" href="#" data-status="Inprogress" data-task-id="<?php echo $taskData['task_id']; ?>"><i class="fa-solid fa-circle-dot fa-fw text-primary"></i> In Progress</a>
                                                                                        <a class="dropdown-item editable-statusTask" href="#" data-status="Todo" data-task-id="<?php echo $taskData['task_id']; ?>"><i class="fa-solid fa-circle-dot fa-fw text-secondary"></i> To Do</a>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="" style="width: 43%; font-size: 15px;"><?php echo $taskData['description']; ?></div>
                                                                                <div class="text-center" style="width: 25%; font-size: 15px;"><?php echo $taskData['quantity']; ?></div>
                                                                                <div class="text-center" style="width: 25%; font-size: 15px;"><?php echo $taskData['x_coordinate']; ?><sup>x</sup> x <?php echo $taskData['y_coordinate']; ?><sup>y</sup> x <?php echo $taskData['depth']; ?><sup>d</sup></div>
                                                                            </div>
                                                                            <hr class="my-1">
                                                                            <?php
                                                                        }
                                                                    ?>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    