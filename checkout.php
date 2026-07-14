<?php
require "connection.php";
session_start();
if (isset($_SESSION["u"])) {
    $email = $_SESSION["u"]["email"];
    $uid = $_SESSION["u"]["id"];
?>
    <!DOCTYPE html>
    <html lang="en" class="no-js">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Checkout - Avika</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
        <link rel="stylesheet" href="css/style.css">
        <link rel="icon" href="resources/logo.png">
    </head>

    <body class="bg-cream">

        <?php include 'includes/navbar.php'; ?>

        <div class="py-5">
            <div class="container">
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="cart.php" class="text-decoration-none text-muted">Cart</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Checkout</li>
                    </ol>
                </nav>
                <?php
                $cart_rs = Database::search("SELECT * FROM `cart` WHERE `users_id`='" . $uid . "'");
                $cart_num = $cart_rs->num_rows;
                if ($cart_num == 0) {
                ?>
                    <div class="col-12 mt-5">
                        <div class="row">
                            <div class="col-12 emptyCart"></div>
                            <div class="col-12 text-center mb-2">
                                <label class="form-label fs-1 fw-bold">
                                    You have no items in your Cart yet.
                                </label>
                            </div>
                            <div class="offset-lg-4 col-12 col-lg-4 mb-4 d-grid">
                                <a href="index.php" class="btn border fs-3 fw-bold" style="color: rgba(193, 212, 182, 0.95); border: rgba(193, 212, 182, 0.95);"><i class="bi bi-bag-plus fs-3"></i>
                                    Start Shopping
                                </a>
                            </div>
                        </div>
                    </div>
                <?php } else { ?>
                    <form id="checkoutForm">
                        <div class="row g-4">
                            <div class="col-lg-8">

                                <div class="bg-white p-4 p-md-5 rounded-4 shadow-sm mb-4">
                                    <h4 class="mb-4 fw-bold"><i class="bi bi-geo-alt-fill me-2" style="color:var(--primary);"></i>Shipping Details</h4>
                                    <div class="row g-3">
                                        <div class="form-check mb-2">
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                id="useDefaultAddress">

                                            <label class="form-check-label fw-bold" for="useDefaultAddress">
                                                Use My Default Address
                                            </label>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold">First Name</label>
                                            <input type="text" class="form-control" id="first_name" name="first_name" placeholder="" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold">Last Name</label>
                                            <input type="text" class="form-control" id="last_name" name="last_name" required >
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label small fw-bold">Email Address</label>
                                            <input type="email" class="form-control" id="email" name="email" required>
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label small fw-bold">Mobile</label>
                                            <input type="tel" class="form-control" id="mobile" name="mobile" required>
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label small fw-bold">Address Line one</label>
                                            <input type="text" class="form-control" placeholder="House number and street name" id="address1" name="address1" required>
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label small fw-bold">Address Line two</label>
                                            <input type="text" class="form-control" placeholder="street name" id="address2" name="address2" required>
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label small fw-bold">City</label>
                                            <input type="text" class="form-control" id="city" name="city" required>
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label small fw-bold">Postal Code</label>
                                            <input type="text" class="form-control" id="postal_code" name="postal_code" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="bg-white p-4 p-md-5 rounded-4 shadow-sm position-sticky" style="top: 100px;">
                                    <h4 class="mb-4 fw-bold">Your Order</h4>
                                    <?php
                                    $netTotal = 0;
                                    $finalPrice = 0;
                                    $total_weight = 0;
                                    $w =   0;
                                    $shipping = 0;
                                    $total = 0;
                                    for ($x = 0; $x < $cart_num; $x++) {
                                        $cart_data = $cart_rs->fetch_assoc();

                                        $stock_rs = Database::search("SELECT * FROM `stock` WHERE `id`='" . $cart_data['stock_id'] . "'");
                                        $stock_data = $stock_rs->fetch_assoc();

                                        $product_rs = Database::search("SELECT * FROM `product` WHERE `id`='" . $stock_data['product_id'] . "'");
                                        $product_data = $product_rs->fetch_assoc();

                                        $netTotal = $netTotal + ($stock_data["price"] * $cart_data["qty"]);

                                        $total += $cart_data["qty"] * $stock_data["price"];

                                        $w = $w + (($stock_data["weight"] / 1000) * $cart_data["qty"]);
                                        $fw = ceil(num: $w);

                                        if ($total <= 5000) {
                                            if ($fw <= 1) {
                                                $shipping = $fw * 350;
                                            } else {
                                                $pwe = $fw - 1;
                                                $fwe = ceil(num: $pwe);
                                                $shipping = ($fwe * 180) + 350;
                                            }
                                        }

                                        $finalPrice =  $shipping + $netTotal;

                                    ?>
                                        <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                                            <div class="d-flex align-items-center">
                                                <img src="<?php echo $product_data["img_url"] ?>" width="40" height="40" class="rounded me-2" style="object-fit:cover;">
                                                <div>
                                                    <small class="fw-bold d-block text-dark"><?php echo $product_data["name"] ?></small>
                                                    <small class="text-muted">Qty: <?php echo $cart_data["qty"] ?></small>
                                                </div>
                                            </div>
                                            <span class="small fw-bold">RS. <?php echo $stock_data["price"] ?>.00</span>
                                        </div>
                                    <?php } ?>

                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">Subtotal</span>
                                        <span>RS. <?php echo  $total ?>.00</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-3">
                                        <span class="text-muted">Shipping</span>
                                        <?php if ($total <= 5000) { ?>
                                            <span class="fw-bold text-success">RS. <?php echo $shipping ?></span>
                                            <?php  } else { ?>Free Shipping</span>
                                        <?php  } ?>
                                    </div>

                                    <div class="d-flex justify-content-between border-top pt-3 mb-4">
                                        <h5 class="mb-0 fw-bold">Total</h5>
                                        <h5 class="mb-0 fw-bold" style="color: var(--primary);">RS. <?php echo $finalPrice ?></h5>
                                    </div>

                                    <button type="submit" class="btn btn-primary-custom w-100 py-3" id="pbtn">
                                        <i class="bi bi-lock-fill me-2"></i>Place Order
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                <?php } ?>
            </div>
        </div>

        <?php include 'includes/footer.php'; ?>

        <script>
            document.documentElement.classList.remove('no-js');
        </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="./js/main.js"></script>
        <script src="./js/script.js"></script>
        <script type="text/javascript" src="https://www.payhere.lk/lib/payhere.js"></script>
    </body>

    </html>
<?php
} else {
    header(header: "Location: index.php");
}
?>