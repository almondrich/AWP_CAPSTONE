<?php
include '../Actions/connection.php';
include '../Actions/items/sidenav.php'; // Sidebar Navigation

// Fetch requisitions
$query = "
    SELECT 
        r.requisition_id, 
        u.name AS user_name, 
        i.item_name, 
        un.unit_desc, 
        r.quantity, 
        r.status, 
        r.request_date,
        r.department_dean_status,
        r.college_dean_status
    FROM requisitions r
    LEFT JOIN user u ON r.user_id = u.user_id
    LEFT JOIN items i ON r.item_id = i.item_id
    LEFT JOIN units un ON r.unit_id = un.unit_id
";
$result = $con->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inventory System - User Requests</title>

    <!-- Stylesheets -->
    <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="../../dist/css/adminlte.min.css">

    <style>
        body {
            background-color: #f3f4f6;
        }
        .navbar {
            background: linear-gradient(to right, #004d00, #000000);
        }
        .navbar-brand {
            color: white;
        }
        .badge {
            font-size: 90%;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-dark">
        <a class="navbar-brand" href="#">Inventory System</a>
    </nav>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <!-- Header -->
        <section class="content-header">
            <div class="container-fluid">
                <h1 class="mt-3">List of Requested Items</h1>
            </div>
        </section>

        <!-- Main Content -->
        <section class="content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Requisitions</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="requisitionTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>User</th>
                                        <th>Item</th>
                                        <th>Unit</th>
                                        <th>Quantity</th>
                                        <th>Status</th>
                                        <th>Dept Dean</th>
                                        <th>College Dean</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if ($result && $result->num_rows > 0): ?>
                                    <?php while ($row = $result->fetch_assoc()): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($row['requisition_id']); ?></td>
                                            <td><?= htmlspecialchars($row['user_name'] ?? 'N/A'); ?></td>
                                            <td><?= htmlspecialchars($row['item_name'] ?? 'N/A'); ?></td>
                                            <td><?= htmlspecialchars($row['unit_desc'] ?? 'N/A'); ?></td>
                                            <td><?= htmlspecialchars($row['quantity']); ?></td>
                                            <td>
                                                <span class="badge <?= $row['status'] === 'Pending' ? 'bg-warning text-dark' : ($row['status'] === 'Approved' ? 'bg-success' : 'bg-danger'); ?>">
                                                    <?= htmlspecialchars($row['status']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge <?= $row['department_dean_status'] === 'Approved' ? 'bg-success' : ($row['department_dean_status'] === 'Disapproved' ? 'bg-danger' : 'bg-warning text-dark'); ?>">
                                                    <?= htmlspecialchars($row['department_dean_status'] ?? 'Pending'); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge <?= $row['college_dean_status'] === 'Approved' ? 'bg-success' : ($row['college_dean_status'] === 'Disapproved' ? 'bg-danger' : 'bg-warning text-dark'); ?>">
                                                    <?= htmlspecialchars($row['college_dean_status'] ?? 'Pending'); ?>
                                                </span>
                                            </td>
                                            <td><?= htmlspecialchars($row['request_date']); ?></td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-primary dropdown-toggle btn-sm" data-toggle="dropdown" aria-expanded="false">
                                                        Action
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <?php if ($row['status'] === 'Pending'): ?>
                                                            <li>
                                                                <a class="dropdown-item approve-btn" data-id="<?= $row['requisition_id']; ?>" href="#">
                                                                    <i class="fas fa-check"></i> Approve
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item disapprove-btn" data-id="<?= $row['requisition_id']; ?>" href="#">
                                                                    <i class="fas fa-times"></i> Disapprove
                                                                </a>
                                                            </li>
                                                        <?php endif; ?>
                                                        <li>
                                                            <a class="dropdown-item update-status" data-id="<?= $row['requisition_id']; ?>" data-status="Preparing for Release" href="#">
                                                                <i class="fas fa-spinner"></i> Preparing for Release
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item update-status" data-id="<?= $row['requisition_id']; ?>" data-status="Ready for Release" href="#">
                                                                <i class="fas fa-box"></i> Ready for Release
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item update-status" data-id="<?= $row['requisition_id']; ?>" data-status="Not Ready: Stocks Unavailable" href="#">
                                                                <i class="fas fa-exclamation-circle"></i> Not Ready
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item update-status" data-id="<?= $row['requisition_id']; ?>" data-status="Released" href="#">
                                                                <i class="fas fa-dolly"></i> Release
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="10" class="text-center text-muted">No requisitions found.</td>
                                    </tr>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Footer -->
    <footer class="main-footer text-center">
        <strong>&copy; 2024 Inventory System</strong>
    </footer>
</div>

<!-- Scripts -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../../dist/js/adminlte.min.js"></script>

<script>
$(document).on('click', '.update-status', function () {
    const requisitionId = $(this).data('id');
    const status = $(this).data('status');

    Swal.fire({
        title: `Are you sure you want to set this requisition to "${status}"?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, update',
        cancelButtonText: 'Cancel',
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '../Actions/updateRequisitionStatus.php',
                type: 'POST',
                data: { requisition_id: requisitionId, status: status },
                success: function (response) {
                    const res = JSON.parse(response);
                    if (res.success) {
                        Swal.fire('Updated!', res.message, 'success').then(() => location.reload());
                    } else {
                        Swal.fire('Error!', res.message, 'error');
                    }
                },
                error: function () {
                    Swal.fire('Error!', 'Something went wrong.', 'error');
                }
            });
        }
    });
});
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
