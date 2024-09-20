<?php
include '../includes/connection.php'; // Ensure your connection file is correct

// Fetch form data
$productName = $_POST['productName'];
$categoryId = $_POST['category'];
$startingBid = $_POST['startingBid'];
$priceInterval = $_POST['priceInterval'];
$reservePrice = $_POST['reservePrice'];
$startDate = $_POST['startDate'];
$endDate = $_POST['endDate'];
$description = $_POST['description'];
$keywords = $_POST['keywords'];
$subcategoryName = $_POST['subcategoryName'] ?? '';
$currentDateTime = date('Y-m-d H:i:s');

// Handle file uploads
$uploadedImages = [];
if (!empty($_FILES['productImages']['name'][0])) {
    $uploadDir = '../public/uploads/'; // Directory to store uploaded images
    foreach ($_FILES['productImages']['name'] as $key => $name) {
        $uploadFile = $uploadDir . basename($name);
        if (move_uploaded_file($_FILES['productImages']['tmp_name'][$key], $uploadFile)) {
            $uploadedImages[] = $uploadFile; // Store file path
        } else {
            echo "Error uploading file: " . htmlspecialchars($name);
        }
    }
}

// Additional fields based on category
$additionalFields = [
    'painting' => ['artist', 'technique', 'yearCreated'],
    'jewelry' => ['material', 'weight', 'gemstones'],
    'antique' => ['origin', 'historicalPeriod', 'antiqueconditionn']
];

// Insert product information into the `products` table
$sql = "INSERT INTO product (product_name, category_id, minimum_price_interval, reserve_price, starting_bid, description, keywords) 
        VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param('sddssss', $productName, $categoryId, $priceInterval, $reservePrice, $startingBid, $description, $keywords);
$stmt->execute();
$productId = $stmt->insert_id; // Get the last inserted ID for foreign key usage

// Insert additional data based on the category
if ($categoryId == 1) {
    // Category: Painting
    $artist = $_POST['artist'] ?? NULL;
    $technique = $_POST['technique'] ?? NULL;
    $yearCreated = $_POST['yearCreated'] ?? NULL;

    $sql = "INSERT INTO paintings (product_id, artist, technique, year_created) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('isss', $productId, $artist, $technique, $yearCreated);
    $stmt->execute();

} elseif ($categoryId == 2) {
    // Category: Jewelry
    $material = $_POST['material'] ?? NULL;
    $weight = $_POST['weight'] ?? NULL;
    $gemstones = $_POST['gemstones'] ?? NULL;

    $sql = "INSERT INTO jewelry (product_id, material, weight, gemstones) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('isss', $productId, $material, $weight, $gemstones);
    $stmt->execute();

} elseif ($categoryId == 3) {
    // Category: Antique
    $origin = $_POST['origin'] ?? NULL;
    $historicalPeriod = $_POST['historicalPeriod'] ?? NULL;
    $antiqueCondition = $_POST['antiqueconditionn'] ?? NULL;

    $sql = "INSERT INTO antiques (product_id, origin, historical_period, conditionn) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('isss', $productId, $origin, $historicalPeriod, $antiqueCondition);
    $stmt->execute();
}
// Determine the auction status
if ( $currentDateTime >= $startDate && $currentDateTime <= $endDate) {
    $status = 'live';
} elseif ($currentDateTime < $startDate) {
    $status = 'upcoming';
} else {
    $status = 'closed';
}

// Insert auction details into the `auctions` table with status
$sql = "INSERT INTO auctions (product_id, start_date, end_date, status) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param('isss', $productId, $startDate, $endDate, $status);
$stmt->execute();

// Insert subcategory data if provided
if (!empty($subcategoryName)) {
    $sql = "INSERT INTO subcategory (subcategory_name, category_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $subcategoryName, $categoryId);
    $stmt->execute();
}

// Insert product images into the `product_images` table, ensuring `product_id` is used as a foreign key
if (!empty($uploadedImages)) {
    $sql = "INSERT INTO product_images (product_id, image_url) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    foreach ($uploadedImages as $imageUrl) {
        $stmt->bind_param('is', $productId, $imageUrl);
        $stmt->execute();
    }
}

// Close connections
$stmt->close();
$conn->close();

echo "Product, auction, and associated details have been successfully submitted!";
?>
