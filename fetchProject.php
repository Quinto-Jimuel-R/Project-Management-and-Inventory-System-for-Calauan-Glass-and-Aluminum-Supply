<?php
include 'database.php';

$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($action == 'project') {
    $projectId = $_POST['projectId'];

    $sql = "SELECT project.*, customer.*, task.*
        FROM project
        INNER JOIN customer ON project.customer_id = customer.customer_id
        LEFT JOIN task ON project.project_id = task.project_id
        WHERE project.project_id = $projectId";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $projectData = $result->fetch_assoc();

        // Calculate progress and task counts
        $percentageCompletion = 0;
        $finishedTasksCount = 0;
        $totalTasksCount = 0;

        if ($result->num_rows > 0) {
            $totalTasksCount = $result->num_rows; // Assuming each row represents a task

            while ($task = $result->fetch_assoc()) {
                if ($task['status'] == 'completed') {
                    $finishedTasksCount++;
                }
            }

            // Fetch finished tasks
            $finishedTasksQuery = "SELECT COUNT(*) as completed FROM task WHERE project_id = '$projectId' AND status = 'completed'";
            $finishedTasksResult = mysqli_query($conn, $finishedTasksQuery);
            $finishedTasksCount = mysqli_fetch_assoc($finishedTasksResult)['completed'];

            // Fetch total tasks
            $totalTasksQuery = "SELECT COUNT(*) as Total FROM task WHERE project_id = '$projectId'";
            $totalTasksResult = mysqli_query($conn, $totalTasksQuery);
            $totalTasksCount = mysqli_fetch_assoc($totalTasksResult)['Total'];

            // Calculate percentage completion
            $percentageCompletion = ($totalTasksCount > 0) ? (($finishedTasksCount / $totalTasksCount) * 100) : 0;

            ?>
            <div class="modal-header text-center pb-0">
                <h5>View Project</h5>
            </div> 
            <div class="modal-body pb-0">
                <div class="row">
                    <div class="col-lg-6 col-sm-3">
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th class="text-center" colspan="2">Project Details</th>
                                </tr>
                                <tr>
                                    <td class="w-25">Name</td>
                                    <td class="w-25"> <?php echo $projectData['project_name']; ?> </td>
                                </tr>
                                <tr>
                                    <td class="w-25">Customer</td>
                                    <td class="w-25"> <?php echo $projectData['customer_name']; ?> </td>
                                </tr>
                                <tr>
                                    <td class="w-25">Location</td>
                                    <td class="w-25"> <?php echo $projectData['location']; ?> </td>
                                </tr>
                                <tr>
                                    <td class="w-25">Start Date</td>
                                    <td class="w-25"> <?php echo date('M d, Y', strtotime($projectData['start_date'])); ?> </td>
                                </tr>
                                <tr>
                                    <td class="w-25">Deadline</td>
                                    <td class="w-25"> <?php echo date('M d, Y', strtotime($projectData['end_date'])); ?> </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-lg-6 col-sm-3">
                        <?php
                        if ($totalTasksCount > 0) {
                            ?>
                            <table class="table table-bordered table-striped" id="progressTable">
                                <tbody>
                                    <tr>
                                        <th class="text-center" colspan="2">Order Details</th>
                                    </tr>
                                    <?php
                                    // Reset the result set pointer to the beginning
                                    $result->data_seek(0);

                                    while ($taskData = $result->fetch_assoc()) {
                                        ?>
                                        <tr>
                                            <td class="w-50"><?php echo $taskData['description']; ?></td>
                                            <td class="w-50">
                                                <?php if ($taskData['status'] == 'Todo'): ?>
                                                    To Do
                                                <?php elseif ($taskData['status'] == 'Inprogress'): ?>
                                                    In Progress
                                                <?php elseif ($taskData['status'] == 'Completed'): ?>
                                                    Completed
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                            <?php
                        } else {
                            ?>
                            <table class="table table-bordered table-striped" id="progressTable">
                                <tbody>
                                    <tr>
                                        <th class="text-center" colspan="2">Task Details</th>
                                    </tr>
                                    <tr>
                                        <td class="text-center w-25"> No data available in table </td>
                                    </tr>
                                </tbody>
                            </table>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="modal-body text-right pb-3">
                <a class="btn btn-primary" href="projectUpdate.php?project_id=<?php echo $projectId; ?>"><i class="fa-solid fa-pen-to-square me-2"></i>Update Progress</a>
                <a href="#" class="btn btn-danger" data-bs-dismiss="modal" style="width: 100px;">Close</a>
            </div>
            <?php
        } else {
            echo 'Project not found.';
        }
    }
}
?>

<script>
    $(document).ready(function() 
    {
            $('#progressTable').DataTable({
                
            });
    });
</script>