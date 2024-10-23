<?php
include '../includes/connection.php'; 
include 'dashboard_flex.php'; // Your database connection file

$sql = "
    SELECT 
        u.user_id, 
        u.username, 
        u.profile_photo,  -- Assuming 'profile_photo' is a column in your users table
        b.bid_id, 
        b.product_id, 
        b.bid_amount, 
        b.bid_time, 
        p.product_name, 
        pi.image_url 
    FROM 
        users u
    JOIN 
        bids b ON u.user_id = b.user_id
    JOIN 
        product p ON b.product_id = p.product_id
    JOIN 
        product_images pi ON p.product_id = pi.product_id
    WHERE 
        u.role = 'user'
    ORDER BY 
        u.user_id, 
        b.product_id,
        b.bid_time DESC
";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Initialize an array to hold grouped data
    $userBiddingHistory = [];

    // Fetch data and group by user_id and product_id
    while ($row = $result->fetch_assoc()) {
        $user_id = $row['user_id'];
        $product_id = $row['product_id'];

        // Initialize user entry if not exists
        if (!isset($userBiddingHistory[$user_id])) {
            $userBiddingHistory[$user_id] = [
                'username' => $row['username'],
                'profile_photo' => $row['profile_photo'],
                'bids' => []
            ];
        }

        // Group bids by product
        if (!isset($userBiddingHistory[$user_id]['bids'][$product_id])) {
            $userBiddingHistory[$user_id]['bids'][$product_id] = [
                'product_name' => $row['product_name'],
                'image_url' => $row['image_url'],
                'bids' => []
            ];
        }

        // Add bid information
        $userBiddingHistory[$user_id]['bids'][$product_id]['bids'][] = [
            'bid_id' => $row['bid_id'],
            'bid_amount' => $row['bid_amount'],
            'bid_time' => $row['bid_time']
        ];
    }

    // Display header
    echo "<h2 style='text-align: center; color: #3498db;'>Bidders Report</h2>";
    
    // Display user bidding history
    echo "<div style='padding: 20px; background-color: #f8f9fa; border-radius: 8px;'>";
    foreach ($userBiddingHistory as $user_id => $data) {
        // Create a collapsible header for each user
        echo "<div style='margin-bottom: 15px;'>";
        echo "<h3 style='cursor: pointer; color: #3498db;' onclick='toggleBids($user_id)'>";
        echo "<img src='" . htmlspecialchars($data['profile_photo']) . "' alt='Profile Photo' style='width: 30px; height: 30px; border-radius: 50%; margin-right: 10px;'>" . htmlspecialchars($data['username']);
        echo "</h3>";
        echo "<div id='bids-$user_id' style='display: none; padding: 15px; background-color: #ffffff; border-radius: 5px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);'>";

        foreach ($data['bids'] as $product_id => $product) {
            echo "<div style='margin-bottom: 20px;'>";
            echo "<h5 style='margin-bottom: 10px;'>" . htmlspecialchars($product['product_name']) . "</h5>";
            echo "<img src='" . htmlspecialchars($product['image_url']) . "' alt='Product Image' style='width:50px;height:50px; border-radius: 5px; margin-bottom: 10px;'>";
            echo "<ul style='list-style-type: none; padding: 0;'>";

            // Sort bids by bid_time descending
            usort($product['bids'], function($a, $b) {
                return strtotime($b['bid_time']) - strtotime($a['bid_time']);
            });

            foreach ($product['bids'] as $bid) {
                echo "<li style='padding: 5px; background-color: #f2f2f2; border: 1px solid #ddd; border-radius: 3px; margin-bottom: 5px;'>";
                echo "Bid ID: " . htmlspecialchars($bid['bid_id']) . " | Amount: " . htmlspecialchars($bid['bid_amount']) . " | Time: " . htmlspecialchars($bid['bid_time']) . "</li>";
            }

            echo "</ul>";
            echo "</div>";
        }
        
        echo "</div>"; // Close bids div
        echo "</div>"; // Close user div
    }
    echo "</div>"; // Close outer div
} else {
    echo "<p style='color: #e74c3c;'>No bidding history found.</p>";
}

// Close the database connection
$conn->close();
?>

<script>
function toggleBids(userId) {
    var bidsDiv = document.getElementById('bids-' + userId);
    if (bidsDiv.style.display === 'none' || bidsDiv.style.display === '') {
        bidsDiv.style.display = 'block';
    } else {
        bidsDiv.style.display = 'none';
    }
}
</script>
