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
                  LEFT JOIN items i ON sl.item_id = i.item_id $filterQuery";
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enhanced Inventory Reports</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Inventory Reports</h2>

        <!-- Summary Cards -->
        <div class="row text-center mb-4">
            <div class="col-md-3">
                <div class="card shadow">
                    <div class="card-body">
                        <h5 class="card-title text-primary"><i class="fas fa-hourglass-half"></i> Total Pending Requests</h5>
                        <p class="card-text"><?= $totalPending; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow">
                    <div class="card-body">
                        <h5 class="card-title text-success"><i class="fas fa-check-circle"></i> Total Items Released</h5>
                        <p class="card-text"><?= $totalReleased ?: 0; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow">
                    <div class="card-body">
                        <h5 class="card-title text-info"><i class="fas fa-box"></i> Total Stock Added</h5>
                        <p class="card-text"><?= $totalStockAdded ?: 0; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow">
                    <div class="card-body">
                        <h5 class="card-title text-warning"><i class="fas fa-star"></i> Highest Requisitioned Item</h5>
                        <p class="card-text"><?= $highestRequisitionedItem['item_name'] ?? 'N/A'; ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <form method="GET" action="reports.php" class="mb-4">
            <div class="row">
                <div class="col-md-3">
                    <label for="start_date" class="form-label">Start Date:</label>
                    <input type="date" name="start_date" id="start_date" class="form-control" value="<?= $startDate; ?>">
                </div>
                <div class="col-md-3">
                    <label for="end_date" class="form-label">End Date:</label>
                    <input type="date" name="end_date" id="end_date" class="form-control" value="<?= $endDate; ?>">
                </div>
                <div class="col-md-3">
                    <label for="keyword" class="form-label">Search:</label>
                    <input type="text" name="keyword" id="keyword" class="form-control" placeholder="Search..." value="<?= $keyword; ?>">
                </div>
                <div class="col-md-3">
                    <label for="report_type" class="form-label">Report Type:</label>
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
                <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Apply Filters</button>
            </div>
        </form>

        <!-- Report Table -->
        <?php if ($result && $result->num_rows > 0): ?>
            <div class="table-responsive">
                <table id="reportTable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Item</th>
                            <th>Quantity</th>
                            <th>Remaining</th>
                            <th>Status</th>
                            <th>Request Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['requisition_id'] ?? $row['log_id']); ?></td>
                                <td><?= htmlspecialchars($row['user_name'] ?? ''); ?></td>
                                <td><?= htmlspecialchars($row['item_name'] ?? ''); ?></td>
                                <td><?= htmlspecialchars($row['quantity'] ?? ''); ?></td>
                                <td><?= htmlspecialchars($row['remaining_quantity'] ?? ''); ?></td>
                                <td><?= htmlspecialchars($row['status'] ?? ''); ?></td>
                                <td><?= htmlspecialchars($row['request_date'] ?? ''); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-center">No data available for the selected report.</p>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#reportTable').DataTable();
        });
    </script>
</body>
</html>
