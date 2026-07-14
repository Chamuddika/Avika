<!-- Navbar Component -->
<nav class="navbar navbar-expand-lg navbar-custom" style="background-color: rgba(193, 212, 182, 0.95);">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <img src="resources/full-logo.png" alt="Avika Logo" width="150">
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <i class="bi bi-list fs-4"></i>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="products.php">Products</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#about">About</a>
                </li>
            </ul>
            
            <div class="d-flex align-items-center gap-3">

                <a href="profile.php" class="nav-icon-link" title="My Account">
                    <i class="bi bi-person"></i>
                </a>
                <?php
                if (isset($_SESSION["u"])) {
                    $session_data = $_SESSION["u"];
                    $uid = $uid = $_SESSION["u"]["id"];

                    $cart_rs = Database::search("SELECT * FROM `cart` WHERE `users_id`='" . $uid . "'");
                    $cart_num = $cart_rs->num_rows;
                ?>
                <a href="cart.php" class="nav-icon-link position-relative" title="Shopping Cart">
                    <i class="bi bi-bag"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                       <?php echo $cart_num ?>
                    </span>
                </a>
                <?php } else { ?>
                <a href="cart.php" class="nav-icon-link position-relative" title="Shopping Cart">
                    <i class="bi bi-bag"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                        0
                    </span>
                </a>
                <?php }?>

                <?php
                if (isset($_SESSION["u"])) {
                    $session_data = $_SESSION["u"];
                ?>
                    <a onclick="signout();" class="btn btn-outline-custom btn-sm">Sign Out</a>
                <?php } else { ?>
                    <a href="login.php" class="btn btn-outline-custom btn-sm">Login</a>
                    <a href="register.php" class="btn btn-primary-custom btn-sm">Sign Up</a>
                <?php } ?>
            </div>
        </div>
    </div>
</nav>