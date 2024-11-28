<?php
include '../Actions/connection.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id']; // Identify the logged-in Department Dean

// Fetch requisition history
$query = "SELECT r.requisition_id, r.item_id, i.item_name, i.item_desc, r.quantity, r.justification, r.status, r.request_date, u.name AS requestor_name, r.remarks
          FROM requisitions r
          JOIN items i ON r.item_id = i.item_id
          JOIN user u ON r.user_id = u.user_id
          WHERE r.status IN ('Approved', 'Rejected', 'Pending')
          ORDER BY r.request_date DESC";

$result = $con->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Department Dean - Requisition History</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Roboto', sans-serif;
        }

        .navbar {
            background: linear-gradient(to right, #004d00, #000000);
        }

        .navbar-brand {
            color: white;
            font-weight: bold;
        }

        .navbar-nav .nav-link {
            color: #ffffff;
            margin-left: 15px;
        }

        .navbar-nav .nav-link:hover {
            color: #00cc00;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: #343a40;
            color: white;
            font-weight: bold;
        }

        .badge {
            padding: 0.5rem;
            font-size: 0.9rem;
        }

        .table th, .table td {
            vertical-align: middle;
        }

        .table th {
            background-color: #004d00;
            color: white;
        }

        .text-muted {
            color: #6c757d !important;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="department_dean.php">
            <img src="../../dist/img/sjcbaggao.png" alt="Logo" width="300" height="75">
            <span class="ml-3">Department Dean Portal</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="department_dean.php"><i data-feather="home"></i> Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="department_dean_history.php"><i data-feather="list"></i> History</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="profile.php"><i data-feather="user"></i> Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php"><i data-feather="log-out"></i> Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">Department Dean History</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Requestor</th>
                        <th>Item Name</th>
                        <th>Description</th>
                        <th>Quantity</th>
                        <th>Justification</th>
                        <th>Status</th>
                        <th>Remarks</th>
                        <th>Request Date</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['requisition_id']; ?></td>
                                <td><?php echo htmlspecialchars($row['requestor_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['item_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['item_desc']); ?></td>
                                <td><?php echo $row['quantity']; ?></td>
                                <td><?php echo htmlspecialchars($row['justification']); ?></td>
                                <td>
                                    <span class="badge 
                                        <?php 
                                            echo ($row['status'] == 'Pending') ? 'bg-warning text-dark' : 
                                                 (($row['status'] == 'Approved') ? 'bg-success' : 'bg-danger'); 
                                        ?>">
                                        <?php echo $row['status']; ?>
                                    </span>
                                </td>
                                <td><?php echo $row['remarks'] ? htmlspecialchars($row['remarks']) : 'N/A'; ?></td>
                                <td><?php echo date('F j, Y', strtotime($row['request_date'])); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center text-muted">No requisitions found.</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    feather.replace(); // Render Feather Icons
</script>
</body>
</html>
