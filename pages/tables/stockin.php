<?php
include '../Actions/connection.php'; // Ensure this file correctly connects to your database
include '../Actions/items/sidenav.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Inventory Management - Stock In</title>

  <!-- Include CSS libraries -->
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="../../plugins/bootstrap/css/bootstrap.min.css">
  <!-- DataTables CSS -->
  <link rel="stylesheet" href="../../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="../../dist/css/adminlte.min.css">

  <!-- Custom Styles -->
  <style>
    .modal-header {
      background-color: #007bff;
      color: #fff;
    }
    .modal-title {
      margin: 0 auto;
    }
    .btn-custom {
      margin-right: 5px;
    }
  </style>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Navbar and Sidebar -->
  <!-- Include your navbar and sidebar code here -->
  <!-- For brevity, I'm focusing on the main content -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Page Header -->
    <section class="content-header">
      <div class="container-fluid">
        <h1>Manage Items - Stock In</h1>
      </div>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Item Management Table -->
        <div class="card">
          <div class="card-header">
            <button type="button" id="addItemBtn" class="btn btn-primary" data-toggle="modal" data-target="#addItemModal">
              <i class="fas fa-plus"></i> Add New Item
            </button>
          </div>
          <div class="card-body">
            <table id="itemsTable" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>Item ID</th>
                  <th>Item Name</th>
                  <th>Description</th>
                  <th>Category</th>
                  <th>Unit</th>
                  <th>Available Stock</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
              <?php
                // Ensure table and column names are correct
                $sql = "SELECT items.item_id, items.item_name, items.item_desc, category.category_desc, units.unit_desc, items.qty
                        FROM items
                        JOIN category ON items.category_id = category.category_id
                        JOIN units ON items.unit_id = units.unit_id";

                $result = $con->query($sql);

                if (!$result) {
                    die("Query failed: " . $con->error); // Print SQL error for debugging
                }

                while ($row = $result->fetch_assoc()) {
                  echo '<tr>';
                  echo '<td>' . $row['item_id'] . '</td>';
                  echo '<td>' . htmlspecialchars($row['item_name']) . '</td>';
                  echo '<td>' . htmlspecialchars($row['item_desc']) . '</td>';
                  echo '<td>' . htmlspecialchars($row['category_desc']) . '</td>';
                  echo '<td>' . htmlspecialchars($row['unit_desc']) . '</td>';
                  echo '<td>' . $row['qty'] . '</td>';
                  echo '<td>
                  <div class="d-flex justify-content-between">
                      <button class="custom-btn custom-btn-success addStockBtn" data-id="' . $row['item_id'] . '" data-toggle="tooltip" title="Add Stock">
                          <i class="fas fa-plus-circle"></i>
                      </button>

                      <button class="custom-btn custom-btn-danger deleteItemBtn" data-id="' . $row['item_id'] . '" data-toggle="tooltip" title="Delete Item">
                          <i class="fas fa-trash-alt"></i>
                      </button>
                  </div>
                </td>';
          

                  echo '</tr>';
                }
                ?>
          
              </tbody>
              <tfoot>
                <tr>
                  <th>Item ID</th>
                  <th>Item Name</th>
                  <th>Description</th>
                  <th>Category</th>
                  <th>Unit</th>
                  <th>Available Stock</th>
                  <th>Actions</th>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
        <!-- /.card -->
      </div>
    </section>
    <!-- /.content -->

    <!-- Modals -->
    <!-- Add New Item Modal -->
    <div class="modal fade" id="addItemModal">
      <div class="modal-dialog">
        <div class="modal-content">
          <form id="addItemForm">
            <div class="modal-header">
              <h4 class="modal-title">Add New Item</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
              <!-- Item Name -->
              <div class="form-group">
                <label>Item Name</label>
                <input type="text" class="form-control" name="nameitem" required>
              </div>
              <!-- Item Description -->
              <div class="form-group">
                <label>Item Description</label>
                <input type="text" class="form-control" name="itemDesc" required>
              </div>
              <!-- Category -->
              <div class="form-group">
                <label>Category</label>
                <select class="form-control" name="cid" required>
                  <option value="">Select Category</option>
                  <?php
                  // Fetch categories
                  function fetchCategories($con) {
                    $categories = [];
                    $result = $con->query("SELECT category_id, category_desc FROM category");
                    while ($row = $result->fetch_assoc()) {
                        $categories[] = $row;
                    }
                    return $categories;
                }
                
                  ?>
                </select>
              </div>
              <!-- Unit -->
              <div class="form-group">
                <label>Unit</label>
                <select class="form-control" name="uid" required>
                  <option value="">Select Unit</option>
                  <?php
                  // Fetch units
                  $unitSql = "SELECT * FROM unit";
                  $unitResult = $con->query($unitSql);
                  while ($unitRow = $unitResult->fetch_assoc()) {
                    echo '<option value="' . $unitRow['uid'] . '">' . htmlspecialchars($unitRow['unitDesc']) . '</option>';
                  }
                  ?>
                </select>
              </div>
              <!-- Initial Quantity -->
              <div class="form-group">
                <label>Initial Quantity</label>
                <input type="number" class="form-control" name="qty" min="0" required>
              </div>
              <!-- Hidden Field for iid (item ID) -->
              <input type="hidden" name="iid" value="">
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary">Add Item</button>
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- /.modal -->

    <!-- Add Stock Modal -->
<!-- Add Stock Modal -->
<!-- Add Stock Modal -->
<div class="modal fade" id="addStockModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="addStockForm">
        <div class="modal-header">
          <h4 class="modal-title">Add Stock to Item</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="iid" id="stockItemId">
          <div class="form-group">
            <label>Item Name</label>
            <input type="text" class="form-control" id="stockItemName" readonly>
          </div>
          <div class="form-group">
            <label>Quantity to Add</label>
            <input type="number" class="form-control" name="addQty" min="1" required>
          </div>
          <div class="form-group">
            <label>Date</label>
            <input type="date" class="form-control" name="stockDate" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Add Stock</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>

    <!-- /.modal -->

    <!-- Edit Item Modal -->
    <!-- Edit Item Modal -->
<div class="modal fade" id="editItemModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="editItemForm">
        <div class="modal-header">
          <h4 class="modal-title">Edit Item</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="iid" id="editItemId">
          <div class="form-group">
            <label>Item Name</label>
            <input type="text" class="form-control" name="nameitem" id="editItemName" required>
          </div>
          <div class="form-group">
            <label>Item Description</label>
            <input type="text" class="form-control" name="itemDesc" id="editItemDesc" required>
          </div>
          <div class="form-group">
            <label>Category</label>
            <select name="cid" class="form-control" required>
    <option value="">Select Category</option>
    <?php
    $categoriesQuery = "SELECT category_id, category_desc FROM category";
    $categoriesResult = mysqli_query($con, $categoriesQuery);

    if (!$categoriesResult) {
        die("Query failed: " . mysqli_error($con));
    }

    while ($category = mysqli_fetch_assoc($categoriesResult)) {
        echo '<option value="' . htmlspecialchars($category['category_id']) . '">' . htmlspecialchars($category['category_desc']) . '</option>';
    }
    ?>
</select>


          </div>
          <div class="form-group">
            <label>Unit</label>
            <select class="form-control" name="uid" id="editUnit" required>
              <option value="">Select Unit</option>
              <?php
              $unitSql = "SELECT * FROM units";
              $unitResult = $con->query($unitSql);
              while ($unitRow = $unitResult->fetch_assoc()) {
                echo '<option value="' . $unitRow['unit_id'] . '">' . htmlspecialchars($unitRow['unit_desc']) . '</option>';
              }
              ?>
            </select>
          </div>
          <div class="form-group">
            <label>Available Quantity</label>
            <input type="number" class="form-control" name="qty" id="editQty" readonly>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-info">Update Item</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- /.modal -->

  <!-- /.content-wrapper -->

  <!-- Footer -->
  <!-- Include your footer code here -->

</div>
<!-- ./wrapper -->

<!-- Include JS libraries -->
<!-- jQuery -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables -->
<script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.min.js"></script>

<script>
$(document).ready(function() {
  // Initialize DataTable
  $('#itemsTable').DataTable()


  // Add New Item Form Submission
  $('#addItemForm').on('submit', function(e) {
    e.preventDefault();
    $.ajax({
      url: '../Actions/items/addItem.php',
      type: 'POST',
      data: $(this).serialize(),
      success: function(response) {
        alert(response);
        location.reload();
      },
      error: function() {
        alert('Error adding item.');
      }
    });
  });

  $('.addStockBtn').on('click', function() {
    var iid = $(this).data('id');
    console.log("Item ID: " + iid); // Debug to check if the ID is correctly passed
    $.ajax({
        url: '../Actions/items/getItem.php',
        type: 'POST',
        dataType: 'json',
        data: { iid: iid },
        success: function(response) {
            if (response.status === "success") {
                var item = response.data;
                $('#stockItemId').val(item.item_id); // Ensure 'item_id' matches the key in the response
                $('#stockItemName').val(item.item_name);
                $('#addStockModal').modal('show');
            } else {
                alert(response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error("AJAX Error: ", error);
            alert('Error fetching item data.');
        }
    });
});

  // Add Stock Form Submission
// Add Stock Form Submission
$('#addStockForm').on('submit', function(e) {
    e.preventDefault();
    $.ajax({
        url: '../Actions/items/addStock.php',
        type: 'POST',
        dataType: 'json', // Ensure JSON response is expected
        data: $(this).serialize(),
        success: function(response) {
            if (response && response.message) {
                alert(response.message);
            } else {
                alert('Unexpected response format.');
            }
            if (response.status === "success") {
                location.reload();
            }
        },
        error: function(xhr, status, error) {
            console.error("Error response: ", xhr.responseText);
            alert('Error adding stock.');
        }
    });
});


  // Open Edit Item Modal and populate data
  $('.editItemBtn').on('click', function() {
    var itemId = $(this).data('id');
    $.ajax({
        url: '../Actions/items/getItem.php',
        type: 'POST',
        data: { item_id: itemId },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                $('#editItemId').val(response.data.item_id);
                $('#editItemName').val(response.data.item_name);
                $('#editItemDesc').val(response.data.item_desc);
                $('#editCategory').val(response.data.category_id);
                $('#editUnit').val(response.data.unit_id);
                $('#editQty').val(response.data.qty);
                $('#editItemModal').modal('show');
            } else {
                alert('Item not found.');
            }
        },
        error: function() {
            alert('Error fetching item details.');
        }
    });
});


  // Edit Item Form Submission
  $('#editItemForm').on('submit', function(e) {
    e.preventDefault();
    $.ajax({
      url: '../Actions/items/addItem.php', // Reuse addItem.php for updating
      type: 'POST',
      data: $(this).serialize(),
      success: function(response) {
        alert(response);
        location.reload();
      },
      error: function() {
        alert('Error updating item.');
      }
    });
  });

  // Delete Item
  $('.deleteItemBtn').on('click', function() {
    if (confirm('Are you sure you want to delete this item?')) {
      var iid = $(this).data('id');
      $.ajax({
        url: '../Actions/items/deleteItem.php',
        type: 'POST',
        data: { iid: iid },
        success: function(response) {
          alert(response);
          location.reload();
        },
        error: function() {
          alert('Error deleting item.');
        }
      });
    }
  });

});
</script>
<style>
.custom-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: none;
    font-size: 1.2rem;
    cursor: pointer;
    transition: all 0.3s ease-in-out;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.custom-btn-success {
    background-color: #28a745; /* Green */
    color: #fff;
}

.custom-btn-info {
    background-color: #17a2b8; /* Teal */
    color: #fff;
}

.custom-btn-danger {
    background-color: #dc3545; /* Red */
    color: #fff;
}

.custom-btn:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
}

.custom-btn:focus {
    outline: none;
}

</style>
<script>
  $(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip(); // Initialize tooltips
});

</script>
</body>
</html>
