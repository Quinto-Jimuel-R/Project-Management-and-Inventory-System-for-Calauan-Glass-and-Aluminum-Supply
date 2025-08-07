<?php include '../database.php' ?>
<div id="listContainer">
        <?php
            $result = mysqli_query($conn, "SELECT * FROM project ORDER BY project_id DESC");

            if (mysqli_num_rows($result) > 0) {
                ?>
                <div class="accordion accordion-flush" id="accordionExample">
                    <?php
                    $index = 1;
                    while ($row = mysqli_fetch_assoc($result)):
                        $projectName = $row['project_name'];
                        $projectId = $row['project_id']; // Assuming 'project_id' is the unique identifier for each project
                        ?>
                            <div class="accordion-item border border-bottom-1 rounded mb-2">
                                <div class="accordion-header d-flex align-items-center justify-content-between p-3" id="heading<?= $index ?>">
                                    <div class="w-25 ms-2" style="font-size: 16px;"><?= $projectName ?></div>
                                    <button class="accordion-button collapsed p-1 w-auto" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapse<?= $index ?>" aria-expanded="false"
                                            aria-controls="collapse<?= $index ?>">
                                    </button>
                                </div>
                                <div id="collapse<?= $index ?>" class="accordion-collapse collapse"
                                    aria-labelledby="heading<?= $index ?>" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <!-- Additional details or content can be added here -->
                                        <div>
                                            <?php
                                                // Fetch status data for the current project
                                                $statusResult = mysqli_query($conn, "SELECT * FROM status WHERE project_id = '$projectId' ORDER BY status_name ASC");

                                                if (mysqli_num_rows($statusResult) > 0) 
                                                {
                                                    while ($statusRow = mysqli_fetch_assoc($statusResult)) 
                                                    {
                                                        $statusId = $statusRow['status_id'];
                                                        $statusName = $statusRow['status_name'];
                                                        $statusColor = $statusRow['color'];
                                                        ?>
                                                            <div class="py-2" style="min-height: 100px;">
                                                                <div class="ms-3 px-2 rounded d-flex align-items-center" style="width: fit-content; font-size: 13px;">
                                                                    <i class="fa-solid fa-circle-dot" style="color: <?= $statusColor ?>;"></i> 
                                                                    <div class="ms-1"><?= $statusName ?></div>
                                                                </div>
                                                                <div class="mt-2">
                                                                    <?php 
                                                                        $resultTask = mysqli_query($conn, "SELECT * FROM task WHERE status = '$statusName' AND project_id = '$projectId'");
                                                                        
                                                                        if(mysqli_num_rows($resultTask) > 0) 
                                                                        {
                                                                            while ($taskRow = mysqli_fetch_assoc($resultTask)) 
                                                                            {
                                                                                ?>
                                                                                    <p class="border-bottom mb-2 p-1" style="font-size: 13px; margin-left: 65px;">
                                                                                        <?= $taskRow['description'] ?>
                                                                                    </p>
                                                                                <?php
                                                                            }
                                                                        }
                                                                        else{
                                                                            ?>
                                                                                <p class="text-center mb-2" style="font-size: 13px; margin-left: 65px;">
                                                                                    No task found
                                                                                </p>
                                                                            <?php
                                                                        }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        <?php
                                                    }
                                                } else {
                                                    echo "No status data found for project ID: $projectId";
                                                }
                                            ?>
                                        </div>

                                        <div class="d-flex justify-content-end">
                                            <a href="projectDetails.php?id=<?= $projectId ?>" class="card-link">View Details</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                        $index++;
                    endwhile;
                    ?>
                </div>
                <?php
            }
        ?>
        </div>