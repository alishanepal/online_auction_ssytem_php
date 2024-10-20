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
                        <a class="nav-link" href="#" onclick="scrollToSection('live')">Live Auction</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="scrollToSection('upcoming')">Upcoming Auction</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="scrollToSection('closed')">Closed Auction</a>
                    </li>
                </ul>
                <form class="d-flex me-3">
                    <input class="form-control me-2" type="search" placeholder="Search items" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>

                <div class="d-flex">
                    <?php
                    // Check if the user is logged in by checking the session
                    if (isset($_SESSION['username'])) {
                        // User is logged in, show username and logout button
                        echo '<span class="navbar-text me-3">Hello, ' . $_SESSION['username'] . '</span>';
                        echo '<button class="btn btn-outline-danger me-2" onclick="window.location.href=\'../process/logout.php\';">Logout</button>';
                    } else {
                        // User is not logged in, show login and sign up buttons
                        echo '<button class="btn btn-outline-success me-2" onclick="window.location.href=\'login.php\';">Login</button>';
                        echo '<button class="btn btn-outline-success me-2" onclick="window.location.href=\'signup.php\';">Sign Up</button>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Second Navbar for Category Navigation -->
    <nav class="navbar navbar-expand-lg" style="background-color:#EDE8DC">
        <div class="container-fluid">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link" href="../admin/painting.php">Paintings</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../admin/jewelry.php">Jewelry</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../admin/antique.php">Antiques</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Auction Sections -->
<div class="container mt-5">
    <!-- Upcoming Auctions Section -->
    <h2 id="upcoming">Upcoming Auctions</h2>
    <div class="row">
        <?php while ($row = mysqli_fetch_assoc($upcomingAuctions)) { ?>
            <div class="col-md-3">
                <a href="product_details.php?product_id=<?php echo $row['product_id']; ?>" class="text-decoration-none">
                    <div class="card">
                        <img src="<?php echo $row['image_url']; ?>" class="card-img-top" alt="<?php echo $row['product_name']; ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $row['product_name']; ?></h5>
                            <p class="card-text">Starts: <span class="countdown" data-start="<?php echo strtotime($row['start_date']); ?>" data-end="<?php echo strtotime($row['end_date']); ?>"></span></p>
                        </div>
                    </div>
                </a>
            </div>
        <?php } ?>
    </div>

   <!-- Live Auctions Section -->
<h2 id="live">Live Auctions</h2>
<div class="row">
    <?php while ($row = mysqli_fetch_assoc($liveAuctions)) { ?>
        <div class="col-md-3">
            <a href="product_details.php?product_id=<?php echo $row['product_id']; ?>" class="text-decoration-none">
                <div class="card">
                    <img src="<?php echo $row['image_url']; ?>" class="card-img-top" alt="<?php echo $row['product_name']; ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $row['product_name']; ?></h5>
                        <p class="card-text">Ends: <span class="countdown" data-end="<?php echo strtotime($row['end_date']); ?>"></span></p>
                        <!-- Bid Now button -->
                        <a href="product_details.php?product_id=<?php echo $row['product_id']; ?>" class="btn btn-primary">Place Bid</a>
                    </div>
                </div>
            </a>
        </div>
    <?php } ?>
</div>


        <!-- Closed Auctions Section -->
        <h2 id="closed">Closed Auctions</h2>
        <div class="row">
            <?php while ($row = mysqli_fetch_assoc($closedAuctions)) { ?>
                <div class="col-md-3">
                    <a href="product_details.php?product_id=<?php echo $row['product_id']; ?>" class="text-decoration-none">
                        <div class="card">
                            <img src="<?php echo $row['image_url']; ?>" class="card-img-top" alt="<?php echo $row['product_name']; ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $row['product_name']; ?></h5>
                                <p class="card-text">Ended: <?php echo $row['end_date']; ?></p>
                            </div>
                        </div>
                    </a>
                </div>
            <?php } ?>
        </div>
    </div>

    <!-- JavaScript to Handle Smooth Scroll -->
    <script>
        function scrollToSection(sectionId) {
            const section = document.getElementById(sectionId);
            section.scrollIntoView({ behavior: 'smooth' });
        }
    </script>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
<script>
    // Function to update the countdown timer
    function updateCountdown() {
        var countdownElements = document.querySelectorAll('.countdown');
        countdownElements.forEach(function (element) {
            var startTime = element.getAttribute('data-start');
            var endTime = element.getAttribute('data-end');
            var currentTime = Math.floor(Date.now() / 1000); // Current time in seconds

            if (startTime && currentTime < startTime) {
                // If the auction hasn't started yet, countdown to start time
                var timeLeft = startTime - currentTime;
                element.innerHTML = formatTimeLeft(timeLeft);
            } else if (endTime && currentTime < endTime) {
                // If the auction is live, countdown to end time
                var timeLeft = endTime - currentTime;
                element.innerHTML = formatTimeLeft(timeLeft);
            } else {
                // If the auction has ended
                element.innerHTML = "Auction ended";
            }
        });
    }

    // Helper function to format time left in days, hours, minutes, seconds
    function formatTimeLeft(seconds) {
        var days = Math.floor(seconds / (60 * 60 * 24));
        var hours = Math.floor((seconds % (60 * 60 * 24)) / (60 * 60));
        var minutes = Math.floor((seconds % (60 * 60)) / 60);
        var seconds = seconds % 60;

        return `${days}d ${hours}h ${minutes}m ${seconds}s`;
    }

    // Update countdown every second
    setInterval(updateCountdown, 1000);

    // Initial call to display the countdown on page load
    updateCountdown();
</script>

</html>
