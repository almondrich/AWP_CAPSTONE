<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!-- Sidebar -->
<aside class="main-sidebar sidebar-dark-green elevation-4" style="background-color: #004d40; height: 100vh; position: fixed; top: 0; left: 0; overflow-y: auto;">
    <!-- Brand Logo -->
    <a href="../../index3.html" class="brand-link text-center">
        <img src="../../dist/img/123.png" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .9">
        <span class="brand-text font-weight-bold text-white">Santor Inventory</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="../../dist/img/admin.png" class="img-circle elevation-2 border border-white" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block text-white">Marissa G. Carbaruan</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2" data-widget="treeview" role="menu" data-accordion="false">
            <ul class="nav nav-pills nav-sidebar flex-column">
                <!-- Dashboard Link -->
                <li class="nav-item">
                    <a href="dashboard.php" class="nav-link <?php echo $current_page == 'dashboard.php' ? 'active' : ''; ?> text-white">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Library Files -->
                <li class="nav-item <?php echo in_array($current_page, ['category.php', 'unit.php', 'suppliers.php', 'items.php', 'security.php', 'employees.php']) ? 'menu-open' : ''; ?>">
                    <a href="#" class="nav-link text-white">
                        <i class="nav-icon fas fa-folder"></i>
                        <p>Library Files <i class="fas fa-angle-left right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="category.php" class="nav-link <?php echo $current_page == 'category.php' ? 'active' : ''; ?> text-white-50">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Category</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="unit.php" class="nav-link <?php echo $current_page == 'unit.php' ? 'active' : ''; ?> text-white-50">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Units</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="items.php" class="nav-link <?php echo $current_page == 'items.php' ? 'active' : ''; ?> text-white-50">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Items</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="security.php" class="nav-link <?php echo $current_page == 'security.php' ? 'active' : ''; ?> text-white-50">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Security</p>
                            </a>
                        </li>
                        <!-- <li class="nav-item">
                            <a href="employees.php" class="nav-link <?php echo $current_page == 'employees.php' ? 'active' : ''; ?> text-white-50">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Employees</p>
                            </a>
                        </li> -->
                    </ul>
                </li>

                <!-- Transactions -->
                <li class="nav-item <?php echo in_array($current_page, ['stockin.php', 'issuance.php', 'UserRequest.php']) ? 'menu-open' : ''; ?>">
                    <a href="#" class="nav-link text-white">
                        <i class="nav-icon fas fa-exchange-alt"></i>
                        <p>Transactions <i class="fas fa-angle-left right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="stockin.php" class="nav-link <?php echo $current_page == 'stockin.php' ? 'active' : ''; ?> text-white-50">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Stockin</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="UserRequest.php" class="nav-link <?php echo $current_page == 'UserRequest.php' ? 'active' : ''; ?> text-white-50">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Request</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Reports -->
                <li class="nav-item <?php echo in_array($current_page, ['reportStockin.php', 'reportIssuance.php']) ? 'menu-open' : ''; ?>">
                    <a href="#" class="nav-link text-white">
                        <i class="nav-icon fas fa-chart-bar"></i>
                        <p>Reports <i class="fas fa-angle-left right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="reportStockin.php" class="nav-link <?php echo $current_page == 'reportStockin.php' ? 'active' : ''; ?> text-white-50">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Stockin</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="reportIssuance.php" class="nav-link <?php echo $current_page == 'reportIssuance.php' ? 'active' : ''; ?> text-white-50">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Issuance</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Logout -->
                <li class="nav-item">
                    <a href="../Actions/logout.php" class="nav-link <?php echo $current_page == 'logout.php' ? 'active' : ''; ?> text-white">
                        <i class="fas fa-sign-out-alt"></i>
                        <p>Logout</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>

<!-- Styling for active state -->
<style>
    .nav-link.active {
        background-color: #2f5e93; /* Darker color for active state */
        color: black !important; /* Ensures text is readable */
    }

    .menu-open > .nav-link {
        background-color: #3a8f7b; /* Highlight parent link */
        color: black !important;
    }
</style>

<!-- Ensure jQuery and AdminLTE's JS are included -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<script src="../../dist/js/adminlte.min.js"></script>
