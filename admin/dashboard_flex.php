<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Online Auction System</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- External CSS -->
    <link rel="stylesheet" href="../public/css/admin.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h4><a class="navbar-brand" href="dashboard.php">Admin Dashboard</a></h4>
        <ul class="nav flex-column">
        <li class="nav-item">
                <a class="nav-link" href="add_product.php">Add Product</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="products_report.php">Product Report</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="categoryDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Category
                </a>
                <ul class="dropdown-menu" aria-labelledby="categoryDropdown">           
                    <li><a class="dropdown-item" href="painting.php">Paintings</a></li>
                    <li><a class="dropdown-item" href="jewelry.php">jewelry</a></li>
                    <li><a class="dropdown-item" href="antique.php">Antiques</a></li>
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#bidders-report">Bidders Report</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#participants-report">Participants Report</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#winners-report">Winners Report</a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Navbar -->
        <nav class="navbar navbar-dark bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="dashboard.php">Dashboard</a>
                <div class="d-flex">
                <?php
                session_start();
                // Check if the session is set
                if (isset($_SESSION['username'])) {
                    // Display the username from the session
                    echo '<span class="navbar-text text-light mr-3">Hello, '. htmlspecialchars($_SESSION['username']) . '</span>';
                }
                ?>
                <a href="../process/logout.php" class="btn btn-danger">Logout</a>
                </div>
            </div>
        </nav>
    </div>
    <main class="col-md-9 ml-sm-auto col-lg-10 px-md-4 mt-4">
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom mt-4">
  </div>
    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

