<?php
    include 'database.php';

    $action = isset($_GET['action']) ? $_GET['action'] : '';

    if ($action == 'rail') 
    {
        if (isset($_POST['item_name']) && isset($_POST['dimension']) && isset($_POST['color'])) 
        {
            $item_name = mysqli_real_escape_string($conn, $_POST['item_name']);
            $lFoot = mysqli_real_escape_string($conn, $_POST['lFoot']);
            $dimension = mysqli_real_escape_string($conn, $_POST['dimension']);
            $color = mysqli_real_escape_string($conn, $_POST['color']);

            $query = "
                SELECT 
                    ie.exc_id, 
                    ie.item_name,
                    ie.exc_foot,
                    inv.price,
                    ie.color,
                    ie.dimension
                FROM 
                    inventory_excess ie
                INNER JOIN 
                    inventory inv
                ON 
                    ie.item_name = inv.item_name
                    AND ie.dimension = inv.dimension
                    AND ie.color = inv.color
                WHERE 
                    ie.item_name = '$item_name' 
                    AND ie.exc_foot >= $lFoot
                    AND ie.dimension = '$dimension'
                    AND ie.color = '$color'
                ORDER BY 
                    ie.exc_foot ASC, ie.exc_id ASC
                LIMIT 1
            ";
            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
                $data = mysqli_fetch_assoc($result);
                echo json_encode([
                    'status' => 'found',
                    'exc_id' => $data['exc_id'],
                    'item_name' => $data['item_name'],
                    'exc_foot' => $data['exc_foot'],
                    'price' => $data['price'],
                    'color' => $data['color'],
                    'dimension' => $data['dimension'],
                ]);
            } else {
                // Step 2: Find half of lFoot
                $halfFoot = ceil($lFoot / 2);

                $query = "
                    SELECT 
                        ie.exc_id, 
                        ie.item_name,
                        ie.exc_foot,
                        inv.price,
                        ie.color,
                        ie.dimension
                    FROM 
                        inventory_excess ie
                    INNER JOIN 
                        inventory inv
                    ON 
                        ie.item_name = inv.item_name
                        AND ie.dimension = inv.dimension
                        AND ie.color = inv.color
                    WHERE 
                        ie.item_name = '$item_name' 
                        AND ie.exc_foot >= $halfFoot
                        AND ie.dimension = '$dimension'
                        AND ie.color = '$color'
                    ORDER BY 
                        ie.exc_foot ASC, ie.exc_id ASC
                    LIMIT 1
                ";
                $halfResult = mysqli_query($conn, $query);

                if (mysqli_num_rows($halfResult) > 0) {
                    $halfData = mysqli_fetch_assoc($halfResult);
                    $remainingFoot = $lFoot - $halfData['exc_foot'];

                    $query = "
                        SELECT 
                            ie.exc_id, 
                            ie.item_name,
                            ie.exc_foot,
                            inv.price,
                            ie.color,
                            ie.dimension 
                        FROM 
                            inventory_excess ie
                        INNER JOIN 
                            inventory inv
                        ON 
                            ie.item_name = inv.item_name
                            AND ie.dimension = inv.dimension
                            AND ie.color = inv.color
                        WHERE 
                            ie.item_name = '$item_name' 
                            AND ie.exc_foot >= $remainingFoot
                            AND ie.dimension = '$dimension'
                            AND ie.color = '$color'
                            AND ie.exc_id != {$halfData['exc_id']}
                        ORDER BY 
                            ie.exc_foot ASC, ie.exc_id ASC
                        LIMIT 1
                    ";
                    $remainingHalfResult = mysqli_query($conn, $query);

                    if (mysqli_num_rows($remainingHalfResult) > 0) 
                    {
                        $remainingHalfData = mysqli_fetch_assoc($remainingHalfResult);
                        echo json_encode([
                            'status' => 'found_full',
                            'exc_id_first_half' => $halfData['exc_id'],
                            'first_half_exc_foot' => $halfData['exc_foot'],
                            'exc_id_second_half' => $remainingHalfData['exc_id'],
                            'second_half_exc_foot' => $remainingHalfData['exc_foot'],
                            'price' => $remainingHalfData['price'],
                            'color' => $remainingHalfData['color'],
                            'dimension' => $remainingHalfData['dimension'],
                            'half_foot' => $halfFoot
                        ]);
                    } else {
                        // Find remaining part from main inventory
                        $query = "
                            SELECT item_id, item_name, foot, price, color, dimension, stock
                            FROM inventory
                            WHERE item_name = '$item_name' AND foot >= $remainingFoot AND dimension = '$dimension' AND color = '$color'
                            ORDER BY item_id ASC
                            LIMIT 1
                        ";
                        $remainingResult = mysqli_query($conn, $query);

                        if (mysqli_num_rows($remainingResult) > 0) {
                            $remainingData = mysqli_fetch_assoc($remainingResult);
                            echo json_encode([
                                'status' => 'found_half',
                                'exc_id' => $halfData['exc_id'],
                                'half_exc_foot' => $halfData['exc_foot'],
                                'remaining_item_id' => $remainingData['item_id'],
                                'remaining_foot' => $remainingData['foot'],
                                'remaining_price' => $remainingData['price'],
                                'remaining_color' => $remainingData['color'],
                                'remaining_dimension' => $remainingData['dimension'],
                                'remaining_stock' => $remainingData['stock'],
                                'half_foot' => $halfFoot
                            ]);
                        } else {
                            echo json_encode(['status' => 'not_found']);
                        }
                    }
                } else {
                    // Find a new item from the main inventory
                    $query = "
                        SELECT item_id, item_name, foot, price, color, dimension, stock
                        FROM inventory
                        WHERE item_name = '$item_name' AND foot >= $lFoot AND dimension = '$dimension' AND color = '$color'
                        ORDER BY item_id ASC
                        LIMIT 1
                    ";
                    $newItemResult = mysqli_query($conn, $query);

                    if (mysqli_num_rows($newItemResult) > 0) {
                        $newItemData = mysqli_fetch_assoc($newItemResult);
                        echo json_encode([
                            'status' => 'found_new',
                            'item_id' => $newItemData['item_id'],
                            'item_name' => $newItemData['item_name'],
                            'foot' => $newItemData['foot'],
                            'price' => $newItemData['price'],
                            'color' => $newItemData['color'],
                            'dimension' => $newItemData['dimension'],
                            'stock' => $newItemData['stock'],
                        ]);
                    } else {
                        echo json_encode(['status' => 'not_found']);
                    }
                }
            }
        }
    }

    if ($action == 'sidejamb') 
    {
        if (isset($_POST['item_name']) && isset($_POST['hFoot'])) 
        {
            $item_name = mysqli_real_escape_string($conn, $_POST['item_name']);
            $hFoot = mysqli_real_escape_string($conn, $_POST['hFoot']);
            $dimension = mysqli_real_escape_string($conn, $_POST['dimension']);
            $color = mysqli_real_escape_string($conn, $_POST['color']);

            $query = "
                SELECT 
                    ie.exc_id, 
                    ie.item_name,
                    ie.exc_foot,
                    inv.price,
                    ie.color,
                    ie.dimension
                FROM 
                    inventory_excess ie
                INNER JOIN 
                    inventory inv
                ON 
                    ie.item_name = inv.item_name
                    AND ie.dimension = inv.dimension
                    AND ie.color = inv.color
                WHERE 
                    ie.item_name = '$item_name' 
                    AND ie.exc_foot >= $hFoot
                    AND ie.dimension = '$dimension'
                    AND ie.color = '$color'
                ORDER BY 
                    ie.exc_foot ASC, ie.exc_id ASC
                LIMIT 1
            ";
            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
                $data = mysqli_fetch_assoc($result);
                echo json_encode([
                    'status' => 'found',
                    'exc_id' => $data['exc_id'],
                    'item_name' => $data['item_name'],
                    'exc_foot' => $data['exc_foot'],
                    'price' => $data['price'],
                    'color' => $data['color'],
                    'dimension' => $data['dimension'],
                ]);
            } else {
                // Step 2: Find half of hFoot
                $halfFoot = ceil($hFoot / 2);

                $query = "
                    SELECT 
                        ie.exc_id, 
                        ie.item_name,
                        ie.exc_foot,
                        inv.price,
                        ie.color,
                        ie.dimension
                    FROM 
                        inventory_excess ie
                    INNER JOIN 
                        inventory inv
                    ON 
                        ie.item_name = inv.item_name
                        AND ie.dimension = inv.dimension
                        AND ie.color = inv.color
                    WHERE 
                        ie.item_name = '$item_name' 
                        AND ie.exc_foot >= $halfFoot
                        AND ie.dimension = '$dimension'
                        AND ie.color = '$color'
                    ORDER BY 
                        ie.exc_foot ASC, ie.exc_id ASC
                    LIMIT 1
                ";
                $halfResult = mysqli_query($conn, $query);

                if (mysqli_num_rows($halfResult) > 0) {
                    $halfData = mysqli_fetch_assoc($halfResult);
                    $remainingFoot = $hFoot - $halfData['exc_foot'];

                    $query = "
                        SELECT 
                            ie.exc_id, 
                            ie.item_name,
                            ie.exc_foot,
                            inv.price,
                            ie.color,
                            ie.dimension 
                        FROM 
                            inventory_excess ie
                        INNER JOIN 
                            inventory inv
                        ON 
                            ie.item_name = inv.item_name
                            AND ie.dimension = inv.dimension
                            AND ie.color = inv.color
                        WHERE 
                            ie.item_name = '$item_name' 
                            AND ie.exc_foot >= $remainingFoot
                            AND ie.dimension = '$dimension'
                            AND ie.color = '$color'
                            AND ie.exc_id != {$halfData['exc_id']}
                        ORDER BY 
                            ie.exc_foot ASC, ie.exc_id ASC
                        LIMIT 1
                    ";
                    $remainingHalfResult = mysqli_query($conn, $query);

                    if (mysqli_num_rows($remainingHalfResult) > 0) {
                        $remainingHalfData = mysqli_fetch_assoc($remainingHalfResult);
                        echo json_encode([
                            'status' => 'found_full',
                            'exc_id_first_half' => $halfData['exc_id'],
                            'first_half_exc_foot' => $halfData['exc_foot'],
                            'exc_id_second_half' => $remainingHalfData['exc_id'],
                            'second_half_exc_foot' => $remainingHalfData['exc_foot'],
                            'price' => $remainingHalfData['price'],
                            'color' => $remainingHalfData['color'],
                            'dimension' => $remainingHalfData['dimension'],
                            'half_foot' => $halfFoot
                        ]);
                    } else {
                        $query = "
                            SELECT item_id, item_name, foot, price, color, dimension, stock
                            FROM inventory
                            WHERE item_name = '$item_name' AND foot >= $remainingFoot AND dimension = '$dimension' AND color = '$color'
                            ORDER BY item_id ASC
                            LIMIT 1
                        ";
                        $remainingResult = mysqli_query($conn, $query);

                        if (mysqli_num_rows($remainingResult) > 0) {
                            $remainingData = mysqli_fetch_assoc($remainingResult);
                            echo json_encode([
                                'status' => 'found_half',
                                'exc_id' => $halfData['exc_id'],
                                'half_exc_foot' => $halfData['exc_foot'],
                                'remaining_item_id' => $remainingData['item_id'],
                                'remaining_foot' => $remainingData['foot'],
                                'remaining_price' => $remainingData['price'],
                                'remaining_color' => $remainingData['color'],
                                'remaining_dimension' => $remainingData['dimension'],
                                'remaining_stock' => $remainingData['stock'],
                                'half_foot' => $halfFoot
                            ]);
                        } else {
                            echo json_encode(['status' => 'not_found']);
                        }
                    }
                } else {
                    $query = "
                        SELECT item_id, item_name, foot, price, color, dimension, stock
                        FROM inventory
                        WHERE item_name = '$item_name' AND foot >= $hFoot AND dimension = '$dimension' AND color = '$color'
                        ORDER BY item_id ASC
                        LIMIT 1
                    ";
                    $newItemResult = mysqli_query($conn, $query);

                    if (mysqli_num_rows($newItemResult) > 0) {
                        $newItemData = mysqli_fetch_assoc($newItemResult);
                        echo json_encode([
                            'status' => 'found_new',
                            'item_id' => $newItemData['item_id'],
                            'item_name' => $newItemData['item_name'],
                            'foot' => $newItemData['foot'],
                            'price' => $newItemData['price'],
                            'color' => $newItemData['color'],
                            'dimension' => $newItemData['dimension'],
                            'stock' => $newItemData['stock'],
                        ]);
                    } else {
                        echo json_encode(['status' => 'not_found']);
                    }
                }
            }
        }
    }

    if ($action == 'lockstile') 
    {
        if (isset($_POST['item_name']) && isset($_POST['hFoot'])) 
        {
            $item_name = mysqli_real_escape_string($conn, $_POST['item_name']);
            $hFoot = mysqli_real_escape_string($conn, $_POST['hFoot']);
            $dimension = mysqli_real_escape_string($conn, $_POST['dimension']);
            $color = mysqli_real_escape_string($conn, $_POST['color']);

            $query = "
                SELECT 
                    ie.exc_id, 
                    ie.item_name,
                    ie.exc_foot,
                    inv.price,
                    ie.color,
                    ie.dimension
                FROM 
                    inventory_excess ie
                INNER JOIN 
                    inventory inv
                ON 
                    ie.item_name = inv.item_name
                    AND ie.dimension = inv.dimension
                    AND ie.color = inv.color
                WHERE 
                    ie.item_name = '$item_name' 
                    AND ie.exc_foot >= $hFoot
                    AND ie.dimension = '$dimension'
                    AND ie.color = '$color'
                ORDER BY 
                    ie.exc_foot ASC, ie.exc_id ASC
                LIMIT 1
            ";
            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
                $data = mysqli_fetch_assoc($result);
                echo json_encode([
                    'status' => 'found',
                    'exc_id' => $data['exc_id'],
                    'item_name' => $data['item_name'],
                    'exc_foot' => $data['exc_foot'],
                    'price' => $data['price'],
                    'color' => $data['color'],
                    'dimension' => $data['dimension'],
                ]);
            } else {
                // Step 2: Find half of hFoot
                $halfFoot = ceil($hFoot / 2);

                $query = "
                    SELECT 
                        ie.exc_id, 
                        ie.item_name,
                        ie.exc_foot,
                        inv.price,
                        ie.color,
                        ie.dimension
                    FROM 
                        inventory_excess ie
                    INNER JOIN 
                        inventory inv
                    ON 
                        ie.item_name = inv.item_name
                        AND ie.dimension = inv.dimension
                        AND ie.color = inv.color
                    WHERE 
                        ie.item_name = '$item_name' 
                        AND ie.exc_foot >= $halfFoot
                        AND ie.dimension = '$dimension'
                        AND ie.color = '$color'
                    ORDER BY 
                        ie.exc_foot ASC, ie.exc_id ASC
                    LIMIT 1
                ";
                $halfResult = mysqli_query($conn, $query);

                if (mysqli_num_rows($halfResult) > 0) {
                    $halfData = mysqli_fetch_assoc($halfResult);
                    $remainingFoot = $hFoot - $halfData['exc_foot'];

                    $query = "
                        SELECT 
                            ie.exc_id, 
                            ie.item_name,
                            ie.exc_foot,
                            inv.price,
                            ie.color,
                            ie.dimension 
                        FROM 
                            inventory_excess ie
                        INNER JOIN 
                            inventory inv
                        ON 
                            ie.item_name = inv.item_name
                            AND ie.dimension = inv.dimension
                            AND ie.color = inv.color
                        WHERE 
                            ie.item_name = '$item_name' 
                            AND ie.exc_foot >= $remainingFoot
                            AND ie.dimension = '$dimension'
                            AND ie.color = '$color'
                            AND ie.exc_id != {$halfData['exc_id']}
                        ORDER BY 
                            ie.exc_foot ASC, ie.exc_id ASC
                        LIMIT 1
                    ";
                    $remainingHalfResult = mysqli_query($conn, $query);

                    if (mysqli_num_rows($remainingHalfResult) > 0) {
                        $remainingHalfData = mysqli_fetch_assoc($remainingHalfResult);
                        echo json_encode([
                            'status' => 'found_full',
                            'exc_id_first_half' => $halfData['exc_id'],
                            'first_half_exc_foot' => $halfData['exc_foot'],
                            'exc_id_second_half' => $remainingHalfData['exc_id'],
                            'second_half_exc_foot' => $remainingHalfData['exc_foot'],
                            'price' => $remainingHalfData['price'],
                            'color' => $remainingHalfData['color'],
                            'dimension' => $remainingHalfData['dimension'],
                            'half_foot' => $halfFoot
                        ]);
                    } else {
                        $query = "
                            SELECT item_id, item_name, foot, price, color, dimension, stock
                            FROM inventory
                            WHERE item_name = '$item_name' AND foot >= $remainingFoot AND dimension = '$dimension' AND color = '$color'
                            ORDER BY item_id ASC
                            LIMIT 1
                        ";
                        $remainingResult = mysqli_query($conn, $query);

                        if (mysqli_num_rows($remainingResult) > 0) {
                            $remainingData = mysqli_fetch_assoc($remainingResult);
                            echo json_encode([
                                'status' => 'found_half',
                                'exc_id' => $halfData['exc_id'],
                                'half_exc_foot' => $halfData['exc_foot'],
                                'remaining_item_id' => $remainingData['item_id'],
                                'remaining_foot' => $remainingData['foot'],
                                'remaining_price' => $remainingData['price'],
                                'remaining_color' => $remainingData['color'],
                                'remaining_dimension' => $remainingData['dimension'],
                                'remaining_stock' => $remainingData['stock'],
                                'half_foot' => $halfFoot
                            ]);
                        } else {
                            echo json_encode(['status' => 'not_found']);
                        }
                    }
                } else {
                    $query = "
                        SELECT item_id, item_name, foot, price, color, dimension, stock
                        FROM inventory
                        WHERE item_name = '$item_name' AND foot >= $hFoot AND dimension = '$dimension' AND color = '$color'
                        ORDER BY item_id ASC
                        LIMIT 1
                    ";
                    $newItemResult = mysqli_query($conn, $query);

                    if (mysqli_num_rows($newItemResult) > 0) {
                        $newItemData = mysqli_fetch_assoc($newItemResult);
                        echo json_encode([
                            'status' => 'found_new',
                            'item_id' => $newItemData['item_id'],
                            'item_name' => $newItemData['item_name'],
                            'foot' => $newItemData['foot'],
                            'price' => $newItemData['price'],
                            'color' => $newItemData['color'],
                            'dimension' => $newItemData['dimension'],
                            'stock' => $newItemData['stock'],
                        ]);
                    } else {
                        echo json_encode(['status' => 'not_found']);
                    }
                }
            }
        }
    }

    if ($action == 'interlocker') 
    {
        if (isset($_POST['item_name']) && isset($_POST['hFoot'])) 
        {
            $item_name = mysqli_real_escape_string($conn, $_POST['item_name']);
            $hFoot = mysqli_real_escape_string($conn, $_POST['hFoot']);
            $dimension = mysqli_real_escape_string($conn, $_POST['dimension']);
            $color = mysqli_real_escape_string($conn, $_POST['color']);

            $query = "
                SELECT 
                    ie.exc_id, 
                    ie.item_name,
                    ie.exc_foot,
                    inv.price,
                    ie.color,
                    ie.dimension
                FROM 
                    inventory_excess ie
                INNER JOIN 
                    inventory inv
                ON 
                    ie.item_name = inv.item_name
                    AND ie.dimension = inv.dimension
                    AND ie.color = inv.color
                WHERE 
                    ie.item_name = '$item_name' 
                    AND ie.exc_foot >= $hFoot
                    AND ie.dimension = '$dimension'
                    AND ie.color = '$color'
                ORDER BY 
                    ie.exc_foot ASC, ie.exc_id ASC
                LIMIT 1
            ";
            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
                $data = mysqli_fetch_assoc($result);
                echo json_encode([
                    'status' => 'found',
                    'exc_id' => $data['exc_id'],
                    'item_name' => $data['item_name'],
                    'exc_foot' => $data['exc_foot'],
                    'price' => $data['price'],
                    'color' => $data['color'],
                    'dimension' => $data['dimension'],
                ]);
            } else {
                // Step 2: Find half of hFoot
                $halfFoot = ceil($hFoot / 2);

                $query = "
                    SELECT 
                        ie.exc_id, 
                        ie.item_name,
                        ie.exc_foot,
                        inv.price,
                        ie.color,
                        ie.dimension
                    FROM 
                        inventory_excess ie
                    INNER JOIN 
                        inventory inv
                    ON 
                        ie.item_name = inv.item_name
                        AND ie.dimension = inv.dimension
                        AND ie.color = inv.color
                    WHERE 
                        ie.item_name = '$item_name' 
                        AND ie.exc_foot >= $halfFoot
                        AND ie.dimension = '$dimension'
                        AND ie.color = '$color'
                    ORDER BY 
                        ie.exc_foot ASC, ie.exc_id ASC
                    LIMIT 1
                ";
                $halfResult = mysqli_query($conn, $query);

                if (mysqli_num_rows($halfResult) > 0) {
                    $halfData = mysqli_fetch_assoc($halfResult);
                    $remainingFoot = $hFoot - $halfData['exc_foot'];

                    $query = "
                        SELECT 
                            ie.exc_id, 
                            ie.item_name,
                            ie.exc_foot,
                            inv.price,
                            ie.color,
                            ie.dimension 
                        FROM 
                            inventory_excess ie
                        INNER JOIN 
                            inventory inv
                        ON 
                            ie.item_name = inv.item_name
                            AND ie.dimension = inv.dimension
                            AND ie.color = inv.color
                        WHERE 
                            ie.item_name = '$item_name' 
                            AND ie.exc_foot >= $remainingFoot
                            AND ie.dimension = '$dimension'
                            AND ie.color = '$color'
                            AND ie.exc_id != {$halfData['exc_id']}
                        ORDER BY 
                            ie.exc_foot ASC, ie.exc_id ASC
                        LIMIT 1
                    ";
                    $remainingHalfResult = mysqli_query($conn, $query);

                    if (mysqli_num_rows($remainingHalfResult) > 0) {
                        $remainingHalfData = mysqli_fetch_assoc($remainingHalfResult);
                        echo json_encode([
                            'status' => 'found_full',
                            'exc_id_first_half' => $halfData['exc_id'],
                            'first_half_exc_foot' => $halfData['exc_foot'],
                            'exc_id_second_half' => $remainingHalfData['exc_id'],
                            'second_half_exc_foot' => $remainingHalfData['exc_foot'],
                            'price' => $remainingHalfData['price'],
                            'color' => $remainingHalfData['color'],
                            'dimension' => $remainingHalfData['dimension'],
                            'half_foot' => $halfFoot
                        ]);
                    } else {
                        $query = "
                            SELECT item_id, item_name, foot, price, color, dimension, stock
                            FROM inventory
                            WHERE item_name = '$item_name' AND foot >= $remainingFoot AND dimension = '$dimension' AND color = '$color'
                            ORDER BY item_id ASC
                            LIMIT 1
                        ";
                        $remainingResult = mysqli_query($conn, $query);

                        if (mysqli_num_rows($remainingResult) > 0) {
                            $remainingData = mysqli_fetch_assoc($remainingResult);
                            echo json_encode([
                                'status' => 'found_half',
                                'exc_id' => $halfData['exc_id'],
                                'half_exc_foot' => $halfData['exc_foot'],
                                'remaining_item_id' => $remainingData['item_id'],
                                'remaining_foot' => $remainingData['foot'],
                                'remaining_price' => $remainingData['price'],
                                'remaining_color' => $remainingData['color'],
                                'remaining_dimension' => $remainingData['dimension'],
                                'remaining_stock' => $remainingData['stock'],
                                'half_foot' => $halfFoot
                            ]);
                        } else {
                            echo json_encode(['status' => 'not_found']);
                        }
                    }
                } else {
                    $query = "
                        SELECT item_id, item_name, foot, price, color, dimension, stock
                        FROM inventory
                        WHERE item_name = '$item_name' AND foot >= $hFoot AND dimension = '$dimension' AND color = '$color'
                        ORDER BY item_id ASC
                        LIMIT 1
                    ";
                    $newItemResult = mysqli_query($conn, $query);

                    if (mysqli_num_rows($newItemResult) > 0) {
                        $newItemData = mysqli_fetch_assoc($newItemResult);
                        echo json_encode([
                            'status' => 'found_new',
                            'item_id' => $newItemData['item_id'],
                            'item_name' => $newItemData['item_name'],
                            'foot' => $newItemData['foot'],
                            'price' => $newItemData['price'],
                            'color' => $newItemData['color'],
                            'dimension' => $newItemData['dimension'],
                            'stock' => $newItemData['stock'],
                        ]);
                    } else {
                        echo json_encode(['status' => 'not_found']);
                    }
                }
            }
        }
    }

    if ($action == 'glass') 
    {
        if (isset($_POST['item_name'])) 
        {
            $item_name = mysqli_real_escape_string($conn, $_POST['item_name']);

            $query = "SELECT item_id, item_name, price, stock FROM inventory WHERE item_name = '$item_name'";

            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
                $data = mysqli_fetch_assoc($result);
                echo json_encode([
                    'status' => 'found',
                    'item_id' => $data['item_id'],
                    'item_name' => $data['item_name'],
                    'price' => $data['price'],
                    'stock' => $data['stock']
                ]);
            }
        }
    }

    if ($action == 'HeadAwning') {
        if (isset($_POST['item_name']) && isset($_POST['lFoot'])) {
            // Escape special characters in input to prevent SQL injection
            $item_name = mysqli_real_escape_string($conn, $_POST['item_name']);
            $lFoot = mysqli_real_escape_string($conn, $_POST['lFoot']);
            $dimension = mysqli_real_escape_string($conn, $_POST['dimension']);
            $color = mysqli_real_escape_string($conn, $_POST['color']);
    
            // Updated query to use an inner join between inventory_excess and inventory
            $query = "
                SELECT 
                    ie.exc_id, 
                    ie.item_name,
                    ie.exc_foot,
                    inv.price,
                    ie.color,
                    ie.dimension
                FROM 
                    inventory_excess ie
                INNER JOIN 
                    inventory inv
                ON 
                    ie.item_name = inv.item_name
                    AND ie.dimension = inv.dimension
                    AND ie.color = inv.color
                WHERE 
                    ie.item_name = '$item_name' 
                    AND ie.exc_foot >= $lFoot
                    AND ie.dimension = '$dimension'
                    AND ie.color = '$color'
                ORDER BY 
                    ie.exc_foot ASC, ie.exc_id ASC
                LIMIT 1
            ";
    
            $result = mysqli_query($conn, $query);
            if ($result && mysqli_num_rows($result) > 0) {
                // Fetch the result as an associative array
                $data = mysqli_fetch_assoc($result);
                echo json_encode([
                    'status' => 'found',
                    'exc_id' => $data['exc_id'],
                    'item_name' => $data['item_name'],
                    'exc_foot' => $data['exc_foot'],
                    'price' => $data['price'],
                    'color' => $data['color'],
                    'dimension' => $data['dimension']
                ]);
            } else {
                // No matching item found
                $query = "
                    SELECT * 
                    FROM inventory 
                    WHERE item_name = '$item_name' 
                    AND dimension = '$dimension' 
                    AND color = '$color' 
                    LIMIT 1
                ";
                
                $result = mysqli_query($conn, $query);

                if ($result && mysqli_num_rows($result) > 0) 
                {
                    $data = mysqli_fetch_assoc($result);

                    echo json_encode([
                        'item_id' => $data['item_id'],
                        'foot' => $data['foot'], 
                        'price' => $data['price'],
                        'color' => $data['color'],
                        'dimension' => $data['dimension'],
                        'stock' => $data['stock'],
                        'status' => 'not_found'
                    ]);
                } 
            }
        }
    }
    
    if ($action == 'SillAwning') {
        if (isset($_POST['item_name']) && isset($_POST['lFoot'])) {
            // Escape special characters in input to prevent SQL injection
            $item_name = mysqli_real_escape_string($conn, $_POST['item_name']);
            $lFoot = mysqli_real_escape_string($conn, $_POST['lFoot']);
            $dimension = mysqli_real_escape_string($conn, $_POST['dimension']);
            $color = mysqli_real_escape_string($conn, $_POST['color']);
    
            // Updated query to use an inner join between inventory_excess and inventory
            $query = "
                SELECT 
                    ie.exc_id, 
                    ie.item_name,
                    ie.exc_foot,
                    inv.price,
                    ie.color,
                    ie.dimension
                FROM 
                    inventory_excess ie
                INNER JOIN 
                    inventory inv
                ON 
                    ie.item_name = inv.item_name
                    AND ie.dimension = inv.dimension
                    AND ie.color = inv.color
                WHERE 
                    ie.item_name = '$item_name' 
                    AND ie.exc_foot >= $lFoot
                    AND ie.dimension = '$dimension'
                    AND ie.color = '$color'
                ORDER BY 
                    ie.exc_foot ASC, ie.exc_id ASC
                LIMIT 1
            ";
    
            $result = mysqli_query($conn, $query);
            if ($result && mysqli_num_rows($result) > 0) {
                // Fetch the result as an associative array
                $data = mysqli_fetch_assoc($result);
                echo json_encode([
                    'status' => 'found',
                    'exc_id' => $data['exc_id'],
                    'item_name' => $data['item_name'],
                    'exc_foot' => $data['exc_foot'],
                    'price' => $data['price'],
                    'color' => $data['color'],
                    'dimension' => $data['dimension']
                ]);
            } else {
                // No matching item found
                $query = "
                    SELECT * 
                    FROM inventory 
                    WHERE item_name = '$item_name' 
                    AND dimension = '$dimension' 
                    AND color = '$color' 
                    LIMIT 1
                ";
                
                $result = mysqli_query($conn, $query);

                if ($result && mysqli_num_rows($result) > 0) 
                {
                    $data = mysqli_fetch_assoc($result);

                    echo json_encode([
                        'item_id' => $data['item_id'],
                        'foot' => $data['foot'], 
                        'price' => $data['price'],
                        'color' => $data['color'],
                        'dimension' => $data['dimension'],
                        'stock' => $data['stock'],
                        'status' => 'not_found'
                    ]);
                } 
            }
        }
    }

    if ($action == 'JambAwning') 
    {
        if (isset($_POST['item_name']) && isset($_POST['hFoot'])) 
        {
            $item_name = mysqli_real_escape_string($conn, $_POST['item_name']);
            $hFoot = mysqli_real_escape_string($conn, $_POST['hFoot']);
            $dimension = mysqli_real_escape_string($conn, $_POST['dimension']);
            $color = mysqli_real_escape_string($conn, $_POST['color']);

            $query = "
                SELECT 
                    ie.exc_id, 
                    ie.item_name,
                    ie.exc_foot,
                    inv.price,
                    ie.color,
                    ie.dimension
                FROM 
                    inventory_excess ie
                INNER JOIN 
                    inventory inv
                ON 
                    ie.item_name = inv.item_name
                    AND ie.dimension = inv.dimension
                    AND ie.color = inv.color
                WHERE 
                    ie.item_name = '$item_name' 
                    AND ie.exc_foot >= $hFoot
                    AND ie.dimension = '$dimension'
                    AND ie.color = '$color'
                ORDER BY 
                    ie.exc_foot ASC, ie.exc_id ASC
                LIMIT 1
            ";
            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
                $data = mysqli_fetch_assoc($result);
                echo json_encode([
                    'status' => 'found',
                    'exc_id' => $data['exc_id'],
                    'item_name' => $data['item_name'],
                    'exc_foot' => $data['exc_foot'],
                    'price' => $data['price'],
                    'color' => $data['color'],
                    'dimension' => $data['dimension'],
                ]);
            } else {
                // Step 2: Find half of hFoot
                $halfFoot = ceil($hFoot / 2);

                $query = "
                    SELECT 
                        ie.exc_id, 
                        ie.item_name,
                        ie.exc_foot,
                        inv.price,
                        ie.color,
                        ie.dimension
                    FROM 
                        inventory_excess ie
                    INNER JOIN 
                        inventory inv
                    ON 
                        ie.item_name = inv.item_name
                        AND ie.dimension = inv.dimension
                        AND ie.color = inv.color
                    WHERE 
                        ie.item_name = '$item_name' 
                        AND ie.exc_foot >= $halfFoot
                        AND ie.dimension = '$dimension'
                        AND ie.color = '$color'
                    ORDER BY 
                        ie.exc_foot ASC, ie.exc_id ASC
                    LIMIT 1
                ";
                $halfResult = mysqli_query($conn, $query);

                if (mysqli_num_rows($halfResult) > 0) {
                    $halfData = mysqli_fetch_assoc($halfResult);
                    $remainingFoot = $hFoot - $halfData['exc_foot'];

                    $query = "
                        SELECT 
                            ie.exc_id, 
                            ie.item_name,
                            ie.exc_foot,
                            inv.price,
                            ie.color,
                            ie.dimension 
                        FROM 
                            inventory_excess ie
                        INNER JOIN 
                            inventory inv
                        ON 
                            ie.item_name = inv.item_name
                            AND ie.dimension = inv.dimension
                            AND ie.color = inv.color
                        WHERE 
                            ie.item_name = '$item_name' 
                            AND ie.exc_foot >= $remainingFoot
                            AND ie.dimension = '$dimension'
                            AND ie.color = '$color'
                            AND ie.exc_id != {$halfData['exc_id']}
                        ORDER BY 
                            ie.exc_foot ASC, ie.exc_id ASC
                        LIMIT 1
                    ";
                    $remainingHalfResult = mysqli_query($conn, $query);

                    if (mysqli_num_rows($remainingHalfResult) > 0) {
                        $remainingHalfData = mysqli_fetch_assoc($remainingHalfResult);
                        echo json_encode([
                            'status' => 'found_full',
                            'exc_id_first_half' => $halfData['exc_id'],
                            'first_half_exc_foot' => $halfData['exc_foot'],
                            'exc_id_second_half' => $remainingHalfData['exc_id'],
                            'second_half_exc_foot' => $remainingHalfData['exc_foot'],
                            'price' => $remainingHalfData['price'],
                            'color' => $remainingHalfData['color'],
                            'dimension' => $remainingHalfData['dimension'],
                            'half_foot' => $halfFoot
                        ]);
                    } else {
                        $query = "
                            SELECT item_id, item_name, foot, price, color, dimension, stock
                            FROM inventory
                            WHERE item_name = '$item_name' AND foot >= $remainingFoot AND dimension = '$dimension' AND color = '$color'
                            ORDER BY item_id ASC
                            LIMIT 1
                        ";
                        $remainingResult = mysqli_query($conn, $query);

                        if (mysqli_num_rows($remainingResult) > 0) {
                            $remainingData = mysqli_fetch_assoc($remainingResult);
                            echo json_encode([
                                'status' => 'found_half',
                                'exc_id' => $halfData['exc_id'],
                                'half_exc_foot' => $halfData['exc_foot'],
                                'remaining_item_id' => $remainingData['item_id'],
                                'remaining_foot' => $remainingData['foot'],
                                'remaining_price' => $remainingData['price'],
                                'remaining_color' => $remainingData['color'],
                                'remaining_dimension' => $remainingData['dimension'],
                                'remaining_stock' => $remainingData['stock'],
                                'half_foot' => $halfFoot
                            ]);
                        } else {
                            echo json_encode(['status' => 'not_found']);
                        }
                    }
                } else {
                    $query = "
                        SELECT item_id, item_name, foot, price, color, dimension, stock
                        FROM inventory
                        WHERE item_name = '$item_name' AND foot >= $hFoot AND dimension = '$dimension' AND color = '$color'
                        ORDER BY item_id ASC
                        LIMIT 1
                    ";
                    $newItemResult = mysqli_query($conn, $query);

                    if (mysqli_num_rows($newItemResult) > 0) {
                        $newItemData = mysqli_fetch_assoc($newItemResult);
                        echo json_encode([
                            'status' => 'found_new',
                            'item_id' => $newItemData['item_id'],
                            'item_name' => $newItemData['item_name'],
                            'foot' => $newItemData['foot'],
                            'price' => $newItemData['price'],
                            'color' => $newItemData['color'],
                            'dimension' => $newItemData['dimension'],
                            'stock' => $newItemData['stock'],
                        ]);
                    } else {
                        echo json_encode(['status' => 'not_found']);
                    }
                }
            }
        }
    }

    if ($action == 'GlassAwning') 
    {
        if (isset($_POST['item_name'])) 
        {
            $item_name = mysqli_real_escape_string($conn, $_POST['item_name']);

            $query = "SELECT item_id, item_name, price, stock FROM inventory WHERE item_name = '$item_name'";

            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
                $data = mysqli_fetch_assoc($result);
                echo json_encode([
                    'status' => 'found',
                    'item_id' => $data['item_id'],
                    'item_name' => $data['item_name'],
                    'price' => $data['price'],
                    'stock' => $data['stock']
                ]);
            }
        }
    }

    if ($action == 'HeadFixed') {
        if (isset($_POST['item_name']) && isset($_POST['lFoot'])) {
            // Escape special characters in input to prevent SQL injection
            $item_name = mysqli_real_escape_string($conn, $_POST['item_name']);
            $lFoot = mysqli_real_escape_string($conn, $_POST['lFoot']);
            $dimension = mysqli_real_escape_string($conn, $_POST['dimension']);
            $color = mysqli_real_escape_string($conn, $_POST['color']);
    
            // Updated query to use an inner join between inventory_excess and inventory
            $query = "
                SELECT 
                    ie.exc_id, 
                    ie.item_name,
                    ie.exc_foot,
                    inv.price,
                    ie.color,
                    ie.dimension
                FROM 
                    inventory_excess ie
                INNER JOIN 
                    inventory inv
                ON 
                    ie.item_name = inv.item_name
                    AND ie.dimension = inv.dimension
                    AND ie.color = inv.color
                WHERE 
                    ie.item_name = '$item_name' 
                    AND ie.exc_foot >= $lFoot
                    AND ie.dimension = '$dimension'
                    AND ie.color = '$color'
                ORDER BY 
                    ie.exc_foot ASC, ie.exc_id ASC
                LIMIT 1
            ";
    
            $result = mysqli_query($conn, $query);
            if ($result && mysqli_num_rows($result) > 0) {
                // Fetch the result as an associative array
                $data = mysqli_fetch_assoc($result);
                echo json_encode([
                    'status' => 'found',
                    'exc_id' => $data['exc_id'],
                    'item_name' => $data['item_name'],
                    'exc_foot' => $data['exc_foot'],
                    'price' => $data['price'],
                    'color' => $data['color'],
                    'dimension' => $data['dimension']
                ]);
            } else {
                // No matching item found
                $query = "
                    SELECT * 
                    FROM inventory 
                    WHERE item_name = '$item_name' 
                    AND dimension = '$dimension' 
                    AND color = '$color' 
                    LIMIT 1
                ";
                
                $result = mysqli_query($conn, $query);

                if ($result && mysqli_num_rows($result) > 0) 
                {
                    $data = mysqli_fetch_assoc($result);

                    echo json_encode([
                        'item_id' => $data['item_id'],
                        'foot' => $data['foot'], 
                        'price' => $data['price'],
                        'color' => $data['color'],
                        'dimension' => $data['dimension'],
                        'stock' => $data['stock'],
                        'status' => 'not_found'
                    ]);
                } 
            }
        }
    }

    if ($action == 'SillFixed') {
        if (isset($_POST['item_name']) && isset($_POST['lFoot'])) {
            // Escape special characters in input to prevent SQL injection
            $item_name = mysqli_real_escape_string($conn, $_POST['item_name']);
            $lFoot = mysqli_real_escape_string($conn, $_POST['lFoot']);
            $dimension = mysqli_real_escape_string($conn, $_POST['dimension']);
            $color = mysqli_real_escape_string($conn, $_POST['color']);
    
            // Updated query to use an inner join between inventory_excess and inventory
            $query = "
                SELECT 
                    ie.exc_id, 
                    ie.item_name,
                    ie.exc_foot,
                    inv.price,
                    ie.color,
                    ie.dimension
                FROM 
                    inventory_excess ie
                INNER JOIN 
                    inventory inv
                ON 
                    ie.item_name = inv.item_name
                    AND ie.dimension = inv.dimension
                    AND ie.color = inv.color
                WHERE 
                    ie.item_name = '$item_name' 
                    AND ie.exc_foot >= $lFoot
                    AND ie.dimension = '$dimension'
                    AND ie.color = '$color'
                ORDER BY 
                    ie.exc_foot ASC, ie.exc_id ASC
                LIMIT 1
            ";
    
            $result = mysqli_query($conn, $query);
            if ($result && mysqli_num_rows($result) > 0) {
                // Fetch the result as an associative array
                $data = mysqli_fetch_assoc($result);
                echo json_encode([
                    'status' => 'found',
                    'exc_id' => $data['exc_id'],
                    'item_name' => $data['item_name'],
                    'exc_foot' => $data['exc_foot'],
                    'price' => $data['price'],
                    'color' => $data['color'],
                    'dimension' => $data['dimension']
                ]);
            } else {
                // No matching item found
                $query = "
                    SELECT * 
                    FROM inventory 
                    WHERE item_name = '$item_name' 
                    AND dimension = '$dimension' 
                    AND color = '$color' 
                    LIMIT 1
                ";
                
                $result = mysqli_query($conn, $query);

                if ($result && mysqli_num_rows($result) > 0) 
                {
                    $data = mysqli_fetch_assoc($result);

                    echo json_encode([
                        'item_id' => $data['item_id'],
                        'foot' => $data['foot'], 
                        'price' => $data['price'],
                        'color' => $data['color'],
                        'dimension' => $data['dimension'],
                        'stock' => $data['stock'],
                        'status' => 'not_found'
                    ]);
                } 
            }
        }
    }

    if ($action == 'JambFixed') 
    {
        if (isset($_POST['item_name']) && isset($_POST['hFoot'])) 
        {
            $item_name = mysqli_real_escape_string($conn, $_POST['item_name']);
            $hFoot = mysqli_real_escape_string($conn, $_POST['hFoot']);
            $dimension = mysqli_real_escape_string($conn, $_POST['dimension']);
            $color = mysqli_real_escape_string($conn, $_POST['color']);

            $query = "
                SELECT 
                    ie.exc_id, 
                    ie.item_name,
                    ie.exc_foot,
                    inv.price,
                    ie.color,
                    ie.dimension
                FROM 
                    inventory_excess ie
                INNER JOIN 
                    inventory inv
                ON 
                    ie.item_name = inv.item_name
                    AND ie.dimension = inv.dimension
                    AND ie.color = inv.color
                WHERE 
                    ie.item_name = '$item_name' 
                    AND ie.exc_foot >= $hFoot
                    AND ie.dimension = '$dimension'
                    AND ie.color = '$color'
                ORDER BY 
                    ie.exc_foot ASC, ie.exc_id ASC
                LIMIT 1
            ";
            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
                $data = mysqli_fetch_assoc($result);
                echo json_encode([
                    'status' => 'found',
                    'exc_id' => $data['exc_id'],
                    'item_name' => $data['item_name'],
                    'exc_foot' => $data['exc_foot'],
                    'price' => $data['price'],
                    'color' => $data['color'],
                    'dimension' => $data['dimension'],
                ]);
            } else {
                // Step 2: Find half of hFoot
                $halfFoot = ceil($hFoot / 2);

                $query = "
                    SELECT 
                        ie.exc_id, 
                        ie.item_name,
                        ie.exc_foot,
                        inv.price,
                        ie.color,
                        ie.dimension
                    FROM 
                        inventory_excess ie
                    INNER JOIN 
                        inventory inv
                    ON 
                        ie.item_name = inv.item_name
                        AND ie.dimension = inv.dimension
                        AND ie.color = inv.color
                    WHERE 
                        ie.item_name = '$item_name' 
                        AND ie.exc_foot >= $halfFoot
                        AND ie.dimension = '$dimension'
                        AND ie.color = '$color'
                    ORDER BY 
                        ie.exc_foot ASC, ie.exc_id ASC
                    LIMIT 1
                ";
                $halfResult = mysqli_query($conn, $query);

                if (mysqli_num_rows($halfResult) > 0) {
                    $halfData = mysqli_fetch_assoc($halfResult);
                    $remainingFoot = $hFoot - $halfData['exc_foot'];

                    $query = "
                        SELECT 
                            ie.exc_id, 
                            ie.item_name,
                            ie.exc_foot,
                            inv.price,
                            ie.color,
                            ie.dimension 
                        FROM 
                            inventory_excess ie
                        INNER JOIN 
                            inventory inv
                        ON 
                            ie.item_name = inv.item_name
                            AND ie.dimension = inv.dimension
                            AND ie.color = inv.color
                        WHERE 
                            ie.item_name = '$item_name' 
                            AND ie.exc_foot >= $remainingFoot
                            AND ie.dimension = '$dimension'
                            AND ie.color = '$color'
                            AND ie.exc_id != {$halfData['exc_id']}
                        ORDER BY 
                            ie.exc_foot ASC, ie.exc_id ASC
                        LIMIT 1
                    ";
                    $remainingHalfResult = mysqli_query($conn, $query);

                    if (mysqli_num_rows($remainingHalfResult) > 0) {
                        $remainingHalfData = mysqli_fetch_assoc($remainingHalfResult);
                        echo json_encode([
                            'status' => 'found_full',
                            'exc_id_first_half' => $halfData['exc_id'],
                            'first_half_exc_foot' => $halfData['exc_foot'],
                            'exc_id_second_half' => $remainingHalfData['exc_id'],
                            'second_half_exc_foot' => $remainingHalfData['exc_foot'],
                            'price' => $remainingHalfData['price'],
                            'color' => $remainingHalfData['color'],
                            'dimension' => $remainingHalfData['dimension'],
                            'half_foot' => $halfFoot
                        ]);
                    } else {
                        $query = "
                            SELECT item_id, item_name, foot, price, color, dimension, stock
                            FROM inventory
                            WHERE item_name = '$item_name' AND foot >= $remainingFoot AND dimension = '$dimension' AND color = '$color'
                            ORDER BY item_id ASC
                            LIMIT 1
                        ";
                        $remainingResult = mysqli_query($conn, $query);

                        if (mysqli_num_rows($remainingResult) > 0) {
                            $remainingData = mysqli_fetch_assoc($remainingResult);
                            echo json_encode([
                                'status' => 'found_half',
                                'exc_id' => $halfData['exc_id'],
                                'half_exc_foot' => $halfData['exc_foot'],
                                'remaining_item_id' => $remainingData['item_id'],
                                'remaining_foot' => $remainingData['foot'],
                                'remaining_price' => $remainingData['price'],
                                'remaining_color' => $remainingData['color'],
                                'remaining_dimension' => $remainingData['dimension'],
                                'remaining_stock' => $remainingData['stock'],
                                'half_foot' => $halfFoot
                            ]);
                        } else {
                            echo json_encode(['status' => 'not_found']);
                        }
                    }
                } else {
                    $query = "
                        SELECT item_id, item_name, foot, price, color, dimension, stock
                        FROM inventory
                        WHERE item_name = '$item_name' AND foot >= $hFoot AND dimension = '$dimension' AND color = '$color'
                        ORDER BY item_id ASC
                        LIMIT 1
                    ";
                    $newItemResult = mysqli_query($conn, $query);

                    if (mysqli_num_rows($newItemResult) > 0) {
                        $newItemData = mysqli_fetch_assoc($newItemResult);
                        echo json_encode([
                            'status' => 'found_new',
                            'item_id' => $newItemData['item_id'],
                            'item_name' => $newItemData['item_name'],
                            'foot' => $newItemData['foot'],
                            'price' => $newItemData['price'],
                            'color' => $newItemData['color'],
                            'dimension' => $newItemData['dimension'],
                            'stock' => $newItemData['stock'],
                        ]);
                    } else {
                        echo json_encode(['status' => 'not_found']);
                    }
                }
            }
        }
    }

    if ($action == 'GlassFixed') 
    {
        if (isset($_POST['item_name'])) 
        {
            $item_name = mysqli_real_escape_string($conn, $_POST['item_name']);

            $query = "SELECT item_id, item_name, price, stock FROM inventory WHERE item_name = '$item_name'";

            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
                $data = mysqli_fetch_assoc($result);
                echo json_encode([
                    'status' => 'found',
                    'item_id' => $data['item_id'],
                    'item_name' => $data['item_name'],
                    'price' => $data['price'],
                    'stock' => $data['stock']
                ]);
            }
        }
    }

    if ($action == 'GlassCasement') 
    {
        if (isset($_POST['item_name'])) 
        {
            $item_name = mysqli_real_escape_string($conn, $_POST['item_name']);

            $query = "SELECT item_id, item_name, price, stock FROM inventory WHERE item_name = '$item_name'";

            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
                $data = mysqli_fetch_assoc($result);
                echo json_encode([
                    'status' => 'found',
                    'item_id' => $data['item_id'],
                    'item_name' => $data['item_name'],
                    'price' => $data['price'],
                    'stock' => $data['stock']
                ]);
            }
        }
    }

    if ($action == 'HeadCasement') {
        if (isset($_POST['item_name']) && isset($_POST['lFoot'])) {
            // Escape special characters in input to prevent SQL injection
            $item_name = mysqli_real_escape_string($conn, $_POST['item_name']);
            $lFoot = mysqli_real_escape_string($conn, $_POST['lFoot']);
            $dimension = mysqli_real_escape_string($conn, $_POST['dimension']);
            $color = mysqli_real_escape_string($conn, $_POST['color']);
    
            // Updated query to use an inner join between inventory_excess and inventory
            $query = "
                SELECT 
                    ie.exc_id, 
                    ie.item_name,
                    ie.exc_foot,
                    inv.price,
                    ie.color,
                    ie.dimension
                FROM 
                    inventory_excess ie
                INNER JOIN 
                    inventory inv
                ON 
                    ie.item_name = inv.item_name
                    AND ie.dimension = inv.dimension
                    AND ie.color = inv.color
                WHERE 
                    ie.item_name = '$item_name' 
                    AND ie.exc_foot >= $lFoot
                    AND ie.dimension = '$dimension'
                    AND ie.color = '$color'
                ORDER BY 
                    ie.exc_foot ASC, ie.exc_id ASC
                LIMIT 1
            ";
    
            $result = mysqli_query($conn, $query);
            if ($result && mysqli_num_rows($result) > 0) {
                // Fetch the result as an associative array
                $data = mysqli_fetch_assoc($result);
                echo json_encode([
                    'status' => 'found',
                    'exc_id' => $data['exc_id'],
                    'item_name' => $data['item_name'],
                    'exc_foot' => $data['exc_foot'],
                    'price' => $data['price'],
                    'color' => $data['color'],
                    'dimension' => $data['dimension']
                ]);
            } else {
                // No matching item found
                $query = "
                    SELECT * 
                    FROM inventory 
                    WHERE item_name = '$item_name' 
                    AND dimension = '$dimension' 
                    AND color = '$color' 
                    LIMIT 1
                ";
                
                $result = mysqli_query($conn, $query);

                if ($result && mysqli_num_rows($result) > 0) 
                {
                    $data = mysqli_fetch_assoc($result);

                    echo json_encode([
                        'item_id' => $data['item_id'],
                        'foot' => $data['foot'], 
                        'price' => $data['price'],
                        'color' => $data['color'],
                        'dimension' => $data['dimension'],
                        'stock' => $data['stock'],
                        'status' => 'not_found'
                    ]);
                } 
            }
        }
    }

    if ($action == 'SillCasement') {
        if (isset($_POST['item_name']) && isset($_POST['lFoot'])) {
            // Escape special characters in input to prevent SQL injection
            $item_name = mysqli_real_escape_string($conn, $_POST['item_name']);
            $lFoot = mysqli_real_escape_string($conn, $_POST['lFoot']);
            $dimension = mysqli_real_escape_string($conn, $_POST['dimension']);
            $color = mysqli_real_escape_string($conn, $_POST['color']);
    
            // Updated query to use an inner join between inventory_excess and inventory
            $query = "
                SELECT 
                    ie.exc_id, 
                    ie.item_name,
                    ie.exc_foot,
                    inv.price,
                    ie.color,
                    ie.dimension
                FROM 
                    inventory_excess ie
                INNER JOIN 
                    inventory inv
                ON 
                    ie.item_name = inv.item_name
                    AND ie.dimension = inv.dimension
                    AND ie.color = inv.color
                WHERE 
                    ie.item_name = '$item_name' 
                    AND ie.exc_foot >= $lFoot
                    AND ie.dimension = '$dimension'
                    AND ie.color = '$color'
                ORDER BY 
                    ie.exc_foot ASC, ie.exc_id ASC
                LIMIT 1
            ";
    
            $result = mysqli_query($conn, $query);
            if ($result && mysqli_num_rows($result) > 0) {
                // Fetch the result as an associative array
                $data = mysqli_fetch_assoc($result);
                echo json_encode([
                    'status' => 'found',
                    'exc_id' => $data['exc_id'],
                    'item_name' => $data['item_name'],
                    'exc_foot' => $data['exc_foot'],
                    'price' => $data['price'],
                    'color' => $data['color'],
                    'dimension' => $data['dimension']
                ]);
            } else {
                // No matching item found
                $query = "
                    SELECT * 
                    FROM inventory 
                    WHERE item_name = '$item_name' 
                    AND dimension = '$dimension' 
                    AND color = '$color' 
                    LIMIT 1
                ";
                
                $result = mysqli_query($conn, $query);

                if ($result && mysqli_num_rows($result) > 0) 
                {
                    $data = mysqli_fetch_assoc($result);

                    echo json_encode([
                        'item_id' => $data['item_id'],
                        'foot' => $data['foot'], 
                        'price' => $data['price'],
                        'color' => $data['color'],
                        'dimension' => $data['dimension'],
                        'stock' => $data['stock'],
                        'status' => 'not_found'
                    ]);
                } 
            }
        }
    }

    if ($action == 'JambCasement') 
    {
        if (isset($_POST['item_name']) && isset($_POST['hFoot'])) 
        {
            $item_name = mysqli_real_escape_string($conn, $_POST['item_name']);
            $hFoot = mysqli_real_escape_string($conn, $_POST['hFoot']);
            $dimension = mysqli_real_escape_string($conn, $_POST['dimension']);
            $color = mysqli_real_escape_string($conn, $_POST['color']);

            $query = "
                SELECT 
                    ie.exc_id, 
                    ie.item_name,
                    ie.exc_foot,
                    inv.price,
                    ie.color,
                    ie.dimension
                FROM 
                    inventory_excess ie
                INNER JOIN 
                    inventory inv
                ON 
                    ie.item_name = inv.item_name
                    AND ie.dimension = inv.dimension
                    AND ie.color = inv.color
                WHERE 
                    ie.item_name = '$item_name' 
                    AND ie.exc_foot >= $hFoot
                    AND ie.dimension = '$dimension'
                    AND ie.color = '$color'
                ORDER BY 
                    ie.exc_foot ASC, ie.exc_id ASC
                LIMIT 1
            ";
            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
                $data = mysqli_fetch_assoc($result);
                echo json_encode([
                    'status' => 'found',
                    'exc_id' => $data['exc_id'],
                    'item_name' => $data['item_name'],
                    'exc_foot' => $data['exc_foot'],
                    'price' => $data['price'],
                    'color' => $data['color'],
                    'dimension' => $data['dimension'],
                ]);
            } else {
                // Step 2: Find half of hFoot
                $halfFoot = ceil($hFoot / 2);

                $query = "
                    SELECT 
                        ie.exc_id, 
                        ie.item_name,
                        ie.exc_foot,
                        inv.price,
                        ie.color,
                        ie.dimension
                    FROM 
                        inventory_excess ie
                    INNER JOIN 
                        inventory inv
                    ON 
                        ie.item_name = inv.item_name
                        AND ie.dimension = inv.dimension
                        AND ie.color = inv.color
                    WHERE 
                        ie.item_name = '$item_name' 
                        AND ie.exc_foot >= $halfFoot
                        AND ie.dimension = '$dimension'
                        AND ie.color = '$color'
                    ORDER BY 
                        ie.exc_foot ASC, ie.exc_id ASC
                    LIMIT 1
                ";
                $halfResult = mysqli_query($conn, $query);

                if (mysqli_num_rows($halfResult) > 0) {
                    $halfData = mysqli_fetch_assoc($halfResult);
                    $remainingFoot = $hFoot - $halfData['exc_foot'];

                    $query = "
                        SELECT 
                            ie.exc_id, 
                            ie.item_name,
                            ie.exc_foot,
                            inv.price,
                            ie.color,
                            ie.dimension 
                        FROM 
                            inventory_excess ie
                        INNER JOIN 
                            inventory inv
                        ON 
                            ie.item_name = inv.item_name
                            AND ie.dimension = inv.dimension
                            AND ie.color = inv.color
                        WHERE 
                            ie.item_name = '$item_name' 
                            AND ie.exc_foot >= $remainingFoot
                            AND ie.dimension = '$dimension'
                            AND ie.color = '$color'
                            AND ie.exc_id != {$halfData['exc_id']}
                        ORDER BY 
                            ie.exc_foot ASC, ie.exc_id ASC
                        LIMIT 1
                    ";
                    $remainingHalfResult = mysqli_query($conn, $query);

                    if (mysqli_num_rows($remainingHalfResult) > 0) {
                        $remainingHalfData = mysqli_fetch_assoc($remainingHalfResult);
                        echo json_encode([
                            'status' => 'found_full',
                            'exc_id_first_half' => $halfData['exc_id'],
                            'first_half_exc_foot' => $halfData['exc_foot'],
                            'exc_id_second_half' => $remainingHalfData['exc_id'],
                            'second_half_exc_foot' => $remainingHalfData['exc_foot'],
                            'price' => $remainingHalfData['price'],
                            'color' => $remainingHalfData['color'],
                            'dimension' => $remainingHalfData['dimension'],
                            'half_foot' => $halfFoot
                        ]);
                    } else {
                        $query = "
                            SELECT item_id, item_name, foot, price, color, dimension, stock
                            FROM inventory
                            WHERE item_name = '$item_name' AND foot >= $remainingFoot AND dimension = '$dimension' AND color = '$color'
                            ORDER BY item_id ASC
                            LIMIT 1
                        ";
                        $remainingResult = mysqli_query($conn, $query);

                        if (mysqli_num_rows($remainingResult) > 0) {
                            $remainingData = mysqli_fetch_assoc($remainingResult);
                            echo json_encode([
                                'status' => 'found_half',
                                'exc_id' => $halfData['exc_id'],
                                'half_exc_foot' => $halfData['exc_foot'],
                                'remaining_item_id' => $remainingData['item_id'],
                                'remaining_foot' => $remainingData['foot'],
                                'remaining_price' => $remainingData['price'],
                                'remaining_color' => $remainingData['color'],
                                'remaining_dimension' => $remainingData['dimension'],
                                'remaining_stock' => $remainingData['stock'],
                                'half_foot' => $halfFoot
                            ]);
                        } else {
                            echo json_encode(['status' => 'not_found']);
                        }
                    }
                } else {
                    $query = "
                        SELECT item_id, item_name, foot, price, color, dimension, stock
                        FROM inventory
                        WHERE item_name = '$item_name' AND foot >= $hFoot AND dimension = '$dimension' AND color = '$color'
                        ORDER BY item_id ASC
                        LIMIT 1
                    ";
                    $newItemResult = mysqli_query($conn, $query);

                    if (mysqli_num_rows($newItemResult) > 0) {
                        $newItemData = mysqli_fetch_assoc($newItemResult);
                        echo json_encode([
                            'status' => 'found_new',
                            'item_id' => $newItemData['item_id'],
                            'item_name' => $newItemData['item_name'],
                            'foot' => $newItemData['foot'],
                            'price' => $newItemData['price'],
                            'color' => $newItemData['color'],
                            'dimension' => $newItemData['dimension'],
                            'stock' => $newItemData['stock'],
                        ]);
                    } else {
                        echo json_encode(['status' => 'not_found']);
                    }
                }
            }
        }
    }
?>