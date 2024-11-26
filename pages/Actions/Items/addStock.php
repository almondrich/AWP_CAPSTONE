<?php
include '../connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $iid = filter_var($_POST['iid'] ?? 0, FILTER_VALIDATE_INT);
    $addQty = filter_var($_POST['addQty'] ?? 0, FILTER_VALIDATE_INT);

    // Check if inputs are valid
    if (!$iid || $addQty <= 0) {
        echo json_encode(["status" => "error", "message" => "Quantity to add must be greater than zero and item ID must be valid."]);
        exit;
    }

    try {
        // Prepare the statement to update the item's quantity
        $stmt = $con->prepare("UPDATE items SET qty = qty + ? WHERE item_id = ?");
        $stmt->bind_param("ii", $addQty, $iid);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Stock updated successfully."]);
        } else {
            throw new Exception("Error updating stock: " . $stmt->error);
        }

        $stmt->close();
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    } finally {
        $con->close();
    }
}
?>
