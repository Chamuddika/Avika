<?php

session_start();

require "connection.php";
if (isset($_SESSION["admin"])) {
    $pageTitle = "Dashboard";

    $product_rs = Database::search("SELECT * FROM `product`");
    $product_num = $product_rs->num_rows;
    $order_rs = Database::search("SELECT * FROM `order_data` ");
    $order_num = $order_rs->num_rows;
    $users_rs = Database::search("SELECT * FROM `users`");
    $users_num = $users_rs->num_rows;
    $revenue_rs = Database::search(" SELECT IFNULL(SUM(total),0) AS revenue FROM order_data WHERE  YEAR(created_at) = YEAR(CURDATE()) AND MONTH(created_at) = MONTH(CURDATE())");
    $revenue_data = $revenue_rs->fetch_assoc();
    $resent_rs = Database::search("SELECT * FROM `order_data` ORDER BY `created_at` DESC LIMIT 5 ");
    $resent_num = $resent_rs->num_rows;
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin Dashboard - Avika</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
        <link rel="stylesheet" href="./css/style.css">
        <link rel="icon" href="resources/logo.png">
    </head>

    <body>

        <?php include './includes/admin-sidebar.php'; ?>
        <?php include './includes/admin-topbar.php'; ?>

        <!-- Stats Cards -->
        <div class="row g-4 mb-5">
            <div class="col-xl-3 col-md-6">
                <div class="stat-card card-primary d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small mb-1">Total Products</p>
                        <h3 class="fw-bold mb-0"><?php echo $product_num; ?></h3>
                    </div>
                    <div class="icon-circle bg-primary-light">
                        <i class="bi bi-box-seam"></i>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card card-warning d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small mb-1">Total Orders</p>
                        <h3 class="fw-bold mb-0"><?php echo $order_num; ?></h3>
                    </div>
                    <div class="icon-circle bg-warning-light">
                        <i class="bi bi-bag-check"></i>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card card-info d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small mb-1">Registered Users</p>
                        <h3 class="fw-bold mb-0"><?php echo $users_num; ?></h3>
                    </div>
                    <div class="icon-circle bg-info-light">
                        <i class="bi bi-people"></i>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card card-danger d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small mb-1">Monthly Revenue</p>
                        <h4 class="fw-bold mb-0"><?php echo "RS. " . number_format($revenue_data["revenue"], 2); ?></h4>
                    </div>
                    <div class="icon-circle bg-danger-light">
                        <i class="bi bi-cash"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Orders Table -->
        <div class="admin-table">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0">Recent Orders</h5>
                <a href="manage-orders.php" class="btn btn-sm btn-outline-custom">View All</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        for ($x = 0; $x < $resent_num; $x++) {
                            $resent_data = $resent_rs->fetch_assoc();
                            $year = date("M d, Y", strtotime($resent_data["created_at"]));
                            $users_rs = Database::search("SELECT * FROM `users` WHERE `id` = '" .  $resent_data["users_id"] . "'");
                            $users_data = $users_rs->fetch_assoc();
                            $order_product_rs = Database::search("SELECT * FROM `order_product` WHERE `order_data_id` = '" .  $resent_data["id"] . "'");
                            $order_product_num = $order_product_rs->num_rows;
                        ?>
                            <tr>
                                <td>#AOID-<?php echo $resent_data["order_id"] ?></td>
                                <td><?php echo $users_data["name"] ?></td>
                                <td><?php echo $resent_data["total"] ?></td>
                                <td><?php

                                    $status = $resent_data["status"];

                                    switch ($status) {

                                        case 1:
                                            echo '<span class="badge bg-warning text-dark">Pending</span>';
                                            break;

                                        case 2:
                                            echo '<span class="badge bg-info">Processing</span>';
                                            break;

                                        case 3:
                                            echo '<span class="badge bg-primary">Shipped</span>';
                                            break;

                                        case 4:
                                            echo '<span class="badge bg-success">Delivered</span>';
                                            break;

                                        case 5:
                                            echo '<span class="badge bg-danger">Cancelled</span>';
                                            break;
                                    }

                                    ?></td>
                                <td><?php echo $year ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php include './includes/admin-footer.php'; ?>
        <script src="js/script.js"></script>
        <script src="js/main.js"></script>
    </body>

    </html>
<?php

} else {
    header("Location: admin-login.php");
}

?>