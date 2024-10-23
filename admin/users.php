<?php
// Include the database connection
include '../includes/connection.php';
include 'dashboard_flex.php'; 

// Fetch all users from the 'users' table
$sql = "SELECT user_id, username, first_name, last_name, email, phone, address, profile_photo FROM users WHERE role = 'user'";

$result = $conn->query($sql);
?>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
.table-container {
    max-width: 1100px;
    margin: 0 auto;
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.table-container table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.table-container th, 
.table-container td {
    border: 1px solid #ddd;
    padding: 12px;
    text-align: left;
}

.table-container th {
    background-color: #3498db;
    color: white;
    font-weight: 600;
}

.table-container tr:nth-child(even) {
    background-color: #f2f2f2;
}

.table-container tr:hover {
    background-color: #eaeaea;
}

.table-container h2 {
    text-align: center;
    margin-bottom: 20px;
    color: #34495e;
}

.table-container img {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 50%;
    border: 1px solid #ddd;
}

    </style>
    <div class="table-container">
        <h2>Users List</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Profile Photo</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    // Output each row from the users table
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['user_id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['first_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['last_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['address']) . "</td>";
                        echo "<td><img src='../uploads/" . htmlspecialchars($row['profile_photo']) . "' alt='Profile Photo'></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>No users found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <?php
    // Close the database connection
    $conn->close();
    ?>
</body>

</html>
