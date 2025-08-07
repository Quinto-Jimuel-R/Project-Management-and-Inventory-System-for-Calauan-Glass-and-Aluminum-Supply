<?php include'../database.php' ?>
<div id="employeeContainer" class="card">
    <div class="card-body table table-responsive p-2">
        <table class="table table-hover" id="EmployeeTable" width="100%" cellspacing="0">
            <thead> 
                <tr>
                    <th class="border-bottom border-top-0 px-3" style="width: 20%; font-size: 14px;">Name</th>
                    <th class="border-bottom border-top-0 px-3" style="width: 20%; font-size: 14px;">Address</th>
                    <th class="border-bottom border-top-0 px-3" style="width: 20%; font-size: 14px;">Email</th>
                    <th class="border-bottom border-top-0 px-3" style="width: 20%; font-size: 14px;">Phone Number</th>
                    <th class="border-bottom border-top-0 px-3" style="width: 20%; font-size: 14px;"><center>Controls</center></th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $result = mysqli_query($conn, "SELECT * FROM employee WHERE user_type = 'employee' ORDER BY employee_id DESC");

                    if(mysqli_num_rows($result)>0)
                    {
                        while ($data = mysqli_fetch_array($result))
                        {
                ?>
                    <tr>
                        <td class="border-bottom border-top-0 px-3" style="font-size: 14px;"><?php echo $data['employee_name'] ?></td>
                        <td class="border-bottom border-top-0 px-3" style="font-size: 14px;"><?php echo $data['address'] ?></td>
                        <td class="border-bottom border-top-0 px-3" style="font-size: 14px"><?php echo $data['email'] ?></td>
                        <td class="border-bottom border-top-0 px-3" style="font-size: 14px"><?php echo $data['phone_number'] ?></td>
                        <td class="border-bottom border-top-0 px-3" style="font-size: 14px">
                            <div class="d-flex justify-content-evenly">
                                <a id="editItem" href="#" class="text-success" data-bs-toggle="modal" data-bs-target="#updateInventoryModal" data-id=""><i class="fa-solid fa-pen-to-square"></i></a>
                                <a id="deleteItem" href="#" class="text-danger" data-id=""><i class="fa-solid fa-trash"></i></a>
                            </div>
                        </td>
                    </tr>
                <?php 
                      }
                    } 
                ?>

            </tbody>
        </table>
    </div>
</div>

<script>
    $(document).ready(function() 
    {
        $('#EmployeeTable').DataTable({
            ordering: false,
            paging: false,
            info: false
        });
    });
</script>