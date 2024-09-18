<?php
// Start the session
session_start();

// Destroy all session data
session_unset();
session_destroy();

// Redirect to the home page or login page
header("Location: ../views/index.php"); // You can adjust this URL based on your project's structure
exit();
?>
