<?php
// Include the necessary connection file
include '../connection.php';

try {
    // Validate and sanitize input data
    $nameitem = isset($_POST['nameitem']) ? mysqli_real_escape_string($con, trim($_POST['nameitem'])) : '';
    $itemDesc = isset($_POST['item_desc']) ? mysqli_real_escape_string($con, trim($_POST['item_desc'])) : '';
    $selcat = isset($_POST['selcat']) ? (int)$_POST['selcat'] : 0;
    $selunit = isset($_POST['selunit']) ? (int)$_POST['selunit'] : 0;
    $qty = isset($_POST['qty']) && is_numeric($_POST['qty']) ? (int)$_POST['qty'] : -1;
    $p_id = isset($_POST['p_id']) ? (int)$_POST['p_id'] : null;

    // Validate input fields
    if (empty($nameitem) || empty($itemDesc) || $selcat <= 0 || $selunit <= 0 || $qty < 0) {
        throw new Exception("All fields are required, and quantity must be a non-negative integer.");
    }

    // Check if category exists
    $c = mysqli_query($con, "SELECT category_id FROM category WHERE category_id='$selcat'");
    if (!$c || mysqli_num_rows($c) == 0) {
        throw new Exception("Invalid category selected.");
    }

    // Check if unit exists
    $u = mysqli_query($con, "SELECT unit_id FROM units WHERE unit_id='$selunit'");
    if (!$u || mysqli_num_rows($u) == 0) {
        throw new Exception("Invalid unit selected.");
    }

    // Determine if it is an insert or update operation
    if (empty($p_id)) {
        // Insert operation
        $query = "INSERT INTO items (item_name, item_desc, category_id, unit_id, qty) 
                  VALUES ('$nameitem', '$itemDesc', '$selcat', '$selunit', '$qty')";
        if (!mysqli_query($con, $query)) {
            throw new Exception("Insert operation failed: " . mysqli_error($con));
        }
        echo "Item successfully added."; // Success message for insert
    } else {
        // Update operation
        $query = "UPDATE items 
                  SET item_name='$nameitem', item_desc='$itemDesc', category_id='$selcat', unit_id='$selunit', qty='$qty' 
                  WHERE item_id='$p_id'";
        if (!mysqli_query($con, $query)) {
            throw new Exception("Update operation failed: " . mysqli_error($con));
        }
        echo "Item successfully updated."; // Success message for update
    }
} catch (Exception $e) {
    // Print error message for debugging
    echo "Error: " . $e->getMessage();
} finally {
    // Close the database connection
    mysqli_close($con);
}
?>
