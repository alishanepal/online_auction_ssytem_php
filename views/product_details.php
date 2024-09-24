<?php
session_start();
include '../includes/connection.php'; // Ensure the database connection is included

// Get the product_id from the URL
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    // Fetch basic product details from product, auctions, category, and product_images tables
    $query = "
        SELECT p.product_name, p.starting_bid, p.description, p.keywords, p.category_id,
               pi.image_url, 
               a.start_date, a.end_date, a.status,
               c.category_name
        FROM product p
        JOIN product_images pi ON pi.product_id = p.product_id
        JOIN auctions a ON a.product_id = p.product_id
        JOIN category c ON c.category_id = p.category_id
        WHERE p.product_id = $product_id
        LIMIT 1
    ";

    // Execute the query
    $result = mysqli_query($conn, $query);

    // Check if a product is found
    if (mysqli_num_rows($result) > 0) {
        $product = mysqli_fetch_assoc($result);

        // Fetch additional details based on the category (paintings, antiques, or jewelry)
        $category_id = $product['category_id'];
        $extra_details = [];

        // Adjust the queries based on category_id and the corrected fields
        if ($category_id == 1) { // Assuming category_id = 1 is for paintings
            $extra_query = "SELECT artist, year_created, technique FROM paintings WHERE product_id = $product_id";
        } 
        elseif ($category_id == 2) { // Assuming category_id = 3 is for jewelry
            $extra_query = "SELECT material, gemstones, weight FROM jewelry WHERE product_id = $product_id";
        }elseif ($category_id == 3) { // Assuming category_id = 2 is for antiques
            $extra_query = "SELECT origin, historical_period, conditionn FROM antiques WHERE product_id = $product_id";
        } 
        

        // Execute the extra query for specific product category
        if (isset($extra_query)) {
            $extra_result = mysqli_query($conn, $extra_query);
            if (mysqli_num_rows($extra_result) > 0) {
                $extra_details = mysqli_fetch_assoc($extra_result);
            }
        }

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
    <link rel="stylesheet" href="../public/css/styles.css">
</head>
<body>
    <!-- Navbar -->
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

    <!-- Main Product Details Section -->
    <div class="container mt-5">
        <div class="row">
            <!-- Left Column: Product Image -->
            <div class="col-md-6">
                <div class="card">
                    <img src="<?php echo $product['image_url']; ?>" class="card-img-top" alt="<?php echo $product['product_name']; ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $product['product_name']; ?></h5>
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
                        <p class="card-text"><strong>Start Date:</strong> <?php echo $product['start_date']; ?></p>
                        <p class="card-text"><strong>End Date:</strong> <?php echo $product['end_date']; ?></p>
                        <p class="card-text"><strong>Status:</strong> <?php echo $product['status']; ?></p>
                        
                        <!-- Display category-specific details -->
                        <?php if ($category_id == 1): ?>
                            <h5 class="card-title mt-4">Painting Details</h5>
                            <p class="card-text"><strong>Artist:</strong> <?php echo $extra_details['artist']; ?></p>
                            <p class="card-text"><strong>Year Created:</strong> <?php echo $extra_details['year_created']; ?></p>
                            <p class="card-text"><strong>Technique:</strong> <?php echo $extra_details['technique']; ?></p>
                        <?php elseif ($category_id == 3): ?>
                            <h5 class="card-title mt-4">Antique Details</h5>
                            <p class="card-text"><strong>Origin:</strong> <?php echo $extra_details['origin']; ?></p>
                            <p class="card-text"><strong>Historical Period:</strong> <?php echo $extra_details['historical_period']; ?></p>
                            <p class="card-text"><strong>Condition:</strong> <?php echo $extra_details['conditionn']; ?></p>
                        <?php elseif ($category_id == 2 ): ?>
                            <h5 class="card-title mt-4">Jewelry Details</h5>
                            <p class="card-text"><strong>Material:</strong> <?php echo $extra_details['material']; ?></p>
                            <p class="card-text"><strong>Gemstones:</strong> <?php echo $extra_details['gemstones']; ?></p>
                            <p class="card-text"><strong>Weight:</strong> <?php echo $extra_details['weight']; ?> g</p>
                        <?php endif; ?>

                        <h5 class="card-title mt-4">Product Description</h5>
                        <p class="card-text"><?php echo $product['description']; ?></p>
                        <p class="card-text"><strong>Keywords:</strong> <?php echo $product['keywords']; ?></p>

                        <!-- If you want a bid button -->
                        <?php if ($product['status'] == 'live'): ?>
                            <a href="place_bid.php?product_id=<?php echo $product_id; ?>" class="btn btn-primary">Place Bid</a>
                        <?php else: ?>
                            <button class="btn btn-secondary" disabled>Bidding Closed</button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
