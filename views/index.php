<?php
include '../includes/connection.php'; // Ensure the database connection is included
include 'index_flex.php';

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
    <title>Auction Products</title>
    <link rel="stylesheet" href="../public/css/cards.css">

</head>
<body>
<h1>Live Auctions</h1>
<div class="auctions" id="live">
    <?php while ($auction = mysqli_fetch_assoc($liveAuctions)): ?>
        <a href="product_details.php?product_id=<?php echo htmlspecialchars($auction['product_id']); ?>" class="card">
            <img src="<?php echo htmlspecialchars($auction['image_url']); ?>" alt="<?php echo htmlspecialchars($auction['product_name']); ?>">
            <h3><?php echo htmlspecialchars($auction['product_name']); ?></h3>
            <p class="status countdown" 
               data-end="<?php echo strtotime($auction['end_date']); ?>">
               <!-- "Ends in" timer will go here -->
            </p>
        </a>
    <?php endwhile; ?>
</div>

    <h1>Upcoming Auctions</h1>
<div class="auctions" id="upcoming">
    <?php while ($auction = mysqli_fetch_assoc($upcomingAuctions)): ?>
        <a href="product_details.php?product_id=<?php echo htmlspecialchars($auction['product_id']); ?>" class="card">
            <img src="<?php echo htmlspecialchars($auction['image_url']); ?>" alt="<?php echo htmlspecialchars($auction['product_name']); ?>">
            <h3><?php echo htmlspecialchars($auction['product_name']); ?></h3>
            <p class="status countdown" 
               data-start="<?php echo strtotime($auction['start_date']); ?>">
               <!-- "Starts in" timer will go here -->
            </p>
        </a>
    <?php endwhile; ?>
</div>




<h1>Closed Auctions</h1>
<div class="auctions" id="closed">
    <?php while ($auction = mysqli_fetch_assoc($closedAuctions)): ?>
        <a href="product_details.php?product_id=<?php echo htmlspecialchars($auction['product_id']); ?>" class="card">
            <img src="<?php echo htmlspecialchars($auction['image_url']); ?>" alt="<?php echo htmlspecialchars($auction['product_name']); ?>">
            <h3><?php echo htmlspecialchars($auction['product_name']); ?></h3>
            <p class="status">Ended: <?php echo htmlspecialchars($auction['end_date']); ?></p>
        </a>
    <?php endwhile; ?>
</div>


    <!-- JavaScript to Handle Smooth Scroll -->
    <script src="../public/js/timer.js"></script>
    <script>
    function scrollToSection(sectionId) {
            const section = document.getElementById(sectionId);
            if (section) {
                section.scrollIntoView({ behavior: 'smooth' });
            }
        }

        // Handle page load to detect query parameters and hash links
        window.onload = function () {
            // Check for query parameters (e.g., ?section=live)
            const urlParams = new URLSearchParams(window.location.search);
            const section = urlParams.get('section');

            if (section) {
                scrollToSection(section);  // Scroll to section from query param
            } else if (window.location.hash) {
                // Scroll to section if there's a hash (e.g., #live)
                scrollToSection(window.location.hash.substring(1));
            }
        };
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
