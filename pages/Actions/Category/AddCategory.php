<?php
include '../connection.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $categoryDesc = trim($_POST['cat']);
    $p_id = isset($_POST['p_id']) && $_POST['p_id'] !== '' ? intval($_POST['p_id']) : null;

    if (empty($categoryDesc)) {
        echo json_encode(["status" => "error", "message" => "Category description cannot be empty."]);
        exit;
    }

    try {
        if ($p_id === null) {
            $stmt = $con->prepare("INSERT INTO category (category_desc) VALUES (?)");
            $stmt->bind_param("s", $categoryDesc);

            if ($stmt->execute()) {
                echo json_encode(["status" => "success", "message" => "Category successfully added."]);
            } else {
                echo json_encode(["status" => "error", "message" => "Error inserting category: " . $stmt->error]);
            }
        } else {
            $stmt = $con->prepare("UPDATE category SET category_desc = ? WHERE category_id = ?");
            $stmt->bind_param("si", $categoryDesc, $p_id);

            if ($stmt->execute()) {
                echo json_encode(["status" => "success", "message" => "Category successfully updated."]);
            } else {
                echo json_encode(["status" => "error", "message" => "Error updating category: " . $stmt->error]);
            }
        }

        $stmt->close();
        $con->close();
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
}
?>
