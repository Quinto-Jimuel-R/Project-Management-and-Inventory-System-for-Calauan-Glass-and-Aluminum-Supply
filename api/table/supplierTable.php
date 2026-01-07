<?php include '../database.php' ?>
<div id="supplierContainer" class="card">
    <div class="card-body table table-responsive p-2">
                <table class="table table-striped table-hover" id="SupplierTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th class="border-bottom border-top-0 px-5" style="font-size: 14px;">Company Name</th>
                            <th class="border-bottom border-top-0 px-5" style="font-size: 14px;">Contact Person</th>
                            <th class="border-bottom border-top-0 px-5" style="font-size: 14px;">Address</th>
                            <th class="border-bottom border-top-0 px-5" style="font-size: 14px;">Phone Number</th>
                            <th class="border-bottom border-top-0 px-5" style="font-size: 14px;"><center>Controls</center></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $result = mysqli_query($conn, "SELECT * FROM supplier WHERE active = '1' ORDER BY supplier_id DESC");

                        if (mysqli_num_rows($result) > 0) {
                            while ($data = mysqli_fetch_array($result)) {
                                ?>
                                <tr>
                                    <td class="border-bottom border-top-0 px-5" style="font-size: 14px;"><?php echo $data['company_name']; ?></td>
                                    <td class="border-bottom border-top-0 px-5" style="font-size: 14px;"><?php echo $data['contact_person']; ?></td>
                                    <td class="border-bottom border-top-0 px-5" style="font-size: 14px;"><?php echo $data['address']; ?></td>
                                    <td class="border-bottom border-top-0 px-5" style="font-size: 14px;"><?php echo $data['phone_number']; ?></td>
                                    <td class="border-bottom border-top-0 px-5" >
                                        <div class="d-flex justify-content-evenly">
                                            <a id="editSupplier" href="#" class="text-success" data-bs-toggle="modal" data-bs-target="#updateSupplierModal" data-id="<?php echo $data['supplier_id']; ?>"><i class="fa-solid fa-pen-to-square"></i></a>
                                            <a id="deleteSupplier" href="#" class="text-danger" data-id="<?php echo $data['supplier_id']; ?>"><i class="fa-solid fa-trash"></i></a>
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
        $('#SupplierTable').DataTable({
            ordering: false,
            paging: false,
            info: false
        });
    });
</script>