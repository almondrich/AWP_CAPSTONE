<?php
include '../Actions/connection.php';
include '../Actions/items/sidenav.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Inventory System</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="../../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="../../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="../../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <!-- jQuery -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Items List</h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <button type="button" id="add" class="btn btn-primary" data-toggle="modal" data-target="#modal-default">
                  Add Item
                </button>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="itemsTable" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>Item Name</th>
                      <th>Item Description</th>
                      <th>Category</th>
                      <th>Unit</th>
                      <th>Quantity</th>
                      <th width="200">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $q = mysqli_query($con, "SELECT items.item_id, items.item_name, items.item_desc, category.category_desc, units.unit_desc, items.qty 
                                             FROM items 
                                             INNER JOIN category ON items.category_id = category.category_id 
                                             INNER JOIN units ON items.unit_id = units.unit_id");
                    if (!$q) {
                        die("Query failed: " . mysqli_error($con));
                    }
                    while ($rows = mysqli_fetch_array($q)) {
                    ?>
                      <tr>
                        <td><?php echo htmlspecialchars($rows['item_name']); ?></td>
                        <td><?php echo htmlspecialchars($rows['item_desc']); ?></td>
                        <td><?php echo htmlspecialchars($rows['category_desc']); ?></td>
                        <td><?php echo htmlspecialchars($rows['unit_desc']); ?></td>
                        <td><?php echo htmlspecialchars($rows['qty']); ?></td>
                        <td>
                          <button class="btn btn-warning edit" data-id="<?php echo htmlspecialchars($rows['item_id']); ?>">Edit</button>
                          <button class="btn btn-danger delete" data-id="<?php echo htmlspecialchars($rows['item_id']); ?>">Delete</button>
                        </td>
                      </tr>
                    <?php
                    }
                    ?>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Add/Edit Item Modal -->
  <div class="modal fade" id="modal-default">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add/Edit Item</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="itemForm">
            <div class="form-group">
              <label>Item Name</label>
              <input type="text" class="form-control" id="nameitem" name="nameitem" required>
            </div>
            <div class="form-group">
              <label>Item Description</label>
              <input type="text" class="form-control" id="item_desc" name="item_desc" required>
            </div>
            <div class="form-group">
              <label>Category</label>
              <select class="custom-select" id="selcat" name="selcat" required>
                <option value="">Select Category</option>
                <?php
                $categories = mysqli_query($con, "SELECT * FROM category");
                while ($cat = mysqli_fetch_assoc($categories)) {
                    echo "<option value='" . htmlspecialchars($cat['category_id']) . "'>" . htmlspecialchars($cat['category_desc']) . "</option>";
                }
                ?>
              </select>
            </div>
            <div class="form-group">
              <label>Unit</label>
              <select class="custom-select" id="selunit" name="selunit" required>
                <option value="">Select Unit</option>
                <?php
                $units = mysqli_query($con, "SELECT * FROM units");
                while ($unit = mysqli_fetch_assoc($units)) {
                    echo "<option value='" . htmlspecialchars($unit['unit_id']) . "'>" . htmlspecialchars($unit['unit_desc']) . "</option>";
                }
                ?>
              </select>
            </div>
            <div class="form-group">
              <label>Quantity</label>
              <input type="number" class="form-control" id="qty" name="qty" min="0" required>
            </div>
            <input type="hidden" id="p_id" name="p_id">
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" id="save" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- Include JS libraries -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../../dist/js/adminlte.min.js"></script>

<script>
$(document).ready(function() {
    $("#itemsTable").DataTable({
        "responsive": true,
        "autoWidth": false
    });

    $("#add").click(function() {
        $("#itemForm")[0].reset();
        $("#p_id").val("");
    });

    $("#save").click(function() {
    var formData = $("#itemForm").serialize();
    $(this).prop("disabled", true); // Disable the button
    $.ajax({
        url: '../Actions/Items/AddItems.php',
        type: 'POST',
        data: formData,
        success: function(response) {
            alert(response);
            location.reload();
        },
        error: function() {
            alert("An error occurred.");
        },
        complete: function() {
            $("#save").prop("disabled", false); // Re-enable the button after request completes
        }
    });
});
);

    $(".edit").click(function() {
        var itemId = $(this).data('id');
        $.ajax({
            type: "POST",
            url: '../Actions/Items/fetchItems.php',
            data: { 'item_id': itemId },
            dataType: 'json',
            success: function(response) {
                $("#nameitem").val(response.item_name);
                $("#item_desc").val(response.item_desc);
                $("#selcat").val(response.category_id);
                $("#selunit").val(response.unit_id);
                $("#qty").val(response.qty);
                $("#p_id").val(response.item_id);
                $('#modal-default').modal('show');
            }
        });
    });

    $(".delete").click(function() {
        var itemId = $(this).data('id');
        if (confirm('Are you sure you want to delete this data?')) {
            $.ajax({
                type: "POST",
                url: '../Actions/Items/delItems.php',
                data: { 'item_id': itemId },
                success: function(response) {
                    alert(response);
                    location.reload();
                }
            });
        }
    });
});
</script>
</body>
</html>
<?php
include '../Actions/connection.php';
include '../Actions/items/sidenav.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Inventory System</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="../../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="../../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="../../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <!-- jQuery -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Items List</h1>
          </div>
        </div>
      </div>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <button type="button" id="add" class="btn btn-primary" data-toggle="modal" data-target="#modal-default">
                  Add Item
                </button>
              </div>
              <div class="card-body">
                <table id="itemsTable" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>Item Name</th>
                      <th>Item Description</th>
                      <th>Category</th>
                      <th>Unit</th>
                      <th>Quantity</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $q = mysqli_query($con, "SELECT items.item_id, items.item_name, items.item_desc, category.category_desc, units.unit_desc, items.qty 
                                             FROM items 
                                             INNER JOIN category ON items.category_id = category.category_id 
                                             INNER JOIN units ON items.unit_id = units.unit_id");
                    if (!$q) {
                        die("Query failed: " . mysqli_error($con));
                    }
                    while ($rows = mysqli_fetch_array($q)) {
                    ?>
                      <tr>
                        <td><?php echo htmlspecialchars($rows['item_name']); ?></td>
                        <td><?php echo htmlspecialchars($rows['item_desc']); ?></td>
                        <td><?php echo htmlspecialchars($rows['category_desc']); ?></td>
                        <td><?php echo htmlspecialchars($rows['unit_desc']); ?></td>
                        <td><?php echo htmlspecialchars($rows['qty']); ?></td>
                        <td>
                          <button class="btn btn-warning edit" data-id="<?php echo htmlspecialchars($rows['item_id']); ?>">Edit</button>
                          <button class="btn btn-danger delete" data-id="<?php echo htmlspecialchars($rows['item_id']); ?>">Delete</button>
                        </td>
                      </tr>
                    <?php
                    }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <!-- Add/Edit Item Modal -->
  <div class="modal fade" id="modal-default">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add/Edit Item</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <form id="itemForm">
            <div class="form-group">
              <label>Item Name</label>
              <input type="text" class="form-control" id="nameitem" name="nameitem" required>
            </div>
            <div class="form-group">
              <label>Item Description</label>
              <input type="text" class="form-control" id="item_desc" name="item_desc" required>
            </div>
            <div class="form-group">
              <label>Category</label>
              <select class="custom-select" id="selcat" name="selcat" required>
                <option value="">Select Category</option>
                <?php
                $categories = mysqli_query($con, "SELECT * FROM category");
                while ($cat = mysqli_fetch_assoc($categories)) {
                    echo "<option value='" . htmlspecialchars($cat['category_id']) . "'>" . htmlspecialchars($cat['category_desc']) . "</option>";
                }
                ?>
              </select>
            </div>
            <div class="form-group">
              <label>Unit</label>
              <select class="custom-select" id="selunit" name="selunit" required>
                <option value="">Select Unit</option>
                <?php
                $units = mysqli_query($con, "SELECT * FROM units");
                while ($unit = mysqli_fetch_assoc($units)) {
                    echo "<option value='" . htmlspecialchars($unit['unit_id']) . "'>" . htmlspecialchars($unit['unit_desc']) . "</option>";
                }
                ?>
              </select>
            </div>
            <div class="form-group">
              <label>Quantity</label>
              <input type="number" class="form-control" id="qty" name="qty" min="0" required>
            </div>
            <input type="hidden" id="p_id" name="p_id">
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" id="save" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
  </aside>
</div>

<!-- Include JS libraries -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../../dist/js/adminlte.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize the DataTable with a check for existing initialization
    if (!$.fn.DataTable.isDataTable('#itemsTable')) {
        $('#itemsTable').DataTable({
            "responsive": true,
            "autoWidth": false
        });
    }

    // Function to handle the "Add" button click event
    $("#add").click(function() {
        $("#itemForm")[0].reset();
        $("#p_id").val("");
    });

    // Function to handle the "Save" button click event
    $("#save").click(function() {
        var formData = $("#itemForm").serialize();
        $.ajax({
            url: '../Actions/Items/AddItems.php',
            type: 'POST',
            data: formData,
            success: function(response) {
                alert(response);
                location.reload(); // Reloads the page to reflect changes
            }
        });
    });

    // Function to handle the "Edit" button click event
// Use delegated event handling to attach the click event to dynamically loaded elements
$(document).on("click", ".edit", function () {
    var itemId = $(this).data('id');
    $.ajax({
        type: "POST",
        url: '../Actions/Items/fetchItems.php',
        data: { 'item_id': itemId },
        dataType: 'json',
        success: function (response) {
            if (response) {
                $("#nameitem").val(response.item_name);
                $("#item_desc").val(response.item_desc);
                $("#selcat").val(response.category_id);
                $("#selunit").val(response.unit_id);
                $("#qty").val(response.qty);
                $("#p_id").val(response.item_id);
                $('#modal-default').modal('show');
            } else {
                alert('Error: Item data not found.');
            }
        },
        error: function () {
            alert('Failed to fetch item data. Please try again.');
        }
    });
});


$(".delete").click(function() {
    var itemId = $(this).data('id'); // Fetch the data-id
    if (confirm('Are you sure you want to delete this item?')) {
        $.ajax({
            type: "POST",
            url: '../Actions/Items/delItems.php',
            data: { 'cid': itemId },
            success: function(response) {
                alert(response); // Display success or error message
                location.reload(); // Refresh the table
            },
            error: function() {
                alert("An error occurred while deleting the item.");
            }
        });
    }
});
});




</script>
</body>
</html>
