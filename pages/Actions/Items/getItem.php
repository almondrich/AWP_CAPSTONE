<?php
include '../connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $iid = filter_var($_POST['iid'] ?? 0, FILTER_VALIDATE_INT);

    if ($iid <= 0) {
        echo json_encode(["status" => "error", "message" => "Invalid item ID."]);
        exit;
    }

    $stmt = $con->prepare("SELECT item_id, item_name, item_desc, category_id, unit_id, qty FROM items WHERE item_id = ?");
    $stmt->bind_param("i", $iid);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $item = $result->fetch_assoc();
        echo json_encode(["status" => "success", "data" => $item]);
    } else {
        echo json_encode(["status" => "error", "message" => "Item not found."]);
    }

    $stmt->close();
    $con->close();
}
?>
