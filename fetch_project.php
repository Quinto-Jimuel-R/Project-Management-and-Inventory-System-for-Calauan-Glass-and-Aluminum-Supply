<?php   
    include 'database.php';
    // SQL query to retrieve events from the database
    $query = "SELECT project_name AS title, start_date AS start FROM project";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Set the content type
    header('Content-Type: application/json');

    // Output the events as JSON
    echo json_encode($events);
?>
