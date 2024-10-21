<?php
session_start();
include '../includes/connection.php'; // Ensure the database connection is included

function fetchProductDetails($conn, $product_id) {
    $query = "
    SELECT p.*, 
           pi.image_url, 
           a.start_date, 
           a.end_date, 
           a.status,
           c.category_name
    FROM product p
    JOIN product_images pi ON pi.product_id = p.product_id
    JOIN auctions a ON a.product_id = p.product_id
    JOIN category c ON c.category_id = p.category_id
    WHERE p.product_id = ?
    LIMIT 1
    ";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function fetchExtraDetails($conn, $product_id, $category_id) {
    $extra_details = [];
    if ($category_id == 1) {
        $extra_query = "SELECT artist, year_created, technique FROM paintings WHERE product_id = ?";
    } elseif ($category_id == 2) {
        $extra_query = "SELECT material, gemstones, weight FROM jewelry WHERE product_id = ?";
    } elseif ($category_id == 3) {
        $extra_query = "SELECT origin, historical_period, conditionn FROM antiques WHERE product_id = ?";
    }

    if (isset($extra_query)) {
        $stmt = $conn->prepare($extra_query);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $extra_details = $stmt->get_result()->fetch_assoc();
    }
    
    return $extra_details;
}

if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    $product = fetchProductDetails($conn, $product_id);

    if ($product) {
        $category_id = $product['category_id'];
        $extra_details = fetchExtraDetails($conn, $product_id, $category_id);
    } else {
        echo "Product not found.";
        exit();
    }
} else {
    echo "Invalid product ID.";
    exit();
}

// Handle the bid submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['place_bid'])) {
    $bid_amount = $_POST['bid_amount'];
    // Check if the bid amount is higher than the current highest bid
    if ($bid_amount > $product['starting_bid']) { // You may want to compare with the current highest bid if applicable
        // Insert the bid into the database (assuming you have a bids table)
        $stmt = $conn->prepare("INSERT INTO bids (product_id, user_id, bid_amount) VALUES (?, ?, ?)");
        $user_id = $_SESSION['user_id']; // Assuming user ID is stored in session
        $stmt->bind_param("iii", $product_id, $user_id, $bid_amount);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "<div class='alert alert-success'>Bid placed successfully!</div>";
        } else {
            echo "<div class='alert alert-danger'>Failed to place bid. Please try again.</div>";
        }
    } else {
        echo "<div class='alert alert-warning'>Your bid must be higher than the starting bid.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['product_name']); ?> - Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Online Auction</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
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

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <div class="card border-0">
                    <img src="<?php echo htmlspecialchars($product['image_url']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product['product_name']); ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($product['product_name']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($product['description']); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0">
                    <div class="card-body">
                        <h5 class="card-title">Auction Information</h5>
                        <p class="card-text"><strong>Category:</strong> <?php echo htmlspecialchars($product['category_name']); ?></p>
                        <p class="card-text"><strong>Starting Bid:</strong> $<?php echo htmlspecialchars($product['starting_bid']); ?></p>
                        <p class="card-text"><strong>Minimum Interval:</strong> $<?php echo htmlspecialchars($product['minimum_price_interval']); ?></p>
                        <p class="card-text"><strong>Status:</strong> <?php echo htmlspecialchars($product['status']); ?></p>

                        <!-- Display category-specific details -->
                        <?php if ($category_id == 1): ?>
                            <h5 class="card-title mt-4">Painting Details</h5>
                            <p class="card-text"><strong>Artist:</strong> <?php echo htmlspecialchars($extra_details['artist']); ?></p>
                            <p class="card-text"><strong>Year Created:</strong> <?php echo htmlspecialchars($extra_details['year_created']); ?></p>
                            <p class="card-text"><strong>Technique:</strong> <?php echo htmlspecialchars($extra_details['technique']); ?></p>
                        <?php elseif ($category_id == 3): ?>
                            <h5 class="card-title mt-4">Antique Details</h5>
                            <p class="card-text"><strong>Origin:</strong> <?php echo htmlspecialchars($extra_details['origin']); ?></p>
                            <p class="card-text"><strong>Historical Period:</strong> <?php echo htmlspecialchars($extra_details['historical_period']); ?></p>
                            <p class="card-text"><strong>Condition:</strong> <?php echo htmlspecialchars($extra_details['conditionn']); ?></p>
                        <?php elseif ($category_id == 2): ?>
                            <h5 class="card-title mt-4">Jewelry Details</h5>
                            <p class="card-text"><strong>Material:</strong> <?php echo htmlspecialchars($extra_details['material']); ?></p>
                            <p class="card-text"><strong>Gemstones:</strong> <?php echo htmlspecialchars($extra_details['gemstones']); ?></p>
                            <p class="card-text"><strong>Weight:</strong> <?php echo htmlspecialchars($extra_details['weight']); ?> g</p>
                        <?php endif; ?>

                        <!-- Place Bid Form -->
                            <button name="place_bid" class="btn btn-primary">Place Bid</button>
                       
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
