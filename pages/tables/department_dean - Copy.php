<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Department Dean Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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


    <div class="container mt-5">
        <!-- Dashboard Header -->
        <div class="dashboard-header">
            <h4>Department Dean Dashboard</h4>
        </div>

        <!-- Dashboard Content -->
        <div class="row">
            <!-- Pending Requisitions Card -->
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        Pending Requisitions
                        <span class="badge bg-danger">5</span>
                    </div>
                    <div class="card-body">
                        <p>View and approve pending requisitions submitted by staff.</p>
                        <a href="dean_view_request.php" class="btn btn-primary btn-sm">Manage</a>
                    </div>
                </div>
            </div>

            <!-- Approved Requisitions Card -->
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        Approved Requisitions
                        <span class="badge bg-success">15</span>
                    </div>
                    <div class="card-body">
                        <p>Review requisitions you have already approved.</p>
                        <a href="#" class="btn btn-success btn-sm">View</a>
                    </div>
                </div>
            </div>

            <!-- Disapproved Requisitions Card -->
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        Disapproved Requisitions
                        <span class="badge bg-warning text-dark">3</span>
                    </div>
                    <div class="card-body">
                        <p>Check requisitions that were not approved with remarks.</p>
                        <a href="#" class="btn btn-warning btn-sm">Review</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
