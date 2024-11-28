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
        echo "<script>
                Swal.fire('Error', 'Please select at least one item and provide its quantity.', 'error');
              </script>";
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

        // Success alert and redirection
        echo "<script>
                Swal.fire({
                    title: 'Success!',
                    text: 'Requisition submitted successfully.',
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 2000
                }).then(() => {
                    window.location.href = 'user_dash.php'; // Redirect to request history
                });
              </script>";
    } catch (Exception $e) {
        // Rollback on error and display an error alert
        $con->rollback();
        echo "<script>
                Swal.fire('Error', '" . $e->getMessage() . "', 'error');
              </script>";
    } finally {
        if (isset($stmt)) $stmt->close();
        $con->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Requisition Form</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../tables/request_design.css">
    <link rel="stylesheet" href="../tables/request_design2.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container-fluid">
        <!-- Logo and Title -->
        <a class="navbar-brand d-flex align-items-center" href="department_dean.php">
            <img src="../../dist/img/sjcbaggao.png" alt="Logo" width="300" height="75">
            <span class="fs-5 fw-bold" style="color:white;"> | user Portal</span>
        </a>

        <!-- Toggle Button for Mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a id="navv"  class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard_user.php' ? 'active' : ''; ?>" href="dashboard_user.php">
                        <i data-feather="home"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a  id="navv" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'requisition_form.php' ? 'active' : ''; ?>" href="requisition_form.php">
                        <i data-feather="edit"></i> Request
                    </a>
                </li>
                <li class="nav-item">
                    <a id="navv" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'user_dash.php' ? 'active' : ''; ?>" href="user_dash.php">
                        <i data-feather="list"></i> Request Status
                    </a>
                </li>
                <li class="nav-item">
                    <a id="navv" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'profile.php' ? 'active' : ''; ?>" href="profile.php">
                        <i data-feather="user"></i> Profile
                    </a>
                </li>
                <li class="nav-item">
                    <a id="navvv" class="nav-link  <?php echo basename($_SERVER['PHP_SELF']) == 'logout.php' ? 'active' : ''; ?>" href="logout.php">
                        <i data-feather="log-out"></i> Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

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
                                                           JOIN units ON items.unit_id = units.unit_id ORDER BY items.item_name ASC");
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
                            <div class=" col-md-4 unit-container">
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
                            <div class=" col-md-2 quantity-container">
                                <label class="quantity-label">Quantity</label>
                                <div class="quantity-control">
                                    <button type="button" class="decrement-btn" id="but">-</button>
                                    <input type="number" name="quantity[]" class="form-control quantity-input" value="1" min="1" required>
                                    <button type="button" class="btn btn-secondary increment-btn"id="but">+</button>
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
                <div class=" col-md-4 unit-container">
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
                <div class=" col-md-2 quantity-container">
                    <label class="quantity-label">Quantity</label>
                    <div class="quantity-control">
                        <button type="button" class="btn btn-secondary decrement-btn"id="but">-</button>
                        <input type="number" name="quantity[]" class="form-control quantity-input" value="1" min="1" required>
                        <button type="button" class="btn btn-secondary increment-btn"id="but">+</button>
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
    <script>
    feather.replace(); /* Render Feather Icons */
</script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
