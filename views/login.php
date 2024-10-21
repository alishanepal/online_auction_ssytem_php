<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Online Auction System</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../public/css/login_signup.css" rel="stylesheet">
    
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 login-container">
                <h2 class="text-center login-title">Login</h2>
                <form action="../process/login_process.php" method="POST">
                    <div class="mb-4">
                        <label for="identifier" class="form-label">Username/Email</label>
                        <input type="text" class="form-control" id="identifier" name="identifier" required>
                    </div>
                    <div class="mb-4">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-4">
                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </div>
                    <p class="text-center">Don't have an account? <a href="signup.php" class="signup-link">Sign up here</a></p>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
