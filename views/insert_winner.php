<?php
include '../includes/connection.php'; // Include DB connection

/**
 * Include the bidding algorithm functions
 */
include 'bidding_algorithm.php'; // Assuming your bidding functions are saved here

/**
 * Function to check if a winner already exists for the given product and user
 */
function winnerExists($conn, $user_id, $product_id) {
    $checkSql = $conn->prepare("
        SELECT COUNT(*) AS total 
        FROM winners 
        WHERE user_id = ? AND product_id = ?
    ");
    $checkSql->bind_param("ii", $user_id, $product_id);
    $checkSql->execute();

    $result = $checkSql->get_result()->fetch_assoc();
    $checkSql->close();

    // Return true if the winner entry already exists
    return isset($result['total']) && $result['total'] > 0;
}

/**
 * Function to insert a winner into the winners table
 */
function insertWinner($conn, $user_id, $product_id, $winning_bid_id, $winning_amount, $won_time) {
    if (!winnerExists($conn, $user_id, $product_id)) {
        $insertSql = $conn->prepare("
            INSERT INTO winners (user_id, product_id, winning_bid_id, winning_amount, won_time) 
            VALUES (?, ?, ?, ?, ?)
        ");
        $insertSql->bind_param("iiids", $user_id, $product_id, $winning_bid_id, $winning_amount, $won_time);

        if (!$insertSql->execute()) {
            echo "Error inserting winner: " . $insertSql->error;
        } else {
            echo "Winner inserted: User ID $user_id, Product ID $product_id\n";
        }

        $insertSql->close();
    } else {
        echo "Winner already exists for User ID $user_id and Product ID $product_id.\n";
    }
}

/**
 * Main logic to determine winners and insert them into the winners table
 */
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    $product = getProductDetails($conn, $product_id); // Function from bidding_algorithm.php

    if ($product) {
        $bidHistory = getBidHistory($conn, $product_id); // Function from bidding_algorithm.php
        $isClosed = $product['status'] === 'closed';

        // Get bid history with winner status
        $result = getBidHistoryWithWinners($bidHistory, $product['end_date'], $isClosed);
        $bidHistoryWithWinners = $result['bidHistory'];
        $winningBid = $result['winningBid'];

        // Insert winners into the winners table
        foreach ($bidHistoryWithWinners as $bid) {
            if ($bid['isWinner']) {
                $user_id = $bid['user_id'];
                $winning_bid_id = $bid['bid_id'];
                $winning_amount = $bid['bid_amount'];
                $won_time = $bid['bid_time'];

                // Call function to insert winner
                insertWinner($conn, $user_id, $product_id, $winning_bid_id, $winning_amount, $won_time);
            }
        }

        echo "Winners have been successfully inserted into the winners table.";
    } else {
        die("Product not found.");
    }
} else {
    die("Invalid product ID.");
}

// Close the database connection
$conn->close();
