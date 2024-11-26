<?php
include '../connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $iid = filter_var($_POST['iid'] ?? 0, FILTER_VALIDATE_INT);
    $addQty = filter_var($_POST['addQty'] ?? 0, FILTER_VALIDATE_INT);

    if ($iid <= 0 || $addQty <= 0) {
        echo json_encode(["status" => "error", "message" => "Invalid item ID or quantity."]);
        exit;
    }

    try {
        $stmt = $con->prepare("UPDATE items SET qty = qty + ? WHERE item_id = ?");
        $stmt->bind_param("ii", $addQty, $iid);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Stock added successfully."]);
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

