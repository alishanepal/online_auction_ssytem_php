<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Online Auction System</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- External CSS -->
    <style>
        body {
            background-color: #f8f9fa; /* Light gray background */
        }

        .login-container {
            background-color: #ffffff; /* White background for the form */
            border-radius: 15px; /* Rounded corners */
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2); /* Soft shadow */
            padding: 40px; /* Increased padding */
            margin-top: 50px; /* Spacing from top */
        }

        .login-title {
            font-weight: bold;
            margin-bottom: 30px; /* Increased margin */
            color: #007bff; /* Bootstrap primary color */
            font-size: 1.8rem; /* Larger font size */
        }

        .form-label {
            font-weight: 600; /* Bold labels */
        }

        .btn-primary {
            background-color: #007bff; /* Primary button color */
            border: none; /* No border */
            transition: background-color 0.3s ease; /* Smooth transition */
        }

        .btn-primary:hover {
            background-color: #0056b3; /* Darker shade on hover */
        }

        .signup-link {
            color: #007bff; /* Link color */
            text-decoration: none; /* No underline */
        }

        .signup-link:hover {
            text-decoration: underline; /* Underline on hover */
        }

        /* Additional responsive adjustments */
        @media (max-width: 576px) {
            .login-container {
                padding: 30px; /* Adjust padding for smaller screens */
            }
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 login-container">
                <h2 class="text-center login-title">Sign Up</h2>
                <form action="../process/signup_process.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label for="first_name" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" required>
                    </div>
                    <div class="mb-4">
                        <label for="last_name" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" required>
                    </div>
                    <div class="mb-4">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-4">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-4">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="tel" class="form-control" id="phone" name="phone" required>
                    </div>
                    <div class="mb-4">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" class="form-control" id="address" name="address" required>
                    </div>
                    <div class="mb-4">
                        <label for="profile_photo" class="form-label">Profile Photo</label>
                        <input type="file" class="form-control" id="profile_photo" name="profile_photo" accept="image/*">
                    </div>
                    <div class="mb-4">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-4">
                        <label for="confirm_password" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>

                    <!-- Terms and Conditions Checkbox -->
                    <div class="mb-4 form-check">
                        <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                        <label class="form-check-label" for="terms">
                            I agree to the <a href="terms.php" target="_blank">Terms and Conditions</a>
                        </label>
                    </div>

                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary w-100">Sign Up</button>
                    </div>
                    <p class="text-center">Already have an account? <a href="login.php" class="signup-link">Login here</a></p>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
