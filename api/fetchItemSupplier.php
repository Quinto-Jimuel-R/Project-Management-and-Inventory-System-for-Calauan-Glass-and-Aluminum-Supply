<?php
include 'database.php';

$action = isset($_GET['action']) ? $_GET['action'] : '';

    if ($action == 'supplierItem') 
    {
        $supplierId = $_GET['supplierId'];

        $sql = "SELECT * FROM supplier WHERE supplier_id = $supplierId";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $supplierData = $result->fetch_assoc();
            ?>
                <div class="modal-header d-flex align-items-center">
                    <strong class="text-dark">Supplier</strong>
                    <button id="closeModal" type="button" class="btn-close rounded-0" data-bs-dismiss="modal" aria-label="Close" style="font-size: 12px;"></button>
                </div>
            <div class="modal-body pb-0">
                <p>Company Name: <?php echo $supplierData['company_name']; ?></p>
                <p>Contact Person: <?php echo $supplierData['contact_person']; ?></p>
                <p>Address: <?php echo $supplierData['address']; ?></p>
                <p>Phone Number: <?php echo $supplierData['phone_number']; ?></p>
                <!-- Add other supplier details here -->
            </div>
            <?php
        } 
        else 
        {
            ?>
                <div class="modal-header d-flex align-items-center">
                    <strong class="text-dark">Supplier</strong>
                    <button id="closeModal" type="button" class="btn-close rounded-0" data-bs-dismiss="modal" aria-label="Close" style="font-size: 12px;"></button>
                </div>
                <div class="modal-body pb-0">
                    <p>This Item dont have supplier</p>
                    <!-- Add other supplier details here -->
                </div>
                <div class="modal-body text-right pb-3">
                    <a href="#" class="btn btn-danger" data-bs-dismiss="modal" style="width: 100px;">Close</a>
                </div>
            <?php
        }
    }
?>