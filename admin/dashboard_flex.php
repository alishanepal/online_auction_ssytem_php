<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <style>
    /* Global Reset and Font Settings */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }

    body {
      display: flex;
      min-height: 100vh;
      background-color: white; /* Light background color for consistency */
    }

    /* Sidebar Styling */
    .sidebar {
      width: 250px;
      background-color: #2c3e50;
      color: white;
      position: fixed;
      top: 0;
      left: 0;
      height: 100%;
      padding-top: 30px;
      box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
    }

    .sidebar h2 {
      text-align: center;
      margin-bottom: 40px;
      font-weight: 600;
      font-size: 22px;
      letter-spacing: 1px;
    }

    .sidebar ul {
      list-style: none;
    }

    .sidebar ul li {
      padding: 15px 25px;
      transition: background-color 0.3s;
    }

    .sidebar ul li a {
      text-decoration: none;
      color: white;
      display: block;
      font-size: 18px;
      transition: color 0.3s;
    }

    .sidebar ul li:hover {
      background-color: #1a252f;
    }

    .sidebar ul li a:hover {
      color: #f1c40f;
    }

    /* Top Navigation Bar */
    .top-nav {
      background-color: #3498db;
      height: 60px;
      width: calc(100% - 250px);
      position: fixed;
      left: 250px;
      top: 0;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 30px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
      color: white;
    }

    .top-nav .nav-item {
      font-size: 16px;
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .nav-item img {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid white;
    }

    /* Main Content Styling */
    .main-content {
      margin-top: 60px;
      margin-left: 250px;
      padding: 30px;
      width: calc(100% - 250px);
      background-color: white; /* Same background as the body */
      min-height: calc(100vh - 60px);
    }

    .main-content h1 {
      font-size: 28px;
      margin-bottom: 10px;
    }

    .main-content p {
      font-size: 16px;
      color: #555;
    }
  </style>
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <h2>Dashboard</h2>
    <ul>
      <li><a href="#">Home</a></li>
      <li><a href="users.php">Users</a></li>
      <li><a href="add_product.php">Add Product</a></li>
      <li><a href="products_report.php">Product Report</a></li> 
      <li><a href="bidders_report.php">Bidders Report</a></li>
      <li><a href="#">Winners Report</a></li>
      <li><a href="../process/logout.php">Logout</a></li>
    </ul>
  </div>

  <!-- Top Navigation Bar -->
  <div class="top-nav">
    <div>Admin Dashboard</div>
    <div class="nav-item">
      <span>Welcome, Admin</span>
      <img src="https://via.placeholder.com/40" alt="Profile Picture">
    </div>
  </div>

  <!-- Main Content Area -->
  <div class="main-content">
    

</body>
</html>
