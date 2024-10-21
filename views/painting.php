<?php
include '../includes/connection.php';
include 'index_flex.php';


// Query to fetch paintings data
$productReportQuery = "
    SELECT 
        p.product_id, 
        p.product_name, 
        a.status, 
        pi.image_url, 
        c.category_name
    FROM 
        product p
    LEFT JOIN 
        category c ON p.category_id = c.category_id
    LEFT JOIN 
        auctions a ON p.product_id = a.product_id
    LEFT JOIN 
        product_images pi ON p.product_id = pi.product_id
    WHERE 
        c.category_name = 'Paintings'
";

// Execute the query
$productReportResult = mysqli_query($conn, $productReportQuery);

if (!$productReportResult) {
    die('Error executing query: ' . mysqli_error($conn));
}

$paintings = [];

// Fetch paintings
while ($row = mysqli_fetch_assoc($productReportResult)) {
    $paintings[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paintings Gallery</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../public/css/cards.css"> <!-- External CSS -->
</head>
<body>
    <div class="container mt-5">
        <h2>Paintings</h2>
        <div class="auctions">
            <?php if (empty($paintings)): ?>
                <p>No paintings found.</p>
            <?php else: ?>
                <?php foreach ($paintings as $painting): ?>
                    <a href="product_details.php?product_id=<?php echo htmlspecialchars($painting['product_id']); ?>" class="card mb-4">
                        <img src="<?php echo htmlspecialchars($painting['image_url']); ?>" alt="<?php echo htmlspecialchars($painting['product_name']); ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($painting['product_name']); ?></h5>
                            <p class="status">Status: 
                                <span class="<?php
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
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    
</body>
</html>

<?php
mysqli_close($conn); // Close the database connection
?>
