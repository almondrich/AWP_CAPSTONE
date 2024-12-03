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
    <title>Department Dean Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/feather-icons"></script>
       <link rel="stylesheet" href="../tables/request_design2.css">

</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container-fluid">
        <!-- Logo and Title -->
        <a class="navbar-brand d-flex align-items-center" href="department_dean.php">
            <img src="../../dist/img/sjcbaggao.png" alt="Logo" width="300" height="75">
            <span class="fs-5 fw-bold" style="color:white;"> | Department Dean Portal</span>
        </a>

        <!-- Toggle Button for Mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a id="navv"  class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard_user.php' ? 'active' : ''; ?>" href="dashboard_user.php">
                        <i data-feather="home"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a  id="navv" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'requisition_form.php' ? 'active' : ''; ?>" href="requisition_form.php">
                        <i data-feather="edit"></i> Request
                    </a>
                </li>
                <li class="nav-item">
                    <a id="navv" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'user_dash.php' ? 'active' : ''; ?>" href="user_dash.php">
                        <i data-feather="list"></i> Request Status
                    </a>
                </li>
                <li class="nav-item">
                    <a id="navv" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'profile.php' ? 'active' : ''; ?>" href="profile.php">
                        <i data-feather="user"></i> Profile
                    </a>
                </li>
                <li class="nav-item">
                    <a id="navvv" class="nav-link  <?php echo basename($_SERVER['PHP_SELF']) == 'logout.php' ? 'active' : ''; ?>" href="logout.php">
                        <i data-feather="log-out"></i> Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="content">
    <h2>Request History</h2>
    <p>Below is a summary of all your past requisitions:</p>

    <div class="card shadow-sm p-4">
        <h5 class="mb-3">Requisition History</h5>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Req. ID</th>
                        <th>Item Name</th>
                        <th>Quantity</th>
                        <th>Justification</th>
                        <th>Status</th>
                        <th>Dept Dean</th>
                        <th>College Dean</th>
                        <th>Request Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT r.requisition_id, i.item_name, r.quantity, r.justification, r.status, 
                                     r.department_dean_status, r.department_dean_remarks, 
                                     r.college_dean_status, r.college_dean_remarks, r.request_date
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
                                    <td><span class='badge " . ($row['status'] == 'Pending' ? 'bg-warning text-dark' : ($row['status'] == 'Approved' ? 'bg-success' : 'bg-danger')) . "'>{$row['status']}</span></td>
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
                        echo "<tr><td colspan='8' class='text-center text-muted'>No requisitions found.</td></tr>";
                    }
                    $stmt->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


</body>
</html>
