<?php 
    $id = $_SESSION['adminID'];
?>
<div class="card mb-4 border border-0" style="background-color: transparent;">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="InventoryTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th class="text-center d-none">#</th>
                        <th>Item Name</th>
                        <th>Dimension</th>
                        <th>Foot</th>
                        <th>Sqft</th>
                        <th>Color</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th class="d-none">Category</th>
                        <th>Supplier</th>
                        <th>Controls</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $inventoryResult = mysqli_query($conn, "SELECT * FROM inventory WHERE active = '1' ORDER BY item_id DESC");
                    
                    $counter = 1;

                    if(mysqli_num_rows($inventoryResult) > 0) 
                    {
                        while($itemData = mysqli_fetch_array($inventoryResult)) 
                        {
                            $stock = $itemData['stock'];
                            $backgroundColor = ($stock <= 10) ? 'background-color: #FF7F7F;' : '';
                            $warningSign = ($stock <= 10) ? '<i class="fa-solid fa-circle-exclamation text-danger me-2"></i>' : '';
                            $addSign = ($stock <= 10) ? '<span class="d-flex align-items-center" data-bs-toggle="tooltip" data-bs-placement="top" title="Add stock"><i class="fa-solid fa-circle-plus text-warning" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#addStock"></i></span>' : '';
                ?>
                            <tr data-item-id="<?php echo $itemData['item_id'] ?>">
                                <td class="text-center d-none"><?php echo $counter++ ?></td>
                                <td><?php echo $warningSign ?><?php echo $itemData['item_name'] ?></td>
                                <td><?php echo $itemData['dimension'] ?></td>
                                <td><?php echo $itemData['foot'] ?></td>
                                <td><?php echo $itemData['sqft'] ?></td>
                                <td><?php echo $itemData['color'] ?></td>
                                <td><?php echo $itemData['price'] ?></td>
                                <td>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <?php echo $stock ?> <?php echo $addSign ?>
                                    </div>
                                </td>
                                <td class="d-none"><?php echo $itemData['category'] ?></td>
                                <td class="text-center">
                                    <a id="viewSupplierItem" href="#" data-bs-toggle="modal" data-bs-target="#viewSupplierModal" data-id="<?php echo $itemData['supplier_id']; ?>"><i class="fa-solid fa-eye" data-bs-toggle="tooltip" data-bs-placement="top" title="View Details"></i></a>
                                </td>
                                <td>
                                    <div class="text-center d-flex justify-content-evenly">
                                        <a id="editItem" href="#" class="text-success" data-bs-toggle="modal" data-bs-target="#updateInventoryModal" data-id="<?php echo $itemData['item_id']; ?>"><i class="fa-solid fa-pen-to-square" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"></i></a>
                                        <a id="deleteItem" href="#" class="text-danger" data-id="<?php echo $itemData['item_id']; ?>" data-admin-id="<?php echo $adminID; ?>"><i class="fa-solid fa-trash" data-bs-toggle="tooltip" data-bs-placement="top" title="Remove"></i></a>
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
</div>

<div class="modal fade animated--grow-in" id="addStock" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            
            <form action="update.php?action=addStock" id="addStockForm" name="addStockForm" method="POST" class="needs-validation text-dark" novalidate>
                <div class="modal-header d-flex align-items-center">
                    <strong class="text-dark">Add Stock</strong>
                    <button id="closeModal" type="button" class="btn-close " data-bs-dismiss="modal" aria-label="Close" style="font-size: 12px;"></button>
                </div>
                <div class="modal-body p-2 text-left">
                    <div class="d-flex justify-content-center my-2">

                        <input type="hidden" id="itemId" name="itemId">
                        <input type="hidden" name="adminID" value="<?php echo $adminID; ?>">

                        <div class="col-lg-3">
                            <input type="text" class="form-control" id="stockInput" name="stockInput" readonly>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fa-solid fa-plus"></i>
                        </div>
                        <div class="col-lg-3">
                            <input type="text" class="form-control" id="addStockLevel" name="addStockLevel" placeholder="Add stock" required>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fa-solid fa-equals"></i>
                        </div>
                        <div class="col-lg-3">
                            <input type="text" class="form-control" id="totalStock" name="totalStock" readonly>
                        </div>
                    </div>
                </div>
                <div class="modal-body text-right pt-0">
                    <button id="addStockBtn" type="submit" class="btn btn-success  px-5">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade text-dark animated--grow-in" id="viewSupplierModal" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" style="transition: 0.5s;">
    <div class="modal-dialog modal-dialog-centered">
        <div id="supplierDetailsContainer" class="modal-content">
            <!-- fetch Project -->
        </div>
    </div>
</div>

<div class="modal fade text-dark animated--grow-in" id="updateInventoryModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="transition: 0.5s;">
    <div class="modal-dialog">
        <div class="modal-content">

            <form action="update.php?action=inventory" id="updateInventoryForm" name="updateInventoryForm" method="POST" class="needs-validation" novalidate>

                <div class="modal-header d-flex align-items-center">
                    <strong class="text-dark">Edit Item</strong>
                    <button id="closeModal" type="button" class="btn-close " data-bs-dismiss="modal" aria-label="Close" style="font-size: 12px;"></button>
                </div>
                    <div class="modal-body text-left p-2">                   
                            <div class="text-left px-1">
                                <div class="mb-2">
                                    <label class="form-label mx-1 mt-1 mb-0">Description</label>
                                    <input type="text" class="form-control" id="updateItemName" name="updateItemName" style="height: 45px;" required>
                                    <div class="invalid-feedback">
                                        Please enter description.
                                    </div>
                                </div>

                                <div class="mb-2">
                                    <label class="form-label mx-1 mt-1 mb-0">Supplier</label>
                                    <select class="form-select" id="updateSupplier" name="updateSupplier" style="height: 45px;" required>
                                        <option class="d-none" selected></option>
                                        <?php
                                            $query = "SELECT supplier_id, company_name, address FROM supplier";
                                            $result = mysqli_query($conn, $query);

                                            if ($result) 
                                            {
                                                while ($data = mysqli_fetch_assoc($result)) 
                                                {
                                                ?>
                                                    <option value="<?php echo $data['supplier_id']; ?>">
                                                        <?php echo $data['company_name']; ?> - <?php echo $data['address']; ?>
                                                    </option>;
                                                <?php
                                                }
                                            }
                                        ?>
                                    </select>
                                    <div class="invalid-feedback">
                                        Please select a supplier.
                                    </div>
                                </div>

                                <div class="mb-2">
                                    <label class="form-label mx-1 mt-1 mb-0">Category</label>
                                    <select class="form-select text-dark" id="updateCategory" name="updateCategory" style="height: 45px;" required>
                                        <option value="" class="d-none">Select Category</option>
                                        <option value="Glass">Glass</option>
                                        <option value="Aluminum">Aluminum</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Please select a category.
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-4 mb-2">
                                        <label class="form-label mx-1 mt-1 mb-0">Dimension</label>
                                        <input type="text" class="form-control" id="updateDimension" name="updateDimension" style="height: 45px;" required>
                                        <div class="invalid-feedback">
                                            Please enter dimension.
                                        </div>
                                    </div>

                                    <div class="col-lg-4 mb-2">
                                        <label class="form-label mx-1 mt-1 mb-0">Color</label>
                                        <input type="text" class="form-control" id="updateColor" name="updateColor" style="height: 45px;" required>
                                        <div class="invalid-feedback">
                                            Please enter color
                                        </div>
                                    </div>

                                    <div class="col-lg-4 mb-2" id="inch-field1">
                                        <label class="form-label mx-1 mt-1 mb-0">Foot</label>
                                        <input type="text" class="form-control" id="updateInch" name="updateInch" style="height: 45px;" required>
                                        <div class="invalid-feedback">
                                            Please enter inch.
                                        </div>
                                    </div>

                                    <div class="col-lg-4 mb-2" id="sqft-field1">
                                        <label class="form-label mx-1 mt-1 mb-0">Sqft</label>
                                        <input type="text" class="form-control" id="updateSqft" name="updateSqft" style="height: 45px;">
                                        <div class="invalid-feedback">
                                            Please enter sqft.
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6 mb-2">
                                        <label class="form-label mx-1 mt-1 mb-0">Stock</label>
                                        <input type="text" class="form-control" id="updateStock" name="updateStock" style="height: 45px;" required>
                                        <div class="invalid-feedback">
                                            Please enter stock
                                        </div>
                                    </div>

                                    <div class="col-lg-6 mb-2">
                                        <label class="form-label mx-1 mt-1 mb-0">Price</label>
                                        <input type="text" class="form-control" id="updatePrice" name="updatePrice" style="height: 45px;" required>
                                        <div class="invalid-feedback">
                                            Please enter price
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" id="updateItemId" name="updateItemId">
                            <input type="hidden" id="updateSupplierId" name="updateSupplierId">
                            <input type="hidden" name="adminID" value="<?php echo $adminID; ?>">
                </div>

                <div class="modal-body text-right pt-0">
                        <button id="updateInventory" type="submit" class="btn btn-success  px-5" disabled>Save</button>
                </div>

            </form>

        </div>
    </div>
</div>

<style>
    .custom-width {
        width: 60px; /* Set the desired width */
    }

    .title-button{
        font-size: 12px;
    }

/* Hide filters on mobile and tablet devices */
@media (max-width: 1024px) {
    #categoryFilterFormWrapper, #stockFilterFormWrapper {
        display: none !important; /* Hide the filters on mobile and tablet */
    }

    .d-sm-flex {
        flex-direction: column; /* Stack buttons on mobile and tablet */
    }

    #addItemButtonWrapper {
        margin-bottom: 10px; /* Add space between buttons on mobile/tablet */
    }

    #exportButtonWrapper {
        display: none !important; /* Hide export button on mobile/tablet */
    }
}

/* Show filters and export buttons on desktop only (min-width: 992px) */
@media (min-width: 1024px) {
    #categoryFilterFormWrapper, #stockFilterFormWrapper {
        display: block !important; /* Show filters on desktop */
    }

    .d-sm-flex {
        flex-direction: row; /* Align buttons in a row on desktop */
    }

    #exportButtonWrapper {
        display: block !important; /* Show export button on desktop */
    }
}

/* Show the filters and buttons only for tablet/desktop */
@media (max-width: 767px) {
    #categoryFilterFormWrapper, #stockFilterFormWrapper {
        display: none !important;
    }

    .d-sm-flex {
        flex-direction: column; /* Stack buttons on mobile */
    }

    #addItemButtonWrapper {
        margin-bottom: 10px; /* Add space on mobile */
    }

    #exportButtonWrapper {
        display: none !important; /* Hide export button on mobile */
    }

    .print {
        display: none !important;
    }

    .dt-buttons {
        display: flex;
        margin-left: 3px;
        row-gap: 3px;
    }
}

/* Show the filters and buttons as normal on tablet/desktop */
@media (min-width: 768px) {
    #categoryFilterFormWrapper, #stockFilterFormWrapper {
        display: block !important; /* Show filters on tablet/desktop */
    }

    .d-sm-flex {
        flex-direction: row; /* Align buttons in a row */
    }
}

</style>
    
<script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    $(document).ready(function() 
    {
        var table = $('#InventoryTable').DataTable({
            ordering: false,
            info: false,
            columnDefs: [
                { searchable: true, targets: [1] }, // Make the first column searchable (index 0)
                { searchable: false, targets: [0, 2, 3, 4, 5, 6, 7, 8] } // Make other columns not searchable
            ],
            language: {
                paginate: {
                    previous: '<i class="fa-solid fa-angle-left"></i>', // Custom text for the 'Previous' button
                    next: '<i class="fa-solid fa-angle-right"></i>', // Custom text for the 'Next' button
                }
            },
            dom: "<'row'<'d-flex justify-content-between align-items-center p-0 mb-2 mx-0'<'#addItemButtonWrapper'><'text-right p-0'B>>>" +
                "<'row'<'d-flex flex-column flex-md-row justify-content-between align-items-center p-0 mb-1'<l><'d-flex gap-4' <'#categoryFilterFormWrapper'><'#stockFilterFormWrapper'><'col-sm-12 col-md-3 p-0'f>>>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",


                initComplete: function() 
                {
                    $('#categoryFilterFormWrapper').html(`
                        <form id="categoryFilterForm" class="d-flex align-items-center">
                            <label for="statusFilter" class="mb-0" style="font-size: 1rem">Category:</label>
                            <select class="form-select py-1 px-2" id="categoryFilter" name="categoryFilter" style="width: 180px; font-size: 15px; margin: 0 0 0 7px">
                                <option value="ALL">All</option>
                                <option value="Glass">Glass</option>
                                <option value="Aluminum">Aluminum</option>
                            </select>
                        </form>
                    `);

                    $('#stockFilterFormWrapper').html(`
                        <form id="stockFilterForm" class="d-flex align-items-center">
                            <label for="statusFilter" class="mb-0" style="font-size: 1rem">Status:</label>
                            <select class="form-select py-1 px-2" id="statusFilter" name="statusFilter" style="width: 180px; font-size: 15px; margin: 0 0 0 7px">
                                <option value="ALL">All</option>
                                <option value="LowStock">Low Stock</option>
                            </select>
                        </form>
                    `);

                    $('#addItemButtonWrapper').html(`
                        <a type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createInventoryModal" style="font-size: 14px;" id="addItemButton">
                            + Add Item
                        </a>
                        
                        <a type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#excessModal" style="font-size: 14px;">
                            View Excess
                        </a>
                    `);
                },

                buttons: [
                    {
                        extend: 'excel',
                        text: '<i class="fa-solid fa-file-excel"></i> <div class="title-button">Excel</div>',
                        className: 'btn btn-success py-1 custom-width',
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5, 6, 8] // Export relevant columns (excluding "Stock")
                        },
                        customize: function (xlsx) {
                            var sheet = xlsx.xl.worksheets['sheet1.xml'];

                            $('row c[r="A2"] t', sheet).text('Name');

                            $('row c[r^="A"] t', sheet).each(function () 
                            {
                                var cell = $(this);
                                if (cell.text().indexOf('Calauan Glass') !== -1) {
                                    cell.text(cell.text().replace('Calauan Glass', 'Calauan Glass and Aluminum Services'));
                                }
                            });

                            sheet.querySelectorAll('row c[r^="A1"]').forEach((el) => {
                                el.setAttribute('s', '2');
                            });
                        }
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="fa-solid fa-file-pdf"></i> <div class="title-button">PDF</div>',
                        className: 'btn btn-danger py-1 custom-width',
                        download: 'open',
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5, 6, 8],
                            format: {
                                header: function (data, columnIdx) {
                                    return columnIdx === 1 ? 'Name' : data; // Change "Item Name" to "Name"
                                }
                            }
                        },
                        customize: function (doc) {
                            // Remove the existing title header (example: title text at index 0)
                            if (doc.content[0] && doc.content[0].text === 'Calauan Glass') {
                                doc.content[0].text = ''; // Set title to empty string or adjust as needed
                            }

                            // Add new content
                            doc.content.splice(1, 0, {
                                margin: [0, 0, 0, 30], // Margin below this content block
                                alignment: 'center', // Center the entire block
                                columns: [
                                    {
                                        image: 'data:image/png;base64,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', // Set your image source here
                                        width: 55,
                                        margin: [115, 0, 0, 0], // Adjust margin to center the image
                                        alignment: 'left' // Align image left within the column
                                    },
                                    {
                                        stack: [
                                            {
                                                text: 'Calauan Glass and Aluminum Services',
                                                margin: [0, 0, 0, 4], // Bottom margin
                                                alignment: 'center', // Center the text
                                                fontSize: 12,
                                                bold: true
                                            },
                                            {
                                                text: 'Brgy Masiit Calauan Laguna',
                                                margin: [0, 0, 0, 4], // Bottom margin
                                                alignment: 'center', // Center the text
                                                fontSize: 10
                                            },
                                            {
                                                text: '09278158215',
                                                margin: [0, 0, 0, 4], // Bottom margin
                                                alignment: 'center', // Center the text
                                                fontSize: 10,
                                            }
                                        ],
                                    }
                                ]
                            });

                            // Adjust the table to span the full width of the page
                            var table = doc.content[doc.content.length - 1].table;
                            table.widths = Array(table.body[0].length).fill('*'); // Make all columns equal width

                            // Apply styles to all rows, including header
                            table.body.forEach(function(row, rowIndex) {
                                row.forEach(function(cell) {
                                    cell.alignment = 'center'; // Center align table text
                                    cell.margin = [5, 5, 5, 5]; // Add padding to all cells
                                    cell.fillColor = null; // Ensure no background color is set
                                    if (rowIndex === 0) {
                                        cell.bold = true; // Make header text bold
                                    }
                                });
                            });

                            // Set table layout to span the full width of the page
                            doc.content[doc.content.length - 1].layout = {
                                hLineWidth: function(i, node) {
                                    return (i === 0 || i === node.table.body.length) ? 2 : 1; // Customize horizontal line width
                                },
                                vLineWidth: function(i) {
                                    return 0; // No vertical lines
                                },
                                hLineColor: function(i) {
                                    return '#ffffff'; // Horizontal line color
                                },
                                paddingLeft: function(i) {
                                    return 5; // Left padding
                                },
                                paddingRight: function(i, node) {
                                    return 5; // Right padding
                                }
                            };
                        }
                    },
                    {
                        extend: 'print',
                        text: '<i class="fa-solid fa-print"></i> <div class="title-button">Print</div>',
                        className: 'btn btn-primary py-1 custom-width print',
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5, 6, 7, 8] // Only export the specified columns
                        },
                        customize: function (win) 
                        {
                            $(win.document.body).find('*:contains("Calauan Glass")').each(function() {
                                if ($(this).children().length < 1) {
                                    $(this).remove();
                                }
                            });
                        
                            // Get the current date
                            var currentDate = new Date();
                            var formattedDate = currentDate.toLocaleDateString(); // Formats to MM/DD/YYYY
                        
                            // Collect rows by category
                            var categories = {};
                            $(win.document.body).find('table tbody tr').each(function() {
                                var category = $(this).find('td:nth-child(8)').text();
                                if (!categories[category]) {
                                    categories[category] = [];
                                }
                                categories[category].push($(this));
                            });
                        
                            // Add CSS for print layout and design
                            var css = `
                                <style>
                                    .page-break { 
                                        display: block; 
                                        page-break-before: always; 
                                    }
                                    @media print {
                                        .page-break {
                                            display: block;
                                            page-break-before: always;
                                        }
                                    }
                                    table {
                                        border-collapse: collapse;
                                        width: 100%;
                                        margin-bottom: 20px;
                                    }
                                    table, th, td {
                                        border: 1px solid #ddd;
                                    }
                                    th, td {
                                        padding: 10px;
                                        text-align: left;
                                    }
                                    th {
                                        background-color: #f8f9fa;
                                        font-weight: bold;
                                    }
                                    table td:nth-child(8),
                                    table th:nth-child(8) {
                                        display: none;
                                    }
                                    table td:nth-child(7),
                                    table th:nth-child(7) {
                                        display: none;
                                    }
                                    .category-header {
                                        font-weight: bold;
                                        text-align: center;
                                    }
                                    .print-header {
                                        margin-bottom: 20px;
                                        text-align: center;
                                    }
                                    .print-header img {
                                        vertical-align: middle;
                                    }
                                    .print-header h5 {
                                        margin: 0;
                                    }
                                    .print-header .contact-info {
                                        font-size: 14px;
                                    }
                                    .date-issued {
                                        text-align: right;
                                        font-size: 14px;
                                        font-weight: bold;
                                        margin-bottom: 10px;
                                    }
                                </style>
                            `;
                            $(win.document.body).append(css);
                        
                            // Define the header content with the date issued
                            var headerHtml = `
                                <div class="date-issued">Date: ${formattedDate}</div>
                                <div class="d-flex justify-content-center mb-2">
                                    <div class="d-flex align-items-center me-3">
                                        <img src="pictures/logo-removebg-preview.png" alt="" width="80px">
                                    </div>
                                    <div>
                                        <div><h5 class="fw-bold" style="margin-bottom: 4px;">Calauan Glass and Aluminum Services</h5></div>
                                        <div class="text-center" style="margin-bottom: 4px;">Brgy Masiit Calauan Laguna</div>
                                        <div class="text-center" style="margin-bottom: 4px;">09278158215</div>
                                    </div>
                                </div>
                            `;
                        
                            // Remove the automatic header
                            $(win.document.body).find('table').remove();
                        
                            // Function to add the header content
                            function addHeader() {
                                $(win.document.body).append(headerHtml);
                            }
                        
                            // Add table headers manually
                            function addTableHeaders() {
                                return `
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Dimension</th>
                                            <th>Inch</th>
                                            <th>Sqft</th>
                                            <th>Color</th>
                                            <th>Price</th>
                                            <th class="d-none">Stock</th>
                                            <th class="d-none">Category</th>
                                        </tr>
                                    </thead>
                                `;
                            }
                        
                            // Initialize the header content
                            addHeader();
                        
                            // Append rows by category with headers and page breaks
                            var firstCategory = true;
                            for (var category in categories) {
                                if (categories.hasOwnProperty(category)) {
                                    if (!firstCategory) {
                                        $(win.document.body).append('<div class="page-break"></div>');
                                        addHeader();
                                    }
                                    firstCategory = false;
                        
                                    $(win.document.body).append(`
                                        <h4 class="category-header">${category}</h4>
                                        <table>
                                            ${addTableHeaders()}
                                            <tbody>
                                            ${categories[category].map(row => row.prop('outerHTML')).join('')}
                                            </tbody>
                                        </table>
                                    `);
                                }
                            }
                        
                            $(win.document.body).find('table')
                                .addClass('compact')
                                .css('font-size', 'inherit');
                        }
                    }
                ]
            });

            function filterTable() {
                var selectedCategory = $('#categoryFilter').val();
                var selectedStatus = $('#statusFilter').val();
                var noMatch = true;

                $('#InventoryTable tbody tr').each(function() {
                    var categoryCell = $(this).find('td:nth-child(9)'); // Category is in the 9th column
                    var stockCell = $(this).find('td:nth-child(8)'); // Stock is in the 8th column
                    var category = categoryCell.text().trim();
                    var stock = parseInt(stockCell.text().trim());

                    var categoryMatch = (selectedCategory === 'ALL' || category === selectedCategory);
                    var statusMatch = (selectedStatus === 'ALL' || (selectedStatus === 'LowStock' && stock <= 10));

                    if (categoryMatch && statusMatch) {
                        $(this).show();
                        noMatch = false;
                    } else {
                        $(this).hide();
                    }
                });

                $('#InventoryTable tbody .no-records').remove();

                if (noMatch) {
                    $('#InventoryTable tbody').append('<tr class="no-records"><td colspan="11" style="text-align:center;">No matching records found</td></tr>');
                }
            }

            $('#categoryFilter').on('change', filterTable);
            $('#statusFilter').on('change', filterTable);
    });
    
    $(document).ready(function() {
        // Function to add 'mb-2' class on mobile view
        function adjustButtonMargin() {
            if ($(window).width() < 768) {
                // For mobile size, add the 'mb-2' class
                $('#addItemButton').addClass('mb-1');
            } else {
                // For tablet and desktop, remove the 'mb-2' class
                $('#addItemButton').removeClass('mb-1');
            }
        }

        // Call the function on document ready
        adjustButtonMargin();

        // Recheck on window resize to adjust as needed
        $(window).resize(function() {
            adjustButtonMargin();
        });
    });

</script>

<script>
    $(document).ready(function () {
        // Initially hide both inch and sqft fields
        $('#inch-field1').hide();
        $('#sqft-field1').hide();

        $('#updateCategory').change(function () {
            var selectedCategory = $(this).val();
            
            // Show the relevant field based on the selected category
            if (selectedCategory === 'Aluminum') {
                $('#inch-field1').show();
                $('#sqft-field1').hide();
            } else if (selectedCategory === 'Glass') {
                $('#sqft-field1').show();
                $('#inch-field1').hide();
            } else {
                // Hide both fields if neither Aluminum nor Glass is selected
                $('#inch-field1').hide();
                $('#sqft-field1').hide();
            }
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('[data-bs-toggle="tooltip"]').tooltip();
    });

    $(document).on('click', '#closeModal', function(e) {
        e.preventDefault();

        $('#updateInventoryForm')[0].reset();
        $('#updateInventoryForm').removeClass('was-validated');

        $('#updatePrice')[0].setCustomValidity('');
        $('#updateStock')[0].setCustomValidity('');
        
        $('#addStockForm')[0].reset();
        $('#addStockForm').removeClass('was-validated');
    });

    $(document).on('input', '#addStockLevel', function(e) {
        e.preventDefault();
            
        $(this).val($(this).val().replace(/[^0-9]/g, ''));
    });

    $(document).on('click', '#editItem', function(e) {
        e.preventDefault();

        var itemId = $(this).data('id');

        $.ajax({
            type: "POST",
            url: "fetch.php?action=inventory",
            data: { itemId: itemId },
            dataType: "json",
            success: function(data) {
                console.log(data);  // Check if data is being retrieved

                // Populate the form fields with data from the AJAX response
                $('#updateItemId').val(data.item_id);
                $('#updateSupplierId').val(data.supplier_id);
                $('#updateItemName').val(data.item_name);
                $('#updateSupplier').val(data.supplier_id);
                $('#updateDimension').val(data.dimension);
                $('#updateInch').val(data.foot);
                $('#updateSqft').val(data.sqft);
                $('#updateColor').val(data.color);
                $('#updatePrice').val(data.price);
                $('#updateStock').val(data.stock);
                $('#updateCategory').val(data.category);

                // Show or hide the Sqft field based on the category
                if (data.category === 'Aluminum') {
                    $('#sqft-field1').hide();
                    $('#inch-field1').show();
                } else {
                    $('#sqft-field1').show();
                    $('#inch-field1').hide();
                }

                // Show the modal
                $('#updateInventoryModal').modal('show');
            }
        });
    });

    $(document).ready(function () 
    {
        var initialData = {};

        function getFormData($form) 
        {
            var unindexedArray = $form.serializeArray();
            var indexedArray = {};

            $.map(unindexedArray, function(n, i) {
                indexedArray[n['name']] = n['value'];
            });

            return indexedArray;
        }

        $('#updateInventoryForm').on('input change', function() {
            var currentData = getFormData($('#updateInventoryForm'));
            var isChanged = JSON.stringify(initialData) !== JSON.stringify(currentData);
            $('#updateInventory').prop('disabled', !isChanged);
        });

        $(document).on('click', '#updateInventory', function(e){
            e.preventDefault();

            var form = $('#updateInventoryForm')[0];

            var priceField = $('#updatePrice')[0];
            var stockField = $('#updateStock')[0];

            priceField.setCustomValidity('');
            stockField.setCustomValidity('');

            form.classList.add('was-validated');

            if (form.checkValidity() === false) {
                event.stopPropagation();
            } else {
                
                var priceValue = priceField.value;
                var stockValue = stockField.value;

                if(priceValue == 0)
                {
                    priceField.setCustomValidity('Price must be greater than 0');
                    $('#updatePrice').siblings('.invalid-feedback').text('Price must be greater than 0');

                    return;
                }
                else if(stockValue == 0)
                {
                    stockField.setCustomValidity('Stock must be greater than 0');
                    $('#updateStock').siblings('.invalid-feedback').text('Stock must be greater than 0');

                    return;
                }

                $.ajax({
                    type: "POST",
                    url: "update.php?action=inventory",
                    data: $('#updateInventoryForm').serialize(),
                    success: function (response) {

                        Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                location.reload();
                            });;

                            $('#updateInventoryForm')[0].reset();
                            $('#updateInventoryForm').removeClass('was-validated');
                            $('#updateInventoryModal').modal('hide');
                    }
                });
            }
            form.classList.add('was-validated');
        });
    });

    $(document).on('click', '#deleteItem', function(e) 
    {
        e.preventDefault();

        var itemId = $(this).data('id');
        var adminID = $(this).data('admin-id');

        let timerInterval;
        Swal.fire({
            title: "Confirmation",
            text: "Are you sure to remove this item?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#4e73df",
            cancelButtonColor: "#e74a3b",
            confirmButtonText: "Continue",
            cancelButtonText: "Cancel",
            allowOutsideClick: false, // Make the alert modal static
            preConfirm: () => {
                return new Promise((resolve) => {
                    Swal.showLoading();
                    setTimeout(() => {
                        resolve(true);
                    }, 3000);
                });
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: "delete.php?action=inventory",
                    data: { itemId: itemId, adminID: adminID },
                    success: function(response) {
                        Swal.fire({
                            title: "Remove",
                            text: response,
                            icon: "success",
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            location.reload();
                        });
                    }
                });
            }
        });
    });

    $(document).on('click', '#viewSupplierItem', function(e) 
    {
            e.preventDefault();

            var supplierId = $(this).data('id');

            $.ajax({
                url: 'fetchItemSupplier.php?action=supplierItem',
                method: 'GET',
                data: { supplierId: supplierId },
                success: function(response) 
                {
                    $('#supplierDetailsContainer').html(response);
                },
            });
    });
    
    $(document).on('click', '#addStockBtn', function(e)
    {
        e.preventDefault();

        var form = $('#addStockForm')[0];

        form.classList.add('was-validated');

        if (form.checkValidity() === false) {
            event.stopPropagation();
        } else {
            $.ajax({
                type: "POST",
                url: "update.php?action=addStock",
                data: $('#addStockForm').serialize(),
                success: function (response) {
                    Swal.fire({
                        text: response,
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        location.reload();
                    });
                }
            });
        }
    });

    $(document).on('click', '.fa-circle-plus', function() 
    {
        var stockInput = $('#stockInput');
        var totalStock = $('#totalStock');
        var stockColumn = $(this).closest('tr').find('td:eq(7)');
        var stock = stockColumn.text().trim();
        var itemId = $(this).closest('tr').data('item-id');

        stockInput.val(stock);
        totalStock.val(stock);
        
        $('#itemId').val(itemId);
    });

    $(document).on('input', '#addStockLevel', function() 
    {
        var addStockLevel = $(this).val();

        var stockInput = $('#stockInput');
        var totalStock = $('#totalStock');
        var currentStock = parseInt(stockInput.val().trim());

        if (!isNaN(addStockLevel) && addStockLevel !== '') {
            var newStock = currentStock + parseInt(addStockLevel);
            totalStock.val(newStock);
        } else {
            totalStock.val(currentStock);
        }
    });
</script>