<?php
$userEmail = isset($_GET['email']) ? $_GET['email'] : '';
$verificationCode = isset($_GET['code']) ? $_GET['code'] : '';

if (empty($userEmail) || empty($verificationCode)) {
    $isValidLink = false;
} else {
    $isValidLink = true;
}

$navLight = true;
?>
<!DOCTYPE html>
<html lang="en" class="no-js">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email - Avika</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="resources/logo.png">
</head>

<body class="bg-cream" style="min-height: 100vh; display: flex; align-items: center;"> 

    <div class="container text-center">
        <a href="index.php" class="mb-4 d-inline-block" style="font-family: 'Playfair Display', serif; font-size: 2rem; font-weight: 700; color: var(--primary); text-decoration: none;">
            <img src="resources/full-logo.png" alt="Avika Logo" width="150">
        </a>

        <?php if ($isValidLink): ?>
            <div class="verify-card mx-auto" id="verifyForm">
                <div id="pendingState">
                    <div class="icon-circle icon-pending">
                        <i class="bi bi-envelope-check"></i>
                    </div>
                    <h3 class="fw-bold mb-2">Verify Your Email</h3>
                    <p class="text-muted mb-1">Verifying account for:</p>
                    <h5 class="text-dark mb-4" style="color: var(--primary) !important;"><?php echo (urldecode($userEmail)); ?></h5>

                    <form method="POST" id="verifyEmailForm">
                        <input type="hidden" name="email" type="email" value="<?php echo $userEmail; ?>">
                        <input type="hidden" name="code" value="<?php echo $verificationCode; ?>">

                        <button type="submit" class="btn btn-primary-custom w-100 py-3 mb-3" id="verifyBtn">
                            <i class="bi bi-shield-check me-2"></i>Complete Verification
                        </button>
                    </form>
                    <small class="text-muted">Or simply wait and we'll verify it automatically...</small>
                </div>

                <div id="successState" style="display: none;">
                    <div class="icon-circle icon-success">
                        <svg class="checkmark-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                            <circle class="checkmark-path" cx="26" cy="26" r="25" fill="none" stroke="#1abc9c" stroke-width="2" />
                            <path class="checkmark-path" fill="none" stroke="#1abc9c" stroke-width="3" d="M14.1 27.2l7.1 7.2 16.7-16.8" />
                        </svg>
                    </div>
                    <h3 class="fw-bold mb-2 text-success">Verification Successful!</h3>
                    <p class="text-muted mb-4">Your email has been verified. You can now access your account.</p>
                    <a href="login.php" class="btn btn-primary-custom w-100 py-3">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Proceed to Login
                    </a>
                </div>
            </div>

        <?php else: ?>
            <!-- Invalid/Expired Link View -->
            <div class="verify-card mx-auto">
                <div class="icon-circle icon-error">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
                <h3 class="fw-bold mb-2 text-danger">Invalid Link</h3>
                <p class="text-muted mb-4">The verification link is invalid or has expired. Please request a new one.</p>
                <a href="register.php" class="btn btn-outline-custom w-100 py-3">
                    <i class="bi bi-arrow-left me-2"></i>Back to Registration
                </a>
            </div>
        <?php endif; ?>
    </div>

    <script>
        document.documentElement.classList.remove('no-js');
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/main.js"></script>
    <script src="js/script.js"></script>
</body>

</html>