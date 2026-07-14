<?php
require "connection.php";
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avika - Natural Hair Care</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="resources/logo.png">
</head>

<body>

    <?php include 'includes/navbar.php'; ?>

    <section class="hero-section">
        <div class="hero-shape shape-1"></div>
        <div class="hero-shape shape-2"></div>
        <div class="hero-shape shape-3"></div>

        <div class="floating-leaf leaf-1"></div>
        <div class="floating-leaf leaf-2"></div>
        <div class="floating-leaf leaf-3"></div>

        <div class="container">
            <div class="row align-items-center min-vh-100">
                <div class="col-lg-7 hero-content">
                    <span class="hero-badge">100% Natural Ingredients</span>
                    <h1 class="hero-title">Beauty from <span>Nature's</span> Garden</h1>
                    <p class="hero-desc">Transform your hair with our handcrafted products made from the purest botanical extracts. No chemicals, no compromises - just naturally radiant hair.</p>
                    <div class="hero-btns d-flex flex-wrap gap-3">
                        <a href="#products" class="btn btn-primary-custom">Shop Now <i class="bi bi-arrow-right ms-2"></i></a>
                        <a href="#about" class="btn btn-outline-custom" style="border-color:#fff; color:#fff;">Learn More</a>
                    </div>
                </div>
                <div class="col-lg-5 text-center"
                    data-aos="fade-left">

                    <img src="./resources/banner.png"
                        class="hero-image"
                        alt="Hair Oil">

                </div>
            </div>

        </div>
    </section>

    <section id="about" class="section-padding bg-white">
        <div class="container">
            <div class="text-center mb-5  reveal">
                <h2 class="display-5 fw-bold ">Why Choose Avika?</h2>
                <p class="text-muted">Pure ingredients for perfect hair</p>
            </div>

            <div class="row g-4">
                <div class="col-md-4 reveal reveal-delay-1">
                    <div class="text-center p-4">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:80px; height:80px;">
                            <i class="bi bi-flower1 display-5 text-success"></i>
                        </div>
                        <h4>100% Organic</h4>
                        <p class="text-muted">Sourced directly from nature without any harsh chemicals or sulfates.</p>
                    </div>
                </div>
                <div class="col-md-4 reveal reveal-delay-2">
                    <div class="text-center p-4">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:80px; height:80px;">
                            <i class="bi bi-recycle display-5 text-success"></i>
                        </div>
                        <h4>Eco-Friendly</h4>
                        <p class="text-muted">Sustainable packaging and cruelty-free testing for a better planet.</p>
                    </div>
                </div>
                <div class="col-md-4 reveal reveal-delay-3">
                    <div class="text-center p-4">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:80px; height:80px;">
                            <i class="bi bi-heart-pulse display-5 text-success"></i>
                        </div>
                        <h4>Hair Health</h4>
                        <p class="text-muted">Nourish from root to tip with vitamins and essential oils.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section id="products" class="section-padding bg-cream">
        <div class="container">
            <div class="text-center mb-5 reveal">
                <h2 class="display-5 fw-bold">Our Hair Oil Collection</h2>
                <p class="text-muted">Loved by thousands of happy customers</p>
            </div>
            <div class="row g-4">
                <?php
                $product_rs = Database::search("SELECT * FROM `product` WHERE category = '3' ORDER BY `created_at` DESC LIMIT 3 ");
                $product_num = $product_rs->num_rows;
                for ($x = 0; $x < $product_num; $x++) {
                    $product_data = $product_rs->fetch_assoc();
                    $stock_rs = Database::search("SELECT * FROM `stock` WHERE product_id = '" . $product_data["id"] . "' ");
                    $stock_data = $stock_rs->fetch_assoc();
                ?>
                    <div class="col-lg-4 col-md-6 reveal reveal-delay-1">
                        <div class="product-card">
                            <div class="product-img-wrap">
                                <img src="<?php echo $product_data["img_url"] ?>" alt="img">
                            </div>
                            <div class="product-body d-flex flex-column">
                                <h5 class="mb-1"><?php echo $product_data["name"] ?></h5>
                                <p class="text-muted small mb-3 flex-grow-1"><?php echo substr($product_data["description"], 0, 65); ?>...</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="product-price">RS. <?php echo $stock_data["price"] ?></span>
                                    <a href="product.php?id=<?php echo $product_data["id"] ?>" class=" btn-view-product">View Product <i class="bi bi-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
    </section>

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