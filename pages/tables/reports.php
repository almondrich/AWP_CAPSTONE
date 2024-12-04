<?php
include '../Actions/connection.php';

$result = false; // Initialize $result to prevent errors if no query is executed.
$type = $_GET['report_type'] ?? '';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if ($type == 'pending_requisitions') {
        $query = "SELECT r.requisition_id, u.name AS user_name, i.item_name, r.quantity, r.remaining_quantity, r.status, r.request_date
                  FROM requisitions r
                  LEFT JOIN user u ON r.user_id = u.user_id
                  LEFT JOIN items i ON r.item_id = i.item_id
                  WHERE r.status = 'Pending'";
    } elseif ($type == 'approved_requisitions') {
        $query = "SELECT r.requisition_id, u.name AS user_name, COALESCE(i.item_name, 'Unknown Item') AS item_name, 
                         r.quantity, r.remaining_quantity, r.status, r.request_date
                  FROM requisitions r
                  LEFT JOIN user u ON r.user_id = u.user_id
                  LEFT JOIN items i ON r.item_id = i.item_id
                  WHERE r.status = 'Approved'";
    } elseif ($type == 'released_requisitions') {
        $query = "SELECT r.requisition_id, u.name AS user_name, COALESCE(i.item_name, 'Unknown Item') AS item_name, 
                         r.quantity, r.released_quantity, r.remaining_quantity, r.status, r.request_date
                  FROM requisitions r
                  LEFT JOIN user u ON r.user_id = u.user_id
                  LEFT JOIN items i ON r.item_id = i.item_id
                  WHERE r.status = 'Released'";
    } elseif ($type == 'stock_logs') {
        $query = "SELECT sl.log_id, COALESCE(i.item_name, 'Unknown Item') AS item_name, sl.added_quantity, sl.stock_date, sl.created_at
                  FROM stock_logs sl
                  LEFT JOIN items i ON sl.item_id = i.item_id";
    } else {
        $query = ""; // No valid report type selected
    }

    if (!empty($query)) {
        $result = $con->query($query);
        if (!$result) {
            echo "Error executing query: " . $con->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - Inventory System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <style>
        body {
            background-color: #f4f4f9;
            font-family: 'Arial', sans-serif;
        }
        .container {
            margin-top: 40px;
        }
        .card-header {
            background-color: #2c3e50; /* Subtle Dark Blue */
            color: white;
        }
        .btn-primary, .btn-success {
            border-radius: 5px;
        }
        th {
            background-color: #2c3e50;
            color: white;
        }
        td, th {
            text-align: center;
        }
        .table thead {
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card shadow">
            <div class="card-header text-center">
                <h3><i class="fas fa-chart-line"></i> Inventory Reports</h3>
            </div>
            <div class="card-body">
                <form method="GET" action="reports.php" class="mb-4">
                    <div class="form-group">
                        <label for="report_type"><strong>Select Report Type:</strong></label>
                        <select name="report_type" id="report_type" class="form-control" required>
                            <option value="">-- Select Report Type --</option>
                            <option value="pending_requisitions" <?= ($type == 'pending_requisitions') ? 'selected' : ''; ?>>Pending Requisitions</option>
                            <option value="approved_requisitions" <?= ($type == 'approved_requisitions') ? 'selected' : ''; ?>>Approved Requisitions</option>
                            <option value="released_requisitions" <?= ($type == 'released_requisitions') ? 'selected' : ''; ?>>Released Requisitions</option>
                            <option value="stock_logs" <?= ($type == 'stock_logs') ? 'selected' : ''; ?>>Stock Addition Logs</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-search"></i> Generate Report</button>
                </form>

                <div class="mb-3 d-flex justify-content-between">
                    <button class="btn btn-primary" id="printReport"><i class="fas fa-print"></i> Print</button>
                    <button class="btn btn-success" id="exportExcel"><i class="fas fa-file-excel"></i> Export to Excel</button>
                </div>

                <?php if ($result && $result->num_rows > 0): ?>
                    <div class="table-responsive">
                        <table id="reportTable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <?php if ($type == 'pending_requisitions' || $type == 'approved_requisitions'): ?>
                                        <th>User</th>
                                        <th>Item</th>
                                        <th>Quantity</th>
                                        <th>Remaining</th>
                                        <th>Status</th>
                                        <th>Request Date</th>
                                    <?php elseif ($type == 'released_requisitions'): ?>
                                        <th>User</th>
                                        <th>Item</th>
                                        <th>Quantity</th>
                                        <th>Released</th>
                                        <th>Remaining</th>
                                        <th>Status</th>
                                        <th>Request Date</th>
                                    <?php elseif ($type == 'stock_logs'): ?>
                                        <th>Item</th>
                                        <th>Added Quantity</th>
                                        <th>Stock Date</th>
                                        <th>Logged At</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['requisition_id'] ?? $row['log_id']); ?></td>
                                        <?php if ($type == 'pending_requisitions' || $type == 'approved_requisitions'): ?>
                                            <td><?= htmlspecialchars($row['user_name']); ?></td>
                                            <td><?= htmlspecialchars($row['item_name']); ?></td>
                                            <td><?= htmlspecialchars($row['quantity']); ?></td>
                                            <td><?= htmlspecialchars($row['remaining_quantity']); ?></td>
                                            <td><?= htmlspecialchars($row['status']); ?></td>
                                            <td><?= htmlspecialchars($row['request_date']); ?></td>
                                        <?php elseif ($type == 'released_requisitions'): ?>
                                            <td><?= htmlspecialchars($row['user_name']); ?></td>
                                            <td><?= htmlspecialchars($row['item_name']); ?></td>
                                            <td><?= htmlspecialchars($row['quantity']); ?></td>
                                            <td><?= htmlspecialchars($row['released_quantity']); ?></td>
                                            <td><?= htmlspecialchars($row['remaining_quantity']); ?></td>
                                            <td><?= htmlspecialchars($row['status']); ?></td>
                                            <td><?= htmlspecialchars($row['request_date']); ?></td>
                                        <?php elseif ($type == 'stock_logs'): ?>
                                            <td><?= htmlspecialchars($row['item_name']); ?></td>
                                            <td><?= htmlspecialchars($row['added_quantity']); ?></td>
                                            <td><?= htmlspecialchars($row['stock_date']); ?></td>
                                            <td><?= htmlspecialchars($row['created_at']); ?></td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-center text-muted">No data found for the selected report type.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#reportTable').DataTable({
                paging: true,
                ordering: true,
                info: true
            });

            $('#printReport').on('click', function() {
                window.print();
            });

            $('#exportExcel').on('click', function() {
                const table = document.getElementById('reportTable');
                let csv = [];
                for (let row of table.rows) {
                    let cols = [...row.cells].map(cell => cell.innerText);
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
