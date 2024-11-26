<?php
include '../connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $iid = intval($_POST['iid']);

    // Delete the item
    $stmt = $con->prepare("DELETE FROM items WHERE iid = ?");
    $stmt->bind_param("i", $iid);

    if ($stmt->execute()) {
        echo "Item deleted successfully.";
    } else {
        echo "Error deleting item: " . $stmt->error;
    }

    $stmt->close();
    $con->close();
}
?>
