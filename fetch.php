<?php 
    
    include 'database.php';
    header('Content-Type: application/json');
    $action = isset($_GET['action']) ? $_GET['action'] : '';

    if($action == 'project')
    {
        $projectId = $_POST['projectId'];

        $query = "SELECT project.*, customer.*, employee.*
        FROM project
        INNER JOIN customer ON project.customer_id = customer.customer_id
        LEFT JOIN employee ON project.employee_id = employee.user_id
        WHERE project.project_id = '$projectId' ";

        $result = mysqli_query($conn, $query);

        if ($result) {
            $data = mysqli_fetch_assoc($result);

            echo json_encode($data);
        }
    }

    if($action == 'inventory')
    {
        $itemId = $_POST['itemId'];

        $query = "SELECT inventory.*, supplier.*
        FROM inventory
        INNER JOIN supplier ON inventory.supplier_id = supplier.supplier_id
        WHERE item_id = $itemId";

        $result = mysqli_query($conn, $query);

        if($result)
        {
            $data = mysqli_fetch_assoc($result);

            echo json_encode($data);
        }
    }

    if($action == 'supplier')
    {
        $supplierId = $_POST['supplierId'];

        $query = "SELECT * FROM supplier
        WHERE supplier_id = $supplierId";

        $result = mysqli_query($conn, $query);

        if($result){
            $data = mysqli_fetch_assoc($result);

            echo json_encode($data);
        }
    }

    if($action == 'employee')
    {
        $employeeId = $_POST['employeeId'];

        $query = "SELECT * FROM user
        WHERE user_id = $employeeId";

        $result = mysqli_query($conn, $query);

        if($result)
        {
            $data = mysqli_fetch_assoc($result);

            echo json_encode($data);
        }
    }

    if($action == 'usageInventory')
    {
        $selectedYear = isset($_POST['year']) ? $_POST['year'] : date('Y');

        $query = "
            SELECT inv.item_name, inv.color, MONTH(im.date_created) AS month, COUNT(im.item_id) AS total_count
            FROM inv_mon im
            INNER JOIN inventory inv ON inv.item_id = im.item_id
            WHERE YEAR(im.date_created) = '$selectedYear'
            GROUP BY inv.item_name, inv.color, month
            ORDER BY inv.item_name, month
            LIMIT 5";
        $result = mysqli_query($conn, $query);

        $itemNames = [];
        $yearData = [];
        $itemColors = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $itemNames[] = $row['item_name'];
            $itemColors[$row['item_name']] = $row['color'];
            $yearData[$row['item_name']][$row['month']] = $row['total_count'];
        }

        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $yearCounts = [];

        foreach ($itemNames as $itemName) {
            $yearCounts[$itemName] = [];
            for ($month = 1; $month <= 12; $month++) {
                $yearCounts[$itemName][] = $yearData[$itemName][$month] ?? 0;
            }
        }

        echo json_encode([
            'labels' => array_values(array_unique($itemNames)),
            'data' => $yearCounts,
            'months' => $months,
            'colors' => $itemColors
        ]);
    }
?>