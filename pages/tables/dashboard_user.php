<?php
include '../Actions/connection.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch notifications for the logged-in user
$query = "SELECT notification_id, message, created_at, read_status 
          FROM notifications 
          WHERE user_id = ? 
          ORDER BY created_at DESC LIMIT 3" ;

$stmt = $con->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Notifications</title>
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
        <a class="navbar-brand d-flex align-items-center" href="dashboard_user.php">
            <img src="../../dist/img/sjcbaggao.png" alt="Logo" width="300" height="75">
            <span class="fs-5 fw-bold" style="color:white;"> | User Portal</span>
        </a>

        <!-- Toggle Button for Mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a id="navv" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'dashboard_user.php' ? 'active' : ''; ?>" href="dashboard_user.php">
                        <i data-feather="home"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a id="navv" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'requisition_form.php' ? 'active' : ''; ?>" href="requisition_form.php">
                        <i data-feather="edit"></i> Request
                    </a>
                </li>
                <li class="nav-item">
                    <a id="navv" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'user_dash.php' ? 'active' : ''; ?>" href="user_dash.php">
                        <i data-feather="list"></i> Request Status
                    </a>
                </li>
                <li class="nav-item">
                    <a id="navv" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'profile.php' ? 'active' : ''; ?>" href="profile.php">
                        <i data-feather="user"></i> Profile
                    </a>
                </li>
                <li class="nav-item">
                    <a id="navvv" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'logout.php' ? 'active' : ''; ?>" href="logout.php">
                        <i data-feather="log-out"></i> Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <div class="card bg-white text-white shadow rounded-3">
        <div class="card-header bg-dark text-white d-flex align-items-center border-0 rounded-top">
            <i data-feather="bell" class="me-2"></i>
            <h5 class="mb-0">Notifications</h5>
        </div>
        <div class="card-body">
            <?php if ($result->num_rows > 0): ?>
                <ul class="list-group list-group-flush">
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <li class="list-group-item bg-white text-dark border-0 d-flex justify-content-between align-items-start shadow-sm mb-2 p-3 rounded-3" data-id="<?= htmlspecialchars($row['notification_id']); ?>">
                            <div>
                                <i data-feather="info" class="me-2 text-primary"></i>
                                <?= htmlspecialchars($row['message']); ?>
                                <br>
                                <small class="text-muted"><?= date('F j, Y, g:i a', strtotime($row['created_at'])); ?></small>
                            </div>
                            <?php if ($row['read_status'] === 'Unread'): ?>
                                <span class="badge bg-warning text-dark align-self-center">Unread</span>
                            <?php endif; ?>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p class="text-muted text-center">No notifications found.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
<script>
$(document).on('click', '.list-group-item', function () {
    const notificationId = $(this).data('id');
    const $this = $(this);

    if (!notificationId) {
        Swal.fire('Error', 'Notification ID is missing.', 'error');
        return;
    }

    $.ajax({
        url: '../Actions/markNotificationRead.php',
        type: 'POST',
        data: { notification_id: notificationId },
        success: function (response) {
            try {
                const res = JSON.parse(response);
                if (res.success) {
                    Swal.fire('Success', res.message, 'success');
                    $this.find('.badge').remove();
                   
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            } catch (e) {
                console.error('Invalid response format:', response);
                Swal.fire('Error', 'Unexpected server response.', 'error');
            }
        },
        error: function () {
            Swal.fire('Error', 'Failed to mark notification as read.', 'error');
        }
    });
});
</script>

<script>
    feather.replace(); /* Render Feather Icons */
</script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
