<?php
session_start();
include '../includes/connection.php'; // Ensure the database connection is included

// Get the product_id from the URL
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    // Fetch basic product details from product, auctions, category, and product_images tables
    $query = "
    SELECT p.*,  -- Select all fields from the product table
           pi.image_url, 
           a.start_date, 
           a.end_date, 
           a.status,
           c.category_name
    FROM product p
    JOIN product_images pi ON pi.product_id = p.product_id
    JOIN auctions a ON a.product_id = p.product_id
    JOIN category c ON c.category_id = p.category_id
    WHERE p.product_id = $product_id
    LIMIT 1
";


    $result = mysqli_query($conn, $query);

    // Check if a product is found
    if (mysqli_num_rows($result) > 0) {
        $product = mysqli_fetch_assoc($result);

        // Initialize the variable for the current bid
        $current_bid = $product['starting_bid'];// Start with the starting bid
        $interval= $product['minimum_price_interval'];

        // Check if the user has placed a bid only if user_id is set
        $hasBid = false; // Default to false
        if (isset($_SESSION['user_id'])) { 
            $user_id = $_SESSION['user_id']; 
            $hasBidQuery = "
            SELECT COUNT(*) AS bid_count FROM bids 
            WHERE user_id = $user_id AND product_id = $product_id
            ";
            $hasBidResult = mysqli_query($conn, $hasBidQuery);
            $hasBidData = mysqli_fetch_assoc($hasBidResult);
            $hasBid = $hasBidData['bid_count'] > 0; // true if user has placed at least one bid
        }

        // Fetch the current highest bid for the product
        $highestBidQuery = "SELECT MAX(bid_amount) AS highest_bid FROM bids WHERE product_id = $product_id";
        $highestBidResult = mysqli_query($conn, $highestBidQuery);
        $row = mysqli_fetch_assoc($highestBidResult);
        $highest_bid = $row['highest_bid'] ?? 0; // Default to 0 if there are no bids

        // Update the current_bid to the highest bid if available
        if ($highest_bid > 0) {
            $current_bid = $highest_bid; // Update to current highest bid if it exists
            $must_bid=$highest_bid+$interval;
        }

        // Fetch additional details based on the category (paintings, antiques, or jewelry)
        $category_id = $product['category_id'];
        $extra_details = [];

        // Adjust the queries based on category_id and the corrected fields
        if ($category_id == 1) { 
            $extra_query = "SELECT artist, year_created, technique FROM paintings WHERE product_id = $product_id";
        } elseif ($category_id == 2) { 
            $extra_query = "SELECT material, gemstones, weight FROM jewelry WHERE product_id = $product_id";
        } elseif ($category_id == 3) { 
            $extra_query = "SELECT origin, historical_period, conditionn FROM antiques WHERE product_id = $product_id";
        }

        // Execute the extra query for specific product category
        if (isset($extra_query)) {
            $extra_result = mysqli_query($conn, $extra_query);
            if (mysqli_num_rows($extra_result) > 0) {
                $extra_details = mysqli_fetch_assoc($extra_result);
            }
        }

        // Fetch bid history for the product
        $bidHistoryQuery = "
        SELECT bid_amount, bid_time
        FROM bids
        WHERE product_id = $product_id
        ORDER BY bid_time DESC
        ";

        // Execute the query
        $bidHistoryResult = mysqli_query($conn, $bidHistoryQuery);
        if (!$bidHistoryResult) {
            echo "Error fetching bid history: " . mysqli_error($conn);
        }
        $bidHistory = [];
        while ($row = mysqli_fetch_assoc($bidHistoryResult)) {
            $bidHistory[] = $row;
        }

        // Fetch bid count to show in the button
        $bidCountQuery = "SELECT COUNT(*) AS bid_count FROM bids WHERE product_id = $product_id";
        $bidCountResult = mysqli_query($conn, $bidCountQuery);
        $bidCountData = mysqli_fetch_assoc($bidCountResult);
        $bid_count = $bidCountData['bid_count'];
    } else {
        echo "Product not found.";
        exit();
    }
} else {
    echo "Invalid product ID.";
    exit();
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $product['product_name']; ?> - Details</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- External CSS -->
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Online Auction</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php">Home</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Product Details Section -->
    <div class="container mt-5">
        <div class="row">
            <!-- Left Column: Product Image -->
            <div class="col-md-6">
                <div class="card">
                    <img src="<?php echo $product['image_url']; ?>" class="card-img-top"
                        alt="<?php echo $product['product_name']; ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $product['product_name']; ?></h5>

                        <p class="card-text"><?php echo $product['description']; ?></p>
                    </div>

                </div>
            </div>

            <!-- Right Column: Product Details -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Auction Information</h5>
                        <p class="card-text"><strong>Category:</strong> <?php echo $product['category_name']; ?></p>
                        <p class="card-text"><strong>Starting Bid:</strong> $<?php echo $product['starting_bid']; ?></p>
                        <p class="card-text"><strong>minimum interval:</strong> $<?php echo $product['minimum_price_interval']; ?></p>
                        <p class="card-text"><strong>Status:</strong> <?php echo $product['status']; ?></p>

                        <!-- Display category-specific details -->
                        <?php if ($category_id == 1): ?>
                            <h5 class="card-title mt-4">Painting Details</h5>
                            <p class="card-text"><strong>Artist:</strong> <?php echo $extra_details['artist']; ?></p>
                            <p class="card-text"><strong>Year Created:</strong>
                                <?php echo $extra_details['year_created']; ?></p>
                            <p class="card-text"><strong>Technique:</strong> <?php echo $extra_details['technique']; ?></p>
                        <?php elseif ($category_id == 3): ?>
                            <h5 class="card-title mt-4">Antique Details</h5>
                            <p class="card-text"><strong>Origin:</strong> <?php echo $extra_details['origin']; ?></p>
                            <p class="card-text"><strong>Historical Period:</strong>
                                <?php echo $extra_details['historical_period']; ?></p>
                            <p class="card-text"><strong>Condition:</strong> <?php echo $extra_details['conditionn']; ?></p>
                        <?php elseif ($category_id == 2): ?>
                            <h5 class="card-title mt-4">Jewelry Details</h5>
                            <p class="card-text"><strong>Material:</strong> <?php echo $extra_details['material']; ?></p>
                            <p class="card-text"><strong>Gemstones:</strong> <?php echo $extra_details['gemstones']; ?></p>
                            <p class="card-text"><strong>Weight:</strong> <?php echo $extra_details['weight']; ?> g</p>
                        <?php endif; ?>


<!-- Bid Button and Input Field -->
<?php if ($product['status'] == 'live'): ?>
    <div>
        <strong>Current Auction Price: $<?php echo number_format($current_bid, 2); ?></strong>
    </div>

    <?php if (!isset($_SESSION['user_id'])): ?>
        <!-- User not logged in -->
        <button class="btn btn-primary" id="placeBidButton" onclick="promptLogin()">Place Bid</button>

    <?php else: ?>
        <!-- User logged in -->
        <?php if (!$hasBid): ?>
            <button class="btn btn-primary" id="placeBidButton" onclick="confirmParticipation()">Place Bid</button>
            <div id="bidInputContainer" style="display: none; margin-top: 10px;">
                <input type="number" id="bidAmount" placeholder="Your bid must be equal or more than $<?php echo number_format($must_bid, 2); ?>"
                    class="form-control" data-current-bid="<?php echo $current_bid; ?>"
       data-minimum-price-interval="<?php echo $interval; ?>">
                <button class="btn btn-success mt-2"
                    onclick="submitBid(<?php echo $_SESSION['user_id']; ?>, <?php echo $product_id; ?>)">Submit Bid</button>
            </div>
        <?php else: ?>
            <!-- User has already placed a bid -->
            <div id="bidInputContainer" style="margin-top: 10px;">
                <input type="number" id="bidAmount" placeholder="Your bid must be equal or more than $<?php echo number_format($must_bid, 2); ?>"
                    class="form-control" data-current-bid="<?php echo $current_bid; ?>"            
       data-minimum-price-interval="<?php echo $interval; ?>">
                <button class="btn btn-success mt-2"
                    onclick="submitBid(<?php echo $_SESSION['user_id']; ?>, <?php echo $product_id; ?>)">Submit Bid</button>
            </div>
        <?php endif; ?>

    <?php endif; ?>

<?php else: ?>
    <button class="btn btn-secondary" disabled>Bidding Closed</button>
<?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Button to Open Bid History Modal -->
        <button type="button" class="btn btn-info mt-4" data-bs-toggle="modal" data-bs-target="#bidHistoryModal">
            View Bid History (<?php echo $bid_count; ?>)
        </button>

        <!-- Bid History Modal -->
        <div class="modal fade" id="bidHistoryModal" tabindex="-1" aria-labelledby="bidHistoryModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="bidHistoryModalLabel">Bid History</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Bid Amount</th>
                                    <th scope="col">Bid Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($bidHistory) > 0): ?>
                                    <?php foreach ($bidHistory as $bid): ?>
                                        <tr>
                                            <td>$<?php echo number_format($bid['bid_amount'], 2); ?></td>
                                            <td><?php echo date('Y-m-d H:i:s', strtotime($bid['bid_time'])); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="2">No bids placed yet.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>

    <!-- Bootstrap JS and Popper.js -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    <script src="../public/js/product_details.js"></script>
</body>

</html>