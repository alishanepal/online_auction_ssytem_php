<?php
include 'dashboard_flex.php'; // Assuming this is your PHP file with the sidebar
include '../includes/connection.php'; // Database connection

// Fetch the number of users with role 'user'
$userCountQuery = "SELECT COUNT(*) AS user_count FROM users WHERE role = 'user'";
$userCountResult = $conn->query($userCountQuery);
$userCount = $userCountResult->fetch_assoc()['user_count'];

// Fetch the number of unique bidders from the bids table
$bidderCountQuery = "SELECT COUNT(DISTINCT user_id) AS bidder_count FROM bids";
$bidderCountResult = $conn->query($bidderCountQuery);
$bidderCount = $bidderCountResult->fetch_assoc()['bidder_count'];

// Fetch the total number of products from the products table
$productCountQuery = "SELECT COUNT(*) AS product_count FROM product";
$productCountResult = $conn->query($productCountQuery);
$productCount = $productCountResult->fetch_assoc()['product_count'];

// Close the database connection
$conn->close();
?>
 <!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <style>
    .dashboard-cards {
      display: flex;
      gap: 20px;
      margin: 30px 0;
    }

    .card {
      flex: 1;
      background-color: #77b1d4;
      color: white;
      padding: 20px;
      border-radius: 8px;
      text-align: center;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .card h2 {
      font-size: 2rem;
      margin-bottom: 10px;
    }

    .card p {
      font-size: 1rem;
      font-weight: 300;
    }
  </style>
</head>
<body>

<main>
    <div class="dashboard-cards">
        <!-- Users Card -->
        <div class="card">
            <h2><?= htmlspecialchars($userCount) ?></h2>
            <p>Total Users</p>
        </div>

        <!-- Bidders Card -->
        <div class="card">
            <h2><?= htmlspecialchars($bidderCount) ?></h2>
            <p>Unique Bidders</p>
        </div>

        <!-- Products Card -->
        <div class="card">
            <h2><?= htmlspecialchars($productCount) ?></h2>
            <p>Total Products</p>
        </div>
    </div>

</main>

</body>
</html>
