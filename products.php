<?php
session_start();
require "connection.php";
?>
<!DOCTYPE html>
<html lang="en" class="no-js">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop Products - Avika</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="resources/logo.png">
</head>

<body class="bg-cream">

    <?php include 'includes/navbar.php'; ?>

    <!-- Page Header -->
    <section class="py-5 bg-white border-bottom" style="margin-top: 80px;">
        <div class="container text-center">
            <h1 class="display-5 fw-bold reveal">Our Collection</h1>
            <p class="text-muted lead reveal reveal-delay-1">Crafted from Avika's finest botanicals for every hair type</p>
            <nav aria-label="breadcrumb" class="d-flex justify-content-center reveal reveal-delay-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none text-muted">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Products</li>
                </ol>
            </nav>
        </div>
    </section>

    <section class="section-padding">
        <div class="container">
            <div class="row g-4">

                <div class="col-lg-3 d-none d-lg-block reveal">
                    <div class="bg-white p-4 rounded-4 shadow-sm position-sticky" style="top: 100px;">
                        <h5 class="fw-bold mb-4">Filters</h5>
                        <form>
                            <div class="mb-4">
                                <h6 class="fw-bold small text-uppercase text-muted mb-3">Category</h6>
                                <div class="form-check mb-2">
                                    <input class="form-check-input categoryCheck" type="checkbox" id="1" value="1">
                                    <label class="form-check-label small" for="1">Shampoo</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input categoryCheck" type="checkbox" id="2" value="2">
                                    <label class="form-check-label small" for="2">Conditioner</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input categoryCheck" type="checkbox" id="3" value="3">
                                    <label class="form-check-label small" for="3">Hair Oil</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input categoryCheck" type="checkbox" id="4" value="4">
                                    <label class="form-check-label small" for="4">Hair Serum</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input categoryCheck" type="checkbox" id="5" value="5">
                                    <label class="form-check-label small" for="5">Hair Mask</label>
                                </div>
                            </div>

                            <div class="mb-4">
                                <h6 class="fw-bold small text-uppercase text-muted mb-3">Price Range</h6>
                                <input
                                    type="range"
                                    class="form-range"
                                    min="0"
                                    max="10000"
                                    value="10000"
                                    id="priceRange">
                                <div class="text-end mt-2">

                                    <span class="badge bg-primary" id="priceText">
                                        Rs. 10000
                                    </span>

                                </div>
                            </div>

                            <button type="button" class="btn btn-primary-custom w-100" id="applyFilter">Apply Filters</button>
                        </form>
                    </div>
                </div>

                <div class="col-lg-9">
                    <div class="mb-5">
                        <button class="btn btn-outline-secondary d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileFilters">
                            <i class="bi bi-funnel me-1"></i> Filters
                        </button>
                    </div>

                    <div class="row g-4" id="productContainer">
                        <?php
                        $product_rs = Database::search("SELECT * FROM `product` ");
                        $product_num = $product_rs->num_rows;
                        for ($x = 0; $x < $product_num; $x++) {
                            $product_data = $product_rs->fetch_assoc();
                            $stock_rs = Database::search("SELECT * FROM `stock` WHERE product_id = '" . $product_data["id"] . "' ");
                            $stock_data = $stock_rs->fetch_assoc();
                        ?>

                            <div class="col-xl-4 col-md-6 reveal">
                                <div class="product-card">
                                    <a href="product.php?id=<?php echo $product_data["id"] ?>">
                                        <div class="product-img-wrap">
                                            <img src="<?php echo $product_data["img_url"] ?>" alt="img">
                                        </div>
                                    </a>
                                    <div class="product-body d-flex flex-column">
                                        <small class="text-muted text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;"><?php
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
                                            ?></small>
                                        <h5 class="mb-1 mt-1"><?php echo $product_data["name"] ?></h5>
                                        <p class="text-muted small mb-3 flex-grow-1"><?php echo substr($product_data["description"], 0, 65); ?>...</p>
                                        <span class="product-price">RS. <?php echo $stock_data["price"] ?></span>
                                        <a href="product.php?id=<?php echo $product_data["id"] ?>" class=" btn-view-product">View Product <i class="bi bi-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>

                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="offcanvas offcanvas-start" tabindex="-1" id="mobileFilters" aria-labelledby="mobileFiltersLabel">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title fw-bold" id="mobileFiltersLabel">Filters</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <form>
                <div class="mb-4">
                    <h6 class="fw-bold small text-uppercase text-muted mb-3">Category</h6>
                    <div class="form-check mb-2">
                        <input class="form-check-input categoryCheck" type="checkbox" id="1" value="1">
                        <label class="form-check-label small" for="1">Shampoo</label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input categoryCheck" type="checkbox" id="2" value="2">
                        <label class="form-check-label small" for="2">Conditioner</label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input categoryCheck" type="checkbox" id="3" value="3">
                        <label class="form-check-label small" for="3">Hair Oil</label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input categoryCheck" type="checkbox" id="4" value="4">
                        <label class="form-check-label small" for="4">Hair Serum</label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input categoryCheck" type="checkbox" id="5" value="5">
                        <label class="form-check-label small" for="5">Hair Mask</label>
                    </div>
                </div>

                <div class="mb-4">
                    <h6 class="fw-bold small text-uppercase text-muted mb-3">Price Range</h6>
                    <input
                        type="range"
                        class="form-range"
                        min="0"
                        max="10000"
                        value="10000"
                        id="priceRange">
                    <div class="text-end mt-2">

                        <span class="badge bg-primary" id="priceText">
                            Rs. 10000
                        </span>

                    </div>
                </div>

                <button type="button" class="btn btn-primary-custom w-100" id="applyFilter">Apply Filters</button>
            </form>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script>
        document.documentElement.classList.remove('no-js');
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/main.js"></script>
    <script src="js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>