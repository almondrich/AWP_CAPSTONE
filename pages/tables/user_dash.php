<?php
include '../Actions/connection.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Requisition Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: 'Source Sans Pro', sans-serif;
            background-color: #f3f4f6;
        }
        .wrapper {
            display: flex;
            flex-direction: row;
        }
        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #343a40;
            color: #fff;
            display: flex;
            flex-direction: column;
            padding: 20px;
            position: fixed;
        }
        .sidebar .nav-link {
            color: #ddd;
            margin: 10px 0;
            font-size: 16px;
            display: flex;
            align-items: center;
        }
        .sidebar .nav-link i {
            margin-right: 10px;
        }
        .sidebar .nav-link:hover {
            color: #fff;
            text-decoration: none;
        }
        .content {
            margin-left: 250px;
            padding: 20px;
            flex-grow: 1;
        }
        .card {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }
        .table th, .table td {
            vertical-align: middle;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <!-- Sidebar -->
    <div class="sidebar">
        <h4 class="text-center">Requisition Dashboard</h4>
        <a href="dashboard.php" class="nav-link"><i class="fas fa-home"></i> Dashboard</a>
        <a href="requisition_form.php" class="nav-link"><i class="fas fa-file-alt"></i> Requisition Form</a>
        <a href="request_history.php" class="nav-link"><i class="fas fa-history"></i> Request History</a>
        <a href="logout.php" class="nav-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <!-- Content Area -->
    <div class="content">
        <h2>Request History</h2>
        <p>Below is a summary of all your past requisitions:</p>

        <!-- Request History Table -->
        <div class="card p-3">
            <table class="table table-striped table-bordered">
                <thead class="thead-dark">
                <tr>
                    <th>Requisition ID</th>
                    <th>Item Name</th>
                    <th>Quantity</th>
                    <th>Justification</th>
                    <th>Status</th>
                    <th>Request Date</th>
                </tr>
                </thead>
                <tbody>
                <?php
                // Fetch requisition history for the logged-in user
                $query = "SELECT r.requisition_id, i.item_name, r.quantity, r.justification, r.status, r.request_date 
                          FROM requisitions r
                          JOIN items i ON r.item_id = i.item_id
                          WHERE r.user_id = ?
                          ORDER BY r.request_date DESC";
                $stmt = $con->prepare($query);
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['requisition_id']}</td>
                                <td>{$row['item_name']}</td>
                                <td>{$row['quantity']}</td>
                                <td>" . htmlspecialchars($row['justification']) . "</td>
                                <td>{$row['status']}</td>
                                <td>{$row['request_date']}</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' class='text-center'>No requisitions found.</td></tr>";
                }
                $stmt->close();
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
