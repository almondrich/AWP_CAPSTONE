<?php
include '../Actions/connection.php';

// Initialize variables
$result = false;
$type = $_GET['report_type'] ?? '';
$startDate = $_GET['start_date'] ?? '';
$endDate = $_GET['end_date'] ?? '';
$keyword = $_GET['keyword'] ?? '';
$filterQuery = "";

// Apply advanced filtering options
if (!empty($startDate) && !empty($endDate)) {
    $filterQuery .= " AND r.request_date BETWEEN '$startDate' AND '$endDate'";
}
if (!empty($keyword)) {
    $filterQuery .= " AND (u.name LIKE '%$keyword%' OR i.item_name LIKE '%$keyword%')";
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if ($type == 'pending_requisitions') {
        $query = "SELECT r.requisition_id, u.name AS user_name, i.item_name, r.quantity, r.remaining_quantity, r.status, r.request_date
                  FROM requisitions r
                  LEFT JOIN user u ON r.user_id = u.user_id
                  LEFT JOIN items i ON r.item_id = i.item_id
                  WHERE r.status = 'Pending' $filterQuery";
    } elseif ($type == 'approved_requisitions') {
        $query = "SELECT r.requisition_id, u.name AS user_name, i.item_name, r.quantity, r.remaining_quantity, r.status, r.request_date
                  FROM requisitions r
                  LEFT JOIN user u ON r.user_id = u.user_id
                  LEFT JOIN items i ON r.item_id = i.item_id
                  WHERE r.status = 'Approved' $filterQuery";
    } elseif ($type == 'released_requisitions') {
        $query = "SELECT r.requisition_id, u.name AS user_name, i.item_name, r.quantity, r.released_quantity, r.remaining_quantity, r.status, r.request_date
                  FROM requisitions r
                  LEFT JOIN user u ON r.user_id = u.user_id
                  LEFT JOIN items i ON r.item_id = i.item_id
                  WHERE r.status = 'Released' $filterQuery";
    } elseif ($type == 'stock_logs') {
        $query = "SELECT sl.log_id, i.item_name, sl.added_quantity, sl.stock_date, sl.created_at
                  FROM stock_logs sl
                  LEFT JOIN items i ON sl.item_id = i.item_id
                  WHERE 1=1 $filterQuery";
    } else {
        $query = "";
    }

    if (!empty($query)) {
        $result = $con->query($query);
    }
}

// Fetch summary data
$totalPending = $con->query("SELECT COUNT(*) AS total FROM requisitions WHERE status = 'Pending'")->fetch_assoc()['total'];
$totalReleased = $con->query("SELECT SUM(released_quantity) AS total FROM requisitions WHERE status = 'Released'")->fetch_assoc()['total'];
$totalStockAdded = $con->query("SELECT SUM(added_quantity) AS total FROM stock_logs")->fetch_assoc()['total'];
$highestRequisitionedItem = $con->query("SELECT i.item_name, SUM(r.quantity) AS total_quantity
    FROM requisitions r
    LEFT JOIN items i ON r.item_id = i.item_id
    GROUP BY r.item_id
    ORDER BY total_quantity DESC
    LIMIT 1")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Enhanced Inventory Reports</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="../../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <!-- Custom CSS -->
    <style>
        /* Custom styles if needed */
        .fs-2 {
            font-size: 2rem;
        }
        .fs-4 {
            font-size: 1.5rem;
        }
        .fs-5 {
            font-size: 1.25rem;
        }
        .fw-bold {
            font-weight: bold;
        }
        .gap-2 {
            gap: 10px;
        }
        .text-primary, .text-success, .text-info, .text-warning {
            color: inherit;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    <!-- Sidebar -->
    <aside class="main-sidebar sidebar-dark-green elevation-4" style="background-color: #004d40;">
        <!-- Brand Logo -->
        <a href="#" class="brand-link text-center">
            <img src="../../dist/img/123.png" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .9">
            <span class="brand-text font-weight-bold text-white">Santor Inventory</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar user panel -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img src="../../dist/img/admin.png" class="img-circle elevation-2 border border-white" alt="User Image">
                </div>
                <div class="info">
                    <a href="#" class="d-block text-white">Marissa G. Carbaruan</a>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <!-- You can modify or add menu items here -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                    <!-- Dashboard Link -->
                    <li class="nav-item">
                        <a href="dashboard.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?> text-white">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    <!-- Library Files -->
                    <li class="nav-item <?php echo in_array(basename($_SERVER['PHP_SELF']), ['category.php', 'unit.php', 'suppliers.php', 'items.php', 'archived_items.php', 'security.php', 'employees.php']) ? 'menu-open' : ''; ?>">
                        <a href="#" class="nav-link text-white">
                            <i class="nav-icon fas fa-folder"></i>
                            <p>Library Files <i class="fas fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview" style="display: <?php echo in_array(basename($_SERVER['PHP_SELF']), ['category.php', 'unit.php', 'suppliers.php', 'items.php', 'archived_items.php', 'security.php', 'employees.php']) ? 'block' : 'none'; ?>;">
                            <li class="nav-item">
                                <a href="category.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'category.php' ? 'active' : ''; ?> text-white-50">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Category</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="unit.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'unit.php' ? 'active' : ''; ?> text-white-50">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Units</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="items.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'items.php' ? 'active' : ''; ?> text-white-50">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Items</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="archived_items.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'archived_items.php' ? 'active' : ''; ?> text-white-50">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Archived Items</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="security.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'security.php' ? 'active' : ''; ?> text-white-50">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Security</p>
                                </a>
                            </li>
                            <!-- You can add more items here -->
                        </ul>
                    </li>

                    <!-- Transactions -->
                    <li class="nav-item <?php echo in_array(basename($_SERVER['PHP_SELF']), ['stockin.php', 'issuance.php', 'UserRequest.php']) ? 'menu-open' : ''; ?>">
                        <a href="#" class="nav-link text-white">
                            <i class="nav-icon fas fa-exchange-alt"></i>
                            <p>Transactions <i class="fas fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview" style="display: <?php echo in_array(basename($_SERVER['PHP_SELF']), ['stockin.php', 'issuance.php', 'UserRequest.php']) ? 'block' : 'none'; ?>;">
                            <li class="nav-item">
                                <a href="stockin.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'stockin.php' ? 'active' : ''; ?> text-white-50">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Stockin</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="UserRequest.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'UserRequest.php' ? 'active' : ''; ?> text-white-50">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Request</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Reports -->
                    <li class="nav-item <?php echo in_array(basename($_SERVER['PHP_SELF']), ['reportStockin.php', 'reportIssuance.php', 'reports.php']) ? 'menu-open' : ''; ?>">
                        <a href="#" class="nav-link text-white">
                            <i class="nav-icon fas fa-chart-bar"></i>
                            <p>Reports <i class="fas fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview" style="display: <?php echo in_array(basename($_SERVER['PHP_SELF']), ['reportStockin.php', 'reportIssuance.php', 'reports.php']) ? 'block' : 'none'; ?>;">
                            <li class="nav-item">
                                <a href="reportStockin.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'reportStockin.php' ? 'active' : ''; ?> text-white-50">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Stockin</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="reportIssuance.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'reportIssuance.php' ? 'active' : ''; ?> text-white-50">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Issuance</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="reports.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'reports.php' ? 'active' : ''; ?> text-white-50">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Enhanced Reports</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Logout -->
                    <li class="nav-item">
                        <a href="../Actions/logout.php" class="nav-link text-white">
                            <i class="fas fa-sign-out-alt"></i>
                            <p>Logout</p>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <!-- Main Content -->
        <section class="content mt-3">
            <div class="container-fluid">
                <h2 class="text-center mb-4">Inventory Reports</h2>

                <!-- Summary Cards -->
                <div class="row text-center mb-4">
                    <!-- Total Pending Requests -->
                    <div class="col-md-3">
                        <div class="card shadow-lg border-0">
                            <div class="card-body">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="fas fa-hourglass-half text-primary fs-2 mb-2"></i>
                                    <h5 class="card-title text-primary">Total Pending Requests</h5>
                                    <p class="card-text fs-4 fw-bold"><?= $totalPending; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Total Items Released -->
                    <div class="col-md-3">
                        <div class="card shadow-lg border-0">
                            <div class="card-body">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="fas fa-check-circle text-success fs-2 mb-2"></i>
                                    <h5 class="card-title text-success">Total Items Released</h5>
                                    <p class="card-text fs-4 fw-bold"><?= $totalReleased ?: 0; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Total Stock Added -->
                    <div class="col-md-3">
                        <div class="card shadow-lg border-0">
                            <div class="card-body">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="fas fa-box text-info fs-2 mb-2"></i>
                                    <h5 class="card-title text-info">Total Stock Added</h5>
                                    <p class="card-text fs-4 fw-bold"><?= $totalStockAdded ?: 0; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Highest Requisitioned Item -->
                    <div class="col-md-3">
                        <div class="card shadow-lg border-0">
                            <div class="card-body">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="fas fa-star text-warning fs-2 mb-2"></i>
                                    <h5 class="card-title text-warning">Highest Requisitioned Item</h5>
                                    <p class="card-text fs-5 fw-bold"><?= $highestRequisitionedItem['item_name'] ?? 'N/A'; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <form method="GET" action="reports.php" class="mb-4">
                    <div class="row">
                        <!-- Uncomment if you wish to use Start Date and End Date filters
                        <div class="col-md-3">
                            <label for="start_date" class="form-label">Start Date:</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" value="<?= $startDate; ?>">
                        </div>
                        <div class="col-md-3">
                            <label for="end_date" class="form-label">End Date:</label>
                            <input type="date" name="end_date" id="end_date" class="form-control" value="<?= $endDate; ?>">
                        </div>
                        -->
                        <div class="col-md-3">
                            <label for="keyword" class="form-label">Search:</label>
                            <input type="text" name="keyword" id="keyword" class="form-control" placeholder="Search..." value="<?= $keyword; ?>">
                        </div>
                        <div class="col-md-3">
                            <label for="report_type" class="form-label">Report Type:</label>
                            <BR>
                            <select name="report_type" id="report_type" class="form-select" required>
                                <option value="">-- Select Report Type --</option>
                                <option value="pending_requisitions" <?= ($type == 'pending_requisitions') ? 'selected' : ''; ?>>Pending Requisitions</option>
                                <option value="approved_requisitions" <?= ($type == 'approved_requisitions') ? 'selected' : ''; ?>>Approved Requisitions</option>
                                <option value="released_requisitions" <?= ($type == 'released_requisitions') ? 'selected' : ''; ?>>Released Requisitions</option>
                                <option value="stock_logs" <?= ($type == 'stock_logs') ? 'selected' : ''; ?>>Stock Addition Logs</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-filter me-2"></i> Apply Filters</button>
                    </div>
                </form>

                <!-- Report Table -->
                <?php if ($result && $result->num_rows > 0): ?>
                    <div class="d-flex justify-content-end gap-2 align-items-center mb-3">
                        <button id="printReport" class="btn btn-outline-primary d-flex align-items-center gap-2">
                            <i class="fas fa-print"></i>
                            <span>Print Report</span>
                        </button>
                        <button id="exportExcel" class="btn btn-outline-success d-flex align-items-center gap-2">
                            <i class="fas fa-file-excel"></i>
                            <span>Export to Excel</span>
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table id="reportTable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <?php if ($type == 'stock_logs'): ?>
                                        <th>ID</th>
                                        <th>Item</th>
                                        <th>Added Quantity</th>
                                        <th>Stock Date</th>
                                        <th>Created At</th>
                                    <?php else: ?>
                                        <th>ID</th>
                                        <th>User</th>
                                        <th>Item</th>
                                        <th>Quantity</th>
                                        <th>Remaining</th>
                                        <th>Status</th>
                                        <th>Request Date</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['log_id'] ?? $row['requisition_id']); ?></td>
                                        <?php if ($type == 'stock_logs'): ?>
                                            <td><?= htmlspecialchars($row['item_name']); ?></td>
                                            <td><?= htmlspecialchars($row['added_quantity']); ?></td>
                                            <td><?= htmlspecialchars($row['stock_date']); ?></td>
                                            <td><?= htmlspecialchars($row['created_at']); ?></td>
                                        <?php else: ?>
                                            <td><?= htmlspecialchars($row['user_name'] ?? ''); ?></td>
                                            <td><?= htmlspecialchars($row['item_name'] ?? ''); ?></td>
                                            <td><?= htmlspecialchars($row['quantity'] ?? ''); ?></td>
                                            <td><?= htmlspecialchars($row['remaining_quantity'] ?? ''); ?></td>
                                            <td><?= htmlspecialchars($row['status'] ?? ''); ?></td>
                                            <td><?= htmlspecialchars($row['request_date'] ?? ''); ?></td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-center">No data available for the selected report.</p>
                <?php endif; ?>
            </div>
        </section>
    </div>
    <!-- /.content-wrapper -->
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
<!-- jQuery -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.min.js"></script>
<!-- DataTables -->
<script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<!-- Custom JS -->
<script>
    $(document).ready(function() {
        $('#reportTable').DataTable();

        // Print functionality
        $('#printReport').on('click', function() {
            window.print();
        });

        // Export to Excel functionality
        $('#exportExcel').on('click', function() {
            const table = document.getElementById('reportTable');
            let csv = [];
            for (let row of table.rows) {
                let cols = [...row.cells].map(cell => cell.innerText.replace(/,/g, '')); // Remove commas from cell values
                csv.push(cols.join(','));
            }
            const csvFile = new Blob([csv.join('\n')], { type: 'text/csv' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(csvFile);
            link.download = 'report.csv';
            link.click();
        });
    });
</script>
</body>
</html>
