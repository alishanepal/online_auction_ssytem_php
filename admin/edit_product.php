<?php
include '../includes/connection.php';

// Get the product_id from the URL parameter
if (isset($_GET['product_id'])) {
    $productId = $_GET['product_id'];

    // Fetch product details
    $productQuery = "SELECT p.*, c.category_name, sc.subcategory_name, a.status 
                    FROM product p
                    LEFT JOIN category c ON p.category_id = c.category_id
                    LEFT JOIN subcategory sc ON sc.product_id = p.product_id
                    LEFT JOIN auctions a ON p.product_id = a.product_id
                    WHERE p.product_id = ?";
                    
    $stmt = $conn->prepare($productQuery);
    $stmt->bind_param('i', $productId);
    $stmt->execute();
    $productResult = $stmt->get_result();
    $product = $productResult->fetch_assoc();

    if (!$product) {
        die('Product not found!');
    }
} else {
    die('No product ID specified.');
}

// Handle form submission for updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productName = $_POST['product_name'];
    $startingBid = $_POST['starting_bid'];
    $reservePrice = $_POST['reserve_price'];
    $description = $_POST['description'];
    $keywords = $_POST['keywords'];

    // Update product details in the database
    $updateQuery = "UPDATE product 
                    SET product_name = ?, starting_bid = ?, reserve_price = ?, description = ?, keywords = ? 
                    WHERE product_id = ?";
                    
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param('sssssi', $productName, $startingBid, $reservePrice, $description, $keywords, $productId);
    
    if ($stmt->execute()) {
        echo "<script>alert('Product updated successfully!'); window.location.href = 'product_report.php';</script>";
    } else {
        echo "Error updating product: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Edit Product</h2>
        <form action="edit_product.php?product_id=<?= htmlspecialchars($product['product_id']); ?>" method="POST">
            <div class="mb-3">
                <label for="product_name" class="form-label">Product Name</label>
                <input type="text" class="form-control" id="product_name" name="product_name" value="<?= htmlspecialchars($product['product_name']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="starting_bid" class="form-label">Starting Bid</label>
                <input type="number" class="form-control" id="starting_bid" name="starting_bid" value="<?= htmlspecialchars($product['starting_bid']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="reserve_price" class="form-label">Reserve Price</label>
                <input type="number" class="form-control" id="reserve_price" name="reserve_price" value="<?= htmlspecialchars($product['reserve_price']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" required><?= htmlspecialchars($product['description']); ?></textarea>
            </div>

            <div class="mb-3">
                <label for="keywords" class="form-label">Keywords</label>
                <input type="text" class="form-control" id="keywords" name="keywords" value="<?= htmlspecialchars($product['keywords']); ?>" required>
            </div>

            <button type="submit" class="btn btn-primary">Update Product</button>
            <a href="product_report.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
