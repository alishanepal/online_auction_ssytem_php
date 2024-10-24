<?php
include '../includes/connection.php';
include 'index_flex.php';

// Query to fetch product report data
$productReportQuery = "
    SELECT 
        p.product_id, 
        p.product_name, 
        a.status,          -- Include status
        pi.image_url
    FROM 
        product p
    LEFT JOIN 
        auctions a ON p.product_id = a.product_id
    LEFT JOIN 
        product_images pi ON p.product_id = pi.product_id
    WHERE 
        p.category_id IN (SELECT category_id FROM category WHERE category_name = 'Antiques') -- Filter by antiques
";

// Execute the query
$productReportResult = mysqli_query($conn, $productReportQuery);

if (!$productReportResult) {
    die('Error executing query: ' . mysqli_error($conn));
}

$antiques = [];

// Fetch antiques items
while ($row = mysqli_fetch_assoc($productReportResult)) {
    $antiques[$row['status']][] = $row; // Group by status
}

// Define the order of statuses
$statusOrder = ['upcoming', 'live', 'closed'];
?>

<link rel="stylesheet" href="../public/css/cards.css">

<div class="container mt-5">
    <h2>Antiques</h2>
    <div class="auctions">
        <?php foreach ($statusOrder as $status): ?>
            <?php if (isset($antiques[$status]) && !empty($antiques[$status])): ?>
                
                <?php foreach ($antiques[$status] as $antique): ?>
                    <a href="product_details.php?product_id=<?php echo htmlspecialchars($antique['product_id']); ?>" class="card mb-4">
                        <img src="<?php echo htmlspecialchars($antique['image_url']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($antique['product_name']); ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($antique['product_name']); ?></h5> <!-- Product name -->
                            <p class="status">Status: 
                                <span class="<?php
                                    // Set status color
                                    switch (htmlspecialchars($antique['status'])) {
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
                                    <?php echo htmlspecialchars($antique['status']); ?>
                                </span>
                            </p>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>
</html>

<?php
mysqli_close($conn); // Close the database connection
?>
