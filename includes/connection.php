<?php
// Database credentials
$servername = "localhost";  // Replace with your server name (usually "localhost" for local development)
$username = "root";         // Replace with your database username
$password = "";             // Replace with your database password (leave blank for XAMPP default)
$dbname = "online_auction_system";     // Replace with your database name

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
