<?php
include '../connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $iid = intval($_POST['iid']);
    $itemDesc = $_POST['itemDesc'] ?? '';
    $cid = intval($_POST['cid'] ?? 0);
    $uid = intval($_POST['uid'] ?? 0);

    if (empty($itemDesc) || $cid == 0 || $uid == 0) {
        echo "Error: All fields are required.";
        exit;
    }

    // Update the item's details
    $stmt = $con->prepare("UPDATE items SET itemDesc = ?, cid = ?, uid = ? WHERE iid = ?");
    $stmt->bind_param("siii", $itemDesc, $cid, $uid, $iid);

    if ($stmt->execute()) {
        echo "Item updated successfully.";
    } else {
        echo "Error updating item: " . $stmt->error;
    }

    $stmt->close();
    $con->close();
}
?>
