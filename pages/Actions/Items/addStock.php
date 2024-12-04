<?php
include '../connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $iid = filter_var($_POST['iid'] ?? 0, FILTER_VALIDATE_INT);
    $addQty = filter_var($_POST['addQty'] ?? 0, FILTER_VALIDATE_INT);
    $stockDate = $_POST['stockDate'] ?? null;

    // Validate inputs
    if (!$iid || $addQty <= 0 || !$stockDate) {
        echo json_encode(["status" => "error", "message" => "All fields are required, and quantity must be greater than zero."]);
        exit;
    }

    try {
        // Prepare the statement to update the item's quantity and log the addition
        $con->begin_transaction();

        // Update item quantity
        $updateStmt = $con->prepare("UPDATE items SET qty = qty + ? WHERE item_id = ?");
        $updateStmt->bind_param("ii", $addQty, $iid);

        if (!$updateStmt->execute()) {
            throw new Exception("Error updating stock: " . $updateStmt->error);
        }

        // Insert into stock log table
        $logStmt = $con->prepare("INSERT INTO stock_logs (item_id, added_quantity, stock_date) VALUES (?, ?, ?)");
        $logStmt->bind_param("iis", $iid, $addQty, $stockDate);

        if (!$logStmt->execute()) {
            throw new Exception("Error logging stock addition: " . $logStmt->error);
        }

        $con->commit();
        echo json_encode(["status" => "success", "message" => "Stock updated and logged successfully."]);

        $updateStmt->close();
        $logStmt->close();
    } catch (Exception $e) {
        $con->rollback();
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    } finally {
        $con->close();
    }
}
?>
