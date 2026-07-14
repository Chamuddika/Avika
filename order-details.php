<?php
session_start();
require "connection.php";
if (isset($_SESSION["admin"])) {
    $oid = $_GET["id"];
    $pageTitle = "Order Details";
    $subtotal = 0;
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Order - Avika Admin</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
        <link rel="stylesheet" href="./css/style.css">
        <link rel="icon" href="resources/logo.png">
        <style>
            @media print {
                body * {
                    visibility: hidden;
                }

                #printableArea,
                #printableArea * {
                    visibility: visible;
                }

                #printableArea {
                    position: absolute;
                    left: 0;
                    top: 0;
                    width: 100%;
                    padding: 20px;
                }

                .no-print {
                    display: none !important;
                }

                .admin-sidebar,
                .admin-topbar {
                    display: none !important;
                }

                .admin-main-content {
                    margin-left: 0 !important;
                }
            }
        </style>
    </head>

    <body>

        <?php include './includes/admin-sidebar.php'; ?>
        <?php include './includes/admin-topbar.php'; ?>
        <?php
        $order_rs = Database::search("SELECT * FROM `order_data` WHERE `id` = '" . $oid . "'");
        $order_data = $order_rs->fetch_assoc();
        $year = date("M d, Y  g:i A", strtotime($order_data["created_at"]));
        ?>
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4 no-print">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="admin-dashboard.php" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="manage-orders.php" class="text-decoration-none">Orders</a></li>
                    <li class="breadcrumb-item active" aria-current="page">#AOID-<?php echo  $order_data["order_id"] ?></li>
                </ol>
            </nav>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-custom btn-sm" onclick="window.print()">
                    <i class="bi bi-printer me-1"></i> Print
                </button>
            </div>
        </div>

        <div id="printableArea">
            <div class="admin-table mb-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h4 class="fw-bold mb-1">Order #AOID-<?php echo  $order_data["order_id"] ?></h4>
                        <p class="text-muted small mb-0">Placed on <?php echo $year; ?></p>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="admin-table mb-4">
                        <h5 class="fw-bold mb-4">Order Items</h5>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th class="text-center">Quantity</th>
                                        <th class="text-end">Unit Price</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $order_product_rs = Database::search("SELECT * FROM `order_product` WHERE `order_data_id` = '" . $oid . "'");
                                    $order_product_num = $order_product_rs->num_rows;
                                    for ($x = 0; $x < $order_product_num; $x++) {
                                        $order_product_data = $order_product_rs->fetch_assoc();
                                        $stock_rs = Database::search("SELECT * FROM `stock` WHERE `id` = '" . $order_product_data["stock_id"] . "'");
                                        $stock_data = $stock_rs->fetch_assoc();
                                        $product_rs = Database::search("SELECT * FROM `product` WHERE `id` = '" . $stock_data["product_id"] . "'");
                                        $product_data = $product_rs->fetch_assoc();

                                        
                                        $subtotal += $order_product_data["qty"] * $order_product_data["price"];
                                    ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="<?php echo  $product_data["img_url"] ?>" width="40" height="40" class="rounded me-3" style="object-fit:cover;">
                                                    <div>
                                                        <span class="fw-bold text-dark"><?php echo  $product_data["name"] ?></span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center align-middle"><?php echo $order_product_data["qty"] ?></td>
                                            <td class="text-end align-middle">RS. <?php echo number_format($order_product_data["price"], 2) ?></td>
                                            <td class="text-end align-middle fw-bold">RS. <?php echo number_format($order_product_data["qty"] * $order_product_data["price"], 2) ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="admin-table">
                        <div class="row justify-content-end">
                            <div class="col-md-6">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Subtotal</span>
                                    <span class="fw-bold">RS. <?php echo number_format($subtotal, 2) ?></span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Shipping (Standard)</span>
                                    <span class="fw-bold">RS. <?php echo number_format($order_data["shipping"], 2) ?></span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Tax</span>
                                    <span class="fw-bold">RS. 0.00</span>
                                </div>
                                <div class="d-flex justify-content-between border-top pt-3 mt-2">
                                    <h5 class="fw-bold mb-0">Total</h5>
                                    <h5 class="fw-bold mb-0" style="color: var(--primary);">RS. <?php echo number_format($order_data["total"], 2) ?></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php
                $users_rs = Database::search("SELECT * FROM `users` WHERE `id` = '" . $order_data["users_id"] . "'");
                $users_data = $users_rs->fetch_assoc();
                $firstChar = strtoupper(
                    mb_substr($users_data["name"], 0, 1, "UTF-8")
                );
                ?>
                <div class="col-lg-4">
                    <div class="admin-table mb-4">
                        <h5 class="fw-bold mb-3">Customer</h5>
                        <div class="d-flex align-items-center mb-3">
                            <span class="rounded-circle d-inline-flex align-items-center justify-content-center text-white fw-bold me-3" style="object-fit:cover; background:#6c757d; font-size:30px; width:45px; height:45px; "><?php echo $firstChar ?></span>
                            <div>
                                <h6 class="mb-0 fw-bold text-dark"><?php echo $users_data["name"] ?></h6>
                                <small class="text-muted">Registered User</small>
                            </div>
                        </div>
                        <ul class="list-unstyled mb-0 small">
                            <li class="d-flex align-items-start mb-2">
                                <i class="bi bi-envelope me-2 text-muted"></i>
                                <span><?php echo $users_data["email"] ?></span>
                            </li>
                            <li class="d-flex align-items-start">
                                <i class="bi bi-telephone me-2 text-muted"></i>
                                <span><?php echo $users_data["mobile"] ?></span>
                            </li>
                        </ul>
                    </div>

                    <?php
                    $address_rs = Database::search("SELECT * FROM `order_address` WHERE `order_data_id` = '" . $order_data["id"] . "'");
                    $address_data = $address_rs->fetch_assoc();

                    ?>
                    <div class="admin-table mb-4">
                        <h5 class="fw-bold mb-3">Shipping Address</h5>
                        <p class="small text-muted mb-0">
                            <?php echo $address_data["name"] ?><br>
                            <?php echo $address_data["line_one"] ?><br>
                            <?php echo $address_data["line_two"] ?><br>
                            <?php echo $address_data["city"] ?><br>
                            <?php echo $address_data["postal_code"] ?><br>
                            <?php echo $address_data["mobile"] ?>
                        </p>
                    </div>

                    <div class="admin-table">
                        <h5 class="fw-bold mb-3">Payment Details</h5>
                        <ul class="list-unstyled mb-0 small">
                            <li class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Status</span>
                                <span class="badge bg-success">Paid</span>
                            </li>
                            <li class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Method</span>
                                <span class="fw-bold text-dark">Debit Card</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <?php include './includes/admin-footer.php'; ?>
    </body>

    </html>
<?php
} else {
    header("Location: admin-login.php");
}
?>