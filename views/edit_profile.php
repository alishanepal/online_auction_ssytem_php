<?php
include '../includes/connection.php'; // Ensure the database connection is included

// Check if user_id is set in the query string
if (isset($_GET['user_id'])) {
    $user_id = (int)$_GET['user_id']; // Get user ID from URL

    // SQL query to fetch user details
    $sql = "SELECT 
                u.user_id,
                u.username,
                u.email,
                u.first_name,
                u.last_name,
                u.address,
                u.phone,
                u.profile_photo,
                ai.id_no,
                ai.id_photo,
                ai.account_no
            FROM 
                users u
            JOIN 
                additional_info ai ON u.user_id = ai.user_id
            WHERE 
                u.user_id = ?"; // Use prepared statements to prevent SQL injection

    // Prepare and bind
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $user_id); // 'i' indicates the type of the parameter is integer
        $stmt->execute(); // Execute the prepared statement
        $result = $stmt->get_result(); // Get the result set

        // Fetch user details
        if ($user = $result->fetch_assoc()) {
            // Display the edit profile form
            echo '<div class="container mt-5">';
            echo '<h1 class="text-center mb-4">Edit Profile</h1>';
            echo '<form action="update_profile.php" method="POST" enctype="multipart/form-data" class="bg-light p-4 rounded shadow">';
            echo '<input type="hidden" name="user_id" value="' . htmlspecialchars($user['user_id']) . '">';
            echo '<div class="form-group">';
            echo '<label for="username">Username:</label>';
            echo '<input type="text" class="form-control" id="username" name="username" value="' . htmlspecialchars($user['username']) . '" required>';
            echo '</div>';
            echo '<div class="form-group">';
            echo '<label for="first_name">First Name:</label>';
            echo '<input type="text" class="form-control" id="first_name" name="first_name" value="' . htmlspecialchars($user['first_name']) . '" required>';
            echo '</div>';
            echo '<div class="form-group">';
            echo '<label for="last_name">Last Name:</label>';
            echo '<input type="text" class="form-control" id="last_name" name="last_name" value="' . htmlspecialchars($user['last_name']) . '" required>';
            echo '</div>';
            echo '<div class="form-group">';
            echo '<label for="email">Email:</label>';
            echo '<input type="email" class="form-control" id="email" name="email" value="' . htmlspecialchars($user['email']) . '" required>';
            echo '</div>';
            echo '<div class="form-group">';
            echo '<label for="phone">Phone:</label>';
            echo '<input type="text" class="form-control" id="phone" name="phone" value="' . htmlspecialchars($user['phone']) . '" required>';
            echo '</div>';
            echo '<div class="form-group">';
            echo '<label for="address">Address:</label>';
            echo '<input type="text" class="form-control" id="address" name="address" value="' . htmlspecialchars($user['address']) . '" required>';
            echo '</div>';
            echo '<div class="form-group">';
            echo '<label for="id_no">ID No:</label>';
            echo '<input type="text" class="form-control" id="id_no" name="id_no" value="' . htmlspecialchars($user['id_no']) . '" required>';
            echo '</div>';
            echo '<div class="form-group">';
            echo '<label for="account_no">Account No:</label>';
            echo '<input type="text" class="form-control" id="account_no" name="account_no" value="' . htmlspecialchars($user['account_no']) . '" required>';
            echo '</div>';
            echo '<div class="form-group">';
            echo '<label for="profile_photo">Profile Photo:</label>';
            echo '<input type="file" class="form-control-file" id="profile_photo" name="profile_photo" accept="image/*">';
            echo '</div>';
            echo '<div class="form-group">';
            echo '<label for="id_photo">ID Photo:</label>';
            echo '<input type="file" class="form-control-file" id="id_photo" name="id_photo" accept="image/*">';
            echo '</div>';
            echo '<button type="submit" class="btn btn-primary btn-block">Update Profile</button>';
            echo '</form>';
            echo '</div>'; // Close container
        } else {
            echo '<p>User not found.</p>';
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
} else {
    echo '<p>No user ID specified.</p>';
}

// Close the database connection
$conn->close();
