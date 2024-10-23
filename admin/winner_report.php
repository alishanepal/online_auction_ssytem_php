<?php

include '../includes/connection.php'; // Include DB connection
include 'dashboard_flex.php'; // Include dashboard layout

/**
 * Fetch the winners report by joining the necessary tables.
 */
function getWinnersReport($conn) {
    $query = "
        SELECT 
            u.user_id, 
            u.username, 
            u.profile_photo, 
            p.product_name, 
            pi.image_url, 
            w.winning_bid_id, 
            w.winning_amount, 
            w.won_time 
        FROM 
            winners w
        JOIN 
            users u ON w.user_id = u.user_id
        JOIN 
            product p ON w.product_id = p.product_id
        JOIN 
            product_images pi ON p.product_id = pi.product_id
        JOIN 
            bids b ON w.winning_bid_id = b.bid_id
        ORDER BY 
            w.won_time DESC";

    $stmt = $conn->prepare($query);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// Main logic
$winnersReport = getWinnersReport($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Winners Report</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center mb-4"> Winners Report</h2>

    <?php if ($winnersReport): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>User ID</th>
                        <th>Username</th>
                        <th>Profile Photo</th>
                        <th>Product Name</th>
                        <th>Product Image</th>
                        <th>Winning Bid ID</th>
                        <th>Winning Amount</th>
                        <th>Won Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($winnersReport as $winner): ?>
                        <tr>
                            <td><?= htmlspecialchars($winner['user_id']) ?></td>
                            <td><?= htmlspecialchars($winner['username']) ?></td>
                            <td>
                                <img src="<?= htmlspecialchars($winner['profile_photo']) ?>" 
                                     alt="Profile" class="rounded-circle" width="50" height="50">
                            </td>
                            <td><?= htmlspecialchars($winner['product_name']) ?></td>
                            <td>
                                <img src="<?= htmlspecialchars($winner['image_url']) ?>" 
                                     alt="Product" class="img-thumbnail" width="50" height="50">
                            </td>
                            <td><?= htmlspecialchars($winner['winning_bid_id']) ?></td>
                            <td>$<?= number_format($winner['winning_amount'], 2) ?></td>
                            <td><?= htmlspecialchars(date('Y-m-d H:i:s', strtotime($winner['won_time']))) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-warning text-center">
            No winners found.
        </div>
    <?php endif; ?>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
