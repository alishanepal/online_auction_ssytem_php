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

    // First Name validation
    if (empty($first_name)) {
        $errors[] = "First name is required.";
    } elseif (!preg_match("/^[a-zA-Z]+$/", $first_name)) {
        $errors[] = "First name can only contain alphabets.";
    }

    // Last Name validation
    if (empty($last_name)) {
        $errors[] = "Last name is required.";
    } elseif (!preg_match("/^[a-zA-Z]+$/", $last_name)) {
        $errors[] = "Last name can only contain alphabets.";
    }

    // Username validation
    if (empty($username)) {
        $errors[] = "Username is required.";
    } elseif (!preg_match("/^[a-zA-Z0-9_]*$/", $username)) {
        $errors[] = "Username can only contain letters, numbers, and underscores.";
    }

    // Email validation
    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // Phone validation
    if (!empty($phone) && !preg_match("/^\d{10,15}$/", $phone)) {
        $errors[] = "Phone number should be between 10 to 15 digits.";
    }

    // Address validation
    if (empty($address)) {
        $errors[] = "Address is required.";
    }

    // Password validation
    if (empty($password)) {
        $errors[] = "Password is required.";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    }

    // Confirm password validation
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    // Profile Photo validation
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
        $full_path = null;
    }

    // If no errors, proceed with inserting data into the database
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, username, email, phone, address, password, profile_photo) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $first_name, $last_name, $username, $email, $phone, $address, $hashed_password, $full_path);

        if ($stmt->execute()) {
            echo "<script>alert('Signup successful!'); window.location.href = '../views/index.php';</script>";
        } else {
            echo "<script>alert('Error: " . addslashes($stmt->error) . "'); window.history.back();</script>";
        }

        $stmt->close();
        $conn->close();
    } else {
        // Display errors with JavaScript alerts
        $error_messages = implode("\\n", $errors);
        echo "<script>alert('The following errors occurred:\\n$error_messages'); window.history.back();</script>";
    }
}
?>
