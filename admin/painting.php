<?php
include '../includes/connection.php';
// Ensure the database connection is included

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

$paintings = [];
$jewelryItems = [];

// Filter for paintings and jewelry
while ($row = mysqli_fetch_assoc($productReportResult)) {
    if ($row['category_name'] === 'Paintings') {
        $paintings[] = $row; // Add to paintings array
    } elseif ($row['category_name'] === 'Jewelry') {
        $jewelryItems[] = $row; // Add to jewelry array
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Art Gallery</title>
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
        <h2>Paintings</h2>
        <div class="row">
            <?php foreach ($paintings as $painting): ?>
                <div class="col-md-4">
                    <div class="card mb-4">
                        <img src="<?php echo htmlspecialchars($painting['image_url']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($painting['product_name']); ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($painting['product_name']); ?></h5>
                            <p class="card-text">Status: 
                                <span class="<?php
                                    // Set status color
                                    switch (htmlspecialchars($painting['status'])) {
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
                                    <?php echo htmlspecialchars($painting['status']); ?>
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>

<?php
mysqli_close($conn); // Close the database connection
?>
