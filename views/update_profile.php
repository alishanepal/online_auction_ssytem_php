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

    // Handle profile photo upload
    $profile_photo = '';
    if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] == UPLOAD_ERR_OK) {
        $profile_photo = 'uploads/' . basename($_FILES['profile_photo']['name']);
        move_uploaded_file($_FILES['profile_photo']['tmp_name'], $profile_photo);
    }

    // Handle ID photo upload
    $id_photo = '';
    if (isset($_FILES['id_photo']) && $_FILES['id_photo']['error'] == UPLOAD_ERR_OK) {
        $id_photo = 'uploads/' . basename($_FILES['id_photo']['name']);
        move_uploaded_file($_FILES['id_photo']['tmp_name'], $id_photo);
    }

    // Prepare the update SQL statement
    $sql = "UPDATE users SET 
                username = ?, 
                first_name = ?, 
                last_name = ?, 
                email = ?, 
                phone = ?, 
                address = ? 
            WHERE user_id = ?";

    // Prepare and bind
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssssssi", $username, $first_name, $last_name, $email, $phone, $address, $user_id);
        $stmt->execute();

        // Update additional_info if photos are uploaded
        if ($profile_photo || $id_photo) {
            $sql = "UPDATE additional_info SET 
                        id_no = ?, 
                        account_no = ?, 
                        profile_photo = ?, 
                        id_photo = ? 
                    WHERE user_id = ?";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("ssssi", $id_no, $account_no, $profile_photo, $id_photo, $user_id);
                $stmt->execute();
            }
        }

        echo 'Profile updated successfully.';
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }

    // Close the database connection
    $conn->close();
}
?>
