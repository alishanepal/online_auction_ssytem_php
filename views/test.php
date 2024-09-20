<?php
session_start();
include '../includes/connection.php'; // Ensure the database connection is included

// Query for Upcoming Auctions (status is 'upcoming')
$upcomingAuctions = mysqli_query($conn, "
    SELECT p.product_id, p.product_name, pi.image_url, a.start_date
    FROM auctions a
    JOIN product p ON p.product_id = a.product_id
    JOIN product_images pi ON pi.product_id = p.product_id
    WHERE a.status = 'upcoming'
");

// Query for Live Auctions (status is 'live')
$liveAuctions = mysqli_query($conn, "
    SELECT p.product_id, p.product_name, pi.image_url, a.end_date
    FROM auctions a
    JOIN product p ON p.product_id = a.product_id
    JOIN product_images pi ON pi.product_id = p.product_id
    WHERE a.status = 'live'
");

// Query for Closed Auctions (status is 'closed')
$closedAuctions = mysqli_query($conn, "
    SELECT p.product_id, p.product_name, pi.image_url, a.end_date
    FROM auctions a
    JOIN product p ON p.product_id = a.product_id
    JOIN product_images pi ON pi.product_id = p.product_id
    WHERE a.status = 'closed'
");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Auction System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="public/css/styles.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <!-- Same navbar as before -->
    </nav>

    <!-- Content -->
    <div class="container mt-5">
        <h1>Welcome to the Online Auction System</h1>
        <p>Explore our exciting auctions and stay tuned for upcoming events!</p>

        <!-- Upcoming Auctions Section -->
        <h2>Upcoming Auctions</h2>
        <div class="row">
            <?php while ($row = mysqli_fetch_assoc($upcomingAuctions)) { ?>
                <div class="col-md-3">
                    <div class="card">
                        <img src="<?php echo $row['image_url']; ?>" class="card-img-top" alt="<?php echo $row['product_name']; ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $row['product_name']; ?></h5>
                            <p class="card-text">Starts: <?php echo $row['start_date']; ?></p>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>

        <!-- Live Auctions Section -->
        <h2>Live Auctions</h2>
        <div class="row">
            <?php while ($row = mysqli_fetch_assoc($liveAuctions)) { ?>
                <div class="col-md-3">
                    <div class="card">
                        <img src="<?php echo $row['image_url']; ?>" class="card-img-top" alt="<?php echo $row['product_name']; ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $row['product_name']; ?></h5>
                            <p class="card-text">Ends: <?php echo $row['end_date']; ?></p>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>

        <!-- Closed Auctions Section -->
        <h2>Closed Auctions</h2>
        <div class="row">
            <?php while ($row = mysqli_fetch_assoc($closedAuctions)) { ?>
                <div class="col-md-3">
                    <div class="card">
                        <img src="<?php echo $row['image_url']; ?>" class="card-img-top" alt="<?php echo $row['product_name']; ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $row['product_name']; ?></h5>
                            <p class="card-text">Ended: <?php echo $row['end_date']; ?></p>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
