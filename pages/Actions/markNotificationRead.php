<?php
include '../Actions/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['notification_id']) || empty($_POST['notification_id'])) {
        error_log('Missing notification_id: ' . print_r($_POST, true)); // Debug log
        echo json_encode(['success' => false, 'message' => 'Invalid request. Missing notification ID.']);
        exit;
    }

    $notificationId = intval($_POST['notification_id']);
    $query = "UPDATE notifications SET read_status = 'Read' WHERE notification_id = ?";
    $stmt = $con->prepare($query);

    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $con->error]);
        exit;
    }

    $stmt->bind_param("i", $notificationId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Notification marked as read.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update notification status.']);
    }

    $stmt->close();
}
?>
