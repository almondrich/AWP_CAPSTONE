<?php
include '../Actions/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requisitionId = intval($_POST['requisition_id']);
    $quantityReleased = intval($_POST['quantity_released']);

    // Validate inputs
    if ($requisitionId <= 0 || $quantityReleased <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid input data.']);
        exit;
    }

    // Check current stock and update
    $query = "SELECT quantity, released_quantity FROM requisitions WHERE requisition_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param('i', $requisitionId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        $totalQuantity = $row['quantity'];
        $currentReleased = $row['released_quantity'];

        $newReleased = $currentReleased + $quantityReleased;
        $remainingQuantity = $totalQuantity - $newReleased;

        if ($newReleased > $totalQuantity) {
            echo json_encode(['success' => false, 'message' => 'Release quantity exceeds requested quantity.']);
            exit;
        }

        // Update the requisition
        $updateQuery = "UPDATE requisitions SET released_quantity = ?, remaining_quantity = ?, status = ? WHERE requisition_id = ?";
        $status = $remainingQuantity > 0 ? 'Partially Released' : 'Released';
        $updateStmt = $con->prepare($updateQuery);
        $updateStmt->bind_param('iisi', $newReleased, $remainingQuantity, $status, $requisitionId);

        if ($updateStmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Partial release updated successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update the requisition.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Requisition not found.']);
    }
}
