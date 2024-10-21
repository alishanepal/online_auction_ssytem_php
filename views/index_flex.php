<?php
session_start();
include '../includes/connection.php'; // Ensure the database connection is included
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Auction System</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- External CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../public/css/styles.css">
    <style>
        .dropdown-content {
            display: none;
            position: absolute;
            background-color: white;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            z-index: 1;
        }

        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }

        .dropdown {
            position: relative;
            display: inline-block;
        }

        .profile-icon {
            font-size: 1.5em; /* Adjust icon size */
            margin-left: 5px; /* Space between username and icon */
        }
    </style>
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
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?section=live">Live Auction</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?section=upcoming">Upcoming Auction</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?section=closed">Closed Auction</a>
                    </li>
                </ul>

                <form class="d-flex me-3">
                    <input class="form-control me-2" type="search" placeholder="Search items" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>

                <div class="d-flex">
                    <?php
                    if (isset($_SESSION['username'])) {
                        // User is logged in, show username with dropdown
                        echo '<div class="dropdown me-3">';
                        echo '<span class="navbar-text" id="userDropdown" style="cursor: pointer;">' . htmlspecialchars($_SESSION['username']) . ' <i class="fas fa-user-circle profile-icon"></i></span>';
                        echo '<div class="dropdown-content" id="dropdownMenu">';
                        echo '<a href="profile.php?user_id=' . htmlspecialchars($_SESSION['user_id']) . '">Profile</a>'; // Link to profile page with user_id
echo '<a href="../process/logout.php">Logout</a>'; // Link to logout
                        echo '</div></div>';
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
                    <a class="nav-link" href="painting.php">Paintings</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="jewelry.php">Jewelry</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="antique.php">Antiques</a>
                </li>
            </ul>
        </div>
    </nav>

    <script>
        // JavaScript to toggle dropdown visibility
        document.getElementById("userDropdown").addEventListener("click", function () {
            var dropdown = document.getElementById("dropdownMenu");
            dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
        });

        // Close the dropdown if the user clicks outside of it
        window.onclick = function (event) {
            if (!event.target.matches('#userDropdown')) {
                var dropdowns = document.getElementsByClassName("dropdown-content");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.style.display === "block") {
                        openDropdown.style.display = "none";
                    }
                }
            }
        }
    </script>
</body>
</html>
