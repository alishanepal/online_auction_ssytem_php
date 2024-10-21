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
        at.origin, 
        at.historical_period, 
        at.conditionn, 
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
        antiques at ON p.product_id = at.product_id
    LEFT JOIN 
        jewelry j ON p.product_id = j.product_id
";

// Execute the query
$productReportResult = mysqli_query($conn, $productReportQuery);

if (!$productReportResult) {
    die('Error executing query: ' . mysqli_error($conn));
}

// Initialize an associative array to group products by category
$productsByCategory = [];

// Organize products by category
while ($row = mysqli_fetch_assoc($productReportResult)) {
    $categoryName = $row['category_name'];
    $productsByCategory[$categoryName][] = $row; // Group by category
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Report - Online Auction System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
         <!-- Navigation Button to Dashboard -->
         <a href="dashboard.php" class="btn btn-success mb-2">Back to Dashboard</a>
        <h2>Product Report</h2>
        
       

        <?php
        // Loop through each category and display its products
        foreach ($productsByCategory as $category => $products) {
            echo '<h3>' . htmlspecialchars($category) . '</h3>'; // Category Header
            echo '<table class="table">';
            echo '<thead><tr>
                    <th>Product ID</th>
                    <th>Product Name</th>
                    <th>Starting Bid</th>
                    <th>Reserve Price</th>
                    <th>Description</th>
                    <th>Keywords</th>
                    <th>Subcategory</th>
                    <th>Image</th>
                    <th>Status</th>  <!-- New Status Column -->
                    <th>Details</th>
                  </tr></thead>';
            echo '<tbody>';

            foreach ($products as $product) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($product['product_id']) . '</td>';
                echo '<td><img src="' . htmlspecialchars($product['image_url']) . '" alt="' . htmlspecialchars($product['product_name']) . '" width="100"></td>';
                
                echo '<td>' . htmlspecialchars($product['product_name']) . '</td>';
                echo '<td>' . htmlspecialchars($product['starting_bid']) . '</td>';
                echo '<td>' . htmlspecialchars($product['reserve_price']) . '</td>';
                echo '<td>' . htmlspecialchars($product['description']) . '</td>';
                echo '<td>' . htmlspecialchars($product['keywords']) . '</td>';
                echo '<td>' . htmlspecialchars($product['subcategory_name']) . '</td>';
               
                // Determine status class
                $statusClass = '';
                switch (htmlspecialchars($product['status'])) {
                    case 'live':
                        $statusClass = 'text-success'; // Green
                        break;
                    case 'closed':
                        $statusClass = 'text-danger'; // Red
                        break;
                    case 'upcoming':
                        $statusClass = 'text-warning'; // Yellow
                        break;
                    default:
                        $statusClass = 'text-secondary'; // Default color if needed
                }
                
                echo '<td><span class="' . $statusClass . '">' . htmlspecialchars($product['status']) . '</span></td>';  // Display Status
                echo '<td>';
                echo '<button class="btn btn-sm" onclick="toggleDetails(' . htmlspecialchars($product['product_id']) . ')" style="background-color: #ff7f50; color: white; border-radius: 5px; transition: background-color 0.3s;">View Details</button>';
                echo '<div id="details-' . htmlspecialchars($product['product_id']) . '" style="display:none; margin-top: 10px; padding: 10px; border: 1px solid #ccc; border-radius: 5px; background-color: #f8f9fa;">';
            
                // Display details based on product type
                if (!empty($product['artist'])) {
                    echo '<p><strong>Artist:</strong> ' . htmlspecialchars($product['artist']) . '</p>';
                    echo '<p><strong>Technique:</strong> ' . htmlspecialchars($product['technique']) . '</p>';
                    echo '<p><strong>Year Created:</strong> ' . htmlspecialchars($product['year_created']) . '</p>';
                }
                if (!empty($product['origin'])) {
                    echo '<p><strong>Origin:</strong> ' . htmlspecialchars($product['origin']) . '</p>';
                    echo '<p><strong>Historical Period:</strong> ' . htmlspecialchars($product['historical_period']) . '</p>';
                    echo '<p><strong>Condition:</strong> ' . htmlspecialchars($product['conditionn']) . '</p>';
                }
                if (!empty($product['material'])) {
                    echo '<p><strong>Material:</strong> ' . htmlspecialchars($product['material']) . '</p>';
                    echo '<p><strong>Weight:</strong> ' . htmlspecialchars($product['weight']) . '</p>';
                    echo '<p><strong>Gemstones:</strong> ' . htmlspecialchars($product['gemstones']) . '</p>';
                }
            
                echo '</div></td>';
                echo '</tr>';
            }
            echo '</tbody></table>';
        }
        ?>

        <script>
        function toggleDetails(productId) {
            var detailsDiv = document.getElementById("details-" + productId);
            if (detailsDiv.style.display === "none") {
                detailsDiv.style.display = "block";
            } else {
                detailsDiv.style.display = "none";
            }
        }
        </script>

        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    </div>
</body>
</html>
