<?php
session_start();
include '../includes/connection.php'; // Include DB connection

/**
 * Fetch the winners report by joining the necessary tables.
 */
function getWinnersReport($conn) {
    $query = "
        SELECT 
            u.user_id, 
            u.username, 
            u.profile_photo, 
            p.product_name, 
            pi.image_url, 
            w.winning_bid_id, 
            w.winning_amount, 
            w.won_time 
        FROM 
            winners w
        JOIN 
            users u ON w.user_id = u.user_id
        JOIN 
            product p ON w.product_id = p.product_id
        JOIN 
            product_images pi ON p.product_id = pi.product_id
        JOIN 
            bids b ON w.winning_bid_id = b.bid_id
        ORDER BY 
            w.won_time DESC";

    $stmt = $conn->prepare($query);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// Main logic
$winnersReport = getWinnersReport($conn);

// Display winners report
if ($winnersReport) {
    echo '<h2>Winners Report</h2>';
    echo '<table border="1">';
    echo '<tr>
            <th>User ID</th>
            <th>Username</th>
            <th>Profile Photo</th>
            <th>Product Name</th>
            <th>Image</th>
            <th>Winning Bid ID</th>
            <th>Winning Amount</th>
            <th>Won Time</th>
          </tr>';

    foreach ($winnersReport as $winner) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($winner['user_id']) . '</td>';
        echo '<td>' . htmlspecialchars($winner['username']) . '</td>';
        echo '<td><img src="' . htmlspecialchars($winner['profile_photo']) . '" alt="Profile Photo" width="50" height="50"></td>';
        echo '<td>' . htmlspecialchars($winner['product_name']) . '</td>';
        echo '<td><img src="' . htmlspecialchars($winner['image_url']) . '" alt="Product Image" width="50" height="50"></td>';
        echo '<td>' . htmlspecialchars($winner['winning_bid_id']) . '</td>';
        echo '<td>' . htmlspecialchars($winner['winning_amount']) . '</td>';
        echo '<td>' . htmlspecialchars($winner['won_time']) . '</td>';
        echo '</tr>';
    }

    echo '</table>';
} else {
    echo 'No winners found.';
}

// Close the database connection
$conn->close();
