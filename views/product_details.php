<?php include 'bidding_algorithm.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['product_name']); ?> - Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Online Auction</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="index.php?section=live">Live Auction</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php?section=upcoming">Upcoming Auction</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php?section=closed">Closed Auction</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Product Details Section -->
    <div class="container mt-5">
        <div class="row">
            <!-- Left Column: Product Image -->
            <div class="col-md-6">
                <div class="card">
                    <img src="<?php echo htmlspecialchars($product['image_url']); ?>" class="card-img-top"
                        alt="<?php echo htmlspecialchars($product['product_name']); ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($product['product_name']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($product['description']); ?></p>
                    </div>
                </div>
            </div>

            <!-- Right Column: Product Details -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Auction Information</h5>
                        <p><strong>Category:</strong> <?php echo htmlspecialchars($product['category_name']); ?></p>
                        <p><strong>Starting Bid:</strong> $<?php echo number_format($product['starting_bid'], 2); ?></p>

                        <!-- Display Dynamic Minimum Interval -->
                        <p><strong>Minimum Interval:</strong>
                            $<?php echo number_format($dynamic_interval, 2); ?>

                        </p>

                        <p><strong>Status:</strong> <?php echo htmlspecialchars($product['status']); ?></p>

                        <!-- Category-Specific Details -->
                        <?php if ($category_id == 1): ?>
                            <h5 class="mt-4">Painting Details</h5>
                            <p><strong>Artist:</strong> <?php echo htmlspecialchars($extra_details['artist']); ?></p>
                            <p><strong>Year Created:</strong>
                                <?php echo htmlspecialchars($extra_details['year_created']); ?></p>
                            <p><strong>Technique:</strong> <?php echo htmlspecialchars($extra_details['technique']); ?></p>
                        <?php elseif ($category_id == 3): ?>
                            <h5 class="mt-4">Antique Details</h5>
                            <p><strong>Origin:</strong> <?php echo htmlspecialchars($extra_details['origin']); ?></p>
                            <p><strong>Historical Period:</strong>
                                <?php echo htmlspecialchars($extra_details['historical_period']); ?></p>
                            <p><strong>Condition:</strong> <?php echo htmlspecialchars($extra_details['conditionn']); ?></p>
                        <?php elseif ($category_id == 2): ?>
                            <h5 class="mt-4">Jewelry Details</h5>
                            <p><strong>Material:</strong> <?php echo htmlspecialchars($extra_details['material']); ?></p>
                            <p><strong>Gemstones:</strong> <?php echo htmlspecialchars($extra_details['gemstones']); ?></p>
                            <p><strong>Weight:</strong> <?php echo htmlspecialchars($extra_details['weight']); ?> g</p>
                        <?php endif; ?>

                        <!-- Bid Button and Timer -->
                        <?php if ($product['status'] == 'live'): ?>
                            <div><strong>Current Auction Price:</strong> $<?php echo number_format($current_bid, 2); ?>
                            </div>
                            <?php if (!isset($_SESSION['user_id'])): ?>
                                <!-- If user is not logged in, show the prompt to log in -->
                                <button class="btn btn-primary" onclick="promptLogin()">Place Bid</button>

                            <?php else: ?>
                                <?php if (!$hasBid): ?>
                                    <!-- If the user has not placed a bid yet, show Place Bid button -->
                                    <button id="placeBidButton" class="btn btn-primary" onclick="confirmParticipation()">Place
                                        Bid</button>

                                    <div id="bidInputContainer" style="display: none; margin-top:10px;">
                                        <input type="number" id="bidAmount" class="form-control"
                                            placeholder="Your bid must be at least $<?php echo number_format($must_bid, 2); ?>"
                                            min="<?php echo $must_bid; ?>" data-current-bid="<?php echo $current_bid; ?>"
                                            data-minimum-price-interval="<?php echo $dynamic_interval; ?>">
                                        <button class="btn btn-success mt-2"
                                            onclick="submitBid(<?php echo $_SESSION['user_id']; ?>, <?php echo $product_id; ?>)">
                                            Submit Bid
                                        </button>
                                    </div>

                                <?php else: ?>
                                    <!-- If the user has placed a bid, show the Submit Bid input and button -->
                                    <div id="bidInputContainer" style="margin-top:10px;">
                                        <input type="number" id="bidAmount" class="form-control"
                                            placeholder="Your bid must be at least $<?php echo number_format($must_bid, 2); ?>"
                                            min="<?php echo $must_bid; ?>" data-current-bid="<?php echo $current_bid; ?>"
                                            data-minimum-price-interval="<?php echo $dynamic_interval; ?>">
                                        <button class="btn btn-success mt-2"
                                            onclick="submitBid(<?php echo $_SESSION['user_id']; ?>, <?php echo $product_id; ?>)">
                                            Submit Bid
                                        </button>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>

                        <?php elseif ($product['status'] == 'upcoming'): ?>
                            <div><strong>Auction starts in:</strong>
                                <span class="countdown" id="timer-<?php echo $product_id; ?>"
                                    data-start="<?php echo strtotime($product['start_date']); ?>">
                                </span>
                            </div>
                        <?php else: ?>
                            <button class="btn btn-secondary" disabled>Bidding Closed</button>
                        <?php endif; ?>
                    

                    </div>
                </div>
            </div>
            <!-- Bid History Modal -->
            <div style="text-align: center; margin-top: 1rem;"> <!-- Center the button with inline style -->
                <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#bidHistoryModal"
                    style="padding: 10px 20px; border-radius: 8px; display: inline-block;">
                    View Bid History (<?php echo $bid_count; ?>)
                </button>
            </div>

            <div class="modal fade" id="bidHistoryModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Bid History</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Bid Amount</th>
                                        <th>Bid Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($bidHistoryWithWinners as $bid): ?>
                                        <tr
                                            style="<?php echo $bid['isWinner'] ? 'color: green; font-weight: bold;' : ''; ?>">
                                            <td>
                                                $<?php echo number_format(floatval($bid['bid_amount']), 2); ?>
                                                <?php if ($bid['isWinner']): ?>
                                                    <span class="badge bg-success ms-2">Winner</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo date('Y-m-d H:i:s', strtotime($bid['bid_time'])); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Congratulations Modal -->
            <div class="modal fade" id="congratulationsModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Congratulations!</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>You have won the auction for
                                <strong><?php echo htmlspecialchars($product['product_name']); ?></strong>!
                            </p>
                            <p>Your winning bid was: <strong>$<?php echo number_format($winningBid, 2); ?></strong></p>
                            <p>Thank you for participating!</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

        </div> <!-- End of Main Container -->

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
        <script src="../public/js/product_details.js"></script>

        <script>

            document.addEventListener('DOMContentLoaded', () => {
                // Check if user is the winner and trigger the modal
                <?php if ($userIsWinner): ?>
                    var myModal = new bootstrap.Modal(document.getElementById('congratulationsModal'));
                    myModal.show();
                <?php endif; ?>

                // Timer functionality for upcoming auction
                if (document.querySelector('.countdown')) {
                    const countdownElements = document.querySelectorAll('.countdown');
                    countdownElements.forEach(element => {
                        const startTimestamp = parseInt(element.getAttribute('data-start')) * 1000;
                        startTimer(startTimestamp, element);
                    });
                }

                function startTimer(startTime, countdownElement) {
                    const timerInterval = setInterval(() => {
                        const now = Date.now();
                        const timeLeft = startTime - now;
                        if (timeLeft <= 0) {
                            clearInterval(timerInterval);
                            countdownElement.innerHTML = "Auction is now live!";
                            location.reload();
                        } else {
                            const days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
                            const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                            const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
                            const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);
                            countdownElement.innerHTML = `${days}d ${hours}h ${minutes}m ${seconds}s`;
                        }
                    }, 1000);
                }
            });
        </script>
</body>

</html>