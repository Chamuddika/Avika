<?php
$pageTitle = "Login";
$navLight = true; // Solid navbar for auth pages
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Avika</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="resources/logo.png">
</head>

<body class="bg-cream">

    <div class="auth-page">
        <!-- Left Image Side -->
        <div class="auth-image d-none d-lg-flex">
            <div class="floating-leaf leaf-1"></div>
            <div class="floating-leaf leaf-2"></div>
            <div style="position:relative; z-index:2; text-align:center; color:white;">
                <h2 class="display-4 fw-bold mb-3" style="animation: fadeInUp 1s ease-out;">Welcome Back!</h2>
                <p class="lead" style="animation: fadeInUp 1s ease-out 0.3s backwards;">Sign in to Continue your journey to healthly, beautiful hair.</p>
            </div>
        </div>

        <!-- Right Form Side -->
        <div class="auth-form-section">
            <div class="auth-form-container" style="animation: scaleIn 0.6s ease-out;">
                <h2 class="mb-4">Sign In</h2>

                <?php
                $email = "";
                $password = "";

                if (isset($_COOKIE["email"])) {
                    $email = $_COOKIE["email"];
                }

                if (isset($_COOKIE["password"])) {
                    $password = $_COOKIE["password"];
                }
                ?>

                <form class="auth-form" id="signInFrom" method="POST">
                    <div class="form-floating-custom mb-3">
                        <label>Email Address</label>
                        <input type="email" class="form-control" placeholder="Enter your email" required name="email" value="<?php echo $email; ?>">
                    </div>

                    <div class="form-floating-custom mb-3">
                        <label>Password</label>
                        <div class="position-relative">
                            <input type="password" class="form-control" placeholder="Enter password" required name="password" value="<?php echo $password; ?>">
                            <button type="button" class="btn position-absolute top-50 end-0 translate-middle-y border-0 bg-transparent password-toggle">
                                <i class="bi bi-eye text-muted"></i>
                            </button>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="rememberMe">
                            <label class="form-check-label small" for="rememberMe">Remember me</label>
                        </div>
                        <a onclick="showModel();" class="btn small text-decoration-none" style="color:var(--primary); font-weight:600;">Forgot Password?</a>
                    </div>

                    <button type="submit" id="signInBtn" class="btn btn-primary-custom w-100 py-3 mb-4">
                        Sign In <i class="bi bi-arrow-right ms-2"></i>
                    </button>
                </form>

                <div class="text-center">
                    <span class="text-muted">Don't have an account?</span>
                    <a href="register.php" class="fw-bold" style="color:var(--primary);"> Sign Up</a>
                </div>
            </div>
        </div>
    </div>

    <!-- modal 1 -->
    <div class="modal" tabindex="-1" id="forgotPassword">
        <div class="modal-dialog">
            <form id="emailFrom" method="POST">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Forgot Password?</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row g-3">

                            <div class="form-floating-custom mb-3">
                                <label>Email Address</label>
                                <input type="email" class="form-control" placeholder="Enter your email" required id="email2" name="email">
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button class="btn btn-primary" type="submit" id="sendBtn">Send Code</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- modal 1 -->

    <!-- modal 2 -->
    <div class="modal" tabindex="-1" id="forgotPasswordModal">
        <div class="modal-dialog">
            <form id="forgotPasswordForm" method="POST">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Forgot Password?</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            
                            <div class="col-6">
                                <label class="form-label">New Password</label>
                                <div class="input-group mb-3">
                                    <input type="password" class="form-control" name="np" />
                                    <button type="button" class="btn position-absolute top-50 end-0 translate-middle-y border-0 bg-transparent password-toggle">
                                        <i class="bi bi-eye text-muted"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="col-6">
                                <label class="form-label">Retype New Password</label>
                                <div class="input-group mb-3">
                                    <input type="password" class="form-control" name="rnp" />
                                    <button type="button" class="btn position-absolute top-50 end-0 translate-middle-y border-0 bg-transparent password-toggle">
                                        <i class="bi bi-eye text-muted"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Verifiction Code</label>
                                <input type="text" class="form-control" name="vc" />
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="submitBtn">Reset Password</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- modal 2 -->

    <!-- Footer for Auth -->
    <div class="text-center py-4 bg-white mt-auto">
        <p class="mb-0 small text-muted">&copy; 2026 Avika Hair Care. All rights reserved.</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/main.js"></script>
    <script src="js/script.js"></script>
        <?php include 'includes/footer.php'; ?>

</body>

</html>