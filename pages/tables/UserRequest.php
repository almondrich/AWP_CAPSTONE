<?php
include '../Actions/connection.php'; // Ensure database connection
include '../Actions/items/sidenav.php'; // Include the sidebar navigation if needed
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
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
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
            <h1>List of Requested Items</h1>
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
                <h3 class="card-title">Requisitions</h3>
              </div>
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>Requisition ID</th>
                      <th>User</th>
                      <th>Item</th>
                      <th>Unit</th>
                      <th>Quantity</th>
                      <th>Status</th>
                      <th>Request Date</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $query = "
                      SELECT 
                          r.requisition_id, 
                          u.name AS user_name, 
                          i.item_name, 
                          un.unit_desc, 
                          r.quantity, 
                          r.justification, 
                          r.status, 
                          r.request_date
                      FROM requisitions r
                      LEFT JOIN user u ON r.user_id = u.user_id
                      LEFT JOIN items i ON r.item_id = i.item_id
                      LEFT JOIN units un ON r.unit_id = un.unit_id;
                    ";

                    $result = mysqli_query($con, $query);

                    if (!$result) {
                        echo '<tr><td colspan="8" class="text-center text-danger">Query Error: ' . htmlspecialchars(mysqli_error($con)) . '</td></tr>';
                    } else {
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                ?>
                                <tr>
                                  <td><?php echo htmlspecialchars($row['requisition_id']); ?></td>
                                  <td><?php echo htmlspecialchars($row['user_name'] ?: 'N/A'); ?></td>
                                  <td><?php echo htmlspecialchars($row['item_name'] ?: 'N/A'); ?></td>
                                  <td><?php echo htmlspecialchars($row['unit_desc'] ?: 'N/A'); ?></td>
                                  <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                                  <td>
                                    <?php
                                    switch ($row['status']) {
                                        case 'Pending':
                                            echo '<span class="badge bg-danger">Pending</span>';
                                            break;
                                        case 'Approved':
                                            echo '<span class="badge bg-primary">Approved</span>';
                                            break;
                                        case 'Rejected':
                                            echo '<span class="badge bg-warning text-dark">Rejected</span>';
                                            break;
                                        default:
                                            echo '<span class="badge bg-secondary">Unknown</span>';
                                    }
                                    ?>
                                  </td>
                                  <td><?php echo htmlspecialchars($row['request_date']); ?></td>
                                  <td>
                                    <?php if ($row['status'] === 'Pending') { ?>
                                      <button class="btn btn-success approve-btn" data-id="<?php echo htmlspecialchars($row['requisition_id']); ?>">Approve</button>
                                      <button class="btn btn-danger disapprove-btn" data-id="<?php echo htmlspecialchars($row['requisition_id']); ?>">Disapprove</button>
                                    <?php } ?>
                                  </td>
                                </tr>
                                <?php
                            }
                        } else {
                            echo '<tr><td colspan="8" class="text-center">No requisitions found.</td></tr>';
                        }
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

<script src="../../plugins/jquery/jquery.min.js"></script>
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="../../plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="../../plugins/jszip/jszip.min.js"></script>
<script src="../../plugins/pdfmake/pdfmake.min.js"></script>
<script src="../../plugins/pdfmake/vfs_fonts.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<script src="../../dist/js/adminlte.min.js"></script>

<script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true,
      "lengthChange": false,
      "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
  });

  $(document).ready(function () {
    $(".approve-btn").click(function () {
      const requisitionId = $(this).data("id");

      if (confirm("Are you sure you want to approve this requisition?")) {
        $.post("../Actions/UserRequest.php", { requisition_id: requisitionId }, function (response) {
          if (response.trim() === "success") {
            alert("Requisition approved successfully!");
            location.reload();
          } else {
            alert("Failed to approve requisition.");
          }
        }).fail(function () {
          alert("An error occurred. Please try again.");
        });
      }
    });
  });
</script>
</body>
</html>
