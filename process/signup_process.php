<?php
include '../includes/connection.php';
$errors = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $profile_photo = $_FILES['profile_photo'];

    // Check if all required fields are filled (excluding the optional photo)
    if (
        empty($first_name) || empty($last_name) || empty($username) ||
        empty($email) || empty($phone) || empty($address) ||
        empty($password) || empty($confirm_password)
    ) {
        echo "<script>alert('Please fill in all the required fields.'); window.history.back();</script>";
        exit();
    }

    // First Name validation
    if (!preg_match("/^[a-zA-Z]+$/", $first_name)) {
        $errors[] = "First name can only contain alphabets.";
    }

    // Last Name validation
    if (!preg_match("/^[a-zA-Z]+$/", $last_name)) {
        $errors[] = "Last name can only contain alphabets.";
    }

    // Username validation
    if (!preg_match("/^[a-zA-Z0-9_]*$/", $username)) {
        $errors[] = "Username can only contain letters, numbers, and underscores.";
    }

    // Email validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // Phone validation
    if (!preg_match("/^\d{10,15}$/", $phone)) {
        $errors[] = "Phone number should be between 10 to 15 digits.";
    }

    // Password validation
    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    }

    // Confirm password validation
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    // Profile Photo validation (optional)
    if ($profile_photo['error'] === UPLOAD_ERR_OK) {
        $allowed_extensions = array('jpg', 'jpeg', 'png', 'gif');
        $file_ext = pathinfo($profile_photo['name'], PATHINFO_EXTENSION);

        if (!in_array($file_ext, $allowed_extensions)) {
            $errors[] = "Invalid image format. Allowed formats: jpg, jpeg, png, gif.";
        } else {
            $target_dir = "../public/profile/";
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            $photo_name = uniqid() . "." . $file_ext;
            $target_file = $target_dir . $photo_name;
            $full_path = $target_file;

            if (!move_uploaded_file($profile_photo['tmp_name'], $target_file)) {
                $errors[] = "There was an error uploading the profile photo.";
            }
        }
    } else {
        $full_path = null; // If no photo is uploaded, set the path to null
    }

    // If no errors, proceed with inserting data into the database
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare(
            "INSERT INTO users (first_name, last_name, username, email, phone, address, password, profile_photo) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param(
            "ssssssss", $first_name, $last_name, $username, $email, $phone, $address, $hashed_password, $full_path
        );

        try {
            if ($stmt->execute()) {
                echo "<script>alert('Signup successful!'); window.location.href = '../views/index.php';</script>";
            }
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1062) { // Error code 1062 means duplicate entry
                echo "<script>alert('Username already exists. Please choose a different one.'); window.history.back();</script>";
            } else {
                echo "<script>alert('Error: " . addslashes($e->getMessage()) . "'); window.history.back();</script>";
            }
        }

        $stmt->close();
        $conn->close();
    } else {
        // Display errors with JavaScript alerts
        $error_messages = implode("\\n", $errors);
        echo "<script>alert('\\n$error_messages'); window.history.back();</script>";
    }
}
