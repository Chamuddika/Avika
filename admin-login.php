<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Avika</title>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="resources/logo.png">
</head>

<body class="body">
    <div class="login-shape shape-1"></div>
    <div class="login-shape shape-2"></div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 text-center">
                <div class="login-card mx-auto">
                    <i class="bi bi-shield-lock text-success" style="font-size: 3rem;"></i>
                    <h2 class="mt-3 mb-1" style="font-family: 'Playfair Display', serif;">Admin Panel</h2>
                    <p class="text-muted mb-4">Sign in to manage your store</p>

                    <form id="adminLoginForm" method="POST">
                        <div class="mb-3 text-start">
                            <label class="form-label small fw-bold">Email</label>
                            <input type="email" class="form-control" placeholder="admin@avika.com" required name="email">
                        </div>
                        <div class="mb-4 text-start">
                            <label class="form-label small fw-bold">Password</label>
                            <input type="password" class="form-control" placeholder="Enter password" required name="password">
                        </div>
                        <button type="submit" class="btn btn-admin-login" id="adminSigninBtn">Sign In <i class="bi bi-arrow-right ms-2"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="./js/script.js"></script>
</body>

</html>