<?php
include 'database.php';

if (isset($_POST['item_name']) && isset($_POST['dimension']) && isset($_POST['color'])) {
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
            ie.color,            -- Using ie for color
            ie.dimension         -- Using ie for dimension
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
            AND ie.dimension = '$dimension'   -- Still using ie for dimension
            AND ie.color = '$color'           -- Still using ie for color
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
            'color' => $data['color'],          // Color from inventory_excess
            'dimension' => $data['dimension']   // Dimension from inventory_excess
        ]);
    } else {
        // No matching item found
        echo json_encode(['status' => 'not_found']);
    }
} else {
    // Item name was not provided in the request
    echo json_encode(['status' => 'error', 'message' => 'Item name not provided']);
}
?>