<?php
function getCurrentBid($starting_bid, $highest_bid, $minimum_price_interval) {
    // Start with the starting bid
    $current_bid = $starting_bid;

    // If there's a highest bid, update the current bid
    if ($highest_bid > 0) {
        $current_bid = $highest_bid; // Update to current highest bid if it exists
    }

    return $current_bid;
}

function calculateMustBid($highest_bid, $minimum_price_interval) {
    return $highest_bid + $minimum_price_interval;
}

function hasUserPlacedBid($conn, $user_id, $product_id) {
    $hasBidQuery = "
    SELECT COUNT(*) AS bid_count FROM bids 
    WHERE user_id = $user_id AND product_id = $product_id
    ";
    $hasBidResult = mysqli_query($conn, $hasBidQuery);
    $hasBidData = mysqli_fetch_assoc($hasBidResult);
    
    return $hasBidData['bid_count'] > 0; // true if user has placed at least one bid
}

