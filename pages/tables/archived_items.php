<?php
include '../Actions/connection.php'; // Include your database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Archived Items</title>
    <!-- Include necessary styles -->
    <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
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
    </nav>
    <!-- /.navbar -->

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Archived Items</h1>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main Content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <table id="archivedItemsTable" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>Item Name</th>
                                        <th>Description</th>
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
                                                             INNER JOIN units ON items.unit_id = units.unit_id
                                                             WHERE items.archived = 1");
                                    if (!$q) {
                                        die("Query failed: " . mysqli_error($con));
                                    }
                                    while ($row = mysqli_fetch_array($q)) {
                                    ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($row['item_name']); ?></td>
                                            <td><?php echo htmlspecialchars($row['item_desc']); ?></td>
                                            <td><?php echo htmlspecialchars($row['category_desc']); ?></td>
                                            <td><?php echo htmlspecialchars($row['unit_desc']); ?></td>
                                            <td><?php echo htmlspecialchars($row['qty']); ?></td>
                                            <td>
                                                <button class="btn btn-success restore" data-id="<?php echo htmlspecialchars($row['item_id']); ?>">Restore</button>
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
</div>

<!-- Include necessary scripts -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../../dist/js/adminlte.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize the DataTable
    $('#archivedItemsTable').DataTable({
        "responsive": true,
        "autoWidth": false
    });

    // Handle Restore Button Click
    $(document).on('click', '.restore', function() {
        var itemId = $(this).data('id');
        if (confirm('Are you sure you want to restore this item?')) {
            $.ajax({
                type: "POST",
                url: '../Actions/Items/restoreItem.php', // Adjust the path as necessary
                data: { 'item_id': itemId },
                success: function(response) {
                    alert(response); // Display success or error message
                    location.reload(); // Refresh the table
                },
                error: function() {
                    alert("An error occurred while restoring the item.");
                }
            });
        }
    });
});
</script>
</body>
</html>
