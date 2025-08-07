<?php include('../database.php') ?>
<div id="inventoryContainer" class="card">
    <div class="card-body table table-responsive p-2">
        <table class="table table-hover" id="InventoryTable" width="100%" cellspacing="0">
            <thead> 
                <tr>
                    <th class="border-bottom border-top-0 px-3" style="width: 20%; font-size: 14px;">Description</th>
                    <th class="border-bottom border-top-0 px-3" style="font-size: 14px;">Dimension</th>
                    <th class="border-bottom border-top-0 px-3" style="font-size: 14px;">Sqft</th>
                    <th class="border-bottom border-top-0 px-3" style="font-size: 14px;">Color</th>
                    <th class="border-bottom border-top-0 px-3" style="font-size: 14px;">Price</th>
                    <th class="border-bottom border-top-0 px-3" style="font-size: 14px;">Stock</th>
                    <th class="border-bottom border-top-0 px-3" style="font-size: 14px;">Category</th>
                    <th class="border-bottom border-top-0 px-3" style="font-size: 14px;"><center>Controls</center></th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $result = mysqli_query($conn, "SELECT * FROM inventory WHERE active = '1' ORDER BY item_id DESC");

                    if(mysqli_num_rows($result)>0)
                    {
                        while ($data = mysqli_fetch_array($result))
                        {
                ?>
                    <tr>
                        <td class="border-bottom border-top-0 px-3" style="font-size: 14px;"><?php echo $data['item_name'] ?></td>
                        <td class="border-bottom border-top-0 px-3" style="font-size: 14px;"><?php echo $data['dimension'] ?></td>
                        <td class="border-bottom border-top-0 px-3" style="font-size: 14px;"><?php echo $data['sqft'] ?></td>
                        <td class="border-bottom border-top-0 px-3" style="font-size: 14px;"><?php echo $data['color'] ?></td>
                        <td class="border-bottom border-top-0 px-3" style="font-size: 14px;">₱ <?php echo $data['price'] ?></td>
                        <td class="border-bottom border-top-0 px-3" style="font-size: 14px;"><?php echo $data['stock'] ?></td>
                        <td class="border-bottom border-top-0 px-3" style="font-size: 14px"><?php echo $data['category'] ?></td>
                        <td class="border-bottom border-top-0 px-3" style="font-size: 14px">
                            <div class="d-flex justify-content-evenly">
                            <a id="viewSupplierItem" href="#" data-bs-toggle="modal" data-bs-target="#viewSupplierModal" data-id="<?php echo $data['supplier_id']; ?>"><i class="fa-solid fa-eye"></i></a>
                                <a id="editItem" href="#" class="text-success" data-bs-toggle="modal" data-bs-target="#updateInventoryModal" data-id="<?php echo $data['item_id']; ?>"><i class="fa-solid fa-pen-to-square"></i></a>
                                <a id="deleteItem" href="#" class="text-danger" data-id="<?php echo $data['item_id']; ?>"><i class="fa-solid fa-trash"></i></a>
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
        $('#InventoryTable').DataTable({
            ordering: false,
            paging: false,
            info: false
        });
    });
</script>