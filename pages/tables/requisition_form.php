<?php
include '../Actions/connection.php';

session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $items = $_POST['item_id'] ?? []; // Use empty array if not set
    $units = $_POST['unit_id'] ?? [];
    $quantities = $_POST['quantity'] ?? [];
    $justification = trim($_POST['justification'] ?? '');

    // Validate inputs
    if (empty($items) || empty($quantities) || empty($units) || count($items) !== count($quantities) || count($items) !== count($units)) {
        echo "Error: Please select at least one item and provide its quantity.";
        exit;
    }

    // Start transaction
    $con->begin_transaction();

    try {
        foreach ($items as $index => $item_id) {
            $item_id = intval($item_id);
            $unit_id = intval($units[$index]);
            $quantity = intval($quantities[$index]);

            // Ensure valid values
            if ($item_id <= 0 || $unit_id <= 0 || $quantity <= 0) {
                throw new Exception("Invalid item, unit, or quantity provided.");
            }

            // Insert into requisitions table
            $stmt = $con->prepare("INSERT INTO requisitions (user_id, item_id, unit_id, quantity, justification) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("iiiis", $user_id, $item_id, $unit_id, $quantity, $justification);

            if (!$stmt->execute()) {
                throw new Exception("Error inserting into requisitions: " . $stmt->error);
            }
        }

        // Commit transaction
        $con->commit();
        echo "Requisition submitted successfully.";
    } catch (Exception $e) {
        // Rollback on error
        $con->rollback();
        echo "Error: " . $e->getMessage();
    } finally {
        if (isset($stmt)) $stmt->close();
        $con->close();
    }
}
?>
<!DOCTYPE html>
< lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Requisition Form</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../tables/request_design.css">
</head>
<>
    <div class="wrapper">
        <div class="sidebar">
            <h4 class="text-center">Requisition Dashboard</h4>
            <a href="dashboard.php" class="nav-link"><i class="fas fa-home"></i> Dashboard</a>
            <a href="requisition_form.php" class="nav-link"><i class="fas fa-file-alt"></i> Requisition Form</a>
            <a href="user_dash.php" class="nav-link"><i class="fas fa-history"></i> Request History</a>
            <a href="logout.php" class="nav-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>

        <div class="content">
            <div class="form-container">
                <div class="form-header">
                    <h2>Requisition Form</h2>
                </div>
                <form action="requisition_form.php" method="post" id="requisitionForm">
                    <div id="itemContainer">
                        <div class="card-item">
                            <button type="button" class="close-btn" onclick="removeItem(this)">×</button>
                            <div class="item-details">
                                <label for="item_id">Item:</label>
                                <select name="item_id[]" class="form-control item-select" required onchange="updateItemDetails(this)">
                                    <option value="" disabled selected>Select an item</option>
                                    <?php
                                    $result = $con->query("SELECT items.item_id, items.item_name, items.item_desc, items.qty, units.unit_id, units.unit_desc 
                                                           FROM items 
                                                           JOIN units ON items.unit_id = units.unit_id");
                                    if ($result) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<option value='{$row['item_id']}' 
                                                   data-description='{$row['item_desc']}' 
                                                   data-stock='{$row['qty']}' 
                                                   data-unit-id='{$row['unit_id']}' 
                                                   data-unit-desc='{$row['unit_desc']}'>{$row['item_name']}</option>";
                                        }
                                    }
                                    ?>
                                </select>
                                <p class="item-description" style="font-size: 14px; color: #555;">Description: <span>Not selected</span></p>
                                <p class="item-stock" style="font-size: 14px; color: #555;">Available Stock: <span>Not selected</span></p>
                            </div>
                            <div class="unit-container">
                                <label for="unit_id">Unit:</label>
                                <select name="unit_id[]" class="form-control unit-select" required>
                                    <option value="" disabled selected>Select a unit</option>
                                    <?php
                                    $unitsResult = $con->query("SELECT unit_id, unit_desc FROM units");
                                    if ($unitsResult) {
                                        while ($unitRow = $unitsResult->fetch_assoc()) {
                                            echo "<option value='{$unitRow['unit_id']}'>{$unitRow['unit_desc']}</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="quantity-container">
                                <label class="quantity-label">Quantity</label>
                                <div class="quantity-control">
                                    <button type="button" class="btn btn-secondary decrement-btn">-</button>
                                    <input type="number" name="quantity[]" class="form-control quantity-input" value="1" min="1" required>
                                    <button type="button" class="btn btn-secondary increment-btn">+</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary" id="addItemBtn">+ Add another item</button>
                    <div class="form-group mt-4">
                        <label for="justification">Justification:</label>
                        <textarea name="justification" id="justification" class="form-control" rows="4" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Submit</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Add Item Button Logic
        document.getElementById('addItemBtn').addEventListener('click', function () {
            const itemContainer = document.getElementById('itemContainer');
            const newItemEntry = document.createElement('div');
            newItemEntry.classList.add('card-item');
            newItemEntry.innerHTML = `
                <button type="button" class="close-btn" onclick="removeItem(this)">×</button>
                <div class="item-details">
                    <label for="item_id">Item:</label>
                    <select name="item_id[]" class="form-control item-select" required onchange="updateItemDetails(this)">
                        <option value="" disabled selected>Select an item</option>
                        <?php
                        $result = $con->query("SELECT items.item_id, items.item_name, items.item_desc, items.qty, units.unit_id, units.unit_desc 
                                               FROM items 
                                               JOIN units ON items.unit_id = units.unit_id");
                        if ($result) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value='{$row['item_id']}' 
                                       data-description='{$row['item_desc']}' 
                                       data-stock='{$row['qty']}' 
                                       data-unit-id='{$row['unit_id']}' 
                                       data-unit-desc='{$row['unit_desc']}'>{$row['item_name']}</option>";
                            }
                        }
                        ?>
                    </select>
                    <p class="item-description" style="font-size: 14px; color: #555;">Description: <span>Not selected</span></p>
                    <p class="item-stock" style="font-size: 14px; color: #555;">Available Stock: <span>Not selected</span></p>
                </div>
                <div class="unit-container">
                    <label for="unit_id">Unit:</label>
                    <select name="unit_id[]" class="form-control unit-select" required>
                        <option value="" disabled selected>Select a unit</option>
                        <?php
                        $unitsResult = $con->query("SELECT unit_id, unit_desc FROM units");
                        if ($unitsResult) {
                            while ($unitRow = $unitsResult->fetch_assoc()) {
                                echo "<option value='{$unitRow['unit_id']}'>{$unitRow['unit_desc']}</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="quantity-container">
                    <label class="quantity-label">Quantity</label>
                    <div class="quantity-control">
                        <button type="button" class="btn btn-secondary decrement-btn">-</button>
                        <input type="number" name="quantity[]" class="form-control quantity-input" value="1" min="1" required>
                        <button type="button" class="btn btn-secondary increment-btn">+</button>
                    </div>
                </div>`;
            itemContainer.appendChild(newItemEntry);
            attachQuantityHandlers(newItemEntry);
        });

        // Update Item Details on Selection
        function updateItemDetails(selectElement) {
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            const description = selectedOption.getAttribute('data-description') || 'No description available.';
            const stock = selectedOption.getAttribute('data-stock') || 'Not available';

            const itemDetails = selectElement.closest('.item-details');
            itemDetails.querySelector('.item-description span').textContent = description;
            itemDetails.querySelector('.item-stock span').textContent = stock;
        }

        // Attach Increment/Decrement Handlers
        function attachQuantityHandlers(item) {
            const decrementBtn = item.querySelector('.decrement-btn');
            const incrementBtn = item.querySelector('.increment-btn');
            const quantityInput = item.querySelector('.quantity-input');

            decrementBtn.addEventListener('click', () => {
                if (quantityInput.value > 1) {
                    quantityInput.value--;
                }
            });

            incrementBtn.addEventListener('click', () => {
                quantityInput.value++;
            });
        }

        // Remove Item Logic
        function removeItem(button) {
            const cardItem = button.closest('.card-item');
            cardItem.remove();
        }

        // Attach handlers for existing items
        document.querySelectorAll('.card-item').forEach(attachQuantityHandlers);
    </script>