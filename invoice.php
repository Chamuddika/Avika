<?php
require "connection.php";
session_start();
if (isset($_SESSION["u"])) {
    $email = $_SESSION["u"]["email"];
    $uid = $_SESSION["u"]["id"];

    $oid = $_GET["id"];
    $total = 0;
    $subTotal = 0;
    $delivery = 0;
    $invoice_rs = Database::search("SELECT * FROM `order_data` WHERE `order_id`='" . $oid . "'");
    $invoice_num = $invoice_rs->num_rows;
    $invoice_data = $invoice_rs->fetch_assoc();
    $new_date = date("M d, Y", strtotime($invoice_data["created_at"]));
    if ($invoice_num == 1) {
?>
        <!DOCTYPE html>
        <html lang="en" class="no-js">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Invoice - Avika </title>
            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
            <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
            <link rel="stylesheet" href="css/style.css">
            <link rel="icon" href="resources/logo.png">
            <style>
                @media print {
                    body * {
                        visibility: hidden;
                    }

                    #invoiceArea,
                    #invoiceArea * {
                        visibility: visible;
                    }

                    #invoiceArea {
                        position: absolute;
                        left: 0;
                        top: 0;
                        width: 100%;
                        box-shadow: none !important;
                    }

                    .no-print {
                        display: none !important;
                    }
                }

                .invoice-table th {
                    background-color: var(--cream);
                }
            </style>
        </head>

        <body class="bg-cream">

            <?php include 'includes/navbar.php'; ?>

            <div class="py-5">
                <div class="container">

                    <div class="d-flex justify-content-between align-items-center mb-4 no-print">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none text-muted">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Invoice</li>
                            </ol>
                        </nav>
                        <div>
                            <button class="btn btn-outline-custom btn-sm me-2" onclick="window.print()">
                                <i class="bi bi-printer me-1"></i> Print
                            </button>
                            <a href="profile.php" class="btn btn-primary-custom btn-sm">
                                <i class="bi bi-arrow-left me-1"></i> Back to Profile
                            </a>
                        </div>
                    </div>

                    <div id="invoiceArea" class="bg-white p-4 p-md-5 rounded-4 shadow-sm">

                        <div class="d-flex justify-content-between align-items-start flex-wrap mb-5">
                            <div>
                                <h2 style="font-family: 'Playfair Display', serif; color:var(--primary);"><i class="bi bi-leaf me-2"></i>Avika</h2>
                                <p class="text-muted small mb-0">200/A</p>
                                <p class="text-muted small">Inamaluwa,Dambulla</p>
                            </div>
                            <div class="text-md-end mt-3 mt-md-0">
                                <h3 class="fw-bold mb-1">INVOICE</h3>
                                <p class="mb-0 text-muted small">Order: <strong class="text-dark">#<?php echo $oid ?></strong></p>
                                <p class="mb-0 text-muted small">Date: <strong class="text-dark"><?php echo $new_date ?></strong></p>
                                <span class="badge bg-success mt-2">Paid</span>
                            </div>
                        </div>

                        <!-- Bill To -->
                        <div class="row mb-5">
                            <?php
                            $address_rs = Database::search("SELECT * FROM `order_address` WHERE `order_data_id`='" .  $invoice_data["id"] . "'");
                            $address_data = $address_rs->fetch_assoc();
                            ?>
                            <div class="col-md-6">
                                <h6 class="text-uppercase text-muted fw-bold mb-2" style="font-size: 0.85rem;">Bill To</h6>
                                <p class="fw-bold mb-1 text-dark"><?php echo $address_data["name"] ?></p>
                                <p class="text-muted small mb-0"><?php echo $address_data["line_one"] ?></p>
                                <p class="text-muted small mb-0"><?php echo $address_data["line_two"] ?></p>
                                <p class="text-muted small mb-0"><?php echo $address_data["mobile"] ?></p>
                            </div>
                            <div class="col-md-6 mt-3 mt-md-0">
                                <h6 class="text-uppercase text-muted fw-bold mb-2" style="font-size: 0.85rem;">Ship To</h6>
                                <p class="fw-bold mb-1 text-dark">Avika Hair Products</p>
                                <p class="text-muted small mb-0">200/A</p>
                                <p class="text-muted small mb-0">Inamaluwa,Dambulla</p>
                            </div>
                        </div>

                        <!-- Items Table -->
                        <div class="table-responsive mb-5">
                            <table class="table invoice-table">
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
                                    $iproduct_rs = Database::search("SELECT * FROM `order_product` WHERE `order_data_id`='" .  $invoice_data["id"] . "'");
                                    $invoice_num = $iproduct_rs->num_rows;
                                    for ($i = 0; $i < $invoice_num; $i++) {
                                        $iproduct_data = $iproduct_rs->fetch_assoc();
                                        $stock_rs = Database::search("SELECT * FROM `stock` WHERE `id`='" . $iproduct_data["stock_id"] . "'");
                                        $stock_data = $stock_rs->fetch_assoc();
                                        $stock_rs = Database::search("SELECT * FROM `stock` WHERE `id`='" . $iproduct_data["stock_id"] . "'");
                                        $stock_data = $stock_rs->fetch_assoc();
                                        $product_rs = Database::search("SELECT * FROM `product` WHERE `id`='" .  $stock_data["product_id"] . "'");
                                        $product_data = $product_rs->fetch_assoc();

                                        $total = $iproduct_data["price"] * $iproduct_data["qty"];
                                        $subTotal += $total
                                    ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="<?php echo $product_data["img_url"] ?>" width="40" height="40" class="rounded me-2" style="object-fit:cover;">
                                                    <span class="fw-bold text-dark"><?php echo $product_data["name"] ?></span>
                                                </div>
                                            </td>
                                            <td class="text-center"><?php echo  $iproduct_data["qty"] ?></td>
                                            <td class="text-end"><?php echo  $iproduct_data["price"] ?></td>
                                            <td class="text-end fw-bold"><?php echo  $total ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Totals -->
                        <div class="row justify-content-end">
                            <div class="col-md-5">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Subtotal (RS)</span>
                                    <span class="fw-bold"><?php echo  $subTotal ?></span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Shipping (RS)</span>
                                    <span class="fw-bold text-success"><?php echo  $invoice_data["shipping"] ?></span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Tax (RS)</span>
                                    <span class="fw-bold">0.00</span>
                                </div>
                                <div class="d-flex justify-content-between border-top pt-3 mt-2">
                                    <h5 class="fw-bold mb-0">Grand Total (RS)</h5>
                                    <h5 class="fw-bold mb-0" style="color: var(--primary);"><?php echo  $invoice_data["total"] ?></h5>
                                </div>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="text-center mt-5 pt-4 border-top">
                            <p class="mb-1 text-muted small">Thank you for shopping with Avika!</p>
                            <p class="mb-0 text-muted small">If you have any questions about this invoice, please contact <a href="mailto:support@avika.com" class="text-decoration-none" style="color:var(--primary);">support@avika.com</a></p>
                        </div>

                    </div>
                </div>
            </div>

            <?php include 'includes/footer.php'; ?>

            <script>
                document.documentElement.classList.remove('no-js');
            </script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
            <script src="js/main.js"></script>
        </body>

        </html>
<?php
    } else {
        header("Location: 404.html");
    }
} else {
    header(header: "Location: index.php");
}
?>