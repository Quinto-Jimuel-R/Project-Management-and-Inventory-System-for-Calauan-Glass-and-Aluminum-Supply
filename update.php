<?php

    include 'database.php';

    $action = isset($_GET['action']) ? $_GET['action'] : '';

    use Infobip\Configuration;
    use Infobip\Api\SmsApi;
    use Infobip\Model\SmsDestination;
    use Infobip\Model\SmsTextualMessage;
    use Infobip\Model\SmsAdvancedTextualRequest;

    require 'vendor/autoload.php';
    require './database.php';

    $apiURL = "https://d9k2pv.api.infobip.com";
    $apiKEY = "7b2ad7ce3817c971ad689f60e32de8c6-ed73c164-f2b0-4b1d-9b15-167f5f0f56cb";

    if ($action == 'project') 
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") 
        {
            // Get the data from the POST request
            $projectId = $_POST['updateProjectId'];
            $customerId = $_POST['updateCustomerId'];
            $employeeId = $_POST['updateEmployeeId'];

            $projectName = $_POST['updateProjectName'];
            $location = $_POST['updateLocation'];
            $employee = $_POST['updateEmployee'];
            $startDate = $_POST['updateStartDate'];
            $deadline = $_POST['updateDeadline'];
            $customerName = $_POST['updateCustomerName'];
            $customerAddress = $_POST['updateCustomerAddress'];
            $customerPN = $_POST['updateCustomerPN'];

            $query = "UPDATE project
                    SET project_name = '$projectName', 
                        location = '$location', 
                        start_date = '$startDate', 
                        end_date = '$deadline',
                        employee_id = '$employee'
                    WHERE project_id = $projectId";

            $result = mysqli_query($conn, $query);

            if ($result) {
                $queryCustomer = "UPDATE customer
                                SET customer_name = '$customerName', 
                                    address = '$customerAddress', 
                                    phone_number = '$customerPN'
                                WHERE customer_id = $customerId";

                $resultCustomer = mysqli_query($conn, $queryCustomer);

                if ($resultCustomer) {
                    echo "Project and customer details updated successfully!";
                } else {
                    echo "Error updating customer details: " . mysqli_error($conn);
                }
            } else {
                echo "Error updating project: " . mysqli_error($conn);
            }
        }
    }

    if($action == 'inventory')
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') 
        {
            $adminID = mysqli_real_escape_string($conn, $_POST['adminID']);

            $itemId = mysqli_real_escape_string($conn, $_POST['updateItemId']);
            $supplierId = mysqli_real_escape_string($conn, $_POST['updateSupplierId']);

            $itemName = mysqli_real_escape_string($conn, $_POST['updateItemName']);
            $supplier = mysqli_real_escape_string($conn, $_POST['updateSupplier']);
            $dimension = mysqli_real_escape_string($conn, $_POST['updateDimension']);
            $inch = mysqli_real_escape_string($conn, $_POST['updateInch']);
            $sqft = mysqli_real_escape_string($conn, $_POST['updateSqft']);
            $color = mysqli_real_escape_string($conn, $_POST['updateColor']);
            $price = mysqli_real_escape_string($conn, $_POST['updatePrice']);
            $stock = mysqli_real_escape_string($conn, $_POST['updateStock']);
            $category = mysqli_real_escape_string($conn, $_POST['updateCategory']);

            $currentValuesQuery = "SELECT inventory.*, supplier.company_name AS current_supplier_name 
                                FROM inventory 
                                INNER JOIN supplier ON inventory.supplier_id = supplier.supplier_id 
                                WHERE item_id = '$itemId'";
            $currentValuesResult = mysqli_query($conn, $currentValuesQuery);
            $currentValues = mysqli_fetch_assoc($currentValuesResult);

            $newSupplierQuery = "SELECT company_name AS new_supplier_name FROM supplier WHERE supplier_id = '$supplier'";
            $newSupplierResult = mysqli_query($conn, $newSupplierQuery);
            $newSupplier = mysqli_fetch_assoc($newSupplierResult);

            $itemNAME = $currentValues['item_name'];

            $updateQuery = "UPDATE inventory SET 
                            item_name = '$itemName',
                            dimension = '$dimension',
                            foot = '$inch',
                            sqft = '$sqft',
                            color = '$color',
                            price = '$price',
                            stock = '$stock',
                            category = '$category',
                            supplier_id = '$supplier'
                            WHERE item_id = '$itemId' ";

            if (mysqli_query($conn, $updateQuery)) 
            {
                if ($currentValues['item_name'] != $itemName) 
                {
                    $logDescription = "Item '{$currentValues['item_name']}' updated the name to '$itemName'";
                    $logDescription = mysqli_real_escape_string($conn, $logDescription);
                    $logQuery = "INSERT INTO log (description, date, time, user_id) VALUES ('$logDescription', CURDATE(), CURTIME(), '$adminID')";
                    mysqli_query($conn, $logQuery);
                }
                if ($currentValues['dimension'] != $dimension) 
                {
                    $logDescription = "Item '$itemName' updated the dimension from '{$currentValues['dimension']}' to '$dimension'";
                    $logDescription = mysqli_real_escape_string($conn, $logDescription);
                    $logQuery = "INSERT INTO log (description, date, time, user_id) VALUES ('$logDescription', CURDATE(), CURTIME(), '$adminID')";
                    mysqli_query($conn, $logQuery);
                }
                if ($currentValues['foot'] != $inch) 
                {
                    $logDescription = "Item '$itemName' updated the foot from '{$currentValues['foot']}' to '$inch'";
                    $logDescription = mysqli_real_escape_string($conn, $logDescription);
                    $logQuery = "INSERT INTO log (description, date, time, user_id) VALUES ('$logDescription', CURDATE(), CURTIME(), '$adminID')";
                    mysqli_query($conn, $logQuery);
                }
                if ($currentValues['sqft'] != $sqft) 
                {
                    $logDescription = "Item '$itemName' updated the sqft from '{$currentValues['sqft']}' to '$sqft'";
                    $logDescription = mysqli_real_escape_string($conn, $logDescription);
                    $logQuery = "INSERT INTO log (description, date, time, user_id) VALUES ('$logDescription', CURDATE(), CURTIME(), '$adminID')";
                    mysqli_query($conn, $logQuery);
                }
                if ($currentValues['color'] != $color) 
                {
                    $logDescription = "Item '$itemName' updated the color from '{$currentValues['color']}' to '$color'";
                    $logDescription = mysqli_real_escape_string($conn, $logDescription);
                    $logQuery = "INSERT INTO log (description, date, time, user_id) VALUES ('$logDescription', CURDATE(), CURTIME(), '$adminID')";
                    mysqli_query($conn, $logQuery);
                }
                if ($currentValues['price'] != $price) 
                {
                    $logDescription = "Item '$itemName' updated the price from '{$currentValues['price']}' to '$price'";
                    $logDescription = mysqli_real_escape_string($conn, $logDescription);
                    $logQuery = "INSERT INTO log (description, date, time, user_id) VALUES ('$logDescription', CURDATE(), CURTIME(), '$adminID')";
                    mysqli_query($conn, $logQuery);
                }
                if ($currentValues['stock'] != $stock) 
                {
                    $logDescription = "Item '$itemName' updated the stock from '{$currentValues['stock']}' to '$stock'";
                    $logDescription = mysqli_real_escape_string($conn, $logDescription);
                    $logQuery = "INSERT INTO log (description, date, time, user_id) VALUES ('$logDescription', CURDATE(), CURTIME(), '$adminID')";
                    mysqli_query($conn, $logQuery);
                }
                if ($currentValues['category'] != $category) 
                {
                    $logDescription = "Item '$itemName' updated the category from '{$currentValues['category']}' to '$category'";
                    $logDescription = mysqli_real_escape_string($conn, $logDescription);
                    $logQuery = "INSERT INTO log (description, date, time, user_id) VALUES ('$logDescription', CURDATE(), CURTIME(), '$adminID')";
                    mysqli_query($conn, $logQuery);
                }
                if ($currentValues['supplier_id'] != $supplier) 
                {
                    $logDescription = "Item '$itemName' updated the supplier from '{$currentValues['current_supplier_name']}' to '{$newSupplier['new_supplier_name']}'";
                    $logDescription = mysqli_real_escape_string($conn, $logDescription);
                    $logQuery = "INSERT INTO log (description, date, time, user_id) VALUES ('$logDescription', CURDATE(), CURTIME(), '$adminID')";
                    mysqli_query($conn, $logQuery);
                }
                echo "Item $itemNAME Updated";
            } 
        }
    }

    if($action == 'addStock')
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {
            $adminID = mysqli_real_escape_string($conn, $_POST['adminID']);
            $itemId = mysqli_real_escape_string($conn, $_POST['itemId']);
            $additionalStock = mysqli_real_escape_string($conn, $_POST['addStockLevel']);
        
            $currentStockQuery = "SELECT item_name, stock FROM inventory WHERE item_id = '$itemId'";
            $currentStockResult = mysqli_query($conn, $currentStockQuery);
            $currentStockData = mysqli_fetch_assoc($currentStockResult);
        
            if ($currentStockData) 
            {
                $itemName = $currentStockData['item_name'];
                $currentStock = $currentStockData['stock'];
                $newStock = $currentStock + $additionalStock;
        
                $updateStockQuery = "UPDATE inventory SET stock = '$newStock' WHERE item_id = '$itemId'";

                if (mysqli_query($conn, $updateStockQuery))
                {
                    $description = "Item '$itemName' added new '$newStock' stock";
                    $description = mysqli_real_escape_string($conn, $description);

                    $insertLogQuery = "INSERT INTO log (description, date, time, user_id) VALUES ('$description', CURDATE(), CURTIME(), '$adminID')";
                    
                    mysqli_query($conn, $insertLogQuery);
        
                    echo "Item $itemName added new $newStock stocks";
                }
            }
        }
    }

    if ($action == 'customer')
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') 
        {
            $adminID = mysqli_real_escape_string($conn, $_POST['adminID']);
            $customerId = mysqli_real_escape_string($conn, $_POST['updateId']);
            $name = mysqli_real_escape_string($conn, $_POST['updateName']);
            $address = mysqli_real_escape_string($conn, $_POST['updateAddress']);
            $email = mysqli_real_escape_string($conn, $_POST['updateEmail']);
            $phone = mysqli_real_escape_string($conn, $_POST['updatePN']);
    
            $currentValuesQuery = "SELECT name, address, email, phone_number FROM user WHERE user_id = '$customerId'";
            $currentValuesResult = mysqli_query($conn, $currentValuesQuery);
            $currentValues = mysqli_fetch_assoc($currentValuesResult);
    
            $customerName = $currentValues['name'];

            $updateQuery = "UPDATE user SET 
                        name = '$name', 
                        address = '$address', 
                        email = '$email', 
                        phone_number = '$phone' 
                        WHERE user_id = '$customerId'";
    
            if (mysqli_query($conn, $updateQuery)) 
            {
                if ($currentValues['name'] != $name) 
                {
                    $logDescription = "Customer '{$currentValues['name']}' updated the name to '$name'";
                    $logDescription = mysqli_real_escape_string($conn, $logDescription);
                    $logQuery = "INSERT INTO log (description, date, time, user_id) VALUES ('$logDescription', CURDATE(), CURTIME(), '$adminID')";
                    mysqli_query($conn, $logQuery);
                }
                if ($currentValues['address'] != $address) 
                {
                    $logDescription = "Customer '{$currentValues['name']}' updated the address to '$address'";
                    $logDescription = mysqli_real_escape_string($conn, $logDescription);
                    $logQuery = "INSERT INTO log (description, date, time, user_id) VALUES ('$logDescription', CURDATE(), CURTIME(), '$adminID')";
                    mysqli_query($conn, $logQuery);
                }
                if ($currentValues['email'] != $email) 
                {
                    $logDescription = "Customer '{$currentValues['name']}' updated the email to '$email'";
                    $logDescription = mysqli_real_escape_string($conn, $logDescription);
                    $logQuery = "INSERT INTO log (description, date, time, user_id) VALUES ('$logDescription', CURDATE(), CURTIME(), '$adminID')";
                    mysqli_query($conn, $logQuery);
                }
                if ($currentValues['phone_number'] != $phone) 
                {
                    $logDescription = "Customer '{$currentValues['name']}' updated the phone number to '$phone'";
                    $logDescription = mysqli_real_escape_string($conn, $logDescription);
                    $logQuery = "INSERT INTO log (description, date, time, user_id) VALUES ('$logDescription', CURDATE(), CURTIME(), '$adminID')";
                    mysqli_query($conn, $logQuery);
                }
                echo "Customer $customerName Updated";
            }
        }
    }    

    if ($action == 'supplier') 
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $adminID = mysqli_real_escape_string($conn, $_POST['adminID']);
            $supplierId = mysqli_real_escape_string($conn, $_POST['updateSupplierId']);
    
            $companyName = mysqli_real_escape_string($conn, $_POST['updateCompanyName']);
            $contactPerson = mysqli_real_escape_string($conn, $_POST['updateContactPerson']);
            $address = mysqli_real_escape_string($conn, $_POST['updateAddress']);
            $supplierPN = mysqli_real_escape_string($conn, $_POST['updateSupplierPN']);
    
            $currentValuesQuery = "SELECT company_name, contact_person, address, phone_number FROM supplier WHERE supplier_id = '$supplierId'";
            $currentValuesResult = mysqli_query($conn, $currentValuesQuery);
            $currentValues = mysqli_fetch_assoc($currentValuesResult);
    
            $companyNAME = $currentValues['company_name'];

            $updateQuery = "UPDATE supplier SET 
                            company_name = '$companyName',
                            contact_person = '$contactPerson',
                            address = '$address',
                            phone_number = '$supplierPN'
                            WHERE supplier_id = '$supplierId'";
    
            if (mysqli_query($conn, $updateQuery)) 
            {
                if ($currentValues) 
                {
                    if ($currentValues['company_name'] != $companyName)
                     {
                        $logDescription = "Supplier '{$currentValues['company_name']}' updated the company name to '$companyName'";
                        $logDescription = mysqli_real_escape_string($conn, $logDescription);
                        $logQuery = "INSERT INTO log (description, date, time, user_id) VALUES ('$logDescription', CURDATE(), CURTIME(), '$adminID')";
                        mysqli_query($conn, $logQuery);
                    }
                    if ($currentValues['contact_person'] != $contactPerson) 
                    {
                        $logDescription = "Supplier '{$currentValues['company_name']}' updated contact person from '{$currentValues['contact_person']}' to '$contactPerson'";
                        $logDescription = mysqli_real_escape_string($conn, $logDescription);
                        $logQuery = "INSERT INTO log (description, date, time, user_id) VALUES ('$logDescription', CURDATE(), CURTIME(), '$adminID')";
                        mysqli_query($conn, $logQuery);
                    }
                    if ($currentValues['address'] != $address) 
                    {
                        $logDescription = "Supplier '{$currentValues['company_name']}' updated address from '{$currentValues['address']}' to '$address'";
                        $logDescription = mysqli_real_escape_string($conn, $logDescription);
                        $logQuery = "INSERT INTO log (description, date, time, user_id) VALUES ('$logDescription', CURDATE(), CURTIME(), '$adminID')";
                        mysqli_query($conn, $logQuery);
                    }
                    if ($currentValues['phone_number'] != $supplierPN) 
                    {
                        $logDescription = "Supplier '{$currentValues['company_name']}' updated phone number from '{$currentValues['phone_number']}' to '$supplierPN'";
                        $logDescription = mysqli_real_escape_string($conn, $logDescription);
                        $logQuery = "INSERT INTO log (description, date, time, user_id) VALUES ('$logDescription', CURDATE(), CURTIME(), '$adminID')";
                        mysqli_query($conn, $logQuery);
                    }
                }
    
                echo "Supplier $companyNAME Updated";
            }
        }
    }
    
    if ($action == 'employee') 
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') 
        {
            $adminID = mysqli_real_escape_string($conn, $_POST['adminID']); 
            $employeeId = mysqli_real_escape_string($conn, $_POST['updateEmployeeId']);
            $name = mysqli_real_escape_string($conn, $_POST['updateEmployeeName']);
            $address = mysqli_real_escape_string($conn, $_POST['updateAddress']);
            $phone = mysqli_real_escape_string($conn, $_POST['updateEmployeePN']);
            $email = mysqli_real_escape_string($conn, $_POST['updateEmail']);
    
            $currentValuesQuery = "SELECT name, address, phone_number, email FROM user WHERE user_id='$employeeId'";
            $currentValuesResult = mysqli_query($conn, $currentValuesQuery);
            $currentValues = mysqli_fetch_assoc($currentValuesResult);

            $employeeNAME = $currentValues['name'];
    
            $updateQuery = "UPDATE user SET name='$name', address='$address', phone_number='$phone', email='$email' WHERE user_id='$employeeId'";
    
            if (mysqli_query($conn, $updateQuery))
            {
                if ($currentValues['name'] != $name) 
                {
                    $logDescription = "Employee '{$currentValues['name']}' updated the name to '$name'";
                    $logDescription = mysqli_real_escape_string($conn, $logDescription);
                    $logQuery = "INSERT INTO log (description, date, time, user_id) VALUES ('$logDescription', CURDATE(), CURTIME(), '$adminID')";
                    mysqli_query($conn, $logQuery);
                }
                if ($currentValues['address'] != $address) 
                {
                    $logDescription = "Employee '{$currentValues['name']}' updated address from '{$currentValues['address']}' to '$address'";
                    $logDescription = mysqli_real_escape_string($conn, $logDescription);
                    $logQuery = "INSERT INTO log (description, date, time, user_id) VALUES ('$logDescription', CURDATE(), CURTIME(), '$adminID')";
                    mysqli_query($conn, $logQuery);
                }
                if ($currentValues['phone_number'] != $phone) 
                {
                    $logDescription = "Employee '{$currentValues['name']}' updated phone number from '{$currentValues['phone_number']}' to '$phone'";
                    $logDescription = mysqli_real_escape_string($conn, $logDescription);
                    $logQuery = "INSERT INTO log (description, date, time, user_id) VALUES ('$logDescription', CURDATE(), CURTIME(), '$adminID')";
                    mysqli_query($conn, $logQuery);
                }
                if ($currentValues['email'] != $email) 
                {
                    $logDescription = "Employee '{$currentValues['name']}' updated email from '{$currentValues['email']}' to '$email'";
                    $logDescription = mysqli_real_escape_string($conn, $logDescription);
                    $logQuery = "INSERT INTO log (description, date, time, user_id) VALUES ('$logDescription', CURDATE(), CURTIME(), '$adminID')";
                    mysqli_query($conn, $logQuery);
                }
                echo "Employee $employeeNAME Updated";
            }
        }
    }

    if ($action == 'cashier') 
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') 
        {
            $adminID = mysqli_real_escape_string($conn, $_POST['adminID']); 
            $cashierId = mysqli_real_escape_string($conn, $_POST['updateCashierId']);
            $name = mysqli_real_escape_string($conn, $_POST['updateCashierName']);
            $address = mysqli_real_escape_string($conn, $_POST['updateAddress']);
            $phone = mysqli_real_escape_string($conn, $_POST['updateCashierPN']);
            $email = mysqli_real_escape_string($conn, $_POST['updateEmail']);

            $currentValuesQuery = "SELECT name, address, phone_number, email FROM user WHERE user_id='$cashierId'";
            $currentValuesResult = mysqli_query($conn, $currentValuesQuery);
            $currentValues = mysqli_fetch_assoc($currentValuesResult);

            $cashierNAME = $currentValues['name'];

            $updateQuery = "UPDATE user SET name='$name', address='$address', phone_number='$phone', email='$email' WHERE user_id='$cashierId'";

            if (mysqli_query($conn, $updateQuery))
            {
                if ($currentValues['name'] != $name) 
                {
                    $logDescription = "Cashier '{$currentValues['name']}' updated the name to '$name'";
                    $logDescription = mysqli_real_escape_string($conn, $logDescription);
                    $logQuery = "INSERT INTO log (description, date, time, user_id) VALUES ('$logDescription', CURDATE(), CURTIME(), '$adminID')";
                    mysqli_query($conn, $logQuery);
                }
                if ($currentValues['address'] != $address) 
                {
                    $logDescription = "Cashier '{$currentValues['name']}' updated address from '{$currentValues['address']}' to '$address'";
                    $logDescription = mysqli_real_escape_string($conn, $logDescription);
                    $logQuery = "INSERT INTO log (description, date, time, user_id) VALUES ('$logDescription', CURDATE(), CURTIME(), '$adminID')";
                    mysqli_query($conn, $logQuery);
                }
                if ($currentValues['phone_number'] != $phone) 
                {
                    $logDescription = "Cashier '{$currentValues['name']}' updated phone number from '{$currentValues['phone_number']}' to '$phone'";
                    $logDescription = mysqli_real_escape_string($conn, $logDescription);
                    $logQuery = "INSERT INTO log (description, date, time, user_id) VALUES ('$logDescription', CURDATE(), CURTIME(), '$adminID')";
                    mysqli_query($conn, $logQuery);
                }
                if ($currentValues['email'] != $email) 
                {
                    $logDescription = "Cashier '{$currentValues['name']}' updated email from '{$currentValues['email']}' to '$email'";
                    $logDescription = mysqli_real_escape_string($conn, $logDescription);
                    $logQuery = "INSERT INTO log (description, date, time, user_id) VALUES ('$logDescription', CURDATE(), CURTIME(), '$adminID')";
                    mysqli_query($conn, $logQuery);
                }
                echo "Cashier $cashierNAME Updated";
            }
        }
    }

    if ($action == 'updateProfiles')
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') 
        {
            $adminID = mysqli_real_escape_string($conn, $_POST['adminID']);
            $name = mysqli_real_escape_string($conn, $_POST['username']);
            $address = mysqli_real_escape_string($conn, $_POST['address']);
            $email = mysqli_real_escape_string($conn, $_POST['email']);
            $contact = mysqli_real_escape_string($conn, $_POST['contact']);
            $password = mysqli_real_escape_string($conn, $_POST['password']);

            $updateFields = [];

            $updateFields[] = "name = '$name'";
            $updateFields[] = "address = '$address'";
            $updateFields[] = "email = '$email'";
            $updateFields[] = "phone_number = '$contact'";

            if (!empty($password)) 
            {
                $hashed_password = md5($password);
                $updateFields[] = "password = '$hashed_password'";
            }

            if (!empty($_FILES['image']['name'])) {
                $file_name = $_FILES['image']['name'];
                $tempname = $_FILES['image']['tmp_name'];
                $folder = 'images/' . $file_name;

                if (move_uploaded_file($tempname, $folder)) {
                    $updateFields[] = "image = '$file_name'";
                }
            }

            if (!empty($updateFields)) 
            {
                $updateQuery = "UPDATE user SET " . implode(", ", $updateFields) . " WHERE user_id = $adminID";

                if (mysqli_query($conn, $updateQuery)) 
                {
                    $logDescription = "Profile Updated";
                    $logQuery = "INSERT INTO log (description, date, time, user_id) VALUES ('$logDescription', CURDATE(), CURTIME(), '$adminID')";
                    mysqli_query($conn, $logQuery);

                    echo "Profile Updated";
                }
            }
        }
    }

    if ($action == 'restore')
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
        
                $updateQuery = "UPDATE $table SET active = 1 WHERE $idColumn = $id";

                if (mysqli_query($conn, $updateQuery)) 
                {
                    $logDescription = "Restored $name from $table";
                    $logQuery = "INSERT INTO log (description, date, time, user_id) VALUES ('$logDescription', CURDATE(), CURTIME(), '$adminID')";
                    mysqli_query($conn, $logQuery);
        
                    echo "Restore $name";
                }
            }
        }
    }

    if ($action == 'paymentStatus') 
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') 
        {
            $projectID = mysqli_real_escape_string($conn, $_POST['project_id']);
    
            $updateQuery = "UPDATE payment SET payment_status = 'PAID' WHERE project_id = '$projectID'";
    
            if (mysqli_query($conn, $updateQuery)) 
            {
                echo "Payment Updated";
            }
        }
    }

    if ($action == 'downPayment') 
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') 
        {
            $projectID = mysqli_real_escape_string($conn, $_POST['project_id']);
            $downPayment = mysqli_real_escape_string($conn, $_POST['downPayment']);

            $updateQuery = "UPDATE payment SET down_payment = '$downPayment' WHERE project_id = '$projectID'";

            if (mysqli_query($conn, $updateQuery)) 
            {
                echo "Added $downPayment Down Payment";
            } 
        }
    }
    
    if ($action == 'updateEndDate') 
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') 
        {
            $projectId = isset($_POST['project_id']) ? intval($_POST['project_id']) : 0;
            $endDate = isset($_POST['end_date']) ? mysqli_real_escape_string($conn, $_POST['end_date']) : '';

            if ($projectId && $endDate) 
            {
                $query = "UPDATE project SET end_date = '$endDate' WHERE project_id = $projectId";

                if (mysqli_query($conn, $query)) 
                {
                    echo "Deadline Change";
                }
            }
        }
    }

    if($action == 'updateTaskStatus')
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') 
        {
            $task_id = $_POST['task_id'];
        
            $updateQuery = "UPDATE task SET status = 'Completed' WHERE task_id = '$task_id' ";

            if(mysqli_query($conn, $updateQuery))
            {
                echo "Status updated successfully.";
            }
        }
    }

    if ($action == 'updateProjectStatusEmployee') 
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') 
        {
            $projectID = $_POST['projectId'];
            $status = $_POST['status'];
    
            $updateQuery = "UPDATE project SET status = '$status' WHERE project_id = '$projectID'";
            $updateResult = mysqli_query($conn, $updateQuery);
    
            if ($updateResult) {
                echo 'Project status updated successfully';
            } else {
                echo 'Error updating project status';
            }
        } else {
            echo 'Invalid request method for updating project status';
        }
    }
    

    if($action == 'updateTaskStatusEmployee')
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') 
        {
            $taskId = $_POST['taskId'];
            $status = $_POST['status'];
    
            $updateQuery = "UPDATE task SET status = '$status' WHERE task_id = $taskId";
    
            if (mysqli_query($conn, $updateQuery)) {
                echo 'Task status updated successfully';
            } else {
                echo 'Error updating project status: ' . mysqli_error($conn);
            }
        } else {
            echo 'Invalid request method for updating project status';
        }
    }

    if($action == 'updateTaskToNS')
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $taskId = $_POST['taskId'];

            $updateQuery = "UPDATE task SET status = 'NOT STARTED' WHERE task_id = '$taskId'";

            if(mysqli_query($conn, $updateQuery))
            {
                echo "Task Update successfully";
            }
        }
    }

    if($action == 'updateTaskToOG')
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $taskId = $_POST['taskId'];

            $updateQuery = "UPDATE task SET status = 'ONGOING' WHERE task_id = '$taskId'";

            if(mysqli_query($conn, $updateQuery))
            {
                echo "Task Update successfully";
            }
        }
    }

    if($action == 'updateTaskToC')
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $taskId = $_POST['taskId'];

            $updateQuery = "UPDATE task SET status = 'COMPLETED' WHERE task_id = '$taskId'";

            if(mysqli_query($conn, $updateQuery))
            {
                echo "Task Update successfully";
            }
        }
    }

    if ($action == 'updateProjectStatus') 
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') 
        {
            $projectId = mysqli_real_escape_string($conn, $_POST['projectId']);
            $newStatus = mysqli_real_escape_string($conn, $_POST['newStatus']);
            $employeeId = mysqli_real_escape_string($conn, $_POST['employeeId']);
            $customerId = mysqli_real_escape_string($conn, $_POST['customerId']);

            $updateQuery = "UPDATE project SET status = '$newStatus' WHERE project_id = '$projectId'";

            if (mysqli_query($conn, $updateQuery)) 
            {
                $insertHistoryQuery = "INSERT INTO project_history (project_id, status, date_updated, time_updated, day_updated)
                                    VALUES ('$projectId', '$newStatus', CURDATE(), CURTIME(), DAYNAME(CURDATE()))";

                if (mysqli_query($conn, $insertHistoryQuery)) 
                {
                    $messageContent = "";

                    if ($newStatus === 'DELIVERED') 
                    {
                        $paymentQuery = "SELECT payment_status, down_payment FROM payment WHERE project_id = '$projectId'";
                        $paymentResult = mysqli_query($conn, $paymentQuery);

                        $projectQuery = "SELECT end_date FROM project WHERE project_id = '$projectId'";
                        $projectResult = mysqli_query($conn, $projectQuery);
                        $projectRow = mysqli_fetch_assoc($projectResult);
                        $endDate = $projectRow['end_date'];
                        $formattedEndDate = date('F j, Y', strtotime($endDate));

                        if ($paymentResult && mysqli_num_rows($paymentResult) > 0) 
                        {
                            $paymentRow = mysqli_fetch_assoc($paymentResult);
                            $paymentStatus = $paymentRow['payment_status'];
                            $downpayment = isset($paymentRow['down_payment']) ? $paymentRow['down_payment'] : 0;

                            if ($paymentStatus === 'PAID') 
                            {
                                $messageContent = "Dear customer your project is currently in $newStatus. Expect the installation today.";
                            } 
                            elseif ($paymentStatus === 'NOT PAID') 
                            {
                                $totalPriceQuery = "SELECT SUM(total_price) AS total_price FROM task WHERE project_id = '$projectId'";
                                $totalPriceResult = mysqli_query($conn, $totalPriceQuery);
                                $totalPriceRow = mysqli_fetch_assoc($totalPriceResult);
                                $totalPrice = $totalPriceRow['total_price'];

                                $remainingAmount = $totalPrice - $downpayment;

                                $messageContent = "Dear customer your project is currently in $newStatus. Expect the installation today and please prepare the exact amount of your remaining balance.";
                            }
                        }
                    }
                    else if ($newStatus === 'COMPLETED')
                    {
                        $messageContent = "Dear customer your project is $newStatus. Thank you for trusting Calauan Glass and Aluminum Supply and Services. God Bless! 😇";
                    }
                    else 
                    {
                        $messageContent = "Dear customer your project is currently in $newStatus.";
                    }

                    $insertMessage = "INSERT INTO message (message, `from`, `to`, project_id, sent_date, is_read) 
                                    VALUES ('$messageContent', '$employeeId', '$customerId', '$projectId', NOW(), 0)";

                    if (mysqli_query($conn, $insertMessage)) 
                    {
                        $customerPhoneQuery = "SELECT phone_number FROM user WHERE user_id = '$customerId'";
                        $customerPhoneResult = mysqli_query($conn, $customerPhoneQuery);
                        $customerPhoneRow = mysqli_fetch_assoc($customerPhoneResult);
                        $customerPhone = '+63' . substr($customerPhoneRow['phone_number'], -10);

                        if ($customerPhone) 
                        {
                            $config = new Configuration(host: $apiURL, apiKey: $apiKEY);
                            $api = new SmsApi(config: $config);

                            $destination = new SmsDestination(to: $customerPhone);
                            $sms = new SmsTextualMessage(
                                destinations: [$destination],
                                text: $messageContent,
                                from: "CGAS"
                            );

                            $request = new SmsAdvancedTextualRequest(messages: [$sms]);

                            try 
                            {
                                $response = $api->sendSmsMessage($request);
                                echo "Status Updated";
                            } 
                            catch (Exception $e) 
                            {
                                echo json_encode(['success' => false, 'message' => 'Failed to send SMS: ' . $e->getMessage()]);
                            }
                        } 
                    } 
                } 
            } 
        }
    }
    
    if($action == 'isReadMessage') 
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') 
        {
            $customerID = isset($_POST['customer_id']) ? $_POST['customer_id'] : 0; // Capture employee_id
            $projectID = isset($_POST['project_id']) ? $_POST['project_id'] : 0; // Capture project_id
            $status = isset($_POST['status']) ? $_POST['status'] : 0;

            // Sanitize inputs
            $customerID = mysqli_real_escape_string($conn, $customerID); 
            $projectID = mysqli_real_escape_string($conn, $projectID);
            $status = mysqli_real_escape_string($conn, $status);

            if (!empty($customerID) && !empty($projectID) && in_array($status, [0, 1])) 
            {
                $query = "UPDATE message SET is_read = '$status' WHERE `to` = '$customerID' AND project_id = '$projectID'";

                if (mysqli_query($conn, $query)) 
                {
                } 
            }
        }
    }
    
    if($action == 'employees')
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') 
        {
            $task_id = $_POST['task_id'];
        
            $updateQuery = "UPDATE task SET status = 'Completed' WHERE task_id = '$task_id' ";

            if(mysqli_query($conn, $updateQuery))
            {
                echo "Status updated successfully";
            }
        }
    }
?>
