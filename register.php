
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Avika</title>
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
            <div class="floating-leaf leaf-3"></div>
            <div style="position:relative; z-index:2; text-align:center; color:white;">
                <h2 class="display-4 fw-bold mb-3" style="animation: fadeInUp 1s ease-out;">Join Avika Family!</h2>
                <p class="lead" style="animation: fadeInUp 1s ease-out 0.3s backwards;">Sign up to start your journey to healthly, strong & beautiful hair.</p>
            </div>
        </div>

        <!-- Right Form Side -->
        <div class="auth-form-section">
            <div class="auth-form-container" style="animation: scaleIn 0.6s ease-out;">
                <h2 class="mb-4">Create Account</h2>

                <form class="auth-form" id="registerForm" method="POST">
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <div class="form-floating-custom">
                                <label>Full Name</label>
                                <input type="text" class="form-control" placeholder="Enter your full name" required name="name">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-floating-custom">
                                <label>Email Address</label>
                                <input type="email" class="form-control" placeholder="Enter your Email" required name="email">
                            </div>
                        </div>
                    </div>

                    <div class="form-floating-custom mb-3">
                        <label>Phone Number</label>
                        <input type="tel" class="form-control" placeholder="Enter your phone number" required name="mobile">
                    </div>

                    <div class="form-floating-custom mb-3">
                        <label>Password</label>
                        <div class="position-relative">
                            <input type="password" class="form-control" placeholder="Create password" required name="password">
                            <button type="button" class="btn position-absolute top-50 end-0 translate-middle-y border-0 bg-transparent password-toggle">
                                <i class="bi bi-eye text-muted"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-floating-custom mb-4">
                        <label>Confirm Password</label>
                        <div class="position-relative">
                            <input type="password" class="form-control" placeholder="Confirm password" required name="confirmPassword">
                            <button type="button" class="btn position-absolute top-50 end-0 translate-middle-y border-0 bg-transparent password-toggle">
                                <i class="bi bi-eye text-muted"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" id="terms" required>
                        <label class="form-check-label small" for="terms">
                            I agree to the <a href="#" class="text-decoration-none fw-bold" style="color:var(--primary);">Terms & Conditions</a>
                        </label>
                    </div>

                    <button class="btn btn-primary-custom w-100 py-3 mb-4" type="submit" id="registerBtn">
                        Sign Up <i class="bi bi-arrow-right ms-2"></i>
                    </button>
                </form>

                <div class="text-center">
                    <span class="text-muted">Already have an account?</span>
                    <a href="login.php" class="fw-bold" style="color:var(--primary);"> Sign In</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer for Auth -->
    <div class="text-center py-4 bg-white mt-auto">
        <p class="mb-0 small text-muted">&copy; 2026 Avika Hair Care. All rights reserved.</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/main.js"></script>
    <script src="js/script.js"></script>
    <script>
        const termsCheckbox = document.getElementById("terms");
        const registerBtn = document.getElementById("registerBtn");
        registerBtn.disabled = true;
        termsCheckbox.addEventListener("change", function() {
            if (this.checked) {
                registerBtn.disabled = false;
            } else {
                registerBtn.disabled = true;
            }
        });
    </script>
</body>

</html>