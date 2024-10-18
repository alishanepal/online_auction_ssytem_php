<?php
include '../includes/connection.php';

// Query to fetch product report data
$productReportQuery = "
    SELECT 
        p.product_id, 
        p.product_name, 
        p.starting_bid, 
        p.reserve_price, 
        p.description, 
        p.keywords, 
        c.category_name, 
        sc.subcategory_name, 
        a.start_date, 
        a.end_date, 
        a.status,          -- Include status
        pi.image_url,
        pt.artist, 
        pt.technique, 
        pt.year_created,
        j.material, 
        j.weight, 
        j.gemstones
    FROM 
        product p
    LEFT JOIN 
        category c ON p.category_id = c.category_id
    LEFT JOIN 
        subcategory sc ON sc.product_id = p.product_id

    LEFT JOIN 
        auctions a ON p.product_id = a.product_id
    LEFT JOIN 
        product_images pi ON p.product_id = pi.product_id
    LEFT JOIN 
        paintings pt ON p.product_id = pt.product_id
    LEFT JOIN 
        jewelry j ON p.product_id = j.product_id
";

// Execute the query
$productReportResult = mysqli_query($conn, $productReportQuery);

if (!$productReportResult) {
    die('Error executing query: ' . mysqli_error($conn));
}

$jewelryItems = [];

// Filter for jewelry items only
while ($row = mysqli_fetch_assoc($productReportResult)) {
    if ($row['category_name'] === 'Jewelry') {
        $jewelryItems[] = $row; // Add to jewelry array
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jewelry Gallery</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card {
            height: 100%; /* Make sure the cards take full height */
        }
        .card-img-top {
            height: 200px; /* Fixed height for image */
            object-fit: cover; /* Make sure image fits without stretching */
        }
        .card-body {
            display: flex;
            flex-direction: column;
            justify-content: space-between; /* Ensure content is spaced out evenly */
        }
        .card-title {
            font-size: 1.2rem; /* Adjust title size */
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2>Jewelry</h2>
        <div class="row">
            <?php if (empty($jewelryItems)): ?>
                <p>No jewelry items found.</p>
            <?php else: ?>
                <?php foreach ($jewelryItems as $jewelry): ?>
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <img src="<?php echo htmlspecialchars($jewelry['image_url']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($jewelry['product_name']); ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($jewelry['product_name']); ?></h5>
                                <p class="card-text">Status: 
                                    <span class="<?php
                                        // Set status color
                                        switch (htmlspecialchars($jewelry['status'])) {
                                            case 'live':
                                                echo 'text-success';
                                                break;
                                            case 'closed':
                                                echo 'text-danger';
                                                break;
                                            case 'upcoming':
                                                echo 'text-warning';
                                                break;
                                            default:
                                                echo 'text-secondary';
                                        }
                                    ?>">
                                        <?php echo htmlspecialchars($jewelry['status']); ?>
                                    </span>
                                </p>
                                <?php if (!empty($jewelry['material'])): ?>
                                    <p class="card-text"><strong>Material:</strong> <?php echo htmlspecialchars($jewelry['material']); ?></p>
                                <?php endif; ?>
                                <?php if (!empty($jewelry['weight'])): ?>
                                    <p class="card-text"><strong>Weight:</strong> <?php echo htmlspecialchars($jewelry['weight']); ?> g</p>
                                <?php endif; ?>
                                <?php if (!empty($jewelry['gemstones'])): ?>
                                    <p class="card-text"><strong>Gemstones:</strong> <?php echo htmlspecialchars($jewelry['gemstones']); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>

<?php
mysqli_close($conn); // Close the database connection
?>
