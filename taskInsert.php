<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="" method="POST" id="createTaskForm" name="createTaskForm" class="needs-validation" novalidate>
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center">
                    <strong class="text-dark">Task</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="font-size: 12px;"></button>
                </div>
                <div class="modal-body p-1 mt-3">
                    <!-- Your existing HTML code -->

                    <div class="row">
                        <div class="col-6 mb-2">
                            <select class="form-select rounded-0" name="projName" id="projName" style="height: 45px;" required>
                                <option class="d-none" value="" selected disabled>Select Project Name</option>
                                <?php
                                    // Assuming you have a table named 'inventory' with columns 'item_id', 'item_name', 'dimension', 'color', 'inch', 'stock', 'active'
                                    $sql = "SELECT * FROM project WHERE active = '1'";
                                    $result = $conn->query($sql);

                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            ?>
                                            <option value='<?php echo $row['project_id'] ?>'>
                                                <?php echo $row['project_name'] ?>
                                            </option>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <option value="" disabled>No Item found</option>
                                        <?php
                                    }
                                    ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6 mb-2">
                                <select class="form-select rounded-0" id="aluminum" name="aluminum" style="height: 45px;" required>
                                    <option class="d-none" value="" selected disabled>Select Aluminum</option>
                                    <?php
                                    // Assuming you have a table named 'inventory' with columns 'item_id', 'item_name', 'dimension', 'color', 'inch', 'stock', 'active'
                                    $sql = "SELECT * FROM inventory WHERE category = 'aluminum' AND active = '1'";
                                    $result = $conn->query($sql);

                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            ?>
                                            <option value='<?php echo $row['item_id'] ?>' data-inch='<?php echo $row['inch'] ?>' data-stock='<?php echo $row['stock'] ?>'>
                                                <?php echo $row["item_name"] ?> <?php echo $row["dimension"] ?> <?php echo $row["color"] ?>
                                            </option>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <option value="" disabled>No Item found</option>
                                        <?php
                                    }
                                    ?>
                                </select>
                                <div class="invalid-feedback">
                                    Please select aluminum item.
                                </div>
                            </div>
                        <div class="col-6 mb-2 d-flex align-items-center">
                            <span id="stock-span"></span>
                        </div> 
                    </div>

                    <div class="row">
                        <div class="col-6 mb-2">
                            <input type="text" class="form-control rounded-0" id="y-coordinate" name="y-coordinate" style="height: 45px;" required placeholder="Left Right">
                            <div class="invalid-feedback">
                                Please enter y-coordinate.
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6 mb-2">
                            <input type="text" class="form-control rounded-0" id="x-coordinate" name="x-coordinate" style="height: 45px;" required placeholder="Top Bottom">
                            <div class="invalid-feedback">
                                Please enter x-coordinate.
                            </div>
                        </div>

                        <div class="col-6 mb-2">
                            <span id="calculated-pieces">Calculated Pieces: 0</span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6 mb-2">
                            <input type="text" class="form-control rounded-0" id="depth" name="depth" style="height: 45px;" placeholder="Depth">
                        </div>
                    </div>

                    <!-- End of your existing HTML code -->

                </div>
                <div class="modal-body">
                    <button id="createTask" type="submit" class="btn bg-primary text-white" style="font-size: 14px;">Create Task</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function () {
        var aluminumSelect = $("#aluminum");
        var yCoordinateInput = $("#y-coordinate");
        var xCoordinateInput = $("#x-coordinate");
        var depthInput = $("#depth");
        var calculatedPiecesSpan = $("#calculated-pieces");
        var stockSpan = $("#stock-span");
        var createTaskButton = $("#createTask");

        aluminumSelect.on("change", function () {
            updateStock();
            calculatePieces();
        });

        function updateStock() {
            var selectedOption = aluminumSelect.find(":selected");
            var stock = selectedOption.data("stock") || 0;
            stockSpan.text("Stock: " + stock);
        }

        yCoordinateInput.on("input", function () {
            calculatePieces();
        });

        xCoordinateInput.on("input", function () {
            calculatePieces();
        });

        depthInput.on("input", function () {
            calculatePieces();
        });

        function calculatePieces() {
            var calculatedPieces = calculatePiecesInternal();
            calculatedPiecesSpan.text("Calculated Pieces: " + calculatedPieces);
        }

        function calculatePiecesInternal() {
            var selectedOption = aluminumSelect.find(":selected");
            var inch = parseFloat(selectedOption.data("inch")) || 0;
            var yCoordinateInch = parseFloat(yCoordinateInput.val()) || 0;
            var xCoordinateInch = parseFloat(xCoordinateInput.val()) || 0;
            var depthInch = parseFloat(depthInput.val()) || 0;

            if (depthInch == "") {
                xCoordinateInch *= 2;
                yCoordinateInch *= 2;
            } else {
                // Add additional logic here for when depth has a value
                xCoordinateInch *= 4;
                yCoordinateInch *= 4;
                depthInch *= 4;
            }

            var totalInch = yCoordinateInch + xCoordinateInch + depthInch;

            return Math.ceil(totalInch / inch);
        }

        createTaskButton.on("click", function (e) {
            e.preventDefault(); // Prevent the form from submitting

            var form = $('#createTaskForm')[0];

            // Check if the stock is still enough
            var calculatedPieces = calculatePiecesInternal();
            var selectedOption = aluminumSelect.find(":selected");
            var stock = selectedOption.data("stock") || 0;

            form.classList.add('was-validated');

            if (form.checkValidity() === false) {
                event.stopPropagation();
            } else if (calculatedPieces > stock) {
                Swal.fire({
                    title: "Not Enough Stock!",
                    text: "The selected quantity exceeds the available stock.",
                    icon: "error",
                    confirmButtonText: "OK",
                });
            } else {
                Swal.fire({
                    title: "Enough Stock!",
                    text: "The selected quantity does not exceed the available stock.",
                    icon: "success",
                    confirmButtonText: "OK",
                });
            }
        });
    });
</script>
