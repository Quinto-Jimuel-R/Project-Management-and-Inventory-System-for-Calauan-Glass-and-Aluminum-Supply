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
            $adminID = mysqli_real_escape_string($conn, $_POST['adminID']);
    
            $customerName = mysqli_real_escape_string($conn, $_POST['customerName']);
            $customerAddress = mysqli_real_escape_string($conn, $_POST['customerAddress']);
            $customerPN = mysqli_real_escape_string($conn, $_POST['customerPN']);
            $customerEmail = mysqli_real_escape_string($conn, $_POST['customerEmail']);
            $customerPassword = mysqli_real_escape_string($conn, $_POST['customerPassword']);
    
            $hashedPassword = md5($customerPassword);
    
            $insertCustomer = "INSERT INTO user (name, address, phone_number, email, password, user_type) VALUES ('$customerName', '$customerAddress', '$customerPN', '$customerEmail', '$hashedPassword', 'customer')";
    
            if (mysqli_query($conn, $insertCustomer)) 
            {
                $customerID = mysqli_insert_id($conn);
    
                $projectName = mysqli_real_escape_string($conn, $_POST['projectName']);
                $location = mysqli_real_escape_string($conn, $_POST['location']);
                $employeeName = mysqli_real_escape_string($conn, $_POST['employee']);
                $startDate = mysqli_real_escape_string($conn, $_POST['startDate']);
                $deadline = mysqli_real_escape_string($conn, $_POST['deadline']);
    
                $insertProject = "INSERT INTO project (project_name, location, start_date, end_date, status, customer_id, employee_name, active, date_created) VALUES ('$projectName', '$location', '$startDate', '$deadline', 'TO DO', '$customerID', '$employeeName', '1', NOW())";
    
                if (mysqli_query($conn, $insertProject)) 
                {
                    $projectID = mysqli_insert_id($conn);
    
                    $insertPayment = "INSERT INTO payment (project_id) VALUES ('$projectID')";
    
                    if (mysqli_query($conn, $insertPayment))
                    {
                        $description = mysqli_real_escape_string($conn, "Added new project '$projectName'");
    
                        $insertLog = "INSERT INTO log (description, date, time, user_id) VALUES ('$description', CURDATE(), CURTIME(), '$adminID')";
    
                        if (mysqli_query($conn, $insertLog)) 
                        {
                            echo "Project $projectName Created";
                        }
                    }
                }
            }
        }
    }    

    if ($action == 'projectHA') 
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") 
        {
            $adminID = mysqli_real_escape_string($conn, $_POST['adminID']);
    
            $customerID = mysqli_real_escape_string($conn, $_POST['customerHA']);
    
            $projectName = mysqli_real_escape_string($conn, $_POST['projectName']);
            $location = mysqli_real_escape_string($conn, $_POST['location']);
            $employeeName = mysqli_real_escape_string($conn, $_POST['employee']);
            $startDate = mysqli_real_escape_string($conn, $_POST['startDate']);
            $deadline = mysqli_real_escape_string($conn, $_POST['deadline']);
    
            $insertProject = "INSERT INTO project (project_name, location, start_date, end_date, status, customer_id, employee_name, active, date_created) VALUES ('$projectName', '$location', '$startDate', '$deadline', 'TO DO', '$customerID', '$employeeName', '1', NOW())";
            
            if (mysqli_query($conn, $insertProject)) 
            {
                $projectID = mysqli_insert_id($conn);
    
                $insertPayment = "INSERT INTO payment (project_id) VALUES ('$projectID')";
    
                if (mysqli_query($conn, $insertPayment)) 
                {
                    $description = mysqli_real_escape_string($conn, "Added new project '$projectName'");
    
                    $insertLog = "INSERT INTO log (description, date, time, user_id) VALUES ('$description', CURDATE(), CURTIME(), '$adminID')";
    
                    if (mysqli_query($conn, $insertLog)) 
                    {
                        echo "Project $projectName Created";
                    }
                }
            }
        }
    }    
    
    if ($action == 'inventory') 
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") 
        {
            $adminID = mysqli_real_escape_string($conn, $_POST['adminID']);
            $itemName = mysqli_real_escape_string($conn, $_POST['itemName']);
            $dimension = mysqli_real_escape_string($conn, $_POST['dimension']);
            $color = mysqli_real_escape_string($conn, $_POST['color']);
            $price = mysqli_real_escape_string($conn, $_POST['price']);
            $stock = mysqli_real_escape_string($conn, $_POST['stock']);
            $category = mysqli_real_escape_string($conn, $_POST['category']);
            $supplierId = mysqli_real_escape_string($conn, $_POST['supplier']);
            
            // Check which field to use (inch for Aluminum, sqft for Glass)
            $inch = isset($_POST['inch']) && $_POST['inch'] !== '' ? mysqli_real_escape_string($conn, $_POST['inch']) : 'NULL';
            $sqft = isset($_POST['sqft']) && $_POST['sqft'] !== '' ? mysqli_real_escape_string($conn, $_POST['sqft']) : 'NULL';
            
            if ($category == 'Aluminum') {
                $insertInventory = "INSERT INTO inventory (item_name, dimension, foot, sqft, color, price, stock, category, active, supplier_id) 
                                    VALUES ('$itemName', '$dimension', $inch, NULL, '$color', '$price', '$stock', '$category', '1', '$supplierId')";
            } else if ($category == 'Glass') {
                $insertInventory = "INSERT INTO inventory (item_name, dimension, foot, sqft, color, price, stock, category, active, supplier_id) 
                                    VALUES ('$itemName', '$dimension', NULL, $sqft, '$color', '$price', '$stock', '$category', '1', '$supplierId')";
            }
    
            if (mysqli_query($conn, $insertInventory)) 
            {
                $inventoryID = mysqli_insert_id($conn);
                
                $description = mysqli_real_escape_string($conn, "Added new item '$itemName'");
                $insertLog = "INSERT INTO log (description, date, time, user_id) 
                              VALUES ('$description', CURDATE(), CURTIME(), '$adminID')";
                
                if (mysqli_query($conn, $insertLog)) 
                {
                    echo "Item '$itemName' Created";
                }
            }
        }
    }

    if ($action == 'supplier') 
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") 
        {
            $adminID = mysqli_real_escape_string($conn, $_POST['adminID']);
            $companyName = mysqli_real_escape_string($conn, $_POST['companyName']);
            $contactPerson = mysqli_real_escape_string($conn, $_POST['contactPerson']);
            $address = mysqli_real_escape_string($conn, $_POST['address']);
            $supplierPN = mysqli_real_escape_string($conn, $_POST['supplierPN']);
    
            // Check if the supplier already exists
            $checkQuery = "SELECT * FROM supplier WHERE company_name='$companyName' AND contact_person='$contactPerson' AND address='$address'";
            $checkResult = mysqli_query($conn, $checkQuery);
    
            if (mysqli_num_rows($checkResult) > 0) 
            {
                echo "error:Supplier '$companyName' with contact person '$contactPerson' at address '$address' already exists";
            } 
            else 
            {
                $insertSupplier = "INSERT INTO supplier (company_name, contact_person, address, phone_number, active) 
                                   VALUES ('$companyName', '$contactPerson', '$address', '$supplierPN', '1')";
    
                if (mysqli_query($conn, $insertSupplier)) 
                {
                    $logDescription = "Added new supplier '$companyName'";
                    $logDescription = mysqli_real_escape_string($conn, $logDescription);
    
                    $logQuery = "INSERT INTO log (description, date, time, user_id) VALUES ('$logDescription', CURDATE(), CURTIME(), '$adminID')";
                    
                    mysqli_query($conn, $logQuery);
    
                    echo "success:Supplier '$companyName' Created";
                }
            }
        }
    }
    
    if ($action == 'employee') 
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST") 
        {
            $adminID = mysqli_real_escape_string($conn, $_POST['adminID']);
            $employeeName = mysqli_real_escape_string($conn, $_POST["employeeName"]);
            $address = mysqli_real_escape_string($conn, $_POST["address"]);
            $employeePN = mysqli_real_escape_string($conn, $_POST["employeePN"]);
            $email = mysqli_real_escape_string($conn, $_POST["email"]);
            $password = mysqli_real_escape_string($conn, $_POST["password"]);
    
            $hashedPassword = md5($password);
    
            $insertEmployee = "INSERT INTO user (name, address, phone_number, email, password, user_type) VALUES ('$employeeName', '$address', '$employeePN', '$email', '$hashedPassword', 'employee')";
    
            if (mysqli_query($conn, $insertEmployee)) 
            {
                $logDescription = "Added new employee '$employeeName'";
                $logDescription = mysqli_real_escape_string($conn, $logDescription);

                $logQuery = "INSERT INTO log (description, date, time, user_id) VALUES ('$logDescription', CURDATE(), CURTIME(), '$adminID')";

                mysqli_query($conn, $logQuery);
    
                echo "Employee $employeeName Created";
            }
        }
    }

    if ($action == 'cashier') 
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST") 
        {
            $adminID = mysqli_real_escape_string($conn, $_POST['adminID']);
            $cashierName = mysqli_real_escape_string($conn, $_POST["cashierName"]);
            $address = mysqli_real_escape_string($conn, $_POST["address"]);
            $cashierPN = mysqli_real_escape_string($conn, $_POST["cashierPN"]);
            $email = mysqli_real_escape_string($conn, $_POST["email"]);
            $password = mysqli_real_escape_string($conn, $_POST["password"]);

            $hashedPassword = md5($password);

            $insertCashier = "INSERT INTO user (name, address, phone_number, email, password, user_type) VALUES ('$cashierName', '$address', '$cashierPN', '$email', '$hashedPassword', 'cashier')";

            if (mysqli_query($conn, $insertCashier)) 
            {
                $logDescription = "Added new cashier '$cashierName'";
                $logDescription = mysqli_real_escape_string($conn, $logDescription);

                $logQuery = "INSERT INTO log (description, date, time, user_id) VALUES ('$logDescription', CURDATE(), CURTIME(), '$adminID')";

                mysqli_query($conn, $logQuery);

                echo "Cashier $cashierName Created";
            }
        }
    }

    if ($action == 'insertMessage') 
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST") 
        {
            $projectID = mysqli_real_escape_string($conn, $_POST['projectID']);
            $customerID = mysqli_real_escape_string($conn, $_POST['customerID']);
            $employeeID = mysqli_real_escape_string($conn, $_POST['employeeID']);
            $message = mysqli_real_escape_string($conn, $_POST['message']);
    
            $insertMessage = "INSERT INTO message (message, `from`, `to`, project_id, sent_date, is_read) 
                              VALUES ('$message', '$customerID', '$employeeID', '$projectID', NOW(), '0')";
    
            if (mysqli_query($conn, $insertMessage)) 
            {
            }
        }
    }    

    if ($action == 'task') 
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") 
        {
            // Check if POST variables are set and sanitize input data
            $projectID = mysqli_real_escape_string($conn, $_POST['projectID'] ?? '');
            $length = floatval(mysqli_real_escape_string($conn, $_POST['length'] ?? ''));
            $height = floatval(mysqli_real_escape_string($conn, $_POST['height'] ?? ''));
            $lFoot = floatval(mysqli_real_escape_string($conn, $_POST['lFoot'] ?? ''));
            $lFoot2 = floatval(mysqli_real_escape_string($conn, $_POST['lFootX2'] ?? ''));
            $hFoot = floatval(mysqli_real_escape_string($conn, $_POST['hFoot'] ?? ''));
            $hFoot2 = floatval(mysqli_real_escape_string($conn, $_POST['hFootX2'] ?? ''));

            $topHead = mysqli_real_escape_string($conn, $_POST['topHead'] ?? '');
            $thPrice = floatval(mysqli_real_escape_string($conn, $_POST['thPrice'] ?? ''));
            $thFootExcess = floatval(mysqli_real_escape_string($conn, $_POST['thFootExcess'] ?? ''));
            $thItemID = mysqli_real_escape_string($conn, $_POST['thItemID'] ?? '');
            $thEItemID = mysqli_real_escape_string($conn, $_POST['thEItemID'] ?? '');
            $thColor = mysqli_real_escape_string($conn, $_POST['thColor'] ?? '');
            $thDimension = mysqli_real_escape_string($conn, $_POST['thDimension'] ?? '');
            $thStock = intval(mysqli_real_escape_string($conn, $_POST['thStock'] ?? ''));

            $bottomSill = mysqli_real_escape_string($conn, $_POST['bottomSill'] ?? '');
            $bsPrice = floatval(mysqli_real_escape_string($conn, $_POST['bsPrice'] ?? ''));
            $bsFootExcess = floatval(mysqli_real_escape_string($conn, $_POST['bsFootExcess'] ?? ''));
            $bsItemID = mysqli_real_escape_string($conn, $_POST['bsItemID'] ?? '');
            $bsEItemID = mysqli_real_escape_string($conn, $_POST['bsEItemID'] ?? '');
            $bsColor = mysqli_real_escape_string($conn, $_POST['bsColor'] ?? '');
            $bsDimension = mysqli_real_escape_string($conn, $_POST['bsDimension'] ?? '');
            $bsStock = intval(mysqli_real_escape_string($conn, $_POST['bsStock'] ?? ''));

            // Sanitize RAIL
            $rail = mysqli_real_escape_string($conn, $_POST['rail'] ?? '');
            $rPrice = floatval(mysqli_real_escape_string($conn, $_POST['rPrice'] ?? ''));
            $rFootExcess = floatval(mysqli_real_escape_string($conn, $_POST['rFootExcess'] ?? ''));
            $rFoot = floatval(mysqli_real_escape_string($conn, $_POST['rFoot'] ?? ''));
            $rItemID = mysqli_real_escape_string($conn, $_POST['rItemID'] ?? '');
            $rEItemID = mysqli_real_escape_string($conn, $_POST['rEItemID'] ?? '');
            $rUEI = mysqli_real_escape_string($conn, $_POST['rUEI'] ?? '');
            $rUNI = mysqli_real_escape_string($conn, $_POST['rUNI'] ?? '');
            $rFEID = mysqli_real_escape_string($conn, $_POST['rFEID'] ?? '');
            $rFEID1 = mysqli_real_escape_string($conn, $_POST['rFEID1'] ?? '');
            $rFH = mysqli_real_escape_string($conn, $_POST['rFH'] ?? '');
            $rSH = mysqli_real_escape_string($conn, $_POST['rSH'] ?? '');
            $rColor = mysqli_real_escape_string($conn, $_POST['rColor'] ?? '');
            $rDimension = mysqli_real_escape_string($conn, $_POST['rDimension'] ?? '');
            $rStock = intval(mysqli_real_escape_string($conn, $_POST['rStock'] ?? ''));
            $rHFoot = intval(mysqli_real_escape_string($conn, $_POST['rHFoot'] ?? ''));

            // Sanitize SIDE JAMB
            $sideJamb = mysqli_real_escape_string($conn, $_POST['sideJamb'] ?? '');
            $sjPrice = floatval(mysqli_real_escape_string($conn, $_POST['sjPrice'] ?? ''));
            $sjFootExcess = floatval(mysqli_real_escape_string($conn, $_POST['sjFootExcess'] ?? ''));
            $sjFoot = floatval(mysqli_real_escape_string($conn, $_POST['sjFoot'] ?? ''));
            $sjItemID = mysqli_real_escape_string($conn, $_POST['sjItemID'] ?? '');
            $sjEItemID = mysqli_real_escape_string($conn, $_POST['sjEItemID'] ?? '');
            $sjUEI = mysqli_real_escape_string($conn, $_POST['sjUEI'] ?? '');
            $sjUNI = mysqli_real_escape_string($conn, $_POST['sjUNI'] ?? '');
            $sjFEID = mysqli_real_escape_string($conn, $_POST['sjFEID'] ?? '');
            $sjFEID1 = mysqli_real_escape_string($conn, $_POST['sjFEID1'] ?? '');
            $sjFH = mysqli_real_escape_string($conn, $_POST['sjFH'] ?? '');
            $sjSH = mysqli_real_escape_string($conn, $_POST['sjSH'] ?? '');
            $sjColor = mysqli_real_escape_string($conn, $_POST['sjColor'] ?? '');
            $sjDimension = mysqli_real_escape_string($conn, $_POST['sjDimension'] ?? '');
            $sjStock = intval(mysqli_real_escape_string($conn, $_POST['sjStock'] ?? ''));
            $sjHFoot = intval(mysqli_real_escape_string($conn, $_POST['sjHFoot'] ?? ''));

            // Sanitize LOCKSTILE
            $lockStile = mysqli_real_escape_string($conn, $_POST['lockStile'] ?? '');
            $lsPrice = floatval(mysqli_real_escape_string($conn, $_POST['lsPrice'] ?? ''));
            $lsFootExcess = floatval(mysqli_real_escape_string($conn, $_POST['lsFootExcess'] ?? ''));
            $lsFoot = floatval(mysqli_real_escape_string($conn, $_POST['lsFoot'] ?? ''));
            $lsItemID = mysqli_real_escape_string($conn, $_POST['lsItemID'] ?? '');
            $lsEItemID = mysqli_real_escape_string($conn, $_POST['lsEItemID'] ?? '');
            $lsUEI = mysqli_real_escape_string($conn, $_POST['lsUEI'] ?? '');
            $lsUNI = mysqli_real_escape_string($conn, $_POST['lsUNI'] ?? '');
            $lsFEID = mysqli_real_escape_string($conn, $_POST['lsFEID'] ?? '');
            $lsFEID1 = mysqli_real_escape_string($conn, $_POST['lsFEID1'] ?? '');
            $lsFH = mysqli_real_escape_string($conn, $_POST['lsFH'] ?? '');
            $lsSH = mysqli_real_escape_string($conn, $_POST['lsSH'] ?? '');
            $lsColor = mysqli_real_escape_string($conn, $_POST['lsColor'] ?? '');
            $lsDimension = mysqli_real_escape_string($conn, $_POST['lsDimension'] ?? '');
            $lsStock = intval(mysqli_real_escape_string($conn, $_POST['lsStock'] ?? ''));
            $lsHFoot = intval(mysqli_real_escape_string($conn, $_POST['lsHFoot'] ?? ''));

            // Sanitize INTERLOCKER
            $interlocker = mysqli_real_escape_string($conn, $_POST['interlocker'] ?? '');
            $ilPrice = floatval(mysqli_real_escape_string($conn, $_POST['ilPrice'] ?? ''));
            $ilFootExcess = floatval(mysqli_real_escape_string($conn, $_POST['ilFootExcess'] ?? ''));
            $ilFoot = floatval(mysqli_real_escape_string($conn, $_POST['ilFoot'] ?? ''));
            $ilItemID = mysqli_real_escape_string($conn, $_POST['ilItemID'] ?? '');
            $ilEItemID = mysqli_real_escape_string($conn, $_POST['ilEItemID'] ?? '');
            $ilUEI = mysqli_real_escape_string($conn, $_POST['ilUEI'] ?? '');
            $ilUNI = mysqli_real_escape_string($conn, $_POST['ilUNI'] ?? '');
            $ilFEID = mysqli_real_escape_string($conn, $_POST['ilFEID'] ?? '');
            $ilFEID1 = mysqli_real_escape_string($conn, $_POST['ilFEID1'] ?? '');
            $ilFH = mysqli_real_escape_string($conn, $_POST['ilFH'] ?? '');
            $ilSH = mysqli_real_escape_string($conn, $_POST['ilSH'] ?? '');
            $ilColor = mysqli_real_escape_string($conn, $_POST['ilColor'] ?? '');
            $ilDimension = mysqli_real_escape_string($conn, $_POST['ilDimension'] ?? '');
            $ilStock = intval(mysqli_real_escape_string($conn, $_POST['ilStock'] ?? ''));
            $ilHFoot = intval(mysqli_real_escape_string($conn, $_POST['ilHFoot'] ?? ''));

            // Sanitize GLASS
            $glass = mysqli_real_escape_string($conn, $_POST['glass'] ?? '');
            $gItemID = mysqli_real_escape_string($conn, $_POST['gItemID'] ?? '');
            $gPrice = floatval(mysqli_real_escape_string($conn, $_POST['gPrice'] ?? ''));
            $gStock = intval(mysqli_real_escape_string($conn, $_POST['gStock'] ?? ''));

            //length
            $thFoot = $thFootExcess - $lFoot;
            $bsFoot = $bsFootExcess - $lFoot;
            $rFoots = $rFootExcess - $lFoot2;
            $rFoots1 = $rFoot - $lFoot2;

            //height
            $sjFoots = $sjFootExcess - $hFoot2;
            $sjFoots1 = $sjFoot - $hFoot2;

            $lsFoots = $lsFootExcess - $hFoot2;
            $lsFoots1 = $lsFoot - $hFoot2;
            
            $ilFoots = $ilFootExcess - $hFoot2;
            $ilFoots1 = $ilFoot - $hFoot2;

            $items = "Head - $lFoot feet of $topHead, Sill - $lFoot feet of $bottomSill, Rails - $lFoot2 feet of $rail, Jamb - $hFoot2 feet of $sideJamb, Stiles - $hFoot2 feet of $lockStile, Interlocker - $hFoot2 feet of $interlocker";

            $size = $lFoot * $hFoot;
            $sum = $thPrice + $bsPrice + $rPrice + $sjPrice + $lsPrice + $ilPrice + $gPrice;
            $totalPrice = $size * $sum;

            $query = "INSERT INTO task (description, length, height, status, items, total_price, project_id) VALUES ('Two Panel Slider', $length, $height, 'NOT STARTED', '$items', $totalPrice, $projectID)";

            if (mysqli_query($conn, $query)) 
            {
                if (!empty($gItemID))
                {
                    $newGStock = $gStock - 1;
                    $updateThInventoryQuery = "UPDATE inventory SET stock = $newGStock WHERE item_name = '$glass'";
                    mysqli_query($conn, $updateThInventoryQuery);
                }

                if (!empty($thEItemID))
                {
                    $updateThExcessQuery = "UPDATE inventory_excess SET exc_foot = $thFoot WHERE exc_id = $thEItemID";
                    mysqli_query($conn, $updateThExcessQuery);
                        
                    if ($thFoot <= 0) 
                    {
                        $deleteThExcessQuery = "DELETE FROM inventory_excess WHERE exc_id = $thEItemID";
                        mysqli_query($conn, $deleteThExcessQuery);
                    }
                }
                elseif (!empty($thItemID)) 
                {
                    if (strpos($topHead, '|') !== false) {
                        $pipePos = strpos($topHead, '|');
                        $topHead = substr($topHead, 0, $pipePos);
                    }

                    if ($thFoot > 0) {
                        $insertThExcessQuery = "INSERT INTO inventory_excess (item_name, exc_foot, dimension, color) VALUES ('$topHead', $thFoot, '$thDimension', '$thColor')";
                        mysqli_query($conn, $insertThExcessQuery);
                    }

                    $newThStock = $thStock - 1;
                    $updateThInventoryQuery = "UPDATE inventory SET stock = $newThStock WHERE item_id = $thItemID";
                    mysqli_query($conn, $updateThInventoryQuery);

                    // Insert into inv_mon table
                    $insertItemQuery = "INSERT INTO inv_mon (item_id, date_created) VALUES ('$thItemID', NOW())";
                    mysqli_query($conn, $insertItemQuery);

                    // Check if the new stock is 5
                    if ($newThStock == 5) {
                        // Fetch the supplier's phone number
                        $getSupplierQuery = "SELECT s.phone_number FROM supplier s INNER JOIN inventory i ON s.supplier_id = i.supplier_id WHERE i.item_id = $thItemID LIMIT 1";
                        $supplierResult = mysqli_query($conn, $getSupplierQuery);

                        if (mysqli_num_rows($supplierResult) > 0) {
                            $supplier = mysqli_fetch_assoc($supplierResult);
                            $supplierPhone = '+63' . substr($supplier['phone_number'], -10);  // Format for international phone number

                            // Prepare the message to send
                            $message = "Good day this is Calauan Glass and Aluminum Supply and Services. I need 20 pieces of $topHead in dimension $thDimension and color $thColor. Thank you and God Bless!";

                            // Initialize SMS API configuration and send message
                            $config = new Configuration(host: $apiURL, apiKey: $apiKEY);
                            $api = new SmsApi(config: $config);

                            $destination = new SmsDestination(to: $supplierPhone);
                            $sms = new SmsTextualMessage(
                                destinations: [$destination],
                                text: $message,
                                from: "CGAS"
                            );

                            $request = new SmsAdvancedTextualRequest(messages: [$sms]);

                            try {
                                $response = $api->sendSmsMessage($request);
                            } catch (Exception $e) {
                                echo json_encode(['success' => false, 'message' => 'Failed to send SMS: ' . $e->getMessage()]);
                            }
                        }
                    }
                }

                if (!empty($bsEItemID))
                {
                    $updateBsExcessQuery = "UPDATE inventory_excess SET exc_foot = $bsFoot WHERE exc_id = $bsEItemID";
                    mysqli_query($conn, $updateBsExcessQuery);
                        
                    if ($bsFoot <= 0) 
                    {
                        $deleteBsExcessQuery = "DELETE FROM inventory_excess WHERE exc_id = $bsEItemID";
                        mysqli_query($conn, $deleteBsExcessQuery);
                    }
                }
                elseif (!empty($bsItemID))
                {
                    if (strpos($bottomSill, '|') !== false) {
                        $pipePos = strpos($bottomSill, '|');
                        $bottomSill = substr($bottomSill, 0, $pipePos);
                    }

                    if ($bsFoot > 0) 
                    {
                        $insertBsExcessQuery = "INSERT INTO inventory_excess (item_name, exc_foot, dimension, color) VALUES ('$bottomSill', $bsFoot, '$bsDimension', '$bsColor')";
                        mysqli_query($conn, $insertBsExcessQuery);
                    }

                    $newBsStock = $bsStock - 1;
                    $updateBsInventoryQuery = "UPDATE inventory SET stock = $newBsStock WHERE item_id = $bsItemID";
                    mysqli_query($conn, $updateBsInventoryQuery);

                    // Insert into inv_mon table
                    $insertItemQuery = "INSERT INTO inv_mon (item_id, date_created) VALUES ('$bsItemID', NOW())";
                    mysqli_query($conn, $insertItemQuery);

                    if ($newBsStock == 5) {
                        // Fetch the supplier's phone number
                        $getSupplierQuery = "SELECT s.phone_number FROM supplier s INNER JOIN inventory i ON s.supplier_id = i.supplier_id WHERE i.item_id = $bsItemID LIMIT 1";
                        $supplierResult = mysqli_query($conn, $getSupplierQuery);

                        if (mysqli_num_rows($supplierResult) > 0) {
                            $supplier = mysqli_fetch_assoc($supplierResult);
                            $supplierPhone = '+63' . substr($supplier['phone_number'], -10);  // Format for international phone number

                            // Prepare the message to send
                            $message = "Good day this is Calauan Glass and Aluminum Supply and Services. I need 20 pieces of $bottomSill in dimension $bsDimension and color $bsColor. Thank you and God Bless!";

                            // Initialize SMS API configuration and send message
                            $config = new Configuration(host: $apiURL, apiKey: $apiKEY);
                            $api = new SmsApi(config: $config);

                            $destination = new SmsDestination(to: $supplierPhone);
                            $sms = new SmsTextualMessage(
                                destinations: [$destination],
                                text: $message,
                                from: "CGAS"
                            );

                            $request = new SmsAdvancedTextualRequest(messages: [$sms]);

                            try 
                            {
                                $response = $api->sendSmsMessage($request);
                            } 
                            catch (Exception $e) 
                            {
                                echo json_encode(['success' => false, 'message' => 'Failed to send SMS: ' . $e->getMessage()]);
                            }
                        }
                    }
                }

                if (!empty($rEItemID)) 
                {
                    // Handle excess item
                    $updateRExcessQuery = "UPDATE inventory_excess SET exc_foot = $rFoots WHERE exc_id = $rEItemID";
                    mysqli_query($conn, $updateRExcessQuery);
                
                    if ($rFoots <= 0) {
                        $deleteRExcessQuery = "DELETE FROM inventory_excess WHERE exc_id = $rEItemID";
                        mysqli_query($conn, $deleteRExcessQuery);
                    }
                } 
                elseif (!empty($rItemID)) 
                {
                    if (strpos($rail, '|') !== false) {
                        $pipePos = strpos($rail, '|');
                        $rail = substr($rail, 0, $pipePos);
                    }

                    if ($rFoots1 > 0) 
                    {
                        $insertRExcessQuery = "INSERT INTO inventory_excess (item_name, exc_foot, dimension, color) VALUES ('$rail', $rFoots1, '$rDimension', '$rColor')";
                        mysqli_query($conn, $insertRExcessQuery);
                    }
                
                    $newRStock = $rStock - 1;
                    $updateRInventoryQuery = "UPDATE inventory SET stock = $newRStock WHERE item_id = $rItemID";
                    mysqli_query($conn, $updateRInventoryQuery);

                    // Insert into inv_mon table
                    $insertItemQuery = "INSERT INTO inv_mon (item_id, date_created) VALUES ('$rItemID', NOW())";
                    mysqli_query($conn, $insertItemQuery);

                    if ($newRStock == 5) {
                        // Fetch the supplier's phone number
                        $getSupplierQuery = "SELECT s.phone_number FROM supplier s INNER JOIN inventory i ON s.supplier_id = i.supplier_id WHERE i.item_id = $rItemID LIMIT 1";
                        $supplierResult = mysqli_query($conn, $getSupplierQuery);

                        if (mysqli_num_rows($supplierResult) > 0) {
                            $supplier = mysqli_fetch_assoc($supplierResult);
                            $supplierPhone = '+63' . substr($supplier['phone_number'], -10);  // Format for international phone number

                            // Prepare the message to send
                            $message = "Good day this is Calauan Glass and Aluminum Supply and Services. I need 20 pieces of $rail in dimension $rDimension and color $rColor. Thank you and God Bless!";

                            // Initialize SMS API configuration and send message
                            $config = new Configuration(host: $apiURL, apiKey: $apiKEY);
                            $api = new SmsApi(config: $config);

                            $destination = new SmsDestination(to: $supplierPhone);
                            $sms = new SmsTextualMessage(
                                destinations: [$destination],
                                text: $message,
                                from: "CGAS"
                            );

                            $request = new SmsAdvancedTextualRequest(messages: [$sms]);

                            try 
                            {
                                $response = $api->sendSmsMessage($request);
                            } 
                            catch (Exception $e) 
                            {
                                echo json_encode(['success' => false, 'message' => 'Failed to send SMS: ' . $e->getMessage()]);
                            }
                        }
                    }
                }
                
                if (!empty($rUEI) && !empty($rUNI)) 
                {
                    $rFoo = $rFootExcess - $rHFoot; // For excess
                    $rFoo1 = $rFoot - $rHFoot; // For new item

                    if (!empty($rUEI)) 
                    {
                        if ($rFoo > 0)
                        {
                            $updateRExcessQuery = "UPDATE inventory_excess SET exc_foot = $rFoo WHERE exc_id = $rUEI";
                            mysqli_query($conn, $updateRExcessQuery);
                        }
                        elseif ($rFoo <= 0) 
                        {
                            $deleteRExcessQuery = "DELETE FROM inventory_excess WHERE exc_id = $rUEI";
                            mysqli_query($conn, $deleteRExcessQuery);
                        }
                    }

                    if (!empty($rUNI)) 
                    {
                        if (strpos($rail, '|') !== false) {
                            $pipePos = strpos($rail, '|');
                            $rail = substr($rail, 0, $pipePos);
                        }

                        if ($rFoo1 > 0) 
                        {
                            $insertRExcessQuery = "INSERT INTO inventory_excess (item_name, exc_foot, dimension, color) VALUES ('$rail', $rFoo1, '$rDimension', '$rColor')";
                            mysqli_query($conn, $insertRExcessQuery);
                        }

                        $newRStock = $rStock - 1;
                        $updateRInventoryQuery = "UPDATE inventory SET stock = $newRStock WHERE item_id = $rUNI'";
                        mysqli_query($conn, $updateRInventoryQuery);

                        // Insert into inv_mon table
                        $insertItemQuery = "INSERT INTO inv_mon (item_id, date_created) VALUES ('$rUNI', NOW())";
                        mysqli_query($conn, $insertItemQuery);

                        if ($newRStock == 5) {
                            // Fetch the supplier's phone number
                            $getSupplierQuery = "SELECT s.phone_number FROM supplier s INNER JOIN inventory i ON s.supplier_id = i.supplier_id WHERE i.item_id = $rItemID LIMIT 1";
                            $supplierResult = mysqli_query($conn, $getSupplierQuery);
        
                            if (mysqli_num_rows($supplierResult) > 0) {
                                $supplier = mysqli_fetch_assoc($supplierResult);
                                $supplierPhone = '+63' . substr($supplier['phone_number'], -10);  // Format for international phone number
        
                                // Prepare the message to send
                                $message = "Good day this is Calauan Glass and Aluminum Supply and Services. I need 20 pieces of $rail in dimension $rDimension and color $rColor. Thank you and God Bless!";
        
                                // Initialize SMS API configuration and send message
                                $config = new Configuration(host: $apiURL, apiKey: $apiKEY);
                                $api = new SmsApi(config: $config);
        
                                $destination = new SmsDestination(to: $supplierPhone);
                                $sms = new SmsTextualMessage(
                                    destinations: [$destination],
                                    text: $message,
                                    from: "CGAS"
                                );
        
                                $request = new SmsAdvancedTextualRequest(messages: [$sms]);
        
                                try 
                                {
                                    $response = $api->sendSmsMessage($request);
                                } 
                                catch (Exception $e) 
                                {
                                    echo json_encode(['success' => false, 'message' => 'Failed to send SMS: ' . $e->getMessage()]);
                                }
                            }
                        }
                    }
                }

                if (!empty($rFEID) && !empty($rFEID1))
                {
                    $rFee = $rFH - $rHFoot;
                    $rFee1 = $rSH - $rHFoot;

                    if (!empty($rFEID))
                    {
                        if ($rFee > 0)
                        {
                            $updateRExcessQuery = "UPDATE inventory_excess SET exc_foot = $rFee WHERE exc_id = $rFEID";
                            mysqli_query($conn, $updateRExcessQuery);
                        }
                        elseif ($rFee <= 0) 
                        {
                            $deleteRExcessQuery = "DELETE FROM inventory_excess WHERE exc_id = $rFEID";
                            mysqli_query($conn, $deleteRExcessQuery);
                        }
                    }

                    if (!empty($rFEID1))
                    {
                        if ($rFee1 > 0)
                        {
                            $updateRExcessQuery = "UPDATE inventory_excess SET exc_foot = $rFee1 WHERE exc_id = $rFEID1";
                            mysqli_query($conn, $updateRExcessQuery);
                        }
                        elseif ($rFee1 <= 0) 
                        {
                            $deleteRExcessQuery = "DELETE FROM inventory_excess WHERE exc_id = $rFEID1";
                            mysqli_query($conn, $deleteRExcessQuery);
                        }
                    }
                }
                
                // Handle excess item for SIDE JAMB
                if (!empty($sjEItemID)) 
                {
                    $updateSJExcessQuery = "UPDATE inventory_excess SET exc_foot = $sjFoots WHERE exc_id = $sjEItemID";
                    mysqli_query($conn, $updateSJExcessQuery);

                    if ($sjFoots <= 0) 
                    {
                        $deleteSJExcessQuery = "DELETE FROM inventory_excess WHERE exc_id = $sjEItemID";
                        mysqli_query($conn, $deleteSJExcessQuery);
                    }
                }
                elseif (!empty($sjItemID)) 
                {
                    if (strpos($sideJamb, '|') !== false) {
                        $pipePos = strpos($sideJamb, '|');
                        $sideJamb = substr($sideJamb, 0, $pipePos);
                    }

                    if ($sjFoots1 > 0) 
                    {
                        $insertSJExcessQuery = "INSERT INTO inventory_excess (item_name, exc_foot, dimension, color) VALUES ('$sideJamb', $sjFoots1, '$sjDimension', '$sjColor')";
                        mysqli_query($conn, $insertSJExcessQuery);
                    }

                    $newSjStock = $sjStock - 1;
                    $updateSJInventoryQuery = "UPDATE inventory SET stock = $newSjStock WHERE item_id = $sjItemID";
                    mysqli_query($conn, $updateSJInventoryQuery);

                    $insertItemQuery = "INSERT INTO inv_mon (item_id, date_created) VALUES ('$sjItemID', NOW())";
                    mysqli_query($conn, $insertItemQuery);

                    if ($newSjStock == 5) {
                        // Fetch the supplier's phone number
                        $getSupplierQuery = "SELECT s.phone_number FROM supplier s INNER JOIN inventory i ON s.supplier_id = i.supplier_id WHERE i.item_id = $sjItemID LIMIT 1";
                        $supplierResult = mysqli_query($conn, $getSupplierQuery);

                        if (mysqli_num_rows($supplierResult) > 0) {
                            $supplier = mysqli_fetch_assoc($supplierResult);
                            $supplierPhone = '+63' . substr($supplier['phone_number'], -10);  // Format for international phone number

                            // Prepare the message to send
                            $message = "Good day this is Calauan Glass and Aluminum Supply and Services. I need 20 pieces of $sideJamb in dimension $sjDimension and color $sjColor. Thank you and God Bless!";

                            // Initialize SMS API configuration and send message
                            $config = new Configuration(host: $apiURL, apiKey: $apiKEY);
                            $api = new SmsApi(config: $config);

                            $destination = new SmsDestination(to: $supplierPhone);
                            $sms = new SmsTextualMessage(
                                destinations: [$destination],
                                text: $message,
                                from: "CGAS"
                            );

                            $request = new SmsAdvancedTextualRequest(messages: [$sms]);

                            try 
                            {
                                $response = $api->sendSmsMessage($request);
                            } 
                            catch (Exception $e) 
                            {
                                echo json_encode(['success' => false, 'message' => 'Failed to send SMS: ' . $e->getMessage()]);
                            }
                        }
                    }
                }

                // Handle excess for UEI and UNI
                if (!empty($sjUEI) && !empty($sjUNI)) 
                {
                    $sjFoo = $sjFootExcess - $sjHFoot; // For excess
                    $sjFoo1 = $sjFoot - $sjHFoot; // For new item

                    if (!empty($sjUEI)) 
                    {
                        if ($sjFoo > 0) 
                        {
                            $updateSJExcessQuery = "UPDATE inventory_excess SET exc_foot = $sjFoo WHERE exc_id = $sjUEI";
                            mysqli_query($conn, $updateSJExcessQuery);
                        } elseif ($sjFoo <= 0) {
                            $deleteSJExcessQuery = "DELETE FROM inventory_excess WHERE exc_id = $sjUEI";
                            mysqli_query($conn, $deleteSJExcessQuery);
                        }
                    }

                    if (!empty($sjUNI)) 
                    {
                        if (strpos($sideJamb, '|') !== false) {
                            $pipePos = strpos($sideJamb, '|');
                            $sideJamb = substr($sideJamb, 0, $pipePos);
                        }

                        if ($sjFoo1 > 0) 
                        {
                            $insertSJExcessQuery = "INSERT INTO inventory_excess (item_name, exc_foot, dimension, color) VALUES ('$sideJamb', $sjFoo1, '$sjDimension', '$sjColor')";
                            mysqli_query($conn, $insertSJExcessQuery);
                        }

                        $newSjStock = $sjStock - 1;
                        $updateSJInventoryQuery = "UPDATE inventory SET stock = $newSjStock WHERE item_id = $sjUNI";
                        mysqli_query($conn, $updateSJInventoryQuery);

                        $insertItemQuery = "INSERT INTO inv_mon (item_id, date_created) VALUES ('$sjUNI', NOW())";
                        mysqli_query($conn, $insertItemQuery);

                        if ($newSjStock == 5) {
                            // Fetch the supplier's phone number
                            $getSupplierQuery = "SELECT s.phone_number FROM supplier s INNER JOIN inventory i ON s.supplier_id = i.supplier_id WHERE i.item_id = $sjItemID LIMIT 1";
                            $supplierResult = mysqli_query($conn, $getSupplierQuery);
        
                            if (mysqli_num_rows($supplierResult) > 0) {
                                $supplier = mysqli_fetch_assoc($supplierResult);
                                $supplierPhone = '+63' . substr($supplier['phone_number'], -10);  // Format for international phone number
        
                                // Prepare the message to send
                                $message = "Good day this is Calauan Glass and Aluminum Supply and Services. I need 20 pieces of $sideJamb in dimension $sjDimension and color $sjColor. Thank you and God Bless!";
        
                                // Initialize SMS API configuration and send message
                                $config = new Configuration(host: $apiURL, apiKey: $apiKEY);
                                $api = new SmsApi(config: $config);
        
                                $destination = new SmsDestination(to: $supplierPhone);
                                $sms = new SmsTextualMessage(
                                    destinations: [$destination],
                                    text: $message,
                                    from: "CGAS"
                                );
        
                                $request = new SmsAdvancedTextualRequest(messages: [$sms]);
        
                                try 
                                {
                                    $response = $api->sendSmsMessage($request);
                                } 
                                catch (Exception $e) 
                                {
                                    echo json_encode(['success' => false, 'message' => 'Failed to send SMS: ' . $e->getMessage()]);
                                }
                            }
                        }
                    }
                }

                if (!empty($sjFEID) && !empty($sjFEID1)) 
                {
                    $sjFee = $sjFH - $sjHFoot;
                    $sjFee1 = $sjSH - $sjHFoot;

                    if (!empty($sjFEID)) 
                    {
                        if ($sjFee > 0) {
                            $updateSJExcessQuery = "UPDATE inventory_excess SET exc_foot = $sjFee WHERE exc_id = $sjFEID";
                            mysqli_query($conn, $updateSJExcessQuery);
                        } elseif ($sjFee <= 0) {
                            $deleteSJExcessQuery = "DELETE FROM inventory_excess WHERE exc_id = $sjFEID";
                            mysqli_query($conn, $deleteSJExcessQuery);
                        }
                    }

                    if (!empty($sjFEID1))
                    {
                        if ($sjFee1 > 0) 
                        {
                            $updateSJExcessQuery = "UPDATE inventory_excess SET exc_foot = $sjFee1 WHERE exc_id = $sjFEID1";
                            mysqli_query($conn, $updateSJExcessQuery);
                        } elseif ($sjFee1 <= 0) {
                            $deleteSJExcessQuery = "DELETE FROM inventory_excess WHERE exc_id = $sjFEID1";
                            mysqli_query($conn, $deleteSJExcessQuery);
                        }
                    }
                }

                if (!empty($lsEItemID)) 
                {
                    $updateLSExcessQuery = "UPDATE inventory_excess SET exc_foot = $lsFoots WHERE exc_id = $lsEItemID";
                    mysqli_query($conn, $updateLSExcessQuery);

                    if ($lsFoots <= 0) {
                        $deleteLSExcessQuery = "DELETE FROM inventory_excess WHERE exc_id = $lsEItemID";
                        mysqli_query($conn, $deleteLSExcessQuery);
                    }
                } 
                elseif (!empty($lsItemID)) 
                {
                    if (strpos($lockStile, '|') !== false) {
                        $pipePos = strpos($lockStile, '|');
                        $lockStile = substr($lockStile, 0, $pipePos);
                    }

                    if ($lsFoots1 > 0) {
                        $insertLSExcessQuery = "INSERT INTO inventory_excess (item_name, exc_foot, dimension, color) VALUES ('$lockStile', $lsFoots1, '$lsDimension', '$lsColor')";
                        mysqli_query($conn, $insertLSExcessQuery);
                    }

                    $newLsStock = $lsStock - 1;
                    $updateLSInventoryQuery = "UPDATE inventory SET stock = $newLsStock WHERE item_id = $lsItemID";
                    mysqli_query($conn, $updateLSInventoryQuery);

                    $insertItemQuery = "INSERT INTO inv_mon (item_id, date_created) VALUES ('$lsItemID', NOW())";
                    mysqli_query($conn, $insertItemQuery);

                    if ($newLsStock == 5) {
                        // Fetch the supplier's phone number
                        $getSupplierQuery = "SELECT s.phone_number FROM supplier s INNER JOIN inventory i ON s.supplier_id = i.supplier_id WHERE i.item_id = $lsItemID LIMIT 1";
                        $supplierResult = mysqli_query($conn, $getSupplierQuery);

                        if (mysqli_num_rows($supplierResult) > 0) {
                            $supplier = mysqli_fetch_assoc($supplierResult);
                            $supplierPhone = '+63' . substr($supplier['phone_number'], -10);  // Format for international phone number

                            // Prepare the message to send
                            $message = "Good day this is Calauan Glass and Aluminum Supply and Services. I need 20 pieces of $lockStile in dimension $lsDimension and color $lsColor. Thank you and God Bless!";

                            // Initialize SMS API configuration and send message
                            $config = new Configuration(host: $apiURL, apiKey: $apiKEY);
                            $api = new SmsApi(config: $config);

                            $destination = new SmsDestination(to: $supplierPhone);
                            $sms = new SmsTextualMessage(
                                destinations: [$destination],
                                text: $message,
                                from: "CGAS"
                            );

                            $request = new SmsAdvancedTextualRequest(messages: [$sms]);

                            try 
                            {
                                $response = $api->sendSmsMessage($request);
                            } 
                            catch (Exception $e) 
                            {
                                echo json_encode(['success' => false, 'message' => 'Failed to send SMS: ' . $e->getMessage()]);
                            }
                        }
                    }
                }

                // Handle excess for UEI and UNI
                if (!empty($lsUEI) && !empty($lsUNI)) 
                {
                    $lsFoo = $lsFootExcess - $lsHFoot; // For excess
                    $lsFoo1 = $lsFoot - $lsHFoot; // For new item

                    if (!empty($lsUEI)) {
                        if ($lsFoo > 0) {
                            $updateLSExcessQuery = "UPDATE inventory_excess SET exc_foot = $lsFoo WHERE exc_id = $lsUEI";
                            mysqli_query($conn, $updateLSExcessQuery);
                        } elseif ($lsFoo <= 0) {
                            $deleteLSExcessQuery = "DELETE FROM inventory_excess WHERE exc_id = $lsUEI";
                            mysqli_query($conn, $deleteLSExcessQuery);
                        }
                    }

                    if (!empty($lsUNI)) 
                    {
                        if (strpos($lockStile, '|') !== false) {
                            $pipePos = strpos($lockStile, '|');
                            $lockStile = substr($lockStile, 0, $pipePos);
                        }

                        if ($lsFoo1 > 0) 
                        {
                            $insertLSExcessQuery = "INSERT INTO inventory_excess (item_name, exc_foot, dimension, color) VALUES ('$lockStile', $lsFoo1, '$lsDimension', '$lsColor')";
                            mysqli_query($conn, $insertLSExcessQuery);
                        }

                        $newLsStock = $lsStock - 1;
                        $updateLSInventoryQuery = "UPDATE inventory SET stock = $newLsStock WHERE item_id = $lsUNI";
                        mysqli_query($conn, $updateLSInventoryQuery);

                        $insertItemQuery = "INSERT INTO inv_mon (item_id, date_created) VALUES ('$lsUNI', NOW())";
                        mysqli_query($conn, $insertItemQuery);


                        if ($newLsStock == 5) {
                            // Fetch the supplier's phone number
                            $getSupplierQuery = "SELECT s.phone_number FROM supplier s INNER JOIN inventory i ON s.supplier_id = i.supplier_id WHERE i.item_id = $lsItemID LIMIT 1";
                            $supplierResult = mysqli_query($conn, $getSupplierQuery);
        
                            if (mysqli_num_rows($supplierResult) > 0) {
                                $supplier = mysqli_fetch_assoc($supplierResult);
                                $supplierPhone = '+63' . substr($supplier['phone_number'], -10);  // Format for international phone number
        
                                // Prepare the message to send
                                $message = "Good day this is Calauan Glass and Aluminum Supply and Services. I need 20 pieces of $lockStile in dimension $lsDimension and color $lsColor. Thank you and God Bless!";
        
                                // Initialize SMS API configuration and send message
                                $config = new Configuration(host: $apiURL, apiKey: $apiKEY);
                                $api = new SmsApi(config: $config);
        
                                $destination = new SmsDestination(to: $supplierPhone);
                                $sms = new SmsTextualMessage(
                                    destinations: [$destination],
                                    text: $message,
                                    from: "CGAS"
                                );
        
                                $request = new SmsAdvancedTextualRequest(messages: [$sms]);
        
                                try 
                                {
                                    $response = $api->sendSmsMessage($request);
                                } 
                                catch (Exception $e) 
                                {
                                    echo json_encode(['success' => false, 'message' => 'Failed to send SMS: ' . $e->getMessage()]);
                                }
                            }
                        }
                    }
                }

                // Handle excess for FEID and FEID1
                if (!empty($lsFEID) && !empty($lsFEID1)) 
                {
                    $lsFee = $lsFH - $lsHFoot;
                    $lsFee1 = $lsSH - $lsHFoot;

                    if (!empty($lsFEID)) {
                        if ($lsFee > 0) {
                            $updateLSExcessQuery = "UPDATE inventory_excess SET exc_foot = $lsFee WHERE exc_id = $lsFEID";
                            mysqli_query($conn, $updateLSExcessQuery);
                        } elseif ($lsFee <= 0) {
                            $deleteLSExcessQuery = "DELETE FROM inventory_excess WHERE exc_id = $lsFEID";
                            mysqli_query($conn, $deleteLSExcessQuery);
                        }
                    }

                    if (!empty($lsFEID1)) {
                        if ($lsFee1 > 0) {
                            $updateLSExcessQuery = "UPDATE inventory_excess SET exc_foot = $lsFee1 WHERE exc_id = $lsFEID1";
                            mysqli_query($conn, $updateLSExcessQuery);
                        } elseif ($lsFee1 <= 0) {
                            $deleteLSExcessQuery = "DELETE FROM inventory_excess WHERE exc_id = $lsFEID1";
                            mysqli_query($conn, $deleteLSExcessQuery);
                        }
                    }
                }

                // Handle excess item for INTERLOCKER
                if (!empty($ilEItemID)) 
                {
                    $updateILExcessQuery = "UPDATE inventory_excess SET exc_foot = $ilFoots WHERE exc_id = $ilEItemID";
                    mysqli_query($conn, $updateILExcessQuery);

                    if ($ilFoots <= 0) {
                        $deleteILExcessQuery = "DELETE FROM inventory_excess WHERE exc_id = $ilEItemID";
                        mysqli_query($conn, $deleteILExcessQuery);
                    }
                } 
                elseif (!empty($ilItemID)) 
                {
                    if (strpos($interlocker, '|') !== false) {
                        $pipePos = strpos($interlocker, '|');
                        $interlocker = substr($interlocker, 0, $pipePos);
                    }

                    if ($ilFoots1 > 0) {
                        $insertILExcessQuery = "INSERT INTO inventory_excess (item_name, exc_foot, dimension, color) VALUES ('$interlocker', $ilFoots1, '$ilDimension', '$ilColor')";
                        mysqli_query($conn, $insertILExcessQuery);
                    }

                    $newIlStock = $ilStock - 1;
                    $updateILInventoryQuery = "UPDATE inventory SET stock = $newIlStock WHERE item_id = $ilItemID";
                    mysqli_query($conn, $updateILInventoryQuery);

                    $insertItemQuery = "INSERT INTO inv_mon (item_id, date_created) VALUES ('$ilItemID', NOW())";
                    mysqli_query($conn, $insertItemQuery);

                    if ($newIlStock == 5) {
                        // Fetch the supplier's phone number
                        $getSupplierQuery = "SELECT s.phone_number FROM supplier s INNER JOIN inventory i ON s.supplier_id = i.supplier_id WHERE i.item_id = $ilItemID LIMIT 1";
                        $supplierResult = mysqli_query($conn, $getSupplierQuery);

                        if (mysqli_num_rows($supplierResult) > 0) {
                            $supplier = mysqli_fetch_assoc($supplierResult);
                            $supplierPhone = '+63' . substr($supplier['phone_number'], -10);  // Format for international phone number

                            // Prepare the message to send
                            $message = "Good day this is Calauan Glass and Aluminum Supply and Services. I need 20 pieces of $interlocker in dimension $ilDimension and color $ilColor. Thank you and God Bless!";

                            // Initialize SMS API configuration and send message
                            $config = new Configuration(host: $apiURL, apiKey: $apiKEY);
                            $api = new SmsApi(config: $config);

                            $destination = new SmsDestination(to: $supplierPhone);
                            $sms = new SmsTextualMessage(
                                destinations: [$destination],
                                text: $message,
                                from: "CGAS"
                            );

                            $request = new SmsAdvancedTextualRequest(messages: [$sms]);

                            try 
                            {
                                $response = $api->sendSmsMessage($request);
                            } 
                            catch (Exception $e) 
                            {
                                echo json_encode(['success' => false, 'message' => 'Failed to send SMS: ' . $e->getMessage()]);
                            }
                        }
                    }
                }

                // Handle excess for UEI and UNI
                if (!empty($ilUEI) && !empty($ilUNI)) 
                {
                    $ilFoo = $ilFootExcess - $ilHFoot; // For excess
                    $ilFoo1 = $ilFoot - $ilHFoot; // For new item

                    if (!empty($ilUEI)) {
                        if ($ilFoo > 0) {
                            $updateILExcessQuery = "UPDATE inventory_excess SET exc_foot = $ilFoo WHERE exc_id = $ilUEI";
                            mysqli_query($conn, $updateILExcessQuery);
                        } elseif ($ilFoo <= 0) {
                            $deleteILExcessQuery = "DELETE FROM inventory_excess WHERE exc_id = $ilUEI";
                            mysqli_query($conn, $deleteILExcessQuery);
                        }
                    }

                    if (!empty($ilUNI)) 
                    {
                        if (strpos($interlocker, '|') !== false) {
                            $pipePos = strpos($interlocker, '|');
                            $interlocker = substr($interlocker, 0, $pipePos);
                        }

                        if ($ilFoo1 > 0) {
                            $insertILExcessQuery = "INSERT INTO inventory_excess (item_name, exc_foot, dimension, color) VALUES ('$interlocker', $ilFoo1, '$ilDimension', '$ilColor')";
                            mysqli_query($conn, $insertILExcessQuery);
                        }

                        $newIlStock = $ilStock - 1;
                        $updateILInventoryQuery = "UPDATE inventory SET stock = $newIlStock WHERE item_id = $ilUNI";
                        mysqli_query($conn, $updateILInventoryQuery);

                        $insertItemQuery = "INSERT INTO inv_mon (item_id, date_created) VALUES ('$ilUNI', NOW())";
                        mysqli_query($conn, $insertItemQuery);

                        if ($newIlStock == 5) {
                            // Fetch the supplier's phone number
                            $getSupplierQuery = "SELECT s.phone_number FROM supplier s INNER JOIN inventory i ON s.supplier_id = i.supplier_id WHERE i.item_id = $ilItemID LIMIT 1";
                            $supplierResult = mysqli_query($conn, $getSupplierQuery);
        
                            if (mysqli_num_rows($supplierResult) > 0) {
                                $supplier = mysqli_fetch_assoc($supplierResult);
                                $supplierPhone = '+63' . substr($supplier['phone_number'], -10);  // Format for international phone number
        
                                // Prepare the message to send
                                $message = "Good day this is Calauan Glass and Aluminum Supply and Services. I need 20 pieces of $interlocker in dimension $ilDimension and color $ilColor. Thank you and God Bless!";
        
                                // Initialize SMS API configuration and send message
                                $config = new Configuration(host: $apiURL, apiKey: $apiKEY);
                                $api = new SmsApi(config: $config);
        
                                $destination = new SmsDestination(to: $supplierPhone);
                                $sms = new SmsTextualMessage(
                                    destinations: [$destination],
                                    text: $message,
                                    from: "CGAS"
                                );
        
                                $request = new SmsAdvancedTextualRequest(messages: [$sms]);
        
                                try 
                                {
                                    $response = $api->sendSmsMessage($request);
                                } 
                                catch (Exception $e) 
                                {
                                    echo json_encode(['success' => false, 'message' => 'Failed to send SMS: ' . $e->getMessage()]);
                                }
                            }
                        }
                    }
                }

                // Handle excess for FEID and FEID1
                if (!empty($ilFEID) && !empty($ilFEID1)) 
                {
                    $ilFee = $ilFH - $ilHFoot;
                    $ilFee1 = $ilSH - $ilHFoot;

                    if (!empty($ilFEID)) 
                    {
                        if ($ilFee > 0) 
                        {
                            $updateILExcessQuery = "UPDATE inventory_excess SET exc_foot = $ilFee WHERE exc_id = $ilFEID";
                            mysqli_query($conn, $updateILExcessQuery);
                        } 
                        elseif ($ilFee <= 0) {
                            $deleteILExcessQuery = "DELETE FROM inventory_excess WHERE exc_id = $ilFEID";
                            mysqli_query($conn, $deleteILExcessQuery);
                        }
                    }

                    if (!empty($ilFEID1)) {
                        if ($ilFee1 > 0) {
                            $updateILExcessQuery = "UPDATE inventory_excess SET exc_foot = $ilFee1 WHERE exc_id = $ilFEID1";
                            mysqli_query($conn, $updateILExcessQuery);
                        } elseif ($ilFee1 <= 0) {
                            $deleteILExcessQuery = "DELETE FROM inventory_excess WHERE exc_id = $ilFEID1";
                            mysqli_query($conn, $deleteILExcessQuery);
                        }
                    }
                }

                echo 'Task Created';
            }
            
        }
    }

    if($action == 'taskAwning')
    {
        $projectID = mysqli_real_escape_string($conn, $_POST['projectID'] ?? '');
        $length = floatval(mysqli_real_escape_string($conn, $_POST['lengthAwning'] ?? ''));
        $height = floatval(mysqli_real_escape_string($conn, $_POST['heightAwning'] ?? ''));
        $lFoot = floatval(mysqli_real_escape_string($conn, $_POST['lFootAwning'] ?? ''));
        $lFoot2 = floatval(mysqli_real_escape_string($conn, $_POST['lFootX2Awning'] ?? ''));
        $hFoot = floatval(mysqli_real_escape_string($conn, $_POST['hFootAwning'] ?? ''));
        $hFoot2 = floatval(mysqli_real_escape_string($conn, $_POST['hFootX2Awning'] ?? ''));

        $topHead = mysqli_real_escape_string($conn, $_POST['HeadAwning'] ?? '');
        $thPrice = floatval(mysqli_real_escape_string($conn, $_POST['hPriceAwning'] ?? ''));
        $thFootExcess = floatval(mysqli_real_escape_string($conn, $_POST['hFootExcessAwning'] ?? ''));
        $thItemID = mysqli_real_escape_string($conn, $_POST['hItemIDAwning'] ?? '');
        $thEItemID = mysqli_real_escape_string($conn, $_POST['hEItemIDAwning'] ?? '');
        $thColor = mysqli_real_escape_string($conn, $_POST['hColorAwning'] ?? '');
        $thDimension = mysqli_real_escape_string($conn, $_POST['hDimensionAwning'] ?? '');
        $thStock = intval(mysqli_real_escape_string($conn, $_POST['hStockAwning'] ?? ''));

        $bottomSill = mysqli_real_escape_string($conn, $_POST['SillAwning'] ?? '');
        $bsPrice = floatval(mysqli_real_escape_string($conn, $_POST['sPriceAwning'] ?? ''));
        $bsFootExcess = floatval(mysqli_real_escape_string($conn, $_POST['sFootExcessAwning'] ?? ''));
        $bsItemID = mysqli_real_escape_string($conn, $_POST['sItemIDAwning'] ?? '');
        $bsEItemID = mysqli_real_escape_string($conn, $_POST['sEItemIDAwning'] ?? '');
        $bsColor = mysqli_real_escape_string($conn, $_POST['sColorAwning'] ?? '');
        $bsDimension = mysqli_real_escape_string($conn, $_POST['sDimensionAwning'] ?? '');
        $bsStock = intval(mysqli_real_escape_string($conn, $_POST['sStockAwning'] ?? ''));

        $sideJamb = mysqli_real_escape_string($conn, $_POST['JambAwning'] ?? '');
        $sjPrice = floatval(mysqli_real_escape_string($conn, $_POST['sjPriceAwning'] ?? ''));
        $sjFootExcess = floatval(mysqli_real_escape_string($conn, $_POST['sjFootExcessAwning'] ?? ''));
        $sjFoot = floatval(mysqli_real_escape_string($conn, $_POST['sjFootAwning'] ?? ''));
        $sjItemID = mysqli_real_escape_string($conn, $_POST['sjItemIDAwning'] ?? '');
        $sjEItemID = mysqli_real_escape_string($conn, $_POST['sjEItemIDAwning'] ?? '');
        $sjUEI = mysqli_real_escape_string($conn, $_POST['sjUEIAwning'] ?? '');
        $sjUNI = mysqli_real_escape_string($conn, $_POST['sjUNIAwning'] ?? '');
        $sjFEID = mysqli_real_escape_string($conn, $_POST['sjFEIDAwning'] ?? '');
        $sjFEID1 = mysqli_real_escape_string($conn, $_POST['sjFEID1Awning'] ?? '');
        $sjFH = mysqli_real_escape_string($conn, $_POST['sjFHAwning'] ?? '');
        $sjSH = mysqli_real_escape_string($conn, $_POST['sjSHAwning'] ?? '');
        $sjColor = mysqli_real_escape_string($conn, $_POST['sjColorAwning'] ?? '');
        $sjDimension = mysqli_real_escape_string($conn, $_POST['sjDimensionAwning'] ?? '');
        $sjStock = intval(mysqli_real_escape_string($conn, $_POST['sjStockAwning'] ?? ''));
        $sjHFoot = intval(mysqli_real_escape_string($conn, $_POST['sjHFootAwning'] ?? ''));

        $glass = mysqli_real_escape_string($conn, $_POST['GlassAwning'] ?? '');
        $gItemID = mysqli_real_escape_string($conn, $_POST['gItemIDAwning'] ?? '');
        $gPrice = floatval(mysqli_real_escape_string($conn, $_POST['gPriceAwning'] ?? ''));
        $gStock = intval(mysqli_real_escape_string($conn, $_POST['gStockAwning'] ?? ''));

        $thFoot = $thFootExcess - $lFoot;
        $bsFoot = $bsFootExcess - $lFoot;

        $sjFoots = $sjFootExcess - $hFoot2;
        $sjFoots1 = $sjFoot - $hFoot2;

        $items = "Head - $lFoot feet of $topHead, Sill - $lFoot feet of $bottomSill, Jamb - $hFoot2 feet of $sideJamb";

        $size = $lFoot * $hFoot;
        $sum = $thPrice + $bsPrice + $sjPrice + $gPrice;
        $totalPrice = $size * $sum;

        $query = "INSERT INTO task (description, length, height, status, items, total_price, project_id) VALUES ('Awning', $length, $height, 'NOT STARTED', '$items', $totalPrice, $projectID)";

        if (mysqli_query($conn, $query)) 
        {
            if ($_SERVER["REQUEST_METHOD"] == "POST") 
            {
                if (!empty($gItemID))
                {
                    $newGStock = $gStock - 1;
                    $updateThInventoryQuery = "UPDATE inventory SET stock = $newGStock WHERE item_name = '$glass'";
                    mysqli_query($conn, $updateThInventoryQuery);
                }

                if (!empty($thEItemID))
                {
                    $updateThExcessQuery = "UPDATE inventory_excess SET exc_foot = $thFoot WHERE exc_id = $thEItemID";
                    mysqli_query($conn, $updateThExcessQuery);
                        
                    if ($thFoot <= 0) 
                    {
                        $deleteThExcessQuery = "DELETE FROM inventory_excess WHERE exc_id = $thEItemID";
                        mysqli_query($conn, $deleteThExcessQuery);
                    }
                }
                elseif (!empty($thItemID)) 
                {
                    if (strpos($topHead, '|') !== false) {
                        $pipePos = strpos($topHead, '|');
                        $topHead = substr($topHead, 0, $pipePos);
                    }

                    if ($thFoot > 0) 
                    {
                        $insertThExcessQuery = "INSERT INTO inventory_excess (item_name, exc_foot, dimension, color) VALUES ('$topHead', $thFoot, '$thDimension', '$thColor')";
                        mysqli_query($conn, $insertThExcessQuery);
                    }

                    $newThStock = $thStock - 1;
                    $updateThInventoryQuery = "UPDATE inventory SET stock = $newThStock WHERE item_id = $thItemID";
                    mysqli_query($conn, $updateThInventoryQuery);

                    // Insert into inv_mon table
                    $insertItemQuery = "INSERT INTO inv_mon (item_id, date_created) VALUES ('$thItemID', NOW())";
                    mysqli_query($conn, $insertItemQuery);

                    // Check if the new stock is 5
                    if ($newThStock == 5) {
                        // Fetch the supplier's phone number
                        $getSupplierQuery = "SELECT s.phone_number FROM supplier s INNER JOIN inventory i ON s.supplier_id = i.supplier_id WHERE i.item_id = $thItemID LIMIT 1";
                        $supplierResult = mysqli_query($conn, $getSupplierQuery);

                        if (mysqli_num_rows($supplierResult) > 0) {
                            $supplier = mysqli_fetch_assoc($supplierResult);
                            $supplierPhone = '+63' . substr($supplier['phone_number'], -10);  // Format for international phone number

                            // Prepare the message to send
                            $message = "Good day this is Calauan Glass and Aluminum Supply and Services. I need 20 pieces of $topHead in dimension $thDimension and color $thColor. Thank you and God Bless!";

                            // Initialize SMS API configuration and send message
                            $config = new Configuration(host: $apiURL, apiKey: $apiKEY);
                            $api = new SmsApi(config: $config);

                            $destination = new SmsDestination(to: $supplierPhone);
                            $sms = new SmsTextualMessage(
                                destinations: [$destination],
                                text: $message,
                                from: "CGAS"
                            );

                            $request = new SmsAdvancedTextualRequest(messages: [$sms]);

                            try 
                            {
                                $response = $api->sendSmsMessage($request);
                            } 
                            catch (Exception $e) 
                            {
                                echo json_encode(['success' => false, 'message' => 'Failed to send SMS: ' . $e->getMessage()]);
                            }
                        }
                    }
                }

                if (!empty($bsEItemID))
                {
                    $updateBsExcessQuery = "UPDATE inventory_excess SET exc_foot = $bsFoot WHERE exc_id = $bsEItemID";
                    mysqli_query($conn, $updateBsExcessQuery);
                        
                    if ($bsFoot <= 0) 
                    {
                        $deleteBsExcessQuery = "DELETE FROM inventory_excess WHERE exc_id = $bsEItemID";
                        mysqli_query($conn, $deleteBsExcessQuery);
                    }
                }
                elseif (!empty($bsItemID))
                {
                    if (strpos($bottomSill, '|') !== false) {
                        $pipePos = strpos($bottomSill, '|');
                        $bottomSill = substr($bottomSill, 0, $pipePos);
                    }

                    if ($bsFoot > 0) 
                    {
                        $insertBsExcessQuery = "INSERT INTO inventory_excess (item_name, exc_foot, dimension, color) VALUES ('$bottomSill', $bsFoot, '$bsDimension', '$bsColor')";
                        mysqli_query($conn, $insertBsExcessQuery);
                    }

                    $newBsStock = $bsStock - 1;
                    $updateBsInventoryQuery = "UPDATE inventory SET stock = $newBsStock WHERE item_id = $bsItemID";
                    mysqli_query($conn, $updateBsInventoryQuery);

                    // Insert into inv_mon table
                    $insertItemQuery = "INSERT INTO inv_mon (item_id, date_created) VALUES ('$bsItemID', NOW())";
                    mysqli_query($conn, $insertItemQuery);

                    if ($newBsStock == 5) {
                        // Fetch the supplier's phone number
                        $getSupplierQuery = "SELECT s.phone_number FROM supplier s INNER JOIN inventory i ON s.supplier_id = i.supplier_id WHERE i.item_id = $bsItemID LIMIT 1";
                        $supplierResult = mysqli_query($conn, $getSupplierQuery);

                        if (mysqli_num_rows($supplierResult) > 0) {
                            $supplier = mysqli_fetch_assoc($supplierResult);
                            $supplierPhone = '+63' . substr($supplier['phone_number'], -10);  // Format for international phone number

                            // Prepare the message to send
                            $message = "Good day this is Calauan Glass and Aluminum Supply and Services. I need 20 pieces of $bottomSill in dimension $bsDimension and color $bsColor. Thank you and God Bless!";

                            // Initialize SMS API configuration and send message
                            $config = new Configuration(host: $apiURL, apiKey: $apiKEY);
                            $api = new SmsApi(config: $config);

                            $destination = new SmsDestination(to: $supplierPhone);
                            $sms = new SmsTextualMessage(
                                destinations: [$destination],
                                text: $message,
                                from: "CGAS"
                            );

                            $request = new SmsAdvancedTextualRequest(messages: [$sms]);

                            try 
                            {
                                $response = $api->sendSmsMessage($request);
                            } 
                            catch (Exception $e) 
                            {
                                echo json_encode(['success' => false, 'message' => 'Failed to send SMS: ' . $e->getMessage()]);
                            }
                        }
                    }
                }

                // Handle excess item for SIDE JAMB
                if (!empty($sjEItemID)) 
                {
                    $updateSJExcessQuery = "UPDATE inventory_excess SET exc_foot = $sjFoots WHERE exc_id = $sjEItemID";
                    mysqli_query($conn, $updateSJExcessQuery);

                    if ($sjFoots <= 0) 
                    {
                        $deleteSJExcessQuery = "DELETE FROM inventory_excess WHERE exc_id = $sjEItemID";
                        mysqli_query($conn, $deleteSJExcessQuery);
                    }
                }
                elseif (!empty($sjItemID)) 
                {
                    if (strpos($sideJamb, '|') !== false) {
                        $pipePos = strpos($sideJamb, '|');
                        $sideJamb = substr($sideJamb, 0, $pipePos);
                    }

                    if ($sjFoots1 > 0) 
                    {
                        $insertSJExcessQuery = "INSERT INTO inventory_excess (item_name, exc_foot, dimension, color) VALUES ('$sideJamb', $sjFoots1, '$sjDimension', '$sjColor')";
                        mysqli_query($conn, $insertSJExcessQuery);
                    }

                    $newSjStock = $sjStock - 1;
                    $updateSJInventoryQuery = "UPDATE inventory SET stock = $newSjStock WHERE item_id = $sjItemID";
                    mysqli_query($conn, $updateSJInventoryQuery);

                    // Insert into inv_mon table
                    $insertItemQuery = "INSERT INTO inv_mon (item_id, date_created) VALUES ('$sjItemID', NOW())";
                    mysqli_query($conn, $insertItemQuery);


                    if ($newSjStock == 5) {
                        // Fetch the supplier's phone number
                        $getSupplierQuery = "SELECT s.phone_number FROM supplier s INNER JOIN inventory i ON s.supplier_id = i.supplier_id WHERE i.item_id = $sjItemID LIMIT 1";
                        $supplierResult = mysqli_query($conn, $getSupplierQuery);

                        if (mysqli_num_rows($supplierResult) > 0) {
                            $supplier = mysqli_fetch_assoc($supplierResult);
                            $supplierPhone = '+63' . substr($supplier['phone_number'], -10);  // Format for international phone number

                            // Prepare the message to send
                            $message = "Good day this is Calauan Glass and Aluminum Supply and Services. I need 20 pieces of $sideJamb in dimension $sjDimension and color $sjColor. Thank you and God Bless!";

                            // Initialize SMS API configuration and send message
                            $config = new Configuration(host: $apiURL, apiKey: $apiKEY);
                            $api = new SmsApi(config: $config);

                            $destination = new SmsDestination(to: $supplierPhone);
                            $sms = new SmsTextualMessage(
                                destinations: [$destination],
                                text: $message,
                                from: "CGAS"
                            );

                            $request = new SmsAdvancedTextualRequest(messages: [$sms]);

                            try 
                            {
                                $response = $api->sendSmsMessage($request);
                            } 
                            catch (Exception $e) 
                            {
                                echo json_encode(['success' => false, 'message' => 'Failed to send SMS: ' . $e->getMessage()]);
                            }
                        }
                    }
                }

                // Handle excess for UEI and UNI
                if (!empty($sjUEI) && !empty($sjUNI)) 
                {
                    $sjFoo = $sjFootExcess - $sjHFoot; // For excess
                    $sjFoo1 = $sjFoot - $sjHFoot; // For new item

                    if (!empty($sjUEI)) 
                    {
                        if ($sjFoo > 0) 
                        {
                            $updateSJExcessQuery = "UPDATE inventory_excess SET exc_foot = $sjFoo WHERE exc_id = $sjUEI";
                            mysqli_query($conn, $updateSJExcessQuery);
                        } elseif ($sjFoo <= 0) {
                            $deleteSJExcessQuery = "DELETE FROM inventory_excess WHERE exc_id = $sjUEI";
                            mysqli_query($conn, $deleteSJExcessQuery);
                        }
                    }

                    if (!empty($sjUNI)) 
                    {
                        if (strpos($sideJamb, '|') !== false) {
                            $pipePos = strpos($sideJamb, '|');
                            $sideJamb = substr($sideJamb, 0, $pipePos);
                        }

                        if ($sjFoo1 > 0) 
                        {
                            $insertSJExcessQuery = "INSERT INTO inventory_excess (item_name, exc_foot, dimension, color) VALUES ('$sideJamb', $sjFoo1, '$sjDimension', '$sjColor')";
                            mysqli_query($conn, $insertSJExcessQuery);
                        }

                        $newSjStock = $sjStock - 1;
                        $updateSJInventoryQuery = "UPDATE inventory SET stock = $newSjStock WHERE item_id = $sjUNI";
                        mysqli_query($conn, $updateSJInventoryQuery);

                        // Insert into inv_mon table
                        $insertItemQuery = "INSERT INTO inv_mon (item_id, date_created) VALUES ('$sjUNI', NOW())";
                        mysqli_query($conn, $insertItemQuery);


                        if ($newSjStock == 5) {
                            // Fetch the supplier's phone number
                            $getSupplierQuery = "SELECT s.phone_number FROM supplier s INNER JOIN inventory i ON s.supplier_id = i.supplier_id WHERE i.item_id = $sjItemID LIMIT 1";
                            $supplierResult = mysqli_query($conn, $getSupplierQuery);
        
                            if (mysqli_num_rows($supplierResult) > 0) {
                                $supplier = mysqli_fetch_assoc($supplierResult);
                                $supplierPhone = '+63' . substr($supplier['phone_number'], -10);  // Format for international phone number
        
                                // Prepare the message to send
                                $message = "Good day this is Calauan Glass and Aluminum Supply and Services. I need 20 pieces of $sideJamb in dimension $sjDimension and color $sjColor. Thank you and God Bless!";
        
                                // Initialize SMS API configuration and send message
                                $config = new Configuration(host: $apiURL, apiKey: $apiKEY);
                                $api = new SmsApi(config: $config);
        
                                $destination = new SmsDestination(to: $supplierPhone);
                                $sms = new SmsTextualMessage(
                                    destinations: [$destination],
                                    text: $message,
                                    from: "CGAS"
                                );
        
                                $request = new SmsAdvancedTextualRequest(messages: [$sms]);
        
                                try 
                                {
                                    $response = $api->sendSmsMessage($request);
                                } 
                                catch (Exception $e) 
                                {
                                    echo json_encode(['success' => false, 'message' => 'Failed to send SMS: ' . $e->getMessage()]);
                                }
                            }
                        }
                    }
                }

                if (!empty($sjFEID) && !empty($sjFEID1)) 
                {
                    $sjFee = $sjFH - $sjHFoot;
                    $sjFee1 = $sjSH - $sjHFoot;

                    if (!empty($sjFEID)) 
                    {
                        if ($sjFee > 0) {
                            $updateSJExcessQuery = "UPDATE inventory_excess SET exc_foot = $sjFee WHERE exc_id = $sjFEID";
                            mysqli_query($conn, $updateSJExcessQuery);
                        } elseif ($sjFee <= 0) {
                            $deleteSJExcessQuery = "DELETE FROM inventory_excess WHERE exc_id = $sjFEID";
                            mysqli_query($conn, $deleteSJExcessQuery);
                        }
                    }

                    if (!empty($sjFEID1))
                    {
                        if ($sjFee1 > 0) 
                        {
                            $updateSJExcessQuery = "UPDATE inventory_excess SET exc_foot = $sjFee1 WHERE exc_id = $sjFEID1";
                            mysqli_query($conn, $updateSJExcessQuery);
                        } elseif ($sjFee1 <= 0) {
                            $deleteSJExcessQuery = "DELETE FROM inventory_excess WHERE exc_id = $sjFEID1";
                            mysqli_query($conn, $deleteSJExcessQuery);
                        }
                    }
                }
                echo 'Task Created';
            }
        }
    }

    if($action == 'taskFixed')
    {
        $projectID = mysqli_real_escape_string($conn, $_POST['projectID'] ?? '');
        $length = floatval(mysqli_real_escape_string($conn, $_POST['lengthFixed'] ?? ''));
        $height = floatval(mysqli_real_escape_string($conn, $_POST['heightFixed'] ?? ''));
        $lFoot = floatval(mysqli_real_escape_string($conn, $_POST['lFootFixed'] ?? ''));
        $lFoot2 = floatval(mysqli_real_escape_string($conn, $_POST['lFootX2Fixed'] ?? ''));
        $hFoot = floatval(mysqli_real_escape_string($conn, $_POST['hFootFixed'] ?? ''));
        $hFoot2 = floatval(mysqli_real_escape_string($conn, $_POST['hFootX2Fixed'] ?? ''));

        $topHead = mysqli_real_escape_string($conn, $_POST['HeadFixed'] ?? '');
        $thPrice = floatval(mysqli_real_escape_string($conn, $_POST['hPriceFixed'] ?? ''));
        $thFootExcess = floatval(mysqli_real_escape_string($conn, $_POST['hFootExcessFixed'] ?? ''));
        $thItemID = mysqli_real_escape_string($conn, $_POST['hItemIDFixed'] ?? '');
        $thEItemID = mysqli_real_escape_string($conn, $_POST['hEItemIDFixed'] ?? '');
        $thColor = mysqli_real_escape_string($conn, $_POST['hColorFixed'] ?? '');
        $thDimension = mysqli_real_escape_string($conn, $_POST['hDimensionFixed'] ?? '');
        $thStock = intval(mysqli_real_escape_string($conn, $_POST['hStockFixed'] ?? ''));

        $bottomSill = mysqli_real_escape_string($conn, $_POST['SillFixed'] ?? '');
        $bsPrice = floatval(mysqli_real_escape_string($conn, $_POST['sPriceFixed'] ?? ''));
        $bsFootExcess = floatval(mysqli_real_escape_string($conn, $_POST['sFootExcessFixed'] ?? ''));
        $bsItemID = mysqli_real_escape_string($conn, $_POST['sItemIDFixed'] ?? '');
        $bsEItemID = mysqli_real_escape_string($conn, $_POST['sEItemIDFixed'] ?? '');
        $bsColor = mysqli_real_escape_string($conn, $_POST['sColorFixed'] ?? '');
        $bsDimension = mysqli_real_escape_string($conn, $_POST['sDimensionFixed'] ?? '');
        $bsStock = intval(mysqli_real_escape_string($conn, $_POST['sStockFixed'] ?? ''));

        $sideJamb = mysqli_real_escape_string($conn, $_POST['JambFixed'] ?? '');
        $sjPrice = floatval(mysqli_real_escape_string($conn, $_POST['sjPriceFixed'] ?? ''));
        $sjFootExcess = floatval(mysqli_real_escape_string($conn, $_POST['sjFootExcessFixed'] ?? ''));
        $sjFoot = floatval(mysqli_real_escape_string($conn, $_POST['sjFootFixed'] ?? ''));
        $sjItemID = mysqli_real_escape_string($conn, $_POST['sjItemIDFixed'] ?? '');
        $sjEItemID = mysqli_real_escape_string($conn, $_POST['sjEItemIDFixed'] ?? '');
        $sjUEI = mysqli_real_escape_string($conn, $_POST['sjUEIFixed'] ?? '');
        $sjUNI = mysqli_real_escape_string($conn, $_POST['sjUNIFixed'] ?? '');
        $sjFEID = mysqli_real_escape_string($conn, $_POST['sjFEIDFixed'] ?? '');
        $sjFEID1 = mysqli_real_escape_string($conn, $_POST['sjFEID1Fixed'] ?? '');
        $sjFH = mysqli_real_escape_string($conn, $_POST['sjFHFixed'] ?? '');
        $sjSH = mysqli_real_escape_string($conn, $_POST['sjSHFixed'] ?? '');
        $sjColor = mysqli_real_escape_string($conn, $_POST['sjColorFixed'] ?? '');
        $sjDimension = mysqli_real_escape_string($conn, $_POST['sjDimensionFixed'] ?? '');
        $sjStock = intval(mysqli_real_escape_string($conn, $_POST['sjStockFixed'] ?? ''));
        $sjHFoot = intval(mysqli_real_escape_string($conn, $_POST['sjHFootFixed'] ?? ''));

        $glass = mysqli_real_escape_string($conn, $_POST['GlassFixed'] ?? '');
        $gItemID = mysqli_real_escape_string($conn, $_POST['gItemIDFixed'] ?? '');
        $gPrice = floatval(mysqli_real_escape_string($conn, $_POST['gPriceFixed'] ?? ''));
        $gStock = intval(mysqli_real_escape_string($conn, $_POST['gStockFixed'] ?? ''));

        $thFoot = $thFootExcess - $lFoot;
        $bsFoot = $bsFootExcess - $lFoot;

        $sjFoots = $sjFootExcess - $hFoot2;
        $sjFoots1 = $sjFoot - $hFoot2;

        $items = "Head - $lFoot feet of $topHead, Sill - $lFoot feet of $bottomSill, Jamb - $hFoot2 feet of $sideJamb";

        $size = $lFoot * $hFoot;
        $sum = $thPrice + $bsPrice + $sjPrice + $gPrice;
        $totalPrice = $size * $sum;

        $query = "INSERT INTO task (description, length, height, status, items, total_price, project_id) VALUES ('Fixed', $length, $height, 'NOT STARTED', '$items', $totalPrice, $projectID)";

        if (mysqli_query($conn, $query)) 
        {
            if ($_SERVER["REQUEST_METHOD"] == "POST") 
            {
                if (!empty($gItemID))
                {
                    $newGStock = $gStock - 1;
                    $updateThInventoryQuery = "UPDATE inventory SET stock = $newGStock WHERE item_name = '$glass'";
                    mysqli_query($conn, $updateThInventoryQuery);
                }

                if (!empty($thEItemID))
                {
                    $updateThExcessQuery = "UPDATE inventory_excess SET exc_foot = $thFoot WHERE exc_id = $thEItemID";
                    mysqli_query($conn, $updateThExcessQuery);
                        
                    if ($thFoot <= 0) 
                    {
                        $deleteThExcessQuery = "DELETE FROM inventory_excess WHERE exc_id = $thEItemID";
                        mysqli_query($conn, $deleteThExcessQuery);
                    }
                }
                elseif (!empty($thItemID)) 
                {
                    if (strpos($topHead, '|') !== false) {
                        $pipePos = strpos($topHead, '|');
                        $topHead = substr($topHead, 0, $pipePos);
                    }

                    if ($thFoot > 0) 
                    {
                        $insertThExcessQuery = "INSERT INTO inventory_excess (item_name, exc_foot, dimension, color) VALUES ('$topHead', $thFoot, '$thDimension', '$thColor')";
                        mysqli_query($conn, $insertThExcessQuery);
                    }

                    $newThStock = $thStock - 1;
                    $updateThInventoryQuery = "UPDATE inventory SET stock = $newThStock WHERE item_id = $thItemID";
                    mysqli_query($conn, $updateThInventoryQuery);

                    // Insert into inv_mon table
                    $insertItemQuery = "INSERT INTO inv_mon (item_id, date_created) VALUES ('$thItemID', NOW())";
                    mysqli_query($conn, $insertItemQuery);

                    // Check if the new stock is 5
                    if ($newThStock == 5) {
                        // Fetch the supplier's phone number
                        $getSupplierQuery = "SELECT s.phone_number FROM supplier s INNER JOIN inventory i ON s.supplier_id = i.supplier_id WHERE i.item_id = $thItemID LIMIT 1";
                        $supplierResult = mysqli_query($conn, $getSupplierQuery);

                        if (mysqli_num_rows($supplierResult) > 0) {
                            $supplier = mysqli_fetch_assoc($supplierResult);
                            $supplierPhone = '+63' . substr($supplier['phone_number'], -10);  // Format for international phone number

                            // Prepare the message to send
                            $message = "Good day this is Calauan Glass and Aluminum Supply and Services. I need 20 pieces of $topHead in dimension $thDimension and color $thColor. Thank you and God Bless!";

                            // Initialize SMS API configuration and send message
                            $config = new Configuration(host: $apiURL, apiKey: $apiKEY);
                            $api = new SmsApi(config: $config);

                            $destination = new SmsDestination(to: $supplierPhone);
                            $sms = new SmsTextualMessage(
                                destinations: [$destination],
                                text: $message,
                                from: "CGAS"
                            );

                            $request = new SmsAdvancedTextualRequest(messages: [$sms]);

                            try 
                            {
                                $response = $api->sendSmsMessage($request);
                            } 
                            catch (Exception $e) 
                            {
                                echo json_encode(['success' => false, 'message' => 'Failed to send SMS: ' . $e->getMessage()]);
                            }
                        }
                    }
                }

                if (!empty($bsEItemID))
                {
                    $updateBsExcessQuery = "UPDATE inventory_excess SET exc_foot = $bsFoot WHERE exc_id = $bsEItemID";
                    mysqli_query($conn, $updateBsExcessQuery);
                        
                    if ($bsFoot <= 0) 
                    {
                        $deleteBsExcessQuery = "DELETE FROM inventory_excess WHERE exc_id = $bsEItemID";
                        mysqli_query($conn, $deleteBsExcessQuery);
                    }
                }
                elseif (!empty($bsItemID))
                {
                    if (strpos($bottomSill, '|') !== false) {
                        $pipePos = strpos($bottomSill, '|');
                        $bottomSill = substr($bottomSill, 0, $pipePos);
                    }

                    if ($bsFoot > 0) 
                    {
                        $insertBsExcessQuery = "INSERT INTO inventory_excess (item_name, exc_foot, dimension, color) VALUES ('$bottomSill', $bsFoot, '$bsDimension', '$bsColor')";
                        mysqli_query($conn, $insertBsExcessQuery);
                    }

                    $newBsStock = $bsStock - 1;
                    $updateBsInventoryQuery = "UPDATE inventory SET stock = $newBsStock WHERE item_id = $bsItemID";
                    mysqli_query($conn, $updateBsInventoryQuery);

                    // Insert into inv_mon table
                    $insertItemQuery = "INSERT INTO inv_mon (item_id, date_created) VALUES ('$bsItemID', NOW())";
                    mysqli_query($conn, $insertItemQuery);

                    if ($newBsStock == 5) {
                        // Fetch the supplier's phone number
                        $getSupplierQuery = "SELECT s.phone_number FROM supplier s INNER JOIN inventory i ON s.supplier_id = i.supplier_id WHERE i.item_id = $bsItemID LIMIT 1";
                        $supplierResult = mysqli_query($conn, $getSupplierQuery);

                        if (mysqli_num_rows($supplierResult) > 0) {
                            $supplier = mysqli_fetch_assoc($supplierResult);
                            $supplierPhone = '+63' . substr($supplier['phone_number'], -10);  // Format for international phone number

                            // Prepare the message to send
                            $message = "Good day this is Calauan Glass and Aluminum Supply and Services. I need 20 pieces of $bottomSill in dimension $bsDimension and color $bsColor. Thank you and God Bless!";

                            // Initialize SMS API configuration and send message
                            $config = new Configuration(host: $apiURL, apiKey: $apiKEY);
                            $api = new SmsApi(config: $config);

                            $destination = new SmsDestination(to: $supplierPhone);
                            $sms = new SmsTextualMessage(
                                destinations: [$destination],
                                text: $message,
                                from: "CGAS"
                            );

                            $request = new SmsAdvancedTextualRequest(messages: [$sms]);

                            try 
                            {
                                $response = $api->sendSmsMessage($request);
                            } 
                            catch (Exception $e) 
                            {
                                echo json_encode(['success' => false, 'message' => 'Failed to send SMS: ' . $e->getMessage()]);
                            }
                        }
                    }
                }

                // Handle excess item for SIDE JAMB
                if (!empty($sjEItemID)) 
                {
                    $updateSJExcessQuery = "UPDATE inventory_excess SET exc_foot = $sjFoots WHERE exc_id = $sjEItemID";
                    mysqli_query($conn, $updateSJExcessQuery);

                    if ($sjFoots <= 0) 
                    {
                        $deleteSJExcessQuery = "DELETE FROM inventory_excess WHERE exc_id = $sjEItemID";
                        mysqli_query($conn, $deleteSJExcessQuery);
                    }
                }
                elseif (!empty($sjItemID)) 
                {
                    if (strpos($sideJamb, '|') !== false) {
                        $pipePos = strpos($sideJamb, '|');
                        $sideJamb = substr($sideJamb, 0, $pipePos);
                    }

                    if ($sjFoots1 > 0) 
                    {
                        $insertSJExcessQuery = "INSERT INTO inventory_excess (item_name, exc_foot, dimension, color) VALUES ('$sideJamb', $sjFoots1, '$sjDimension', '$sjColor')";
                        mysqli_query($conn, $insertSJExcessQuery);
                    }

                    $newSjStock = $sjStock - 1;
                    $updateSJInventoryQuery = "UPDATE inventory SET stock = $newSjStock WHERE item_id = $sjItemID";
                    mysqli_query($conn, $updateSJInventoryQuery);

                    // Insert into inv_mon table
                    $insertItemQuery = "INSERT INTO inv_mon (item_id, date_created) VALUES ('$sjItemID', NOW())";
                    mysqli_query($conn, $insertItemQuery);

                    if ($newSjStock == 5) {
                        // Fetch the supplier's phone number
                        $getSupplierQuery = "SELECT s.phone_number FROM supplier s INNER JOIN inventory i ON s.supplier_id = i.supplier_id WHERE i.item_id = $sjItemID LIMIT 1";
                        $supplierResult = mysqli_query($conn, $getSupplierQuery);

                        if (mysqli_num_rows($supplierResult) > 0) {
                            $supplier = mysqli_fetch_assoc($supplierResult);
                            $supplierPhone = '+63' . substr($supplier['phone_number'], -10);  // Format for international phone number

                            // Prepare the message to send
                            $message = "Good day this is Calauan Glass and Aluminum Supply and Services. I need 20 pieces of $sideJamb in dimension $sjDimension and color $sjColor. Thank you and God Bless!";

                            // Initialize SMS API configuration and send message
                            $config = new Configuration(host: $apiURL, apiKey: $apiKEY);
                            $api = new SmsApi(config: $config);

                            $destination = new SmsDestination(to: $supplierPhone);
                            $sms = new SmsTextualMessage(
                                destinations: [$destination],
                                text: $message,
                                from: "CGAS"
                            );

                            $request = new SmsAdvancedTextualRequest(messages: [$sms]);

                            try 
                            {
                                $response = $api->sendSmsMessage($request);
                            } 
                            catch (Exception $e) 
                            {
                                echo json_encode(['success' => false, 'message' => 'Failed to send SMS: ' . $e->getMessage()]);
                            }
                        }
                    }
                }

                // Handle excess for UEI and UNI
                if (!empty($sjUEI) && !empty($sjUNI)) 
                {
                    $sjFoo = $sjFootExcess - $sjHFoot; // For excess
                    $sjFoo1 = $sjFoot - $sjHFoot; // For new item

                    if (!empty($sjUEI)) 
                    {
                        if ($sjFoo > 0) 
                        {
                            $updateSJExcessQuery = "UPDATE inventory_excess SET exc_foot = $sjFoo WHERE exc_id = $sjUEI";
                            mysqli_query($conn, $updateSJExcessQuery);
                        } elseif ($sjFoo <= 0) {
                            $deleteSJExcessQuery = "DELETE FROM inventory_excess WHERE exc_id = $sjUEI";
                            mysqli_query($conn, $deleteSJExcessQuery);
                        }
                    }

                    if (!empty($sjUNI)) 
                    {
                        if (strpos($sideJamb, '|') !== false) {
                            $pipePos = strpos($sideJamb, '|');
                            $sideJamb = substr($sideJamb, 0, $pipePos);
                        }

                        if ($sjFoo1 > 0) 
                        {
                            $insertSJExcessQuery = "INSERT INTO inventory_excess (item_name, exc_foot, dimension, color) VALUES ('$sideJamb', $sjFoo1, '$sjDimension', '$sjColor')";
                            mysqli_query($conn, $insertSJExcessQuery);
                        }

                        $newSjStock = $sjStock - 1;
                        $updateSJInventoryQuery = "UPDATE inventory SET stock = $newSjStock WHERE item_id = $sjUNI";
                        mysqli_query($conn, $updateSJInventoryQuery);

                        // Insert into inv_mon table
                        $insertItemQuery = "INSERT INTO inv_mon (item_id, date_created) VALUES ('$sjUNI', NOW())";
                        mysqli_query($conn, $insertItemQuery);

                        if ($newSjStock == 5) {
                            // Fetch the supplier's phone number
                            $getSupplierQuery = "SELECT s.phone_number FROM supplier s INNER JOIN inventory i ON s.supplier_id = i.supplier_id WHERE i.item_id = $sjItemID LIMIT 1";
                            $supplierResult = mysqli_query($conn, $getSupplierQuery);
        
                            if (mysqli_num_rows($supplierResult) > 0) {
                                $supplier = mysqli_fetch_assoc($supplierResult);
                                $supplierPhone = '+63' . substr($supplier['phone_number'], -10);  // Format for international phone number
        
                                // Prepare the message to send
                                $message = "Good day this is Calauan Glass and Aluminum Supply and Services. I need 20 pieces of $sideJamb in dimension $sjDimension and color $sjColor. Thank you and God Bless!";
        
                                // Initialize SMS API configuration and send message
                                $config = new Configuration(host: $apiURL, apiKey: $apiKEY);
                                $api = new SmsApi(config: $config);
        
                                $destination = new SmsDestination(to: $supplierPhone);
                                $sms = new SmsTextualMessage(
                                    destinations: [$destination],
                                    text: $message,
                                    from: "CGAS"
                                );
        
                                $request = new SmsAdvancedTextualRequest(messages: [$sms]);
        
                                try 
                                {
                                    $response = $api->sendSmsMessage($request);
                                } 
                                catch (Exception $e) 
                                {
                                    echo json_encode(['success' => false, 'message' => 'Failed to send SMS: ' . $e->getMessage()]);
                                }
                            }
                        }
                    }
                }

                if (!empty($sjFEID) && !empty($sjFEID1)) 
                {
                    $sjFee = $sjFH - $sjHFoot;
                    $sjFee1 = $sjSH - $sjHFoot;

                    if (!empty($sjFEID)) 
                    {
                        if ($sjFee > 0) {
                            $updateSJExcessQuery = "UPDATE inventory_excess SET exc_foot = $sjFee WHERE exc_id = $sjFEID";
                            mysqli_query($conn, $updateSJExcessQuery);
                        } elseif ($sjFee <= 0) {
                            $deleteSJExcessQuery = "DELETE FROM inventory_excess WHERE exc_id = $sjFEID";
                            mysqli_query($conn, $deleteSJExcessQuery);
                        }
                    }

                    if (!empty($sjFEID1))
                    {
                        if ($sjFee1 > 0) 
                        {
                            $updateSJExcessQuery = "UPDATE inventory_excess SET exc_foot = $sjFee1 WHERE exc_id = $sjFEID1";
                            mysqli_query($conn, $updateSJExcessQuery);
                        } elseif ($sjFee1 <= 0) {
                            $deleteSJExcessQuery = "DELETE FROM inventory_excess WHERE exc_id = $sjFEID1";
                            mysqli_query($conn, $deleteSJExcessQuery);
                        }
                    }
                }
                echo 'Task Created';
            }
        }
    }

    if($action == 'taskCasement')
    {
        $projectID = mysqli_real_escape_string($conn, $_POST['projectID'] ?? '');
        $length = floatval(mysqli_real_escape_string($conn, $_POST['lengthCasement'] ?? ''));
        $height = floatval(mysqli_real_escape_string($conn, $_POST['heightCasement'] ?? ''));
        $lFoot = floatval(mysqli_real_escape_string($conn, $_POST['lFootCasement'] ?? ''));
        $lFoot2 = floatval(mysqli_real_escape_string($conn, $_POST['lFootX2Casement'] ?? ''));
        $hFoot = floatval(mysqli_real_escape_string($conn, $_POST['hFootCasement'] ?? ''));
        $hFoot2 = floatval(mysqli_real_escape_string($conn, $_POST['hFootX2Casement'] ?? ''));

        $topHead = mysqli_real_escape_string($conn, $_POST['HeadCasement'] ?? '');
        $thPrice = floatval(mysqli_real_escape_string($conn, $_POST['hPriceCasement'] ?? ''));
        $thFootExcess = floatval(mysqli_real_escape_string($conn, $_POST['hFootExcessCasement'] ?? ''));
        $thItemID = mysqli_real_escape_string($conn, $_POST['hItemIDCasement'] ?? '');
        $thEItemID = mysqli_real_escape_string($conn, $_POST['hEItemIDCasement'] ?? '');
        $thColor = mysqli_real_escape_string($conn, $_POST['hColorCasement'] ?? '');
        $thDimension = mysqli_real_escape_string($conn, $_POST['hDimensionCasement'] ?? '');
        $thStock = intval(mysqli_real_escape_string($conn, $_POST['hStockCasement'] ?? ''));

        $bottomSill = mysqli_real_escape_string($conn, $_POST['SillCasement'] ?? '');
        $bsPrice = floatval(mysqli_real_escape_string($conn, $_POST['sPriceCasement'] ?? ''));
        $bsFootExcess = floatval(mysqli_real_escape_string($conn, $_POST['sFootExcessCasement'] ?? ''));
        $bsItemID = mysqli_real_escape_string($conn, $_POST['sItemIDCasement'] ?? '');
        $bsEItemID = mysqli_real_escape_string($conn, $_POST['sEItemIDCasement'] ?? '');
        $bsColor = mysqli_real_escape_string($conn, $_POST['sColorCasement'] ?? '');
        $bsDimension = mysqli_real_escape_string($conn, $_POST['sDimensionCasement'] ?? '');
        $bsStock = intval(mysqli_real_escape_string($conn, $_POST['sStockCasement'] ?? ''));

        $sideJamb = mysqli_real_escape_string($conn, $_POST['JambCasement'] ?? '');
        $sjPrice = floatval(mysqli_real_escape_string($conn, $_POST['sjPriceCasement'] ?? ''));
        $sjFootExcess = floatval(mysqli_real_escape_string($conn, $_POST['sjFootExcessCasement'] ?? ''));
        $sjFoot = floatval(mysqli_real_escape_string($conn, $_POST['sjFootCasement'] ?? ''));
        $sjItemID = mysqli_real_escape_string($conn, $_POST['sjItemIDCasement'] ?? '');
        $sjEItemID = mysqli_real_escape_string($conn, $_POST['sjEItemIDCasement'] ?? '');
        $sjUEI = mysqli_real_escape_string($conn, $_POST['sjUEICasement'] ?? '');
        $sjUNI = mysqli_real_escape_string($conn, $_POST['sjUNICasement'] ?? '');
        $sjFEID = mysqli_real_escape_string($conn, $_POST['sjFEIDCasement'] ?? '');
        $sjFEID1 = mysqli_real_escape_string($conn, $_POST['sjFEID1Casement'] ?? '');
        $sjFH = mysqli_real_escape_string($conn, $_POST['sjFHCasement'] ?? '');
        $sjSH = mysqli_real_escape_string($conn, $_POST['sjSHCasement'] ?? '');
        $sjColor = mysqli_real_escape_string($conn, $_POST['sjColorCasement'] ?? '');
        $sjDimension = mysqli_real_escape_string($conn, $_POST['sjDimensionCasement'] ?? '');
        $sjStock = intval(mysqli_real_escape_string($conn, $_POST['sjStockCasement'] ?? ''));
        $sjHFoot = intval(mysqli_real_escape_string($conn, $_POST['sjHFootCasement'] ?? ''));

        $glass = mysqli_real_escape_string($conn, $_POST['GlassCasement'] ?? '');
        $gItemID = mysqli_real_escape_string($conn, $_POST['gItemIDCasement'] ?? '');
        $gPrice = floatval(mysqli_real_escape_string($conn, $_POST['gPriceCasement'] ?? ''));
        $gStock = intval(mysqli_real_escape_string($conn, $_POST['gStockCasement'] ?? ''));

        $thFoot = $thFootExcess - $lFoot;
        $bsFoot = $bsFootExcess - $lFoot;

        $sjFoots = $sjFootExcess - $hFoot2;
        $sjFoots1 = $sjFoot - $hFoot2;

        $items = "Head - $lFoot feet of $topHead, Sill - $lFoot feet of $bottomSill, Jamb - $hFoot2 feet of $sideJamb";

        $size = $lFoot * $hFoot;
        $sum = $thPrice + $bsPrice + $sjPrice + $gPrice;
        $totalPrice = $size * $sum;

        $query = "INSERT INTO task (description, length, height, status, items, total_price, project_id) VALUES ('Casement', $length, $height, 'NOT STARTED', '$items', $totalPrice, $projectID)";

        if (mysqli_query($conn, $query)) 
        {
            if ($_SERVER["REQUEST_METHOD"] == "POST") 
            {
                if (!empty($gItemID))
                {
                    $newGStock = $gStock - 1;
                    $updateThInventoryQuery = "UPDATE inventory SET stock = $newGStock WHERE item_name = '$glass'";
                    mysqli_query($conn, $updateThInventoryQuery);
                }

                if (!empty($thEItemID))
                {
                    $updateThExcessQuery = "UPDATE inventory_excess SET exc_foot = $thFoot WHERE exc_id = $thEItemID";
                    mysqli_query($conn, $updateThExcessQuery);
                        
                    if ($thFoot <= 0) 
                    {
                        $deleteThExcessQuery = "DELETE FROM inventory_excess WHERE exc_id = $thEItemID";
                        mysqli_query($conn, $deleteThExcessQuery);
                    }
                }
                elseif (!empty($thItemID)) 
                {
                    if (strpos($topHead, '|') !== false) {
                        $pipePos = strpos($topHead, '|');
                        $topHead = substr($topHead, 0, $pipePos);
                    }

                    if ($thFoot > 0) 
                    {
                        $insertThExcessQuery = "INSERT INTO inventory_excess (item_name, exc_foot, dimension, color) VALUES ('$topHead', $thFoot, '$thDimension', '$thColor')";
                        mysqli_query($conn, $insertThExcessQuery);
                    }

                    $newThStock = $thStock - 1;
                    $updateThInventoryQuery = "UPDATE inventory SET stock = $newThStock WHERE item_id = $thItemID";
                    mysqli_query($conn, $updateThInventoryQuery);

                    // Insert into inv_mon table
                    $insertItemQuery = "INSERT INTO inv_mon (item_id, date_created) VALUES ('$thItemID', NOW())";
                    mysqli_query($conn, $insertItemQuery);

                    // Check if the new stock is 5
                    if ($newThStock == 5) {
                        // Fetch the supplier's phone number
                        $getSupplierQuery = "SELECT s.phone_number FROM supplier s INNER JOIN inventory i ON s.supplier_id = i.supplier_id WHERE i.item_id = $thItemID LIMIT 1";
                        $supplierResult = mysqli_query($conn, $getSupplierQuery);

                        if (mysqli_num_rows($supplierResult) > 0) {
                            $supplier = mysqli_fetch_assoc($supplierResult);
                            $supplierPhone = '+63' . substr($supplier['phone_number'], -10);  // Format for international phone number

                            // Prepare the message to send
                            $message = "Good day this is Calauan Glass and Aluminum Supply and Services. I need 20 pieces of $topHead in dimension $thDimension and color $thColor. Thank you and God Bless!";

                            // Initialize SMS API configuration and send message
                            $config = new Configuration(host: $apiURL, apiKey: $apiKEY);
                            $api = new SmsApi(config: $config);

                            $destination = new SmsDestination(to: $supplierPhone);
                            $sms = new SmsTextualMessage(
                                destinations: [$destination],
                                text: $message,
                                from: "CGAS"
                            );

                            $request = new SmsAdvancedTextualRequest(messages: [$sms]);

                            try 
                            {
                                $response = $api->sendSmsMessage($request);
                            } 
                            catch (Exception $e) 
                            {
                                echo json_encode(['success' => false, 'message' => 'Failed to send SMS: ' . $e->getMessage()]);
                            }
                        }
                    }
                }

                if (!empty($bsEItemID))
                {
                    $updateBsExcessQuery = "UPDATE inventory_excess SET exc_foot = $bsFoot WHERE exc_id = $bsEItemID";
                    mysqli_query($conn, $updateBsExcessQuery);
                        
                    if ($bsFoot <= 0) 
                    {
                        $deleteBsExcessQuery = "DELETE FROM inventory_excess WHERE exc_id = $bsEItemID";
                        mysqli_query($conn, $deleteBsExcessQuery);
                    }
                }
                elseif (!empty($bsItemID))
                {
                    if (strpos($bottomSill, '|') !== false) {
                        $pipePos = strpos($bottomSill, '|');
                        $bottomSill = substr($bottomSill, 0, $pipePos);
                    }

                    if ($bsFoot > 0) 
                    {
                        $insertBsExcessQuery = "INSERT INTO inventory_excess (item_name, exc_foot, dimension, color) VALUES ('$bottomSill', $bsFoot, '$bsDimension', '$bsColor')";
                        mysqli_query($conn, $insertBsExcessQuery);
                    }

                    $newBsStock = $bsStock - 1;
                    $updateBsInventoryQuery = "UPDATE inventory SET stock = $newBsStock WHERE item_id = $bsItemID";
                    mysqli_query($conn, $updateBsInventoryQuery);

                    // Insert into inv_mon table
                    $insertItemQuery = "INSERT INTO inv_mon (item_id, date_created) VALUES ('$bsItemID', NOW())";
                    mysqli_query($conn, $insertItemQuery);

                    if ($newBsStock == 5) {
                        // Fetch the supplier's phone number
                        $getSupplierQuery = "SELECT s.phone_number FROM supplier s INNER JOIN inventory i ON s.supplier_id = i.supplier_id WHERE i.item_id = $bsItemID LIMIT 1";
                        $supplierResult = mysqli_query($conn, $getSupplierQuery);

                        if (mysqli_num_rows($supplierResult) > 0) {
                            $supplier = mysqli_fetch_assoc($supplierResult);
                            $supplierPhone = '+63' . substr($supplier['phone_number'], -10);  // Format for international phone number

                            // Prepare the message to send
                            $message = "Good day this is Calauan Glass and Aluminum Supply and Services. I need 20 pieces of $bottomSill in dimension $bsDimension and color $bsColor. Thank you and God Bless!";

                            // Initialize SMS API configuration and send message
                            $config = new Configuration(host: $apiURL, apiKey: $apiKEY);
                            $api = new SmsApi(config: $config);

                            $destination = new SmsDestination(to: $supplierPhone);
                            $sms = new SmsTextualMessage(
                                destinations: [$destination],
                                text: $message,
                                from: "CGAS"
                            );

                            $request = new SmsAdvancedTextualRequest(messages: [$sms]);

                            try 
                            {
                                $response = $api->sendSmsMessage($request);
                            } 
                            catch (Exception $e) 
                            {
                                echo json_encode(['success' => false, 'message' => 'Failed to send SMS: ' . $e->getMessage()]);
                            }
                        }
                    }
                }

                // Handle excess item for SIDE JAMB
                if (!empty($sjEItemID)) 
                {
                    $updateSJExcessQuery = "UPDATE inventory_excess SET exc_foot = $sjFoots WHERE exc_id = $sjEItemID";
                    mysqli_query($conn, $updateSJExcessQuery);

                    if ($sjFoots <= 0) 
                    {
                        $deleteSJExcessQuery = "DELETE FROM inventory_excess WHERE exc_id = $sjEItemID";
                        mysqli_query($conn, $deleteSJExcessQuery);
                    }
                }
                elseif (!empty($sjItemID)) 
                {
                    if (strpos($sideJamb, '|') !== false) {
                        $pipePos = strpos($sideJamb, '|');
                        $sideJamb = substr($sideJamb, 0, $pipePos);
                    }

                    if ($sjFoots1 > 0) 
                    {
                        $insertSJExcessQuery = "INSERT INTO inventory_excess (item_name, exc_foot, dimension, color) VALUES ('$sideJamb', $sjFoots1, '$sjDimension', '$sjColor')";
                        mysqli_query($conn, $insertSJExcessQuery);
                    }

                    $newSjStock = $sjStock - 1;
                    $updateSJInventoryQuery = "UPDATE inventory SET stock = $newSjStock WHERE item_id = $sjItemID";
                    mysqli_query($conn, $updateSJInventoryQuery);

                    // Insert into inv_mon table
                    $insertItemQuery = "INSERT INTO inv_mon (item_id, date_created) VALUES ('$sjItemID', NOW())";
                    mysqli_query($conn, $insertItemQuery);

                    if ($newSjStock == 5) {
                        // Fetch the supplier's phone number
                        $getSupplierQuery = "SELECT s.phone_number FROM supplier s INNER JOIN inventory i ON s.supplier_id = i.supplier_id WHERE i.item_id = $sjItemID LIMIT 1";
                        $supplierResult = mysqli_query($conn, $getSupplierQuery);

                        if (mysqli_num_rows($supplierResult) > 0) {
                            $supplier = mysqli_fetch_assoc($supplierResult);
                            $supplierPhone = '+63' . substr($supplier['phone_number'], -10);  // Format for international phone number

                            // Prepare the message to send
                            $message = "Good day this is Calauan Glass and Aluminum Supply and Services. I need 20 pieces of $sideJamb in dimension $sjDimension and color $sjColor. Thank you and God Bless!";

                            // Initialize SMS API configuration and send message
                            $config = new Configuration(host: $apiURL, apiKey: $apiKEY);
                            $api = new SmsApi(config: $config);

                            $destination = new SmsDestination(to: $supplierPhone);
                            $sms = new SmsTextualMessage(
                                destinations: [$destination],
                                text: $message,
                                from: "CGAS"
                            );

                            $request = new SmsAdvancedTextualRequest(messages: [$sms]);

                            try 
                            {
                                $response = $api->sendSmsMessage($request);
                            } 
                            catch (Exception $e) 
                            {
                                echo json_encode(['success' => false, 'message' => 'Failed to send SMS: ' . $e->getMessage()]);
                            }
                        }
                    }
                }

                // Handle excess for UEI and UNI
                if (!empty($sjUEI) && !empty($sjUNI)) 
                {
                    $sjFoo = $sjFootExcess - $sjHFoot; // For excess
                    $sjFoo1 = $sjFoot - $sjHFoot; // For new item

                    if (!empty($sjUEI)) 
                    {
                        if ($sjFoo > 0) 
                        {
                            $updateSJExcessQuery = "UPDATE inventory_excess SET exc_foot = $sjFoo WHERE exc_id = $sjUEI";
                            mysqli_query($conn, $updateSJExcessQuery);
                        } elseif ($sjFoo <= 0) {
                            $deleteSJExcessQuery = "DELETE FROM inventory_excess WHERE exc_id = $sjUEI";
                            mysqli_query($conn, $deleteSJExcessQuery);
                        }
                    }

                    if (!empty($sjUNI)) 
                    {
                        if (strpos($sideJamb, '|') !== false) {
                            $pipePos = strpos($sideJamb, '|');
                            $sideJamb = substr($sideJamb, 0, $pipePos);
                        }

                        if ($sjFoo1 > 0) 
                        {
                            $insertSJExcessQuery = "INSERT INTO inventory_excess (item_name, exc_foot, dimension, color) VALUES ('$sideJamb', $sjFoo1, '$sjDimension', '$sjColor')";
                            mysqli_query($conn, $insertSJExcessQuery);
                        }

                        $newSjStock = $sjStock - 1;
                        $updateSJInventoryQuery = "UPDATE inventory SET stock = $newSjStock WHERE item_id = $sjUNI";
                        mysqli_query($conn, $updateSJInventoryQuery);

                        // Insert into inv_mon table
                        $insertItemQuery = "INSERT INTO inv_mon (item_id, date_created) VALUES ('$sjUNI', NOW())";
                        mysqli_query($conn, $insertItemQuery);

                        if ($newSjStock == 5) {
                            // Fetch the supplier's phone number
                            $getSupplierQuery = "SELECT s.phone_number FROM supplier s INNER JOIN inventory i ON s.supplier_id = i.supplier_id WHERE i.item_id = $sjItemID LIMIT 1";
                            $supplierResult = mysqli_query($conn, $getSupplierQuery);
        
                            if (mysqli_num_rows($supplierResult) > 0) {
                                $supplier = mysqli_fetch_assoc($supplierResult);
                                $supplierPhone = '+63' . substr($supplier['phone_number'], -10);  // Format for international phone number
        
                                // Prepare the message to send
                                $message = "Good day this is Calauan Glass and Aluminum Supply and Services. I need 20 pieces of $sideJamb in dimension $sjDimension and color $sjColor. Thank you and God Bless!";
        
                                // Initialize SMS API configuration and send message
                                $config = new Configuration(host: $apiURL, apiKey: $apiKEY);
                                $api = new SmsApi(config: $config);
        
                                $destination = new SmsDestination(to: $supplierPhone);
                                $sms = new SmsTextualMessage(
                                    destinations: [$destination],
                                    text: $message,
                                    from: "CGAS"
                                );
        
                                $request = new SmsAdvancedTextualRequest(messages: [$sms]);
        
                                try 
                                {
                                    $response = $api->sendSmsMessage($request);
                                } 
                                catch (Exception $e) 
                                {
                                    echo json_encode(['success' => false, 'message' => 'Failed to send SMS: ' . $e->getMessage()]);
                                }
                            }
                        }
                    }
                }

                if (!empty($sjFEID) && !empty($sjFEID1)) 
                {
                    $sjFee = $sjFH - $sjHFoot;
                    $sjFee1 = $sjSH - $sjHFoot;

                    if (!empty($sjFEID)) 
                    {
                        if ($sjFee > 0) {
                            $updateSJExcessQuery = "UPDATE inventory_excess SET exc_foot = $sjFee WHERE exc_id = $sjFEID";
                            mysqli_query($conn, $updateSJExcessQuery);
                        } elseif ($sjFee <= 0) {
                            $deleteSJExcessQuery = "DELETE FROM inventory_excess WHERE exc_id = $sjFEID";
                            mysqli_query($conn, $deleteSJExcessQuery);
                        }
                    }

                    if (!empty($sjFEID1))
                    {
                        if ($sjFee1 > 0) 
                        {
                            $updateSJExcessQuery = "UPDATE inventory_excess SET exc_foot = $sjFee1 WHERE exc_id = $sjFEID1";
                            mysqli_query($conn, $updateSJExcessQuery);
                        } elseif ($sjFee1 <= 0) {
                            $deleteSJExcessQuery = "DELETE FROM inventory_excess WHERE exc_id = $sjFEID1";
                            mysqli_query($conn, $deleteSJExcessQuery);
                        }
                    }
                }
                echo 'Task Created';
            }
        }
    }




    if ($action == 'sentNeededMessage') 
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST") 
        {
            $supplierId = $_POST['supplier_id'];
            $message = $_POST['message'];

            $query = "SELECT phone_number FROM supplier WHERE supplier_id = '$supplierId'";

            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) 
            {
                $data = mysqli_fetch_assoc($result);
                $customerPhone = '+63' . substr($data['phone_number'], -10);

                $config = new Configuration(host: $apiURL, apiKey: $apiKEY);
                $api = new SmsApi(config: $config);

                $destination = new SmsDestination(to: $customerPhone);
                $sms = new SmsTextualMessage(
                    destinations: [$destination],
                    text: $message,
                    from: "CGAS"
                );

                $request = new SmsAdvancedTextualRequest(messages: [$sms]);

                try 
                {
                    $response = $api->sendSmsMessage($request);
                    echo "Message sent successfully.";
                } 
                catch (Exception $e) 
                {
                    echo json_encode(['success' => false, 'message' => 'Failed to send SMS: ' . $e->getMessage()]);
                }
            } 
        }
    }

    if ($action == 'notifyCustomer') 
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST") 
        {
            $projectID = mysqli_real_escape_string($conn, $_POST['project_id'] ?? '');
            $employeeID = mysqli_real_escape_string($conn, $_POST['employee_id'] ?? '');
            $customerID = mysqli_real_escape_string($conn, $_POST['customer_id'] ?? '');

            $queryPhone = "SELECT phone_number FROM user WHERE user_id = '$customerID'";
            $phoneResult = mysqli_query($conn, $queryPhone);
            
            if ($phoneResult && mysqli_num_rows($phoneResult) > 0) 
            {
                $phoneRow = mysqli_fetch_assoc($phoneResult);
                $customerPhone = '+63' . substr($phoneRow['phone_number'], -10);
            }

            $queryProject = "SELECT project_name FROM project WHERE project_id = '$projectID'";
            $projectResult = mysqli_query($conn, $queryProject);
            
            if ($projectResult && mysqli_num_rows($projectResult) > 0) 
            {
                $projectRow = mysqli_fetch_assoc($projectResult);
                $projectName = $projectRow['project_name'];
            }

            if ($customerPhone && $projectName) 
            {
                $message = "Dear customer your order is being prepared for delivery.";

                $queryInsertMessage = "INSERT INTO message (`message`, `from`, `to`, `project_id`, `sent_date`, `is_read`) 
                                    VALUES ('$message', '$employeeID', '$customerID', '$projectID', NOW(), 0)";
                
                if (mysqli_query($conn, $queryInsertMessage)) 
                {
                    $config = new Configuration(host: $apiURL, apiKey: $apiKEY);
                    $api = new SmsApi(config: $config);

                    $destination = new SmsDestination(to: $customerPhone);
                    $sms = new SmsTextualMessage(
                        destinations: [$destination],
                        text: $message,
                        from: "CGAS"
                    );

                    $request = new SmsAdvancedTextualRequest(messages: [$sms]);

                    try {
                        $response = $api->sendSmsMessage($request);
                        echo "Notification has been sent.";
                    } 
                    catch (Exception $e) {
                        echo json_encode(['success' => false, 'message' => 'Failed to send SMS: ' . $e->getMessage()]);
                    }
                }
            } 
        }
    }

    if ($action == 'status') 
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST") 
        {
            $statusName = strtoupper($_POST["statusName"]);
            $statusDescription = $_POST["statusDescription"];
            $statusColor = $_POST["statusColor"];
    
            // Check if status already exists
            $checkStatusQuery = "SELECT * FROM status WHERE status_name = '$statusName'";
            $result = mysqli_query($conn, $checkStatusQuery);
    
            if (mysqli_num_rows($result) > 0) {
                // Status already exists
                echo "Status with name '$statusName' already exists!";
            } else {
                // Status does not exist, proceed with insertion
                $insertStatus = "INSERT INTO status (status_name, description, color) VALUES ('$statusName', '$statusDescription', '$statusColor')";
    
                if (mysqli_query($conn, $insertStatus)) {
                    echo "Status created!";
                }
            }
        }
    }

    if($action == 'logout') 
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $userId = $_POST['userId'];
    
            $insertQuery = "INSERT INTO log (description, date, time, user_id) VALUES ('Logout', CURDATE(), CURTIME(), '$userId')";
            
            if (mysqli_query($conn, $insertQuery)) {
                echo "Logout successfully";
            }
        }   
    }

    if($action == 'dashboard-link') 
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $userId = $_POST['userId'];
    
            $insertQuery = "INSERT INTO log (description, date, time, user_id) VALUES ('Viewed dashboard', CURDATE(), CURTIME(), '$userId')";
            
            if (mysqli_query($conn, $insertQuery)) {
                echo "Logout successfully";
            }
        }   
    }

    if($action == 'project-link') 
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $userId = $_POST['userId'];
    
            $insertQuery = "INSERT INTO log (description, date, time, user_id) VALUES ('Viewed project', CURDATE(), CURTIME(), '$userId')";
            
            if (mysqli_query($conn, $insertQuery)) {
                echo "Logout successfully";
            }
        }   
    }

    if($action == 'inventory-link') 
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $userId = $_POST['userId'];
    
            $insertQuery = "INSERT INTO log (description, date, time, user_id) VALUES ('Viewed inventory', CURDATE(), CURTIME(), '$userId')";
            
            if (mysqli_query($conn, $insertQuery)) {
                echo "Logout successfully";
            }
        }   
    }

    if($action == 'customer-link') 
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $userId = $_POST['userId'];
    
            $insertQuery = "INSERT INTO log (description, date, time, user_id) VALUES ('Viewed customer', CURDATE(), CURTIME(), '$userId')";
            
            if (mysqli_query($conn, $insertQuery)) {
                echo "Logout successfully";
            }
        }   
    }

    if($action == 'supplier-link') 
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $userId = $_POST['userId'];
    
            $insertQuery = "INSERT INTO log (description, date, time, user_id) VALUES ('Viewed supplier', CURDATE(), CURTIME(), '$userId')";
            
            if (mysqli_query($conn, $insertQuery)) {
                echo "Logout successfully";
            }
        }   
    }

    if($action == 'employee-link') 
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $userId = $_POST['userId'];
    
            $insertQuery = "INSERT INTO log (description, date, time, user_id) VALUES ('Viewed employee', CURDATE(), CURTIME(), '$userId')";
            
            if (mysqli_query($conn, $insertQuery)) {
                echo "Logout successfully";
            }
        }   
    }

    if($action == 'archive-link') 
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $userId = $_POST['userId'];
    
            $insertQuery = "INSERT INTO log (description, date, time, user_id) VALUES ('Viewed archive', CURDATE(), CURTIME(), '$userId')";
            
            if (mysqli_query($conn, $insertQuery)) {
                echo "Logout successfully";
            }
        }   
    }

    if($action == 'profile-link') 
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $userId = $_POST['userId'];
    
            $insertQuery = "INSERT INTO log (description, date, time, user_id) VALUES ('Viewed manage account', CURDATE(), CURTIME(), '$userId')";
            
            if (mysqli_query($conn, $insertQuery)) {
                echo "Logout successfully";
            }
        }   
    }

    if($action == 'user-dashboard-link') 
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $userId = $_POST['userId'];
    
            $insertQuery = "INSERT INTO log (description, date, time, user_id) VALUES ('Viewed dashboard', CURDATE(), CURTIME(), '$userId')";
            
            if (mysqli_query($conn, $insertQuery)) {
                echo "Logout successfully";
            }
        }   
    }

    if($action == 'user-project-link') 
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $userId = $_POST['userId'];
    
            $insertQuery = "INSERT INTO log (description, date, time, user_id) VALUES ('Viewed project', CURDATE(), CURTIME(), '$userId')";
            
            if (mysqli_query($conn, $insertQuery)) {
                echo "Logout successfully";
            }
        }   
    }

    if($action == 'user-profile-link') 
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $userId = $_POST['userId'];
    
            $insertQuery = "INSERT INTO log (description, date, time, user_id) VALUES ('Viewed manage account', CURDATE(), CURTIME(), '$userId')";
            
            if (mysqli_query($conn, $insertQuery)) {
                echo "Logout successfully";
            }
        }   
    }

    if($action == 'customer-purchase-link') 
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $userId = $_POST['userId'];
    
            $insertQuery = "INSERT INTO log (description, date, time, user_id) VALUES ('Viewed payment', CURDATE(), CURTIME(), '$userId')";
            
            if (mysqli_query($conn, $insertQuery)) {
                echo "Logout successfully";
            }
        }   
    }

    if($action == 'customer-profile-link') 
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $userId = $_POST['userId'];
    
            $insertQuery = "INSERT INTO log (description, date, time, user_id) VALUES ('Viewed manage account', CURDATE(), CURTIME(), '$userId')";
            
            if (mysqli_query($conn, $insertQuery)) {
                echo "Logout successfully";
            }
        }   
    }
?>
