<?php
include '../Actions/connection.php';
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id']; // Assuming this will verify the logged-in Department Dean

// Fetch pending requisitions for the department
$query = "SELECT r.requisition_id, r.item_id, i.item_name, i.item_desc, r.quantity, r.justification, r.status, r.request_date, u.name AS requestor_name
          FROM requisitions r
          JOIN items i ON r.item_id = i.item_id
          JOIN user u ON r.user_id = u.user_id
          WHERE r.status = 'Pending'";
$result = $con->query($query);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $requisition_id = intval($_POST['requisition_id']);
    $action = $_POST['action'];
    $remarks = trim($_POST['remarks'] ?? '');

    if ($action === 'approve') {
        // Update status and department_dean_status to Approved
        $updateQuery = "UPDATE requisitions 
                        SET status = 'Approved', 
                            department_dean_status = 'Approved', 
                            department_dean_remarks = NULL 
                        WHERE requisition_id = ?";
        $stmt = $con->prepare($updateQuery);
        $stmt->bind_param("i", $requisition_id);
        $stmt->execute();
        $stmt->close();
        $_SESSION['success_message'] = "Requisition approved and forwarded to the College Dean.";
    } elseif ($action === 'disapprove') {
        // Update status and department_dean_status to Disapproved with remarks
        $updateQuery = "UPDATE requisitions 
                        SET status = 'Rejected', 
                            department_dean_status = 'Disapproved', 
                            department_dean_remarks = ? 
                        WHERE requisition_id = ?";
        $stmt = $con->prepare($updateQuery);
        $stmt->bind_param("si", $remarks, $requisition_id);
        $stmt->execute();
        $stmt->close();
        $_SESSION['success_message'] = "Requisition disapproved with remarks.";
    }

    // Redirect to avoid form resubmission
    header("Location: department_dean.php");
    exit;
}




// Pagination setup
$limit = 10;
$currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($currentPage - 1) * $limit;

// Count total records
$countQuery = "SELECT COUNT(*) as total FROM requisitions WHERE status = 'Pending'";
$countResult = $con->query($countQuery);
$row = $countResult->fetch_assoc();
$totalRecords = $row['total'];
$totalPages = ceil($totalRecords / $limit);

// Fetch records for the current page
$query = "SELECT r.requisition_id, r.item_id, i.item_name, i.item_desc, r.quantity, r.justification, r.status, r.request_date, u.name AS requestor_name
          FROM requisitions r
          JOIN items i ON r.item_id = i.item_id
          JOIN user u ON r.user_id = u.user_id
          WHERE r.status = 'Pending'
          LIMIT $limit OFFSET $offset";
$result = $con->query($query);
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
</head>
<body>
<style>
.navbar {
    background: linear-gradient(to right, #004d00, #000000); /* Dark green to black gradient */
    border-bottom: 1px solid #002200; /* Subtle darker green border */
}

.navbar-brand {
    font-family: 'Roboto', sans-serif;
    color: #ffffff; /* White text for logo/title */
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.8); /* Subtle shadow */
}

.navbar-brand img {
    
    padding: 2px;
}

.navbar-nav .nav-link {
    font-size: 1rem;
    color: #ffffff; /* White text for navigation links */
    margin-left: 15px;
    transition: color 0.3s ease-in-out;
}

.navbar-nav .nav-link:hover {
    color: #00cc00; /* Bright green hover effect */
}

.navbar-toggler {
    border: none;
}

.navbar-toggler-icon {
    background-image: url('data:image/svg+xml;charset=UTF8,%3Csvg xmlns%3D%27http://www.w3.org/2000/svg%27 viewBox%3D%270 0 30 30%27%3E%3Cpath stroke%3D%27rgba(255%2C 255%2C 255%2C 1%27 stroke-width%3D%272%27 stroke-linecap%3D%27round%27 stroke-miterlimit%3D%2710%27 d%3D%27M4 7h22M4 15h22M4 23h22%27/%3E%3C/svg%3E'); /* White toggle icon */
}

@media (max-width: 768px) {
    .navbar-nav .nav-link {
        margin-left: 0;
        padding: 10px 15px;
    }
}


</style>
<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container-fluid">
        <!-- Logo and Title -->
        <a class="navbar-brand d-flex align-items-center" href="department_dean.php">
        <img src="../../dist/img/sjcbaggao.png" alt="Logo" width="300" height="75">
        <span class="fs-5 fw-bold" style="color:white;">    |    Department Dean Portal</span>
        </a>

        <!-- Toggle Button for Mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navigation Links -->
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link text-white fw-semibold" href="department_dean.php">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white fw-semibold" href="department_dean_history.php">
                        <i class="fas fa-history"></i> History
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white fw-semibold" href="profile.php">
                        <i class="fas fa-user-circle"></i> Profile
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white fw-semibold" href="logout.php">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>


<div class="container mt-4">
    <h2 class="text-center">Department Dean Dashboard</h2>
    <hr>

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
    <div class="row mb-3">
    <div class="col-md-6">
        <input type="text" class="form-control" id="searchInput" placeholder="Search by item name or requestor">
    </div>
    <div class="col-md-3">
        <select id="statusFilter" class="form-control">
            <option value="">All Status</option>
            <option value="Approved">Approved</option>
            <option value="Rejected">Rejected</option>
            <option value="Pending">Pending</option>
        </select>
    </div>
    <div class="col-md-3">
        <button class="btn btn-primary w-100" onclick="applyFilters()">Apply Filters</button>
    </div>
</div>


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
                <td><?php echo $row['requestor_name']; ?></td> <!-- Display requestor name -->
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
            <td colspan="8" class="text-center">No pending requisitions found.</td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>

</div>

<div class="mt-4">
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">

            <!-- Previous Button -->
            <li class="page-item <?php echo ($currentPage <= 1) ? 'disabled' : ''; ?>">
                <a class="page-link" href="?page=<?php echo max(1, $currentPage - 1); ?>" aria-label="Previous">
                    <span aria-hidden="true">&laquo; Previous</span>
                </a>
            </li>

            <!-- Page Links -->
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?php echo ($i == $currentPage) ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>

            <!-- Next Button -->
            <li class="page-item <?php echo ($currentPage >= $totalPages) ? 'disabled' : ''; ?>">
                <a class="page-link" href="?page=<?php echo min($totalPages, $currentPage + 1); ?>" aria-label="Next">
                    <span aria-hidden="true">Next &raquo;</span>
                </a>
            </li>

        </ul>
    </nav>
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
        form.action = 'department_dean.php';

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



    function applyFilters() {
    const searchInput = document.getElementById('searchInput').value.toLowerCase();
    const statusFilter = document.getElementById('statusFilter').value;
    const rows = document.querySelectorAll('tbody tr');

    rows.forEach(row => {
        const itemName = row.children[2].textContent.toLowerCase();
        const requestor = row.children[1].textContent.toLowerCase();
        const status = row.children[6].textContent.toLowerCase();

        const matchesSearch = itemName.includes(searchInput) || requestor.includes(searchInput);
        const matchesStatus = !statusFilter || status.includes(statusFilter.toLowerCase());

        row.style.display = matchesSearch && matchesStatus ? '' : 'none';
    });
}

</script>
</body>
</html>
