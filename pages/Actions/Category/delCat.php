<?php
include '../connection.php';

header('Content-Type: application/json');

try {
    // Validate and sanitize the category ID
    if (!isset($_POST['cid']) || !is_numeric($_POST['cid'])) {
        throw new Exception("Invalid category ID.");
    }

    $cid = intval($_POST['cid']);

    // Use prepared statements to prevent SQL injection
    $stmt = $con->prepare("DELETE FROM category WHERE category_id = ?");
    $stmt->bind_param("i", $cid);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Category successfully deleted."]);
    } else {
        throw new Exception("Error deleting category: " . $stmt->error);
    }

    $stmt->close();
    $con->close();
} catch (Exception $e) {
    // Return an error message as JSON
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>
