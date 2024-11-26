<?php
include '../connection.php';

try {
    // Validate and sanitize input
    if (!isset($_POST['cid']) || !is_numeric($_POST['cid'])) {
        throw new Exception("Invalid or missing item ID.");
    }

    $cid = (int)$_POST['cid'];

    // Use prepared statements to prevent SQL injection
    $stmt = $con->prepare("DELETE FROM items WHERE item_id = ?");
    $stmt->bind_param("i", $cid);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo "Item successfully deleted.";
        } else {
            throw new Exception("No item found with the provided ID.");
        }
    } else {
        throw new Exception("Failed to execute delete query: " . $stmt->error);
    }

    $stmt->close();
} catch (Exception $e) {
    // Return the error message
    echo "Error: " . $e->getMessage();
} finally {
    // Close the database connection
    $con->close();
}
?>
