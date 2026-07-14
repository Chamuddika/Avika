<?php
session_start();
require "connection.php";
if (isset($_SESSION["u"])) {
    $email = $_SESSION["u"]["email"];
    $id = $_SESSION["u"]["id"];
    $details_rs = Database::search(q: "SELECT * FROM `users` WHERE `email`='$email'");
    $details_data = $details_rs->fetch_assoc();
    $year = date("Y", strtotime($details_data["created_at"]));
    $firstChar = strtoupper(
        mb_substr($details_data["name"], 0, 1, "UTF-8")
    );
    $nameParts = explode(" ", $details_data["name"]);
?>
    <!DOCTYPE html>
    <html lang="en" class="no-js">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>My Profile - Avika</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
        <link rel="stylesheet" href="css/style.css">
        <link rel="icon" href="resources/logo.png">
    </head>

    <body class="bg-cream">

        <?php include 'includes/navbar.php'; ?>

        <section class="section-padding">
            <div class="container">
                <div class="row g-4">

                    <!-- Sidebar Navigation -->
                    <div class="col-lg-3 reveal">
                        <div class="profile-sidebar bg-white p-4 rounded-4 shadow-sm">
                            <div class="text-center mb-4">
                                <span class="rounded-circle d-inline-flex align-items-center justify-content-center text-white fw-bold me-3" style=" width:70px; height:70px; background:#6c757d; font-size:50px; "><?php echo $firstChar ?></span>
                                <h5 class="mb-0"><?php echo $details_data["name"] ?></h5>
                                <small class="text-muted">Member since <?php echo $year ?></small>
                            </div>
                            <div class="nav flex-column nav-pills profile-nav" id="profileNav" role="tablist">
                                <button class="nav-link active text-start mb-2" data-bs-toggle="pill" data-bs-target="#personal-info" type="button">
                                    <i class="bi bi-person me-2"></i> Personal Details
                                </button>
                                <button class="nav-link text-start mb-2" data-bs-toggle="pill" data-bs-target="#order-history" type="button">
                                    <i class="bi bi-bag-check me-2"></i> Order History
                                </button>
                                <button class="nav-link text-start mb-2" data-bs-toggle="pill" data-bs-target="#addresses" type="button">
                                    <i class="bi bi-geo-alt me-2"></i> My Addresses
                                </button>
                                <button class="nav-link text-start text-danger" onclick="signout();">
                                    <i class="bi bi-box-arrow-left me-2"></i> Sign Out
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-9 reveal reveal-delay-1">
                        <div class="tab-content" id="profileTabContent">

                            <!-- Personal Details Tab -->
                            <div class="tab-pane fade show active" id="personal-info" role="tabpanel">
                                <div class="bg-white p-4 p-md-5 rounded-4 shadow-sm">
                                    <h3 class="mb-4">Personal Details</h3>
                                    <form id="userDetails">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label small fw-bold">First Name</label>
                                                <input type="text" class="form-control" name="first_name" value="<?php echo $nameParts[0] ?>">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label small fw-bold">Last Name</label>
                                                <input type="text" class="form-control" name="last_name" value="<?php echo $nameParts[1] ?? "" ?>">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label small fw-bold">Email Address</label>
                                                <input type="email" class="form-control" disabled value="<?php echo $details_data["email"] ?>">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label small fw-bold">Phone Number</label>
                                                <input type="tel" class="form-control" name="mobile" value="<?php echo $details_data["mobile"] ?>">
                                            </div>
                                            <div class="col-12 mt-4">
                                                <button type="submit" class="btn btn-primary-custom" id="saveChange">Save Changes</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Order History Tab -->
                            <div class="tab-pane fade" id="order-history" role="tabpanel">
                                <div class="bg-white p-4 p-md-5 rounded-4 shadow-sm">
                                    <h3 class="mb-4">Order History</h3>

                                    <?php
                                    $order_rs = Database::search("SELECT * FROM `order_data` WHERE `users_id` = '" . $id . "'");
                                    $order_num = $order_rs->num_rows;
                                    for ($i = 0; $i < $order_num; $i++) {
                                        $order_data = $order_rs->fetch_assoc();
                                        $new_date = date("M d, Y", strtotime($order_data["created_at"]));
                                        $oproduct_rs = Database::search("SELECT * FROM `order_product` WHERE `order_data_id` = '" . $order_data["id"] . "'");
                                        $oproduct_num = $oproduct_rs->num_rows;

                                    ?>
                                        <div class="order-card border rounded-3 mb-4 overflow-hidden">
                                            <div class="order-header bg-cream p-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                                                <div>
                                                    <span class="fw-bold text-dark">Order #AOID-<?php echo $order_data["order_id"] ?></span>
                                                    <small class="text-muted d-block">Placed on <?php echo $new_date ?></small>
                                                </div>
                                                <div class="text-end">
                                                    <?php
                                                    $status = $order_data["status"];

                                                    switch ($status) {

                                                        case 1:
                                                            echo '<span class="badge bg-warning text-dark mb-1">Pending</span>';
                                                            break;

                                                        case 2:
                                                            echo '<span class="badge bg-info mb-1">Processing</span>';
                                                            break;

                                                        case 3:
                                                            echo '<span class="badge bg-primary mb-1">Shipped</span>';
                                                            break;

                                                        case 4:
                                                            echo '<span class="badge bg-success mb-1">Delivered</span>';
                                                            break;

                                                        case 5:
                                                            echo '<span class="badge bg-danger mb-1">Cancelled</span>';
                                                            break;
                                                    }

                                                    ?>

                                                    <span class="fw-bold d-block text-dark">RS. <?php echo $order_data["total"] ?>.00</span>
                                                </div>
                                            </div>
                                            <div class="p-3">
                                                <?php for ($y = 0; $y < $oproduct_num; $y++) {
                                                    $oproduct_data = $oproduct_rs->fetch_assoc();
                                                    $stock_rs = Database::search("SELECT * FROM `stock` WHERE `id` = '" . $oproduct_data["stock_id"] . "'");
                                                    $stock_data = $stock_rs->fetch_assoc();
                                                    $product_rs = Database::search("SELECT * FROM `product` WHERE `id` = '" . $stock_data["product_id"] . "'");
                                                    $product_data = $product_rs->fetch_assoc();
                                                ?>
                                                    <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                                                        <img src="<?php echo $product_data["img_url"] ?>" alt="Product" class="rounded me-3" width="60" height="60" style="object-fit:cover;">
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-0"><?php echo $product_data["name"] ?></h6>
                                                            <small class="text-muted">Qty: <?php echo $oproduct_data["qty"] ?></small>
                                                        </div>
                                                        <button class="btn btn-sm btn-outline-custom" onclick="addFeedback(<?php echo $product_data['id']; ?>);" data-product="Argan Shine Shampoo">
                                                            <i class="bi bi-star me-1"></i>Write Review
                                                        </button>
                                                    </div>
                                                <?php } ?>
                                                <div class="text-end mt-3">
                                                    <button class="btn btn-sm btn-primary-custom" onclick="invoice('<?php echo $order_data['order_id']; ?>');">
                                                        <i class="bi bi-receipt me-1"></i>View Invoice
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>

                                </div>
                            </div>

                            <!-- Addresses Tab -->
                            <div class="tab-pane fade" id="addresses" role="tabpanel">
                                <div class="bg-white p-4 p-md-5 rounded-4 shadow-sm">
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <h3 class="mb-0">My Addresses</h3>
                                        <button class="btn btn-primary-custom btn-sm" data-bs-toggle="modal" data-bs-target="#addressModal">
                                            <i class="bi bi-plus me-1"></i>Add New
                                        </button>
                                    </div>

                                    <div class="row g-4">
                                        <?php
                                        $address_rs = Database::search("SELECT * FROM `address` WHERE `users_id` = '" . $id . "'");
                                        $address_num = $address_rs->num_rows;
                                        for ($x = 0; $x < $address_num; $x++) {
                                            $address_data = $address_rs->fetch_assoc();
                                        ?>

                                            <div class="col-md-6">
                                                <div class="address-card border rounded-3 p-4 h-100 position-relative">
                                                    <?php if ($address_data["is_default"]) { ?>
                                                        <span class="badge bg-success position-absolute top-0 end-0 m-3">Default</span>
                                                    <?php } ?>
                                                    <h6 class="fw-bold mb-2"><?php echo $address_data["title"] ?></h6>
                                                    <p class="text-muted small mb-1"><?php echo $details_data["name"] ?></p>
                                                    <p class="text-muted small mb-1"><?php echo $address_data["line_one"] ?></p>
                                                    <p class="text-muted small mb-1"><?php echo $address_data["line_two"] ?></p>
                                                    <p class="text-muted small mb-1">City: <?php echo $address_data["city"] ?></p>
                                                    <p class="text-muted small mb-3">Phone: <?php echo $details_data["mobile"] ?></p>
                                                    <div class="d-flex gap-2">
                                                        <a class="btn text-decoration-none small fw-bold" style="color:var(--primary);" onclick="editAddress(<?php echo $address_data['id']; ?>)">Edit</a>
                                                        <a class=" btn text-decoration-none small fw-bold text-danger" onclick="deleteAddress(<?php echo $address_data['id']; ?>)">Delete</a>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Review Modal -->
        <div class="modal fade" id="reviewModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4 overflow-hidden">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold" id="reviewModalLabel">Write a Review</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body pt-0">
                        <p class="text-muted small mb-3">Reviewing: <strong id="reviewProductName">Product Name</strong></p>
                        <form id="modalReviewForm">
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Your Rating *</label>
                                <div class="modal-star-rating">
                                    <i class="bi bi-star" data-rating="1"></i>
                                    <i class="bi bi-star" data-rating="2"></i>
                                    <i class="bi bi-star" data-rating="3"></i>
                                    <i class="bi bi-star" data-rating="4"></i>
                                    <i class="bi bi-star" data-rating="5"></i>
                                </div>
                                <input type="hidden" id="modalRatingValue" name="rating" value="0" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Your Review </label>
                                <textarea name="review" class="form-control" rows="4" placeholder="How was your experience with this product?" style="border-radius:10px;"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary-custom w-100">Submit Review</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Add Address Modal -->
        <div class="modal fade" id="addressModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold" id="addressModalTitle">Add New Address</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body pt-0">
                        <form id="addressForm">
                            <input type="hidden" name="address_id" id="address_id">
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Address Title</label>
                                <input type="text" class="form-control" placeholder="e.g. Home, Office" name="title" id="title">
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Address Line One</label>
                                <input type="text" class="form-control" placeholder="Apartment, suite, unit etc." name="line_one" id="line_one">
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Address Line Two</label>
                                <input type="text" class="form-control" placeholder="Apartment, suite, unit etc." name="line_two" id="line_two">
                            </div>
                            <div class="row g-3 mb-3">
                                <div class="mb-3 col-6">
                                    <label class="form-label small fw-bold">City</label>
                                    <input type="text" class="form-control" placeholder="colombo" name="city" id="city">
                                </div>

                                <div class="mb-3 col-6">
                                    <label class="form-label small fw-bold">Postal Code</label>
                                    <input type="text" class="form-control" placeholder="12345" name="postal_code" id="postal_code">
                                </div>
                            </div>
                            <div class="form-check mb-4 ">
                                <input class="form-check-input" type="checkbox" id="defaultAddress" name="default">
                                <label class="form-check-label small" for="defaultAddress">Set as default address</label>
                            </div>

                            <button type="submit" class="btn btn-primary-custom w-100" data-bs-dismiss="modal" id="saveAddress">Save Address</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php include 'includes/footer.php'; ?>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        <script src="./js/main.js"></script>
        <script src="./js/script.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </body>

    </html>
<?php
} else {
    header("Location: login.php");
}
?>