<?php
include '../connection.php';

if (isset($_POST['item_id'])) {
    $item_id = intval($_POST['item_id']);
    $query = "SELECT * FROM items WHERE item_id = $item_id";
    $result = mysqli_query($con, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $item = mysqli_fetch_assoc($result);
        echo json_encode($item);
    } else {
        echo json_encode(null);
    }
}
?>
