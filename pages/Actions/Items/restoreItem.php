<?php
include '../connection.php'; // Include your database connection

if (isset($_POST['item_id'])) {
    $item_id = intval($_POST['item_id']);
    
    // Update the database to mark the item as not archived
    $query = "UPDATE items SET archived = 0 WHERE item_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $item_id);

    if ($stmt->execute()) {
        echo "Item successfully restored.";
    } else {
        echo "Failed to restore item: " . $stmt->error;
    }

    $stmt->close();
}
$con->close();
?>
