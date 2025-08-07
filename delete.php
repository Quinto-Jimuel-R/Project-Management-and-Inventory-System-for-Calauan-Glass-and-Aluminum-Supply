<?php include'database.php' ?>

<?php 
    session_start();

    $action = isset($_GET['action']) ? $_GET['action'] : '';

    if ($action == 'project') 
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {
            $adminID = mysqli_real_escape_string($conn, $_POST['adminID']);
            $projectId = mysqli_real_escape_string($conn, $_POST['projectId']);

            $projectQuery = "SELECT project_name FROM project WHERE project_id = '$projectId'";
            $projectResult = mysqli_query($conn, $projectQuery);
            $project = mysqli_fetch_assoc($projectResult);

            if ($project) 
            {
                $projectName = $project['project_name'];
                
                $deleteQuery = "UPDATE project SET active = '0' WHERE project_id = '$projectId'";

                if (mysqli_query($conn, $deleteQuery)) 
                {
                    // Add status update to "CANCEL"
                    $statusUpdateQuery = "UPDATE project SET status = 'CANCEL' WHERE project_id = '$projectId'";
                    
                    if (mysqli_query($conn, $statusUpdateQuery)) 
                    {
                        $description = mysqli_real_escape_string($conn, "Project '$projectName' was removed");

                        $insertLog = "INSERT INTO log (description, date, time, user_id) VALUES ('$description', CURDATE(), CURTIME(), '$adminID')";
                        
                        if (mysqli_query($conn, $insertLog)) 
                        {
                            echo "Project $projectName removed";
                        }
                    }
                }
            }
        }
    }

    if($action == 'inventory')
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {
            $adminID = mysqli_real_escape_string($conn, $_POST['adminID']);
            $itemId = mysqli_real_escape_string($conn, $_POST['itemId']);
    
            $itemQuery = "SELECT item_name FROM inventory WHERE item_id = '$itemId'";
            $itemResult = mysqli_query($conn, $itemQuery);
            $item = mysqli_fetch_assoc($itemResult);
    
            if ($item) 
            {
                $itemName = $item['item_name'];
    
                $deleteQuery = "UPDATE inventory SET active = '0' WHERE item_id = '$itemId'";

                if (mysqli_query($conn, $deleteQuery)) 
                {
                    $description = mysqli_real_escape_string($conn, "Item '$itemName' was remove");
                    
                    $insertLog = "INSERT INTO log (description, date, time, user_id) VALUES ('$description', CURDATE(), CURTIME(), '$adminID')";

                    if (mysqli_query($conn, $insertLog)) 
                    {
                        echo "Item $itemName";
                    }
                }
            }
        }
    }

    if ($action == 'supplier') 
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {
            $adminID = mysqli_real_escape_string($conn, $_POST['adminID']);
            $supplierId = mysqli_real_escape_string($conn, $_POST['supplierId']);
            
            $currentSupplierQuery = "SELECT company_name FROM supplier WHERE supplier_id = $supplierId";
            $currentSupplierResult = mysqli_query($conn, $currentSupplierQuery);
            $currentSupplier = mysqli_fetch_assoc($currentSupplierResult);

            $supplierNAME = $currentSupplier['company_name'];
    
            $deleteQuery = "UPDATE supplier SET active = '0' WHERE supplier_id = $supplierId";
            
            if (mysqli_query($conn, $deleteQuery)) 
            {
                $companyName = $currentSupplier['company_name'];
                $logDescription = "Supplier '$companyName' was remove";
                $logDescription = mysqli_real_escape_string($conn, $logDescription);
                $logQuery = "INSERT INTO log (description, date, time, user_id) VALUES ('$logDescription', CURDATE(), CURTIME(), '$adminID')";
                mysqli_query($conn, $logQuery);
    
                echo "Supplier $supplierNAME";
            }
        }
    }

    if ($action == 'employee') 
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {
            $adminID = mysqli_real_escape_string($conn, $_POST['adminID']);
            $employeeId = mysqli_real_escape_string($conn, $_POST['employeeId']);
            
            $currentValuesQuery = "SELECT name FROM user WHERE user_id = '$employeeId'";
            $currentValuesResult = mysqli_query($conn, $currentValuesQuery);
            $currentValues = mysqli_fetch_assoc($currentValuesResult);
            
            $employeeName = $currentValues['name'];
    
            $deleteQuery = "DELETE FROM user WHERE user_id = '$employeeId'";
            
            if (mysqli_query($conn, $deleteQuery)) 
            {
                $logDescription = "Employee '$employeeName' was remove";
                $logDescription = mysqli_real_escape_string($conn, $logDescription);
                $logQuery = "INSERT INTO log (description, date, time, user_id) VALUES ('$logDescription', CURDATE(), CURTIME(), '$adminID')";
                mysqli_query($conn, $logQuery);
    
                echo "Employee $employeeName";
            }
        }
    }

    if ($action == 'cashier') 
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {
            $adminID = mysqli_real_escape_string($conn, $_POST['adminID']);
            $cashierId = mysqli_real_escape_string($conn, $_POST['cashierId']);
            
            $currentValuesQuery = "SELECT name FROM user WHERE user_id = '$cashierId'";
            $currentValuesResult = mysqli_query($conn, $currentValuesQuery);
            $currentValues = mysqli_fetch_assoc($currentValuesResult);
            
            $cashierName = $currentValues['name'];

            $deleteQuery = "DELETE FROM user WHERE user_id = '$cashierId'";
            
            if (mysqli_query($conn, $deleteQuery)) 
            {
                $logDescription = "Cashier '$cashierName' was removed";
                $logDescription = mysqli_real_escape_string($conn, $logDescription);
                $logQuery = "INSERT INTO log (description, date, time, user_id) VALUES ('$logDescription', CURDATE(), CURTIME(), '$adminID')";
                mysqli_query($conn, $logQuery);

                echo "Cashier $cashierName";
            }
        }
    }

    if ($action == 'delete_perm') 
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') 
        {
            $adminID = mysqli_real_escape_string($conn, $_POST['adminID']);
            $id = mysqli_real_escape_string($conn, $_POST['id']);
            $table = mysqli_real_escape_string($conn, $_POST['table']);

            $idColumn = [
                'project' => 'project_id',
                'supplier' => 'supplier_id',
                'inventory' => 'item_id'
            ][$table];
            $nameColumn = [
                'project' => 'project_name',
                'supplier' => 'company_name',
                'inventory' => 'item_name'
            ][$table];
        
            $nameQuery = "SELECT $nameColumn as name FROM $table WHERE $idColumn = $id";
            $nameResult = mysqli_query($conn, $nameQuery);

            if ($nameResult && mysqli_num_rows($nameResult) > 0) 
            {
                $nameRow = mysqli_fetch_assoc($nameResult);
                $name = $nameRow['name'];
        
                $deleteQuery = "DELETE FROM $table WHERE $idColumn = $id";
                
                if (mysqli_query($conn, $deleteQuery)) 
                {
                    $logDescription = "Deleted $name from $table";
                    $logQuery = "INSERT INTO log (description, date, time, user_id) VALUES ('$logDescription', CURDATE(), CURTIME(), '$adminID')";
                    mysqli_query($conn, $logQuery);
        
                    echo "Deleted $name";
                }
            }
        }
    }

    if ($action == 'excess') 
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {
            $excId = mysqli_real_escape_string($conn, $_POST['id']);
            $adminID = mysqli_real_escape_string($conn, $_POST['adminID']);

            // Fetch the item details before deletion
            $fetchItemQuery = "SELECT item_name FROM inventory_excess WHERE exc_id = '$excId'";
            $fetchItemResult = mysqli_query($conn, $fetchItemQuery);
            $item = mysqli_fetch_assoc($fetchItemResult);
            $itemName = $item['item_name'];

            // Proceed to delete the item
            $deleteQuery = "DELETE FROM inventory_excess WHERE exc_id = '$excId'";

            if (mysqli_query($conn, $deleteQuery)) 
            {
                // Log the deletion action
                $logDescription = "Dispose excess item '$itemName'";
                $logDescription = mysqli_real_escape_string($conn, $logDescription);
                $logQuery = "INSERT INTO log (description, date, time, user_id) VALUES ('$logDescription', CURDATE(), CURTIME(), '$adminID')";
                mysqli_query($conn, $logQuery);

                // Return a success message
                echo "Dispose $itemName";
            }
        }
    }
?>