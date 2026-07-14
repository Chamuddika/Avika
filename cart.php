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
        <title>Shopping Cart - Avika</title>
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
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none text-muted">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Cart</li>
                    </ol>
                </nav>

                <div class="row g-4">
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
                        <div class="col-lg-8">
                            <div class="bg-white p-4 p-md-5 rounded-4 shadow-sm">
                                <h3 class="mb-4 fw-bold">Shopping Cart (<?php echo $cart_num ?>)</h3>
                                <?php
                                $netTotal = 0;
                                $finalPrice = 0;
                                $total_weight = 0;
                                $w =   0;
                                $shipping = 0;
                                $total = 0 ;
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
                                    <div class="cart-item d-flex align-items-center pb-4 mb-4 border-bottom">
                                        <img src="<?php echo $product_data["img_url"] ?>" alt="Product" class="rounded-3 me-3" width="90" height="90" style="object-fit:cover;">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 fw-bold text-dark"><?php echo $product_data["name"] ?></h6>
                                            <small class="text-muted d-block mb-2">Category:
                                                <?php
                                                if ($product_data["category"] == "1") {
                                                ?>
                                                    Shampoo
                                                <?php
                                                } else if ($product_data["category"] == "2") {
                                                ?>
                                                    Conditioner
                                                <?php
                                                } else if ($product_data["category"] == "3") {
                                                ?>
                                                    Hair Oil
                                                <?php
                                                } else if ($product_data["category"] == "4") {
                                                ?>
                                                    Hair Serum
                                                <?php
                                                } else {
                                                ?>
                                                    Hair Mask
                                                <?php
                                                }
                                                ?>
                                            </small>
                                            <h6 class="mb-1 fw-bold text-dark">Quantity: <?php echo $cart_data["qty"] ?></h6>
                                        </div>
                                        <div class="text-end ms-3">
                                            <h6 class="fw-bold text-dark mb-1">RS. <?php echo $stock_data["price"] ?>.00</h6>
                                            <button class="btn btn-sm text-danger p-0" onclick="deleteFromCart(<?php echo $cart_data['id']; ?>);"><i class="bi bi-trash"></i>Remove</button>
                                        </div>
                                    </div>
                                <?php } ?>

                                <div class="mt-4">
                                    <a href="index.php" class="btn btn-outline-custom btn-sm">
                                        <i class="bi bi-arrow-left me-1"></i> Continue Shopping
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Order Summary -->
                        <div class="col-lg-4">
                            <div class="bg-white p-4 p-md-5 rounded-4 shadow-sm position-sticky" style="top: 100px;">
                                <h4 class="mb-4 fw-bold">Order Summary</h4>

                                <div class="d-flex justify-content-between mb-3">
                                    <span class="text-muted">Subtotal</span>
                                    <span class="fw-bold">RS. <?php echo  $total ?>.00</span>
                                </div>
                                <div class="d-flex justify-content-between mb-3">
                                    <span class="text-muted">Shipping</span>
                                    <?php if ($total <= 5000) { ?>
                                        <span class="fw-bold text-success">RS. <?php echo $shipping ?></span>
                                        <?php  } else { ?>Free Shipping</span>
                                    <?php  } ?>
                                </div>
                                <div class="d-flex justify-content-between mb-4">
                                    <span class="text-muted">Estimated Tax</span>
                                    <span class="fw-bold">RS. 0.00</span>
                                </div>

                                <div class="d-flex justify-content-between align-items-center border-top pt-4 mb-4">
                                    <h5 class="mb-0 fw-bold">Total</h5>
                                    <h4 class="mb-0 fw-bold" style="color: var(--primary);">RS. <?php echo $finalPrice ?></h4>
                                </div>

                                <a href="checkout.php" class="btn btn-primary-custom w-100 py-3 mb-2">
                                    Proceed to Checkout <i class="bi bi-arrow-right ms-2"></i>
                                </a>
                                <p class="text-center text-muted small mt-2"><i class="bi bi-shield-lock me-1"></i> Secure 256-bit SSL Encryption</p>
                            </div>
                        </div>
                    <?php } ?>


                </div>
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
    </body>

    </html>
<?php
} else {
    header(header: "Location: index.php");
}
?>