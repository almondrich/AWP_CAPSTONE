<?php
include '../Actions/connection.php';
include '../Actions/items/sidenav.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Inventory System - Category Management</title>

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="../../plugins/bootstrap/css/bootstrap.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="../../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
  <style>
    .btn-modern {
      background-color: #28a745;
      color: #fff;
      border: none;
      transition: background-color 0.3s, transform 0.2s;
    }
    .btn-modern:hover {
      background-color: #218838;
      transform: translateY(-3px);
    }
    .btn-modern:focus {
      outline: none;
      box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
    }
    .modal-header-custom {
      background-color: #007bff;
      color: #fff;
    }
    .custom-alert {
      position: fixed;
      top: 20px;
      right: 20px;
      z-index: 1051;
      display: none;
    }
    .custom-alert.show {
      display: block;
    }
  </style>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button" aria-label="Full Screen">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
    </ul>
  </nav>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Category List</h1>
          </div>
        </div>
      </div>
    </section>

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <button type="button" id="add" class="btn btn-modern" data-toggle="modal" data-target="#modal-default">
                  <i class="fas fa-plus"></i> Add Category
                </button>
              </div>
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Category</th>
                    <th width="200">Actions</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php
$query = "SELECT * FROM category ORDER BY category_desc DESC";
$stmt = $con->prepare($query);

if (!$stmt->execute()) {
    die("Query failed: " . $stmt->error);
}

$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    echo '<tr>';
    echo '<td>' . htmlspecialchars($row['category_desc']) . '</td>';
    echo '<td>
            <button class="btn btn-info edit" data-id="' . htmlspecialchars($row['category_id']) . '" data-desc="' . htmlspecialchars($row['category_desc']) . '" title="Edit Category">
                <i class="fas fa-edit"></i>
            </button>
            <button class="btn btn-danger delete" data-id="' . htmlspecialchars($row['category_id']) . '" title="Delete Category">
                <i class="fas fa-trash-alt"></i>
            </button>
          </td>';
    echo '</tr>';
}
$stmt->close();
?>


                  </tbody>
                  <tfoot>
                  <tr>
                    <th>Category</th>
                    <th>Actions</th>
                  </tr>
                  </tfoot>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <div class="modal fade" id="modal-default">
    <div class="modal-dialog">
      <div class="modal-content">
        <form id="frm">
          <div class="modal-header modal-header-custom">
            <h4 class="modal-title">Add Category</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="cat">Category Description</label>
              <input type="text" class="form-control" id="cat" name="cat" placeholder="Enter category name" required>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <input type="hidden" name="p_id" id="p_id">

            <button type="submit" class="btn btn-modern" id="save">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="../../plugins/jquery/jquery.min.js"></script>
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../../dist/js/adminlte.min.js"></script>

<script>

$("#save").click(function (e) {
    e.preventDefault();
    var formData = $("#frm").serialize();
    $.ajax({
        url: '../Actions/Category/AddCategory.php',
        type: 'POST',
        data: formData,
        dataType: 'json', // Ensure response is expected as JSON
        success: function (response) {
            console.log(response); // Log response to check content
            if (response.status === "success") {
                alert(response.message);
                location.reload();
            } else {
                alert("Error: " + response.message);
            }
        },
        error: function (xhr, status, error) {
            console.error("AJAX Error: ", error);
            console.log("Server Response: ", xhr.responseText); // Log server response for debugging
            alert('An error occurred. Please try again.');
        }
    });
});

// DELETE CATEGORY
$(document).ready(function () {
    $('.delete').click(function () {
        var cid = $(this).data('id'); // Get the category ID from the button

        if (confirm('Are you sure you want to delete this category?')) {
            $.ajax({
                url: '../Actions/Category/delCat.php', // Adjust the path as needed
                type: 'POST',
                data: { cid: cid },
                dataType: 'json',
                success: function (response) {
                    if (response.status === "success") {
                        alert(response.message);
                        location.reload(); // Reload the page to show the updated list
                    } else {
                        alert("Error: " + response.message);
                    }
                },
                error: function (xhr, status, error) {
                    console.log("Error: " + error);
                    console.log(xhr.responseText); // Log the response for debugging
                    alert('An error occurred while trying to delete the category.');
                }
            });
        }
    });
});
//EDIT CATEGORY
$(document).ready(function() {
    // When the edit button is clicked
    $('.edit').click(function() {
        var categoryId = $(this).data('id');
        var categoryDesc = $(this).data('desc');

        // Populate the form fields in the modal with the existing category data
        $('#p_id').val(categoryId);
        $('#cat').val(categoryDesc);

        // Show the modal
        $('#modal-default').modal('show');
    });

    // AJAX call to save the edited category
    $('#save').click(function(e) {
        e.preventDefault();
        var formData = $('#frm').serialize();

        $.ajax({
            url: '../Actions/Category/AddCategory.php',
            type: 'POST',
            data: formData,
            success: function(response) {
                console.log(response); // Log response for debugging
                if (response.status === "success") {
                    alert(response.message);
                    location.reload();
                } else {
                    alert("Error: " + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.log("Error: " + error);
                console.log(xhr.responseText); // Log server response for debugging
                alert('An error occurred. Please try again.');
            }
        });
    });
});


</script>
</body>
</html>
