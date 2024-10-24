<?php
include '../includes/connection.php'; // Ensure the connection file is correct

$errors = []; // To store validation errors

// Fetch form data with trim to avoid extra spaces
$productName = trim($_POST['productName']);
$categoryId = trim($_POST['category']);
$startingBid = trim($_POST['startingBid']);
$priceInterval = trim($_POST['priceInterval']);
$reservePrice = trim($_POST['reservePrice']) ?: NULL; // Optional field
$startDate = trim($_POST['startDate']);
$endDate = trim($_POST['endDate']);
$description = trim($_POST['description']);
$keywords = trim($_POST['keywords']);
$subcategoryName = trim($_POST['subcategoryName'] ?? '');
$currentDateTime = date('Y-m-d H:i:s');

// **Validation: Ensure required fields are filled**
if (empty($productName)) $errors[] = "Product name is required.";
if (empty($categoryId)) $errors[] = "Category is required.";
if (empty($startingBid) || !is_numeric($startingBid)) $errors[] = "Starting bid must be a valid number.";
if (empty($priceInterval) || !is_numeric($priceInterval)) $errors[] = "Price interval must be a valid number.";
if (empty($startDate)) $errors[] = "Start date is required.";
if (empty($endDate)) $errors[] = "End date is required.";
if (empty($description)) $errors[] = "Product description is required.";
if (empty($keywords)) $errors[] = "Keywords are required.";

// Ensure start date is not in the past
if (strtotime($startDate) < strtotime($currentDateTime)) {
    $errors[] = "Start date cannot be in the past.";
}

// Ensure end date is after start date
if (strtotime($endDate) <= strtotime($startDate)) {
    $errors[] = "End date must be after the start date.";
}

// **File Upload Handling**
$uploadedImages = [];
if (!empty($_FILES['productImages']['name'][0])) {
    $uploadDir = '../public/uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true); // Create upload directory if it doesn't exist
    }

    foreach ($_FILES['productImages']['name'] as $key => $name) {
        $uploadFile = $uploadDir . basename($name);
        $fileExt = pathinfo($uploadFile, PATHINFO_EXTENSION);
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($fileExt, $allowedExtensions)) {
            $errors[] = "Invalid file type for image: $name. Allowed types: jpg, jpeg, png, gif.";
        } elseif (move_uploaded_file($_FILES['productImages']['tmp_name'][$key], $uploadFile)) {
            $uploadedImages[] = $uploadFile;
        } else {
            $errors[] = "Error uploading file: " . htmlspecialchars($name);
        }
    }
}

// If there are errors, display them and exit
if (!empty($errors)) {
    $errorMessages = implode("\\n", $errors);
    echo "<script>
        alert(' \\n$errorMessages');
        window.history.back();
    </script>";
    exit();
}

// **Insert product into the `products` table**
$sql = "INSERT INTO product (product_name, category_id, minimum_price_interval, reserve_price, starting_bid, description, keywords) 
        VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param('sddssss', $productName, $categoryId, $priceInterval, $reservePrice, $startingBid, $description, $keywords);
$stmt->execute();
$productId = $stmt->insert_id; // Get the inserted product ID

// **Insert additional data based on the category**
if ($categoryId == 1) { // Painting
    $artist = $_POST['artist'] ?? NULL;
    $technique = $_POST['technique'] ?? NULL;
    $yearCreated = $_POST['yearCreated'] ?? NULL;

    $sql = "INSERT INTO paintings (product_id, artist, technique, year_created) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('isss', $productId, $artist, $technique, $yearCreated);
    $stmt->execute();
} elseif ($categoryId == 2) { // Jewelry
    $material = $_POST['material'] ?? NULL;
    $weight = $_POST['weight'] ?? NULL;
    $gemstones = $_POST['gemstones'] ?? NULL;

    $sql = "INSERT INTO jewelry (product_id, material, weight, gemstones) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('isss', $productId, $material, $weight, $gemstones);
    $stmt->execute();
} elseif ($categoryId == 3) { // Antique
    $origin = $_POST['origin'] ?? NULL;
    $historicalPeriod = $_POST['historicalPeriod'] ?? NULL;
    $antiqueCondition = $_POST['antiqueconditionn'] ?? NULL;

    $sql = "INSERT INTO antiques (product_id, origin, historical_period, conditionn) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('isss', $productId, $origin, $historicalPeriod, $antiqueCondition);
    $stmt->execute();
}

// **Insert auction details into the `auctions` table**
$status = 'upcoming';
$sql = "INSERT INTO auctions (product_id, start_date, end_date, status) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param('isss', $productId, $startDate, $endDate, $status);
$stmt->execute();

// **Insert subcategory data if provided**
if (!empty($subcategoryName)) {
    $sql = "INSERT INTO subcategory (subcategory_name, product_id, category_id) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sii', $subcategoryName, $productId, $categoryId);
    $stmt->execute();
}

// **Insert product images into the `product_images` table**
if (!empty($uploadedImages)) {
    $sql = "INSERT INTO product_images (product_id, image_url) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    foreach ($uploadedImages as $imageUrl) {
        $stmt->bind_param('is', $productId, $imageUrl);
        $stmt->execute();
    }
}

// Close the connection
$stmt->close();
$conn->close();

// Alert success and redirect
echo "<script>
    alert('Product, auction, and associated details have been successfully submitted!');
    window.location.href = '../admin/add_product.php';
</script>";
?>
