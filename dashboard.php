<?php

    $page = 'dashboard';

    include 'database.php';
    
    include "auth_middleware.php";
    checkAuth();
    checkRole('admin');
    
    if(!isset($_SESSION['admin_name'])){
       header('location:login.php');
    }

    $adminID = $_SESSION['adminID'];

    $result = mysqli_query($conn, "SELECT YEAR(date_created) AS year, COUNT(*) AS total_projects, SUM(CASE WHEN LOWER(status) = 'completed' THEN 1 ELSE 0 END) AS completed_projects 
    FROM project WHERE active = '1' 
    GROUP BY YEAR(date_created) 
    ORDER BY year;");

    $completedProjectData = [];
    $maxYear = 0;
    if (mysqli_num_rows($result) > 0) {
        while ($data = mysqli_fetch_assoc($result)) {
            $completedProjectData[] = [
                'year' => $data['year'],
                'count' => $data['completed_projects']
            ];
            if ($data['year'] > $maxYear) {
                $maxYear = $data['year'];
            }
        }
    }

    $salesResult = mysqli_query($conn, "SELECT YEAR(p.date_created) AS year, SUM(t.total_price) AS total_sales
        FROM project p
        JOIN task t ON p.project_id = t.project_id
        WHERE p.status = 'Completed'
        GROUP BY YEAR(p.date_created)
        ORDER BY YEAR(p.date_created) ASC
    ");

    $years = [];
    $totalSales = [];
    $maxYear = 0;

    // Process the result set and find the maximum year
    if (mysqli_num_rows($salesResult) > 0) {
        while ($row = mysqli_fetch_assoc($salesResult)) {
            $years[] = $row['year'];
            $totalSales[] = $row['total_sales'];

            // Update maxYear if the current row's year is greater
            if ($row['year'] > $maxYear) {
                $maxYear = $row['year'];
            }
        }
    }

    // Ensure the current year is included in the years array
    $currentYear = date("Y");
    if (!in_array($currentYear, $years)) {
        $years[] = $currentYear;
        $totalSales[] = 0; // No sales for the current year
    }

    $userResult = mysqli_query($conn, "SELECT * FROM user WHERE user_id = $adminID");
    if (mysqli_num_rows($userResult) > 0) {
        $userData = mysqli_fetch_array($userResult);
    }

    // Fetch max and min year from inv_mon table
$query = "SELECT MAX(YEAR(date_created)) AS maxYear, MIN(YEAR(date_created)) AS minYear FROM inv_mon";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

$maxYear = $row['maxYear'] ?? date('Y'); // Use current year if maxYear is null
$minYear = $row['minYear'] ?? ($maxYear - 5); // Default to maxYear - 5 if minYear is null

// Ensure at least a 5-year range
if ($maxYear == date('Y')) {
    $minYear = $maxYear - 5;
} else {
    $minYear = max($minYear, $maxYear - 5);
}

// Generate year range
$yearRange = range($minYear, $maxYear);

// Fetch data for default year (maxYear)
$defaultYear = $maxYear;
$query = "SELECT MONTH(date_created) AS month, COUNT(item_id) AS total_usage 
          FROM inv_mon 
          WHERE YEAR(date_created) = $defaultYear 
          GROUP BY MONTH(date_created)";
$result = mysqli_query($conn, $query);

$chartData = [];
while ($row = mysqli_fetch_assoc($result)) {
    $chartData[] = $row;
}

    $defaultImage = '../images/default.jpg';
?>

<?php
    // Get the max and min year from database
    $yearQuery = "SELECT MIN(YEAR(date_created)) AS minYear, MAX(YEAR(date_created)) AS maxYear FROM inv_mon";
    $yearResult = mysqli_query($conn, $yearQuery);
    $yearRow = mysqli_fetch_assoc($yearResult);

    $minYear = $yearRow['minYear'] ?? date('Y');
    $maxYear = $yearRow['maxYear'] ?? date('Y');

    // Get selected year or default to max year
    $selectedYear = isset($_GET['year']) ? $_GET['year'] : $maxYear;

    // Query for Selected Year Data
    $query = "
        SELECT inv.item_name, inv.color, MONTH(im.date_created) AS month, COUNT(im.item_id) AS total_count
        FROM inv_mon im
        INNER JOIN inventory inv ON inv.item_id = im.item_id
        WHERE YEAR(im.date_created) = '$selectedYear'
        GROUP BY inv.item_name, inv.color, month
        ORDER BY inv.item_name, month
        LIMIT 5";
    $result = mysqli_query($conn, $query);

    // Organize Data for Chart.js
    $itemNames = [];
    $yearData = [];
    $itemColors = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $itemNames[] = $row['item_name'];
        $itemColors[$row['item_name']] = $row['color'];
        $yearData[$row['item_name']][$row['month']] = $row['total_count'];
    }

    // Prepare Data for Chart.js
    $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    $yearCounts = [];

    foreach ($itemNames as $itemName) {
        $yearCounts[$itemName] = [];
        for ($month = 1; $month <= 12; $month++) {
            $yearCounts[$itemName][] = $yearData[$itemName][$month] ?? 0;
        }
    }

    $labelsJSON = json_encode($itemNames);
    $yearJSON = json_encode($yearCounts);
    $monthsJSON = json_encode($months);
    $itemColorsJSON = json_encode($itemColors);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'include/header.php' ?>

    <!-- Include FullCalendar CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css" />

    <!-- Include FullCalendar JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>

    <style>
        .datetime-display {
            font-size: 1.2rem;
            font-weight: bold;
            color: #007bff;
            padding: 10px;
            border: 1px solid #007bff;
            border-radius: 5px;
            background-color: #f8f9fa;
            text-align: center;
        }
        #timeDisplay {
            font-size: 1.5rem;
        }
        #dateDisplay {
            font-size: 1rem;
        }
    </style>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php include('include/sidebar.php'); ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content" class="bg-white">

                <!-- Topbar -->
                <?php include('include/topbar.php'); ?>
                <!-- End of Topbar -->

                <div class="container-fluid p-0">
                    
                    <div class="card border-0 mb-4" style="background-color: transparent;">
                        <div class="card-body pb-0">
                            <div class="col-lg-12">
                                <div class="card border-0 mb-4">
                                    <div class="card-body d-flex justify-content-end p-0">
                                        <div class="d-flex justify-content-end">
                                            <div id="dateTimeDisplay" class="datetime-display">
                                                <div id="timeDisplay"></div>
                                                <div id="dateDisplay"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mb-2 py-0">
    <div class="card border-0">
        <div class="card-body p-0">
            <div class="row">
                <?php 
                    $resTODO = mysqli_query($conn, "SELECT status.color, status.status_name, COUNT(project.project_id) AS project_count
                                                    FROM status
                                                    LEFT JOIN project ON status.status_name = project.status
                                                    WHERE status.status_name NOT IN ('Completed', 'Cancel')
                                                    GROUP BY status.color, status.status_name");

                    if ($resTODO) {
                        while ($row = mysqli_fetch_assoc($resTODO)) 
                        {
                            // Determine the icon based on the status
                            $icon = '';

                            if ($row['status_name'] === 'TO DO') 
                            {
                                $icon = '<i class="fa-solid fa-clipboard-list fa-2x text-gray-800"></i>';
                            } 
                            elseif($row['status_name'] === 'IN PROGRESS')
                            {
                                $icon = '<i class="fa-solid fa-arrows-spin fa-2x text-gray-800"></i>';
                            }
                            elseif($row['status_name'] === 'DELIVERED')
                            {
                                $icon = '<i class="fa-solid fa-truck-fast fa-2x text-gray-800"></i>';
                            }
                            elseif($row['status_name'] === 'INSTALLATION')
                            {
                                $icon = '<i class="fa-solid fa-screwdriver-wrench fa-2x text-gray-800"></i>';
                            }
                            else 
                            {
                                $icon = '<i class="fas fa-calendar fa-2x text-gray-800"></i>';
                            }
                            ?>
                                <!-- Earnings (Monthly) Card Example -->
                                <div class="col-xl-3 col-md-6 mb-4">
                                    <div class="card h-100 py-2" style="border-left: 5px solid <?php echo htmlspecialchars($row['color']); ?>;">
                                        <div class="card-body">
                                            <div class="row no-gutters align-items-center">
                                                <div class="col mr-2">
                                                    <div class="font-weight-bold text-dark text-uppercase mb-1">
                                                        <?php echo htmlspecialchars($row['status_name']); ?>
                                                    </div>
                                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                        <?php echo htmlspecialchars($row['project_count']); ?>
                                                    </div>
                                                </div>
                                                <div class="col-auto">
                                                    <?php echo $icon; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php
                        }
                    }
                ?>
            </div>
        </div>
    </div>
</div>

                            
<div class="row">
    <div class="col-lg-6 col-md-12 mb-2">
        <div class="card mb-4">
            <div class="card-body p-3">
                <div class="d-sm-flex align-items-center justify-content-between ps-2 pe-2">
                    <h1 class="h3 mb-0 text-dark fw-bold">Projects</h1>
                    <!-- Optional Year Filter Form, uncomment if needed
                    <form id="yearFilterForm" class="d-flex gap-2">
                        <input type="number" id="yearFrom" name="year_from" placeholder="Year From" class="form-control" style="width: 100px;">
                        <input type="number" id="yearTo" name="year_to" placeholder="Year To" class="form-control" style="width: 100px;">
                    </form>  
                    -->
                </div>
                <div class="card-body p-0" style="min-height: 300px;">
                    <canvas id="completedProjectsChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6 col-md-12 mb-2">
        <div class="card mb-4">
            <div class="card-body p-3">
                <div class="d-sm-flex align-items-center justify-content-between ps-2 pe-2">
                    <h1 class="h3 mb-0 text-dark fw-bold">Sales</h1>
                </div>
                <div class="card-body p-0" style="min-height: 300px;">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="d-flex justify-content-center">
    <div class="col-lg-7 col-md-12 mb-2">
        <div class="card mb-4" style="min-height: 100px;">
            <div class="card-body">
                <div class="d-sm-flex align-items-center justify-content-between ps-2 pe-2">
                    <h1 class="h3 mb-0 text-dark fw-bold">Inventory Usage</h1>
<!-- Year Selection Form -->
<form id="selectFormYear" class="d-flex gap-2">
    <select id="yearSelect" class="form-select">
        <?php foreach ($yearRange as $year) { ?>
            <option value="<?= $year; ?>" <?= ($year == $maxYear) ? 'selected' : ''; ?>><?= $year; ?></option>
        <?php } ?>
    </select>
</form>
                </div>
                <div class="card-body p-0" style="min-height: 100px;">
                    <canvas id="monthlyItemChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
                            <div class="col-lg-12 mb-4 px-0">
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <div class="table-responsive">

                                        <div class="d-flex flex-column flex-sm-row align-items-center justify-content-between mb-1">
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="fa-solid fa-triangle-exclamation fs-4 text-danger"></i>
                                                <h1 class="h3 mb-0 text-dark fw-bold">Low Stocks</h1>
                                            </div>
                                            <a href="inventory.php?filter=low_stock" 
                                            class="btn btn-danger p-1 mb-2 inventory-link mt-2 mt-sm-0" 
                                            style="text-decoration: none;" 
                                            data-user-id="<?php echo $adminID; ?>">View all stocks</a>
                                        </div>
                                            
                                            <table class="table table-bordered" id="InventoryTable" width="100%" cellspacing="0">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center d-none">#</th>
                                                        <th>Name</th>
                                                        <th>Dimension</th>
                                                        <th>Foot</th>
                                                        <th>Sqft</th>
                                                        <th>Color</th>
                                                        <th>Price</th>
                                                        <th>Stock</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        // Ensure to join with supplier table to get the supplier_name
                                                        $inventoryResult = mysqli_query($conn, "SELECT inventory.*, supplier.company_name FROM inventory 
                                                                                                JOIN supplier ON inventory.supplier_id = supplier.supplier_id 
                                                                                                WHERE inventory.active = '1' AND inventory.stock <= 10");

                                                        $counter = 1;

                                                        if(mysqli_num_rows($inventoryResult) > 0) {
                                                            while($itemData = mysqli_fetch_array($inventoryResult)) {
                                                                $stock = $itemData['stock'];
                                                                $supplierId = $itemData['supplier_id'];
                                                                $supplierName = $itemData['company_name'];
                                                    ?>
                                                                <tr>
                                                                    <td class="text-center d-none"><?php echo $counter++ ?></td>
                                                                    <td><?php echo $itemData['item_name'] ?></td>
                                                                    <td><?php echo $itemData['dimension'] ?></td>
                                                                    <td><?php echo $itemData['foot'] ?></td>
                                                                    <td><?php echo $itemData['sqft'] ?></td>
                                                                    <td><?php echo $itemData['color'] ?></td>
                                                                    <td><?php echo $itemData['price'] ?></td>
                                                                    <td><?php echo $stock ?></td>
                                                                    <td class="text-center">
                                                                        <i class="fa-solid fa-paper-plane" style="cursor:pointer;" 
                                                                        data-bs-toggle="modal" 
                                                                        data-bs-target="#myModal" 
                                                                        data-supplier-id="<?php echo $supplierId; ?>" 
                                                                        data-supplier-name="<?php echo htmlspecialchars($supplierName, ENT_QUOTES, 'UTF-8'); ?>" 
                                                                        data-item-id="<?php echo $itemData['item_id']; ?>" 
                                                                        data-item-name="<?php echo htmlspecialchars($itemData['item_name'], ENT_QUOTES, 'UTF-8'); ?>"
                                                                        data-item-dimension="<?php echo htmlspecialchars($itemData['dimension'], ENT_QUOTES, 'UTF-8'); ?>"
                                                                        data-item-color="<?php echo htmlspecialchars($itemData['color'], ENT_QUOTES, 'UTF-8'); ?>"
                                                                        data-item-stock="<?php echo $itemData['stock']; ?>">
                                                                        </i>
                                                                    </td>
                                                                </tr>
                                                    <?php
                                                            }
                                                        } else {
                                                    ?>
                                                            <tr>
                                                                <td colspan="10" class="text-center">No data found</td>
                                                            </tr>
                                                    <?php
                                                        }
                                                    ?>
                                                </tbody>
                                            </table>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade animated--grow-in" id="myModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header d-flex align-items-center justify-content-between">
                                <strong class="text-dark">Order Item</strong>
                                <button id="closeModal" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="font-size: 12px;"></button>
                            </div>
                            <div class="modal-body" style="vertical-align: bottom;">
                                <form id="supplierForm">
                                    <div class="mb-3 fs-5">
                                        <p class="mb-1">
                                            Good day this is Calauan Glass and Aluminum Supply and Services. I need 
                                            <input type="text" class="form-control form-control-md d-inline text-center p-0" name="count" id="count" placeholder="Enter quantity" aria-label="Quantity" style="display: inline-block; width: 50px;">
                                            pieces of <strong id="itemName">[Item Name]</strong> in dimension 
                                            <strong id="itemDimension">[Dimension]</strong> and color 
                                            <strong id="itemColor">[Color]</strong>. Thank you and God Bless!
                                        </p>
                                        <input type="hidden" class="form-control" id="supplierIdInput" name="supplier_id" readonly>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-body text-right pt-0">
                                <button id="sentNeeded" type="submit" class="btn btn-success px-5">Send</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php include('include/footer.php'); ?>
            <!-- End of Footer -->
        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->


</body>
</html>

<script>
    var ctx = document.getElementById('monthlyItemChart').getContext('2d');
    var monthlyItemChart;

    function updateChart(year) {
        $.ajax({
            url: 'fetch.php?action=usageInventory',
            type: 'POST',
            data: { year: year },
            dataType: 'json',
            success: function(response) {
                let labels = response.labels;
                let months = response.months;
                let data = response.data;
                let itemColors = response.colors;

                let datasets = [];
                for (let item in data) {
                    datasets.push({
                        label: item + " (" + (itemColors[item] || "Unknown") + ")",
                        data: data[item],
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        borderColor: 'rgba(54, 162, 235)',
                        borderWidth: 1
                    });
                }

                if (monthlyItemChart) {
                    monthlyItemChart.destroy(); // Destroy previous chart instance
                }

                monthlyItemChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: months,
                        datasets: datasets
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { display: false },
                        },
                        scales: {
                            y: { 
                                beginAtZero: true, 
                                stepSize: 5,
                                max: 50
                            }
                        }
                    }
                });
            }
        });
    }

    $(document).ready(function() {
        let initialYear = $("#yearSelect").val();
        updateChart(initialYear); // Load initial data

        $("#yearSelect").change(function() {
            let selectedYear = $(this).val();
            updateChart(selectedYear);
        });
    });
</script>

<script>
    $(document).ready(function() 
    {
        $('#myModal').on('show.bs.modal', function (e) 
        {
            var button = $(e.relatedTarget);

            var supplierId = button.data('supplier-id');
            var supplierName = button.data('supplier-name');
            var itemName = button.data('item-name');
            var itemDimension = button.data('item-dimension');
            var itemColor = button.data('item-color');
            var itemStock = button.data('item-stock');

            var targetStock = 25;
            var neededPieces = targetStock - itemStock;

            if (neededPieces < 0) {
                neededPieces = 0;
            }

            var modal = $(this);
            modal.find('#supplierIdInput').val(supplierId);
            modal.find('#supplierName').text(supplierName);
            modal.find('#itemName').text(itemName);
            modal.find('#itemDimension').text(itemDimension);
            modal.find('#itemColor').text(itemColor);
            modal.find('#count').val(neededPieces);
        });

        $('#sentNeeded').on('click', function(e) {
            e.preventDefault();

            var supplierId = $('#supplierIdInput').val();
            var count = $('#count').val();
            var itemName = $('#itemName').text();
            var itemDimension = $('#itemDimension').text();
            var itemColor = $('#itemColor').text();

            var messageText = `Good day this is Calauan Glass and Aluminum Supply and Services. I need ${count} pieces of ${itemName} in dimension ${itemDimension} and color ${itemColor}. Thank you and God Bless!`;

            $.ajax({
                type: "POST",
                url: "add.php?action=sentNeededMessage",
                data: {
                    supplier_id: supplierId,
                    message: messageText
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        location.reload();
                    });
                }
            });
        });
    });

    $(document).ready(function () 
    {
        var ctx1 = $('#salesChart')[0].getContext('2d');

        var years = <?php echo json_encode($years); ?>;
        var totalSales = <?php echo json_encode($totalSales); ?>;

        var currentYear = new Date().getFullYear();
        var fiveYearsAgo = currentYear - 4;

        function filterSalesData(years, totalSales, yearFrom, yearTo) {
            var filteredYears = [];
            var filteredSales = [];

            for (var i = 0; i < years.length; i++) {
                if (years[i] >= yearFrom && years[i] <= yearTo) {
                    filteredYears.push(years[i]);
                    filteredSales.push(totalSales[i]);
                }
            }

            return {
                filteredYears: filteredYears,
                filteredSales: filteredSales
            };
        }

        var filteredData = filterSalesData(years, totalSales, fiveYearsAgo, currentYear);

        var salesChart = new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: filteredData.filteredYears,
                datasets: [{
                    label: 'Sales',
                    data: filteredData.filteredSales,
                    backgroundColor: 'rgba(54, 162, 235)',
                    borderColor: 'rgba(54, 162, 235)',
                    borderWidth: 1,
                    barThickness: 90
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        left: 10,
                        right: 25,
                        top: 25,
                        bottom: 0
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 10000,
                        }
                    }
                }
            }
        });
    });
</script>

<script>
    $(document).ready(function () {
        var completedProjectData = <?php echo json_encode($completedProjectData); ?>;
        var ctx = $('#completedProjectsChart')[0].getContext('2d');

        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                datasets: [{
                    label: 'Completed',
                    data: [],
                    backgroundColor: [],
                    borderColor: [],
                    borderWidth: 0,
                    barThickness: 95
                }],
                labels: []
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        left: 10,
                        right: 25,
                        top: 25,
                        bottom: 0
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 10,
                        }
                    }
                }
            }
        });

        // Set default years
        var currentYear = new Date().getFullYear();
        var fiveYearsAgo = currentYear - 4;

        // Function to filter data based on year range
        function filterData(yearFrom, yearTo) {
            return completedProjectData.filter(function (item) {
                return item.year >= yearFrom && item.year <= yearTo;
            });
        }

        // Function to update chart
        function updateChart(filteredData) {
            // If no data is available, show the current year with a count of 0
            if (filteredData.length === 0) {
                filteredData = [{ year: currentYear, count: 0 }];
            }

            myChart.data.labels = filteredData.map(item => item.year);
            myChart.data.datasets[0].data = filteredData.map(item => item.count);
            myChart.data.datasets[0].backgroundColor = filteredData.map(item => getColor(item.count));
            myChart.data.datasets[0].borderColor = filteredData.map(item => getBorderColor(item.count));
            myChart.update();
        }

        // Function to get the color based on count
        function getColor(count) {
            if (count < 50) {
                return 'rgba(255, 0, 0)'; // red
            } else if (count < 100) {
                return 'rgba(255, 255, 0)'; // yellow
            } else if (count < 150) {
                return 'rgba(0, 0, 255)'; // blue
            } else {
                return 'rgba(0, 128, 0)'; // green
            }
        }

        // Function to get the border color based on count
        function getBorderColor(count) {
            return getColor(count); // Same logic as getColor
        }

        // Initial chart rendering with default years
        var filteredData = filterData(fiveYearsAgo, currentYear);
        updateChart(filteredData);
    });
</script>

<script>
    function updateDateTime() 
    {
        const now = new Date();
            
        // Define options for time and date
        const timeOptions = { hour: '2-digit', minute: '2-digit', second: '2-digit' };
        const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            
        // Get formatted time and date
        const formattedTime = now.toLocaleTimeString('en-US', timeOptions);
        const formattedDate = now.toLocaleDateString('en-US', dateOptions);
            
        // Update the time and date display
        $('#timeDisplay').text(formattedTime);
        $('#dateDisplay').text(formattedDate);
    }

    setInterval(updateDateTime, 1000);
    updateDateTime();
</script>