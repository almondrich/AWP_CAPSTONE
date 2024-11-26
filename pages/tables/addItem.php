<?php
include '../connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate and sanitize inputs
    $itemDesc = $_POST['itemDesc'] ?? '';
    $cid = intval($_POST['cid'] ?? 0);
    $uid = intval($_POST['uid'] ?? 0);
    $qty = intval($_POST['qty'] ?? 0);
    $iid = $_POST['iid'] ?? ''; // This will be empty for new items

    if (empty($itemDesc) || $cid == 0 || $uid == 0) {
        echo "Error: All fields are required.";
        exit;
    }

    if (empty($iid)) {
        // Insert operation
        $stmt = $con->prepare("INSERT INTO items (itemDesc, cid, uid, qty) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("siii", $itemDesc, $cid, $uid, $qty);

        if ($stmt->execute()) {
            echo "Item added successfully.";
        } else {
            echo "Error adding item: " . $stmt->error;
        }
    } else {
        // Update operation
        $stmt = $con->prepare("UPDATE items SET itemDesc = ?, cid = ?, uid = ? WHERE iid = ?");
        $stmt->bind_param("siii", $itemDesc, $cid, $uid, $iid);

        if ($stmt->execute()) {
            echo "Item updated successfully.";
        } else {
            echo "Error updating item: " . $stmt->error;
        }
    }

    $stmt->close();
    $con->close();
}
?>
