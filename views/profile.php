<!-- Include Bootstrap CSS -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

<?php
include '../includes/connection.php'; // Ensure the database connection is included

// Check if user_id is set in the query string
if (isset($_GET['user_id'])) {
    $user_id = (int)$_GET['user_id']; // Get user ID from URL

    // SQL query to join users and additional_info
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
            // Display user details prettily
            echo '<h1 style="text-align:center; font-family: Arial, sans-serif;">' . htmlspecialchars($user['username']) . '\'s Profile</h1>';
            echo '<div style="max-width: 600px; margin: auto; border: 1px solid #ccc; padding: 20px; border-radius: 5px; background-color: #f9f9f9; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);">';

            // Display user's profile photo or a placeholder (circular)
            $profilePhoto = !empty($user['profile_photo']) ? htmlspecialchars($user['profile_photo']) : '../public/images/default-profile.png';
            echo '<img src="' . $profilePhoto . '" alt="Profile Photo" width="100" height="100" style="border-radius: 50%; display: block; margin: 0 auto 20px; cursor: pointer;" data-toggle="modal" data-target="#profileModal">';

            // Display user details with a consistent layout
            echo '<div style="font-family: Arial, sans-serif; line-height: 1.6;">';
            echo '<p><strong>Username:</strong> ' . htmlspecialchars($user['username']) . '</p>';
            echo '<p><strong>Name:</strong> ' . htmlspecialchars($user['first_name']) . ' ' . htmlspecialchars($user['last_name']) . '</p>';
            echo '<p><strong>Email:</strong> ' . htmlspecialchars($user['email']) . '</p>';
            echo '<p><strong>Phone:</strong> ' . htmlspecialchars($user['phone']) . '</p>';
            echo '<p><strong>Address:</strong> ' . htmlspecialchars($user['address']) . '</p>';
            echo '<p><strong>ID No:</strong> ' . htmlspecialchars($user['id_no']) . '</p>';
            echo '<p><strong>Account No:</strong> ' . htmlspecialchars($user['account_no']) . '</p>';
            echo '</div>';

            // Display ID photo as a clickable link
            if (!empty($user['id_photo'])) {
                echo '<div style="text-align:center; margin-top: 20px;">';
                echo '<span style="cursor: pointer; color: blue; text-decoration: underline;" data-toggle="modal" data-target="#idModal">ID Picture</span>';
                echo '</div>';
            }

            // Add buttons for viewing bidding history and editing profile
            echo '<div class="d-flex justify-content-center mt-4">';
            echo '<button class="btn btn-info mr-3" data-toggle="modal" data-target="#biddingHistoryModal">View Bidding History</button>';
            echo '<button class="btn btn-primary" data-toggle="modal" data-target="#editProfileModal">Edit Profile</button>';
            echo '</div>';
            echo '</div>'; // Close outer div
        } else {
            echo '<p style="text-align:center; color: red;">User not found.</p>';
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }

    // SQL query to fetch the user's bidding history grouped by product
    $biddingHistoryQuery = "
    SELECT 
        p.product_name, 
        b.bid_amount, 
        b.bid_time 
    FROM 
        bids b
    JOIN 
        product p ON b.product_id = p.product_id
    WHERE 
        b.user_id = ?
    ORDER BY 
        p.product_name, b.bid_time DESC"; // Group by product and order by time

    // Prepare and execute the query
    if ($stmtBids = $conn->prepare($biddingHistoryQuery)) {
        $stmtBids->bind_param("i", $user_id);
        $stmtBids->execute();
        $bidsResult = $stmtBids->get_result();
    }

} else {
    echo '<p style="text-align:center; color: red;">No user ID specified.</p>';
}

// Close the database connection
$conn->close();
?>

<!-- Modal for Profile Photo -->
<div class="modal fade" id="profileModal" tabindex="-1" role="dialog" aria-labelledby="profileModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="profileModalLabel">Profile Photo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" style="text-align:center;">
        <img src="<?php echo $profilePhoto; ?>" alt="Profile Photo" style="max-width: 100%;">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal for ID Photo -->
<div class="modal fade" id="idModal" tabindex="-1" role="dialog" aria-labelledby="idModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="idModalLabel">ID Photo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" style="text-align:center;">
        <img src="<?php echo htmlspecialchars($user['id_photo']); ?>" alt="ID Photo" style="max-width: 100%;">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal for Edit Profile -->
<div class="modal fade" id="editProfileModal" tabindex="-1" role="dialog" aria-labelledby="editProfileModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="editProfileForm" action="update_profile.php" method="POST" enctype="multipart/form-data"> <!-- Add enctype -->
            <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <textarea class="form-control" id="address" name="address" required><?php echo htmlspecialchars($user['address']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
            </div>
            <div class="form-group">
                <label for="profile_photo">Profile Photo</label>
                <input type="file" class="form-control" id="profile_photo" name="profile_photo">
            </div>
            <div class="form-group">
                <label for="id_photo">ID Photo</label>
                <input type="file" class="form-control" id="id_photo" name="id_photo">
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal for Bidding History -->
<div class="modal fade" id="biddingHistoryModal" tabindex="-1" role="dialog" aria-labelledby="biddingHistoryModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="biddingHistoryModalLabel">Bidding History</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?php if ($bidsResult->num_rows > 0): ?>
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>Product Name</th>
                <th>Bid Amount</th>
                <th>Bid Time</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($bid = $bidsResult->fetch_assoc()): ?>
                <tr>
                  <td><?php echo htmlspecialchars($bid['product_name']); ?></td>
                  <td><?php echo htmlspecialchars($bid['bid_amount']); ?></td>
                  <td><?php echo htmlspecialchars($bid['bid_time']); ?></td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        <?php else: ?>
          <p>No bidding history available.</p>
        <?php endif; ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Include Bootstrap and jQuery scripts -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
