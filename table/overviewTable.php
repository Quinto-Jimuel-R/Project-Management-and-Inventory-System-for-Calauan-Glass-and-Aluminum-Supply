<?php include('../database.php') ?>
<div id="overviewContainer" class="card-body table-responsive p-2" style="min-height: 300px;">
        <table class="table table-hover" id="OverviewTable" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th class="border-bottom border-top-0 px-3" style="width: 20%; font-size: 14px;">Name</th>
                    <th class="border-bottom border-top-0 px-3" style="width: 30%; font-size: 14px;">Progress</th>
                    <th class="border-bottom border-top-0 text-center ps-5" style="width: 20%; font-size: 14px;">Start date</th>
                    <th class="border-bottom border-top-0 text-center ps-5" style="width: 20%; font-size: 14px;">Due date</th>
                </tr>
            </thead>
            <tbody>
            <?php
                $result = mysqli_query($conn, "SELECT project.*, COUNT(task.task_id) AS total_tasks, SUM(CASE WHEN task.status = 'COMPLETE' THEN 1 ELSE 0 END) AS completed_tasks
                 FROM project
                 LEFT JOIN task ON project.project_id = task.project_id WHERE project.active = '1'
                 GROUP BY project.project_id ORDER BY project.project_id DESC;
             ");

                if ($result) 
                {
                    while ($data = mysqli_fetch_array($result)) {
                        $totalTasks = $data['total_tasks'];
                        $completedTasks = $data['completed_tasks'];
                        $completionPercentage = ($totalTasks > 0) ? ($completedTasks / $totalTasks) * 100 : 0;
                        ?>
                        <tr>
                            <td class="border-bottom border-top-0 px-3" style="font-size: 14px;"><?php echo $data['project_name']; ?></td>
                            <td class="border-bottom border-top-0 px-3" style="font-size: 14px;">
                                <div class="d-flex align-items-center">
                                    <div class="progress w-100 me-2" role="progressbar" aria-label="Progress" aria-valuenow="<?php echo $completionPercentage; ?>" aria-valuemin="0" aria-valuemax="100">
                                        <div class="progress-bar" style="width: <?php echo $completionPercentage; ?>%"><?php echo round($completionPercentage, 2) . '%'; ?></div>
                                    </div>
                                    <?php echo $completedTasks; ?>/<?php echo $totalTasks; ?>   
                                </div>
                            </td>
                            <td class="border-bottom border-top-0 text-center ps-5" style="font-size: 14px;"><?php echo date('n/j/y', strtotime($data['start_date'])); ?></td>
                            <td class="border-bottom border-top-0 text-center ps-5" style="font-size: 14px;"><?php echo date('n/j/y', strtotime($data['end_date'])); ?></td>
                        </tr>
                        <?php
                    }
                }
            ?>
            </tbody>
        </table>
    </div>

<script>
    $(document).ready(function () {
        $('#OverviewTable').DataTable({
            ordering: false,
            paging: false,
            searching: false,
            info: false
        });
    });
</script>