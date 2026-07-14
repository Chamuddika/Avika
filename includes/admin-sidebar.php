<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<!-- Admin Sidebar -->
<div class="admin-sidebar" id="adminSidebar">

    <a class="navbar-brand" href="admin-dashboard.php">
        <img src="resources/full-logo.png" alt="Avika Logo" width="150">
    </a>

    <nav class="sidebar-nav mt-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?php echo $currentPage == 'admin-dashboard.php' ? 'active' : ''; ?>" href="admin-dashboard.php">
                    <i class="bi bi-grid-1x2-fill"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $currentPage == 'manage-product.php' ? 'active' : ''; ?>" href="manage-product.php">
                    <i class="bi bi-box-seam-fill"></i> Products
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $currentPage == 'manage-users.php' ? 'active' : ''; ?>" href="manage-users.php">
                    <i class="bi bi-people-fill"></i> Users
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $currentPage == 'manage-orders.php' ? 'active' : ''; ?>" href="manage-orders.php">
                    <i class="bi bi-bag-check-fill"></i> Orders
                </a>
            </li>
            <li class="nav-item mt-4 border-top pt-3">
                <a class="nav-link text-danger" href="../admin-login.php">
                    <i class="bi bi-box-arrow-left"></i> Sign Out
                </a>
            </li>
        </ul>
    </nav>
</div>