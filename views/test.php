<?php
session_start();
include '../includes/connection.php'; // Ensure the database connection is included
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    echo "User ID: " . $user_id;
}