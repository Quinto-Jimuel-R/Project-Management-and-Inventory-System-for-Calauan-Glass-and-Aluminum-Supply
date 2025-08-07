<?php 

    include '../database.php';

    $project_id = $_GET['project_id'];

?>
<div id="taskContainer" class="table-responsive">   
                                    <table class="table table-bordered table-striped" id="progressTable">
                                        <thead>
                                            <tr>
                                                <th>Description</th>
                                                <th>Quantity</th>
                                                <th>Dimension</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <?php 
                                            $result = mysqli_query($conn, "SELECT * FROM task WHERE project_id = '$project_id'");

                                            if (mysqli_num_rows($result) > 0) {
                                                while ($data = mysqli_fetch_array($result)) {
                                        ?>
                                        <tbody>
                                            <td><?php echo $data['description']; ?></td>
                                            <td><?php echo $data['quantity']; ?></td>
                                            <td><?php echo $data['x_coordinate']; ?><sup>x</sup> x <?php echo $data['y_coordinate']; ?><sup>y</sup> x <?php echo $data['depth']; ?><sup>d</sup></td>
                                            <td class="text-center">
                                            <?php if ($data['status'] == 'NotFinished'): ?>
                                                <button id="updateTaskStatus" class="btn btn-success" data-task-id="<?php echo $data['task_id']; ?>"><i class="fa-solid fa-check"></i></button>
                                            <?php elseif ($data['status'] == 'Finished'): ?>
                                                Finish
                                            <?php endif; ?>
                                            </td>
                                        </tbody>
                                        <?php 
                                                }
                                            }
                                        ?>
                                    </table>
                                </div>
<script>
    $(document).ready(function() 
    {
        $('#progressTable').DataTable({
            responsive: true,
            ordering: false,
            paging: false,
            searching: false,
            info: false
        });
    });
</script>