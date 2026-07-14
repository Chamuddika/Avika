<?php

session_start();

require "connection.php";
if (isset($_SESSION["admin"])) {
    $pageTitle = "Order Management";

    $order_rs = Database::search("SELECT * FROM `order_data` ");
    $order_num = $order_rs->num_rows;
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Orders - Avika Admin</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
        <link rel="stylesheet" href="./css/style.css">
        <link rel="icon" href="resources/logo.png">
    </head>

    <body>

        <?php include './includes/admin-sidebar.php'; ?>
        <?php include './includes/admin-topbar.php'; ?>

        <div class="admin-table">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                <h5 class="fw-bold mb-0">All Orders (<?php echo  $order_num ?>)</h5>
                <select id="orderStatusFilter" class="form-select" style="max-width:200px;" onchange="loadOrders(this.value)">
                    <option value="">All Orders</option>
                    <option value="1">Pending</option>
                    <option value="2">Processing</option>
                    <option value="3">Shipped</option>
                    <option value="4">Delivered</option>
                </select>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Items</th>
                            <th>Total</th>
                            <th>Date</th>
                            <th>Payment</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="orderTableBody">
                        <?php
                        for ($x = 0; $x < $order_num; $x++) {
                            $order_data = $order_rs->fetch_assoc();
                            $year = date("M d, Y", strtotime($order_data["created_at"]));
                            $users_rs = Database::search("SELECT * FROM `users` WHERE `id` = '" . $order_data["users_id"] . "'");
                            $users_data = $users_rs->fetch_assoc();
                            $order_product_rs = Database::search("SELECT * FROM `order_product` WHERE `order_data_id` = '" . $order_data["id"] . "'");
                            $order_product_num = $order_product_rs->num_rows;
                        ?>
                            <tr>
                                <td class="fw-bold text-dark">#AOID-<?php echo $order_data["order_id"] ?></td>
                                <td><?php echo $users_data["name"] ?></td>
                                <td><?php echo $order_product_num ?> Items</td>
                                <td>RS.<?php echo number_format($order_data["total"], 2) ?></td>
                                <td><?php echo $year ?></td>
                                <td><?php

                                    $status = $order_data["status"];

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
                                <td>
                                    <select class="form-select form-select-sm order-status"
                                        data-id="<?php echo $order_data["id"]; ?>"
                                        style="width:150px;"
                                        onchange="updateOrderStatus(<?php echo $order_data['id']; ?>, this.value)">

                                        <option value="1" <?php if ($order_data["status"] == 1) echo "selected"; ?>>
                                            Pending
                                        </option>

                                        <option value="2" <?php if ($order_data["status"] == 2) echo "selected"; ?>>
                                            Processing
                                        </option>

                                        <option value="3" <?php if ($order_data["status"] == 3) echo "selected"; ?>>
                                            Shipped
                                        </option>

                                        <option value="4" <?php if ($order_data["status"] == 4) echo "selected"; ?>>
                                            Delivered
                                        </option>
                                    </select>
                                </td>
                                <td>
                                    <a href="order-details.php?id=<?php echo $order_data['id']; ?>" class="action-btn btn-view" title="View Details"><i class="bi bi-eye-fill"></i></a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php include './includes/admin-footer.php'; ?>
    </body>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/script.js"></script>

    </html>
<?php
} else {
    header("Location: admin-login.php");
}
?>