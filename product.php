<?php
session_start();
require "connection.php";
if (isset($_GET["id"])) {
    $pid = $_GET["id"];
    $product_rs = Database::search("SELECT * FROM `product` WHERE `id` = '" . $pid . "'");
    $product_num = $product_rs->num_rows;
    $product_data = $product_rs->fetch_assoc();
    $stock_rs = Database::search("SELECT * FROM `stock` WHERE product_id = '" . $product_data["id"] . "' ");
    $stock_data = $stock_rs->fetch_assoc();
    if ($product_num == 1) {
?>
        <!DOCTYPE html>
        <html lang="en" class="no-js">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Avika</title>
            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
            <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
            <link rel="stylesheet" href="css/style.css">
            <link rel="icon" href="resources/logo.png">
        </head>

        <body class="bg-cream">

            <?php include 'includes/navbar.php'; ?>

            <section class="py-4 bg-white border-bottom">
                <div class="container">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0" style="font-size: 0.9rem;">
                            <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none text-muted">Home</a></li>
                            <li class="breadcrumb-item"><a href="products.php" class="text-decoration-none text-muted">Products</a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo $product_data["name"] ?></li>
                        </ol>
                    </nav>
                </div>
            </section>

            <section class="section-padding">
                <div class="container">
                    <div class="row g-5">

                        <div class="col-lg-6 reveal">
                            <div class="product-gallery">
                                <div class="main-image mb-3 rounded-4 overflow-hidden shadow-sm" style="height: 450px; background-color: #fff;">
                                    <img id="mainProductImage" src="<?php echo $product_data["img_url"] ?>" alt="Product" class="w-100 h-100" style="object-fit: cover;">
                                </div>
                                <div class="d-flex gap-3 thumbnail-list">
                                    <div class="thumbnail active rounded-3 overflow-hidden" style="width: 100px; height: 100px; cursor: pointer; border: 2px solid var(--primary);">
                                        <img src="<?php echo $product_data["img_url"] ?>" alt="Thumb 1" class="w-100 h-100" style="object-fit: cover;">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 reveal reveal-delay-1">
                            <div class="product-details">
                                <?php if ($stock_data["qty"] != 0) { ?>
                                    <span class="badge bg-success mb-3" style="font-size: 0.8rem;">In Stock</span>
                                <?php } else { ?>
                                    <span class="badge bg-danger mb-3" style="font-size: 0.8rem;">Out of Stock</span>
                                <?php } ?>
                                <h1 class="fw-bold mb-3" style="font-size: 2.5rem;"><?php echo $product_data["name"] ?></h1>

                                <?php
                                $review_rs = Database::search(" SELECT COUNT(*) AS review_count, AVG(rating) AS avg_rating FROM reviews WHERE product_id = '" . $product_data["id"] . "'");
                                $review_data = $review_rs->fetch_assoc();

                                $review_count = $review_data["review_count"];
                                $avg_rating = round($review_data["avg_rating"], 1);

                                if ($avg_rating == null) {
                                    $avg_rating = 0;
                                }
                                ?>
                                <div class="d-flex align-items-center mb-3">
                                    <div class="text-warning me-2">

                                        <?php
                                        $fullStars = floor($avg_rating);
                                        $halfStar = ($avg_rating - $fullStars) >= 0.5;
                                        $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);

                                        for ($i = 0; $i < $fullStars; $i++) {
                                            echo '<i class="bi bi-star-fill"></i>';
                                        }

                                        if ($halfStar) {
                                            echo '<i class="bi bi-star-half"></i>';
                                        }

                                        for ($i = 0; $i < $emptyStars; $i++) {
                                            echo '<i class="bi bi-star"></i>';
                                        }
                                        ?>
                                    </div>
                                    <span class="text-muted">
                                        (<?php echo $avg_rating; ?> / 5 -
                                        <?php echo $review_count; ?> Reviews)
                                    </span>

                                </div>

                                <div class="mb-4">
                                    <span class="display-6 fw-bold" style="color: var(--primary);">RS. <?php echo $stock_data["price"]; ?>.00</span>
                                    <span class="h5 text-muted text-decoration-line-through ms-2">RS. <?php echo (($stock_data["price"] * 12 / 100) + $stock_data["price"]) ?>.00</span>
                                    <span class="badge bg-danger ms-2">-12%</span>
                                </div>

                                <p class="text-muted mb-4" style="line-height: 1.8;">
                                    <?php echo $product_data["description"] ?>
                                </p>

                                <div class="d-flex flex-column flex-md-row align-items-md-center gap-3 mb-4">
                                    <div class="input-group" style="max-width: 130px; height: 50px;">
                                        <button class="btn btn-outline-secondary" type="button" onclick='qty_dec();'>-</button>
                                        <input type="text" class="form-control text-center bg-white" id="qty_input" value="1" readonly onkeyup='check_value(<?php echo $stock_data["qty"]; ?>);'>
                                        <button class="btn btn-outline-secondary" type="button" onclick='qty_inc(<?php echo $stock_data["qty"]; ?>);'>+</button>
                                    </div>

                                    <button class="btn btn-primary-custom flex-grow-1 py-3" onclick="addCart(<?php echo $stock_data['id'] ?>);">
                                        <i class="bi bi-bag-plus me-2"></i>Add to Cart
                                    </button>
                                    <button class="btn btn-outline-custom flex-grow-1 py-3" id="buyNowBtn" data-product-id="<?php echo $stock_data['id']; ?>">
                                        <i class="bi bi-lightning-fill me-2"></i>Buy Now
                                    </button>
                                </div>

                                <!-- Meta Info -->
                                <div class="border-top pt-4 mt-4">
                                    <div class="row g-3 text-muted small">
                                        <div class="col-6">
                                            <i class="bi bi-truck me-2 text-success"></i> Free Shipping over RS. 5000
                                        </div>
                                        <div class="col-6">
                                            <i class="bi bi-arrow-repeat me-2 text-success"></i> 7-Day Returns
                                        </div>
                                        <div class="col-6">
                                            <i class="bi bi-shield-check me-2 text-success"></i> Secure Payment
                                        </div>
                                        <div class="col-6">
                                            <i class="bi bi-flower1 me-2 text-success"></i> 100% Natural
                                        </div>
                                    </div>
                                </div>

                                <!-- Categories -->
                                <div class="mt-4 small text-muted">
                                    <strong>Category:</strong> <a href="#" class="text-decoration-none" style="color: var(--primary);">
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
                                    </a>
                                    <strong class="ms-3">Tags:</strong> <a href="#" class="text-decoration-none" style="color: var(--primary);">Argan Oil</a>, <a href="#" class="text-decoration-none" style="color: var(--primary);">Organic</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Product Details Tabs & Reviews -->
                    <div class="row mt-5 reveal">
                        <div class="col-12">
                            <ul class="nav nav-tabs mb-4" id="productTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="desc-tab" data-bs-toggle="tab" data-bs-target="#description" type="button">Description</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="info-tab" data-bs-toggle="tab" data-bs-target="#information" type="button">Additional Info</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="how-to-use-tab" data-bs-toggle="tab" data-bs-target="#how_to_use" type="button">How to Use</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="review-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button">Reviews (<?php echo $review_count ?>)</button>
                                </li>
                            </ul>

                            <div class="tab-content bg-white p-4 p-md-5 rounded-4 shadow-sm" id="productTabsContent">
                                <!-- Description Tab -->
                                <div class="tab-pane fade show active" id="description" role="tabpanel">
                                    <h4 class="mb-3">Product Description</h4>
                                    <p class="text-muted"><?php echo nl2br(($product_data['description'])) ?></p>
                                </div>

                                <!-- Additional Info Tab -->
                                <div class="tab-pane fade" id="information" role="tabpanel">
                                    <h4 class="mb-4">Additional Information</h4>
                                    <table class="table table-striped">
                                        <tbody>
                                            <tr>
                                                <td class="fw-bold" style="width: 30%;">Ingredients</td>
                                                <td class="text-muted"><?php echo $product_data["ingredients"] ?></td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Hair Type</td>
                                                <td class="text-muted"><?php echo $product_data["hair_type"] ?></td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Volume</td>
                                                <td class="text-muted"><?php echo $stock_data["capacity"] ?>ml</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Cruelty-Free</td>
                                                <td class="text-muted">Yes, Leaping Bunny Certified</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Sulfate-Free</td>
                                                <td class="text-muted">Yes, 100% Free of SLS & SLES</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- How to use Tab -->
                                <div class="tab-pane fade" id="how_to_use" role="tabpanel">
                                    <h4 class="mb-4">Instruction</h4>
                                    <p class="text-muted"><?php echo $product_data["instruction"] ?></p>
                                    <video src="<?php echo $product_data["instruction_video_url"] ?>" muted autoplay width="1000" height="500"></video>
                                </div>

                                <!-- Reviews Tab -->
                                <div class="tab-pane fade" id="reviews" role="tabpanel">
                                    <div class="row g-5">
                                        <h4 class="mb-4">Customer Reviews</h4>
                                        <div class="col-12 overflow-auto " style="max-height: 300px; overflow-y: auto;">
                                            <?php

                                            $reviews_rs = Database::search("SELECT * FROM `reviews` WHERE `product_id` = '" . $product_data["id"] . "' ORDER BY `id` DESC ");
                                            while ($reviews_data = $reviews_rs->fetch_assoc()) {

                                                $user_rs = Database::search("SELECT * FROM `users` WHERE `id` = '" . $reviews_data["users_id"] . "' ");
                                                $user_data = $user_rs->fetch_assoc();

                                                $firstChar = strtoupper(
                                                    mb_substr($user_data["name"], 0, 1, "UTF-8")
                                                );

                                                date_default_timezone_set('Asia/Colombo');

                                                $reviewTime = strtotime($reviews_data["created_at"]);
                                                $currentTime = time();

                                                $seconds = $currentTime - $reviewTime;

                                                if ($seconds < 60) {
                                                    $timeAgo = $seconds . " seconds ago";
                                                } else if ($seconds < 3600) {
                                                    $minutes = floor($seconds / 60);
                                                    $timeAgo = $minutes . ($minutes == 1 ? " minute ago" : " minutes ago");
                                                } else if ($seconds < 86400) {
                                                    $hours = floor($seconds / 3600);
                                                    $timeAgo = $hours . ($hours == 1 ? " hour ago" : " hours ago");
                                                } else if ($seconds < 2592000) {
                                                    $days = floor($seconds / 86400);
                                                    $timeAgo = $days . ($days == 1 ? " day ago" : " days ago");
                                                } else if ($seconds < 31536000) {
                                                    $months = floor($seconds / 2592000);
                                                    $timeAgo = $months . ($months == 1 ? " month ago" : " months ago");
                                                } else {
                                                    $years = floor($seconds / 31536000);
                                                    $timeAgo = $years . ($years == 1 ? " year ago" : " years ago");
                                                }
                                            ?>
                                                <div class="d-flex mb-4 pb-4 border-bottom">
                                                    <span
                                                        class="rounded-circle d-inline-flex align-items-center justify-content-center text-white fw-bold me-3"
                                                        style=" width:50px; height:50px; background:#6c757d; font-size:20px; ">
                                                        <?php echo $firstChar; ?>
                                                    </span>

                                                    <div class="flex-grow-1">

                                                        <div class="d-flex justify-content-between">
                                                            <h6 class="mb-1">
                                                                <?php echo htmlspecialchars($user_data["name"]); ?>
                                                            </h6>
                                                            <small class="text-muted">
                                                                <?php echo $timeAgo; ?>
                                                            </small>
                                                        </div>

                                                        <div class="text-warning mb-2">
                                                            <?php
                                                            $rating = (int)$reviews_data["rating"];
                                                            for ($i = 1; $i <= 5; $i++) {
                                                                if ($i <= $rating) {
                                                                    echo '<i class="bi bi-star-fill"></i>';
                                                                } else {
                                                                    echo '<i class="bi bi-star"></i>';
                                                                }
                                                            }
                                                            ?>
                                                        </div>

                                                        <p class="text-muted mb-0 small">
                                                            <?php echo htmlspecialchars($reviews_data["review"]); ?>
                                                        </p>

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
                </div>
            </section>

            <?php include 'includes/footer.php'; ?>

            <script>
                document.documentElement.classList.remove('no-js');
            </script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
            <script src="./js/main.js"></script>
            <script src="./js/script.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script type="text/javascript" src="https://www.payhere.lk/lib/payhere.js"></script>
        </body>

        </html>

<?php
    } else {
        header("Location: 404.html");
    }
} else {
    header("Location: index.php");
}
?>