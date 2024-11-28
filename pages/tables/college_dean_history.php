<?php
include '../Actions/connection.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id']; // Assuming this will identify the logged-in College Dean
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>College Dean History</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
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
            font-family: 'Roboto', sans-serif;
            color: #fff;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.8);
        }
        .navbar-nav .nav-link {
            color: #fff;
            margin-left: 15px;
        }
        .navbar-nav .nav-link:hover {
            color: #00cc00;
        }
        .content {
            padding: 20px;
        }
        .card {
            border-radius: 10px;
        }
        .card-header {
            background-color: #343a40;
            color: #fff;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .badge {
            padding: 0.5rem;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="college_dean.php">
            <img src="../../dist/img/sjcbaggao.png" alt="Logo" width="300" height="75">
            <span class="fs-5 fw-bold"> | College Dean Portal</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="college_dean.php"><i data-feather="home"></i> Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="college_dean_history.php"><i data-feather="list"></i> History</a>
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

<div class="content">
    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">College Dean History</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>Req. ID</th>
                            <th>Requestor</th>
                            <th>Item Name</th>
                            <th>Quantity</th>
                            <th>Dept Dean</th>
                            <th>College Dean</th>
                            <th>Request Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT r.requisition_id, u.name AS requestor_name, i.item_name, r.quantity, 
                                         r.department_dean_status, r.department_dean_remarks, 
                                         r.college_dean_status, r.college_dean_remarks, r.request_date
                                  FROM requisitions r
                                  JOIN items i ON r.item_id = i.item_id
                                  JOIN user u ON r.user_id = u.user_id
                                  WHERE r.college_dean_status IN ('Approved', 'Disapproved')
                                  ORDER BY r.request_date DESC";
                        $result = $con->query($query);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                        <td>{$row['requisition_id']}</td>
                                        <td>{$row['requestor_name']}</td>
                                        <td>{$row['item_name']}</td>
                                        <td>{$row['quantity']}</td>
                                        <td>
                                            <strong>Status:</strong> {$row['department_dean_status']}<br>
                                            <strong>Remarks:</strong> " . htmlspecialchars($row['department_dean_remarks'] ?? 'None') . "
                                        </td>
                                        <td>
                                            <strong>Status:</strong> {$row['college_dean_status']}<br>
                                            <strong>Remarks:</strong> " . htmlspecialchars($row['college_dean_remarks'] ?? 'None') . "
                                        </td>
                                        <td>" . date('F j, Y', strtotime($row['request_date'])) . "</td>
                                      </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7' class='text-center text-muted'>No requisitions found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    feather.replace(); /* Render Feather Icons */
</script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
