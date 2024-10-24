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
        p.category_id IN (SELECT category_id FROM category WHERE category_name = 'Jewelry') -- Filter for jewelry
";

// Execute the query
$productReportResult = mysqli_query($conn, $productReportQuery);

if (!$productReportResult) {
    die('Error executing query: ' . mysqli_error($conn));
}

$jewelryItems = [];

// Fetch jewelry items
while ($row = mysqli_fetch_assoc($productReportResult)) {
    $jewelryItems[$row['status']][] = $row; // Group by status
}

// Define the order of statuses
$statusOrder = ['upcoming', 'live', 'closed'];
?>

<link rel="stylesheet" href="../public/css/cards.css">

<div class="container mt-5">
    <h2>Jewelry</h2>
    <div class="auctions"> <!-- Updated class name to match CSS -->
        <?php if (empty($jewelryItems)): ?>
            <p>No jewelry items found.</p>
        <?php else: ?>
            <?php foreach ($statusOrder as $status): ?>
                <?php if (isset($jewelryItems[$status]) && !empty($jewelryItems[$status])): ?>
                    
                    <?php foreach ($jewelryItems[$status] as $jewelry): ?>
                        <a href="product_details.php?product_id=<?php echo htmlspecialchars($jewelry['product_id']); ?>" class="card mb-4">
                            <img src="<?php echo htmlspecialchars($jewelry['image_url']); ?>" alt="<?php echo htmlspecialchars($jewelry['product_name']); ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($jewelry['product_name']); ?></h5>
                                <p class="status">Status: 
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
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
</body>
</html>

<?php
mysqli_close($conn); // Close the database connection
?>
