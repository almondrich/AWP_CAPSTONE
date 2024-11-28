<?php
include '../Actions/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requisition_id = intval($_POST['requisition_id']);
    $status = trim($_POST['status']);

    if (empty($requisition_id) || empty($status)) {
        echo json_encode(['success' => false, 'message' => 'Invalid input']);
        exit;
    }

    // Get the user ID associated with the requisition
    $userQuery = "SELECT user_id FROM requisitions WHERE requisition_id = ?";
    $stmt = $con->prepare($userQuery);
    $stmt->bind_param("i", $requisition_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if (!$user) {
        echo json_encode(['success' => false, 'message' => 'Requisition not found']);
        exit;
    }

    $user_id = $user['user_id'];

    // Update the requisition status
    $updateQuery = "UPDATE requisitions SET status = ? WHERE requisition_id = ?";
    $stmt = $con->prepare($updateQuery);
    $stmt->bind_param("si", $status, $requisition_id);

    if ($stmt->execute()) {
        // Create a notification message
        $message = "Your requisition #$requisition_id has been updated to '$status'.";

        // Insert the notification into the notifications table
        $notifyQuery = "INSERT INTO notifications (user_id, requisition_id, message) VALUES (?, ?, ?)";
        $notifyStmt = $con->prepare($notifyQuery);
        $notifyStmt->bind_param("iis", $user_id, $requisition_id, $message);
        $notifyStmt->execute();
        $notifyStmt->close();

        echo json_encode(['success' => true, 'message' => 'Status updated and notification sent.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update requisition.']);
    }

    $stmt->close();
}
?>
