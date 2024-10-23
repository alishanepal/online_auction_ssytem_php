<?php
session_start();
include '../includes/connection.php'; // Include DB connection

/**
 * Fetch product details including images, auctions, and category data.
 */
function getProductDetails($conn, $product_id) {
    $query = "
        SELECT p.*, pi.image_url, a.start_date, a.end_date, a.status, c.category_name 
        FROM product p
        JOIN product_images pi ON pi.product_id = p.product_id
        JOIN auctions a ON a.product_id = p.product_id
        JOIN category c ON c.category_id = p.category_id
        WHERE p.product_id = ? LIMIT 1";
        
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $product_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

/**
 * Check if the user has already placed a bid.
 */
function hasUserBid($conn, $user_id, $product_id) {
    $query = "SELECT COUNT(*) AS bid_count FROM bids WHERE user_id = ? AND product_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ii', $user_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    return $result['bid_count'] > 0;
}

/**
 * Get the highest bid for the product.
 */
function getHighestBid($conn, $product_id) {
    $query = "SELECT MAX(bid_amount) AS highest_bid FROM bids WHERE product_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $product_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    return $result['highest_bid'] ?? 0;
}

/**
 * Get the previous highest bid.
 */
function getPreviousHighestBid($conn, $product_id) {
    $query = "
        SELECT bid_amount 
        FROM bids 
        WHERE product_id = ? 
        ORDER BY bid_time DESC 
        LIMIT 1, 1"; // Second latest bid
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $product_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    return $result['bid_amount'] ?? 0;
}

/**
 * Calculate the dynamic interval.
 */
function calculateDynamicInterval($currentBid, $previousBid, $originalInterval) {
    $threshold = 0.2; // 20% increase threshold

    // If there are no previous bids, reset to the original interval
    if ($previousBid == 0) {
        return $originalInterval; 
    }

    $bidDifference = $currentBid - $previousBid;

    // Adjust interval only if the bid increase exceeds the threshold
    if ($bidDifference >= ($previousBid * $threshold)) {
        if ($currentBid < 500) {
            return $originalInterval;
        } elseif ($currentBid < 1000) {
            return $originalInterval * 2;
        } else {
            return $originalInterval * 5;
        }
    }
    return $originalInterval; // No change if the increase is small
}

/**
 * Fetch category-specific extra details.
 */
function getExtraDetails($conn, $category_id, $product_id) {
    switch ($category_id) {
        case 1: // Paintings
            $query = "SELECT artist, year_created, technique FROM paintings WHERE product_id = ?";
            break;
        case 2: // Jewelry
            $query = "SELECT material, gemstones, weight FROM jewelry WHERE product_id = ?";
            break;
        case 3: // Antiques
            $query = "SELECT origin, historical_period, conditionn FROM antiques WHERE product_id = ?";
            break;
        default:
            return [];
    }
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $product_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

/**
 * Get bid history for a product.
 */
function getBidHistory($conn, $product_id) {
    $query = "SELECT user_id, bid_amount, bid_time FROM bids WHERE product_id = ? ORDER BY bid_time DESC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $product_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

/**
 * Get the number of bids placed on a product.
 */
function getBidCount($conn, $product_id) {
    $query = "SELECT COUNT(*) AS bid_count FROM bids WHERE product_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $product_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    return $result['bid_count'];
}

/**
 * Get bid history with winner status.
 */
function getBidHistoryWithWinners($bidHistory, $endDate, $isClosed) {
    $winningBid = 0;
    $endTime = strtotime($endDate);

    foreach ($bidHistory as $bid) {
        $bidTime = strtotime($bid['bid_time']);
        $bidAmount = floatval($bid['bid_amount']);
        if ($bidTime <= $endTime && $bidAmount > $winningBid) {
            $winningBid = $bidAmount;
        }
    }

    foreach ($bidHistory as &$bid) {
        $bid['isWinner'] = $isClosed && floatval($bid['bid_amount']) === $winningBid;
    }

    return [
        'bidHistory' => $bidHistory,
        'winningBid' => $winningBid,
    ];
}

// Main logic
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    $product = getProductDetails($conn, $product_id);

    if ($product) {
        $category_id = $product['category_id'];
        $extra_details = getExtraDetails($conn, $category_id, $product_id);
        $current_bid = max($product['starting_bid'], getHighestBid($conn, $product_id));
        $previous_bid = getPreviousHighestBid($conn, $product_id);
        $original_interval = $product['minimum_price_interval'];

        // Recalculate dynamic interval based on the current and previous bids
        $dynamic_interval = calculateDynamicInterval($current_bid, $previous_bid, $original_interval);
        $must_bid = $current_bid + $dynamic_interval;

        // Check if the user has placed a bid
        $hasBid = isset($_SESSION['user_id']) && hasUserBid($conn, $_SESSION['user_id'], $product_id);
        $bidHistory = getBidHistory($conn, $product_id);
        $bid_count = getBidCount($conn, $product_id);

        // Determine if the auction is closed
        $isClosed = $product['status'] === 'closed';
        $result = getBidHistoryWithWinners($bidHistory, $product['end_date'], $isClosed);
        $bidHistoryWithWinners = $result['bidHistory'];
        $winningBid = $result['winningBid'];

        $user_id = $_SESSION['user_id'] ?? null;
        $userIsWinner = false;

        if ($user_id) {
            foreach ($bidHistoryWithWinners as $bid) {
                if ($bid['isWinner'] && $bid['user_id'] == $user_id) {
                    $userIsWinner = true;
                    break;
                }
            }
        }
    } else {
        die("Product not found.");
    }
} else {
    die("Invalid product ID.");
}
