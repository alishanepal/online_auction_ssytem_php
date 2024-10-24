<?php
include '../includes/connection.php'; // Ensure the database connection is included

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $username = $_POST['username'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $id_no = $_POST['id_no'];
    $account_no = $_POST['account_no'];

    // Directories for storing uploaded photos
    $profileDir = '../public/profile/';
    $idPhotoDir = '../public/id_photo/';

    // Handle profile photo upload
    $profile_photo = '';
    if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] == UPLOAD_ERR_OK) {
        $profile_photo = $profileDir . basename($_FILES['profile_photo']['name']);
        if (!move_uploaded_file($_FILES['profile_photo']['tmp_name'], $profile_photo)) {
            echo "<script>alert('Failed to upload profile photo.'); window.history.back();</script>";
            exit;
        }
        $profile_photo = '../public/profile/' . basename($_FILES['profile_photo']['name']); // Store relative path
    }

    // Handle ID photo upload
    $id_photo = '';
    if (isset($_FILES['id_photo']) && $_FILES['id_photo']['error'] == UPLOAD_ERR_OK) {
        $id_photo = $idPhotoDir . basename($_FILES['id_photo']['name']);
        if (!move_uploaded_file($_FILES['id_photo']['tmp_name'], $id_photo)) {
            echo "<script>alert('Failed to upload ID photo.'); window.history.back();</script>";
            exit;
        }
        $id_photo = '../public/id_photo/' . basename($_FILES['id_photo']['name']); // Store relative path
    }

    // Update users table
    $userSql = "UPDATE users SET 
                    username = ?, 
                    first_name = ?, 
                    last_name = ?, 
                    email = ?, 
                    phone = ?, 
                    address = ?, 
                    profile_photo = IF(? != '', ?, profile_photo)
                WHERE user_id = ?";

    if ($userStmt = $conn->prepare($userSql)) {
        $userStmt->bind_param(
            "ssssssssi", 
            $username, $first_name, $last_name, 
            $email, $phone, $address, 
            $profile_photo, $profile_photo, 
            $user_id
        );
        $userStmt->execute();
        $userStmt->close();
    } else {
        echo "<script>alert('Error updating user details: " . $conn->error . "'); window.history.back();</script>";
        exit;
    }

    // Update additional_info table
    $infoSql = "UPDATE additional_info SET 
                    id_no = ?, 
                    account_no = ?, 
                    id_photo = IF(? != '', ?, id_photo) 
                WHERE user_id = ?";

    if ($infoStmt = $conn->prepare($infoSql)) {
        $infoStmt->bind_param(
            "ssssi", 
            $id_no, $account_no, 
            $id_photo, $id_photo, 
            $user_id
        );
        $infoStmt->execute();
        $infoStmt->close();
    } else {
        echo "<script>alert('Error updating additional info: " . $conn->error . "'); window.history.back();</script>";
        exit;
    }

    echo "<script>alert('Profile updated successfully.'); window.location.href = 'profile.php?user_id=$user_id';</script>";
    $conn->close();
}
?>
