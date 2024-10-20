<?php
session_start();
include '../includes/connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the parameters from the POST request
    $user_id = $_POST['user_id'];
    $product_id = $_POST['product_id'];
    $bid_amount = $_POST['bid_amount'];

    // Perform validation on the bid amount, user ID, and product ID if necessary

    // Example query to insert the bid into the database
    $query = "INSERT INTO bids (user_id, product_id, bid_amount) VALUES ('$user_id', '$product_id', '$bid_amount')";
    
    if (mysqli_query($conn, $query)) {
        echo "Bid placed successfully!";
    } else {
        echo "Error placing bid: " . mysqli_error($conn);
    }
}
