<?php
include '../Actions/connection.php';
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id']; // Verify logged-in College Dean

// Fetch pending requisitions approved by the Department Dean
$query = "SELECT r.requisition_id, r.item_id, i.item_name, i.item_desc, r.quantity, r.justification, 
                 r.department_dean_status, r.college_dean_status, r.college_dean_remarks, r.request_date, u.name AS requestor_name
          FROM requisitions r
          JOIN items i ON r.item_id = i.item_id
          JOIN user u ON r.user_id = u.user_id
          WHERE r.department_dean_status = 'Approved' AND r.college_dean_status = 'Pending'";
$result = $con->query($query);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $requisition_id = intval($_POST['requisition_id']);
    $action = $_POST['action'];
    $remarks = trim($_POST['remarks'] ?? '');

    if ($action === 'approve') {
        // Check if Department Dean already approved
        $checkQuery = "SELECT department_dean_status FROM requisitions WHERE requisition_id = ?";
        $stmt = $con->prepare($checkQuery);
        $stmt->bind_param("i", $requisition_id);
        $stmt->execute();
        $stmt->bind_result($departmentDeanStatus);
        $stmt->fetch();
        $stmt->close();
    
        if ($departmentDeanStatus === 'Approved') {
            // Update College Dean status and overall requisition status
            $updateQuery = "UPDATE requisitions 
                            SET college_dean_status = 'Approved', college_dean_remarks = NULL, status = 'Approved' 
                            WHERE requisition_id = ?";
            $stmt = $con->prepare($updateQuery);
            $stmt->bind_param("i", $requisition_id);
            $stmt->execute();
            $stmt->close();
            $_SESSION['success_message'] = "Requisition fully approved.";
        } else {
            $_SESSION['error_message'] = "Requisition must first be approved by the Department Dean.";
        }
    } elseif ($action === 'disapprove') {
        // Set status to Disapproved and add remarks
        $updateQuery = "UPDATE requisitions 
                        SET college_dean_status = 'Disapproved', college_dean_remarks = ?, status = 'Disapproved' 
                        WHERE requisition_id = ?";
        $stmt = $con->prepare($updateQuery);
        $stmt->bind_param("si", $remarks, $requisition_id);
        $stmt->execute();
        $stmt->close();
        $_SESSION['success_message'] = "Requisition disapproved by College Dean with remarks.";
    }
    

    // Redirect to avoid form resubmission
    header("Location: college_dean.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>College Dean Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .navbar {
            background: linear-gradient(to right, #004d00, #000000);
            border-bottom: 1px solid #002200;
        }

        .navbar-brand {
            font-family: 'Roboto', sans-serif;
            color: #ffffff;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.8);
        }

        .navbar-brand img {
            padding: 2px;
        }

        .navbar-nav .nav-link {
            font-size: 1rem;
            color: #ffffff;
            margin-left: 15px;
            transition: color 0.3s ease-in-out;
            position: relative;
            padding-bottom: 5px;
        }

        .navbar-nav .nav-link:hover {
            color: #00cc00;
        }

        .navbar-nav .nav-link.active {
            color: #ffffff;
            font-weight: bold;
            border-bottom: 2px solid #ffffff;
        }

        .dashboard-header {
            background-color: #343a40;
            color: #fff;
            padding: 1.5rem;
            border-radius: 10px;
            text-align: center;
            margin-bottom: 20px;
        }

        .card {
            border-radius: 10px;
        }

        .card-header {
            font-weight: bold;
        }

        .badge {
            font-size: 1rem;
            padding: 0.5rem 0.8rem;
        }

        @media (max-width: 768px) {
            .navbar-nav .nav-link {
                margin-left: 0;
                padding: 10px 15px;
            }
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container-fluid">
        <!-- Logo and Title -->
        <a class="navbar-brand d-flex align-items-center" href="college_dean.php">
            <img src="../../dist/img/sjcbaggao.png" alt="Logo" width="300" height="75">
            <span class="fs-5 fw-bold" style="color:white;"> | College Dean Portal</span>
        </a>

        <!-- Toggle Button for Mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navigation Links -->
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'college_dean.php' ? 'active' : ''; ?>" href="college_dean.php">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'college_dean_history.php' ? 'active' : ''; ?>" href="college_dean_history.php">
                        <i class="fas fa-history"></i> History
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'profile.php' ? 'active' : ''; ?>" href="profile.php">
                        <i class="fas fa-user-circle"></i> Profile
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'logout.php' ? 'active' : ''; ?>" href="logout.php">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <h2 class="dashboard-header">College Dean Dashboard</h2>

    <?php if (isset($_SESSION['success_message'])): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '<?php echo $_SESSION['success_message']; ?>'
            });
        </script>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <table class="table table-bordered mt-4">
        <thead class="thead-dark">
        <tr>
            <th>#</th>
            <th>Requestor</th>
            <th>Item Name</th>
            <th>Description</th>
            <th>Quantity</th>
            <th>Justification</th>
            <th>Date Requested</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['requisition_id']; ?></td>
                    <td><?php echo $row['requestor_name']; ?></td>
                    <td><?php echo $row['item_name']; ?></td>
                    <td><?php echo $row['item_desc']; ?></td>
                    <td><?php echo $row['quantity']; ?></td>
                    <td><?php echo $row['justification']; ?></td>
                    <td><?php echo $row['request_date']; ?></td>
                    <td>
                        <button class="btn btn-success btn-sm" onclick="approveRequisition(<?php echo $row['requisition_id']; ?>)">Approve</button>
                        <button class="btn btn-danger btn-sm" onclick="disapproveRequisition(<?php echo $row['requisition_id']; ?>)">Disapprove</button>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="8" class="text-center">No requisitions found.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
    function approveRequisition(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You are about to approve this requisition!",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, approve',
            cancelButtonText: 'Cancel',
        }).then((result) => {
            if (result.isConfirmed) {
                submitForm(id, 'approve');
            }
        });
    }

    function disapproveRequisition(id) {
        Swal.fire({
            title: 'Provide Remarks',
            input: 'textarea',
            inputLabel: 'Reason for disapproval',
            inputPlaceholder: 'Enter your remarks here...',
            inputAttributes: {
                'aria-label': 'Enter your remarks here'
            },
            showCancelButton: true,
            confirmButtonText: 'Submit',
            cancelButtonText: 'Cancel',
        }).then((result) => {
            if (result.isConfirmed && result.value) {
                submitForm(id, 'disapprove', result.value);
            } else if (result.isConfirmed && !result.value) {
                Swal.fire('Error!', 'Remarks are required for disapproval.', 'error');
            }
        });
    }

    function submitForm(id, action, remarks = '') {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'college_dean.php';

        const idField = document.createElement('input');
        idField.type = 'hidden';
        idField.name = 'requisition_id';
        idField.value = id;

        const actionField = document.createElement('input');
        actionField.type = 'hidden';
        actionField.name = 'action';
        actionField.value = action;

        const remarksField = document.createElement('input');
        remarksField.type = 'hidden';
        remarksField.name = 'remarks';
        remarksField.value = remarks;

        form.appendChild(idField);
        form.appendChild(actionField);
        form.appendChild(remarksField);

        document.body.appendChild(form);
        form.submit();
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
