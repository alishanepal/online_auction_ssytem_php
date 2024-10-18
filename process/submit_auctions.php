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

// Additional fields based on category
$additionalFields = [
    'painting' => ['artist', 'technique', 'yearCreated'],
    'jewelry' => ['material', 'weight', 'gemstones'],
    'antique' => ['origin', 'historicalPeriod', 'antiqueconditionn']
];

$additionalData = [];
if (array_key_exists($categoryId, $additionalFields)) {
    foreach ($additionalFields[$categoryId] as $field) {
        $additionalData[$field] = $_POST[$field] ?? '';
    }
}

// Handle file uploads
$uploadedImages = [];
if (!empty($_FILES['productImages']['name'][0])) {
    $uploadDir = '../uploads/';
    foreach ($_FILES['productImages']['name'] as $key => $name) {
        $uploadFile = $uploadDir . basename($name);
        if (move_uploaded_file($_FILES['productImages']['tmp_name'][$key], $uploadFile)) {
            $uploadedImages[] = $uploadFile;
        } else {
            echo "Error uploading file: " . htmlspecialchars($name);
        }
    }
}

// Insert product information
$sql = "INSERT INTO products (product_name, category_id, minimum_price_interval, reserve_price) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param('sdds', $productName, $categoryId, $priceInterval, $reservePrice);
$stmt->execute();
$productId = $stmt->insert_id; // Get last inserted ID

// Insert category-specific data
if (array_key_exists($categoryId, $additionalFields)) {
    $fields = $additionalFields[$categoryId];
    $placeholders = implode(', ', array_fill(0, count($fields), '?'));
    $sql = "INSERT INTO {$categoryId}s (product_id, " . implode(', ', $fields) . ") VALUES (?, " . $placeholders . ")";
    $stmt = $conn->prepare($sql);

    // Bind parameters dynamically
    $params = array_merge([$productId], array_map(function($field) {
        return $_POST[$field] ?? '';
    }, $fields));
    $stmt->bind_param(str_repeat('s', count($params)), ...$params);
    $stmt->execute();
}

// Insert subcategory data if provided
if ($subcategoryName) {
    $sql = "INSERT INTO subcategory (subcategory_name, category_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $subcategoryName, $categoryId);
    $stmt->execute();
}

// Insert auction details
$sql = "INSERT INTO auctions (product_id, start_date, end_date, starting_bid, reserve_price, description, keywords) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param('issdsss', $productId, $startDate, $endDate, $startingBid, $reservePrice, $description, $keywords);
$stmt->execute();

// Insert product images
$sql = "INSERT INTO product_images (product_id, image_url) VALUES (?, ?)";
$stmt = $conn->prepare($sql);

foreach ($uploadedImages as $imageUrl) {
    $stmt->bind_param('is', $productId, $imageUrl);
    $stmt->execute();
}

// Close connections
$stmt->close();
$conn->close();

echo "Product and auction details have been successfully submitted!";
