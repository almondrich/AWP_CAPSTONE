<?php
include '../connection.php';

if (isset($_POST['item_id'])) {
    $item_id = intval($_POST['item_id']);
    
    // Update the database to mark the item as archived
    $query = "UPDATE items SET archived = 1 WHERE item_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $item_id);

    if ($stmt->execute()) {
        echo "Item successfully archived.";
    } else {
        echo "Failed to archive item: " . $stmt->error;
    }

    $stmt->close();
}
$con->close();
?>
