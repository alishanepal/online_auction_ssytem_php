<?php
include 'dashboard_flex.php'; 
include '../includes/connection.php'; // Assuming this is your PHP file with the sidebar
$sql = "SELECT category_id, category_name FROM category";
$result = $conn->query($sql); 
?>  <style>
/* Reset and Global Styles */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Poppins', sans-serif;
}

body {
  background-color: #f8f9fa;
  padding: 20px;
}

.scrollable-form-container {
  max-width: 900px;
  margin: 0 auto;
  background-color: white;
  padding: 30px;
  border-radius: 8px;
  box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
  overflow-y: auto;
}

h3 {
  margin-bottom: 20px;
  font-weight: 600;
  color: #34495e;
  border-bottom: 2px solid #3498db;
  padding-bottom: 5px;
}

.form-row {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
}

.form-group {
  flex: 1;
  min-width: 200px;
  margin-bottom: 15px;
}

.form-control,
.form-control-file,
.form-control select,
textarea {
  width: 100%;
  padding: 10px;
  margin-top: 5px;
  border: 1px solid #ced4da;
  border-radius: 5px;
}

.form-control:focus,
textarea:focus {
  border-color: #3498db;
  outline: none;
  box-shadow: 0 0 8px rgba(52, 152, 219, 0.2);
}

textarea {
  resize: vertical;
}

.btn-primary {
  background-color: #3498db;
  color: white;
  border: none;
  padding: 10px 20px;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.btn-primary:hover {
  background-color: #2980b9;
}

.error {
  color: #e74c3c;
  font-size: 14px;
  margin-top: 5px;
}

#category-specific-fields {
  margin-top: 20px;
}

label {
  font-weight: 500;
  color: #34495e;
}

.form-group.col-md-12 {
  width: 100%;
}

@media (max-width: 768px) {
  .form-row {
    flex-direction: column;
  }
}
</style>

<div class="scrollable-form-container">
    <form id="auctionForm" action="../process/submit_auction.php" method="post" enctype="multipart/form-data">
        <!-- Product Information Section -->
        <h3>Product Information</h3>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="productName">Product Name</label>
                <input type="text" class="form-control" id="productName" name="productName" required>
                <div class="error" id="productNameError"></div>
            </div>
            <div class="form-group col-md-4">
                <label for="productImages">Product Images</label>
                <input type="file" class="form-control-file" id="productImages" name="productImages[]" multiple required>
                <div class="error" id="productImagesError"></div>
            </div>
            <div class="form-group col-md-6">
    <label for="category">Category</label>
    <select class="form-control" id="category" name="category" required>
        <option value="">Select Category</option>
        <?php
        // Check if the query returned any results
        if ($result->num_rows > 0) {
            // Output data for each row
            while ($row = $result->fetch_assoc()) {
                // Set the 'selected' attribute if this is the currently selected category
                $selected = ($currentCategoryName === $row['category_name']) ? 'selected' : '';
                // Output the <option> element
                echo '<option value="' . htmlspecialchars($row['category_id']) . '" ' . $selected . '>' . htmlspecialchars($row['category_name']) . '</option>';
            }
        } else {
            echo '<option value="">No categories available</option>';
        }
        ?>
    </select>
    <div class="error" id="categoryError"></div>
</div>

<?php
// Close the database connection
$conn->close();
?>
        </div>

        <!-- Dynamic Fields Based on Category Selection -->
        <div id="category-specific-fields">
            <!-- Painting-specific fields -->
            <div class="form-group col-md-12" id="painting-fields" style="display: none;">
                <label for="artist">Artist</label>
                <input type="text" class="form-control" id="artist" name="artist">
                <div class="error" id="artistError"></div>
                <label for="technique">technique</label>
                <input type="text" class="form-control" id="technique" name="technique" placeholder="e.g. Oil, Watercolor">
                <div class="error" id="techniqueError"></div>
                <label for="yearCreated">Year Created</label>
                <input type="number" class="form-control" id="yearCreated" name="yearCreated" min="1000" max="9999">
                <div class="error" id="yearCreatedError"></div>
            </div>

            <!-- Jewelry-specific fields -->
            <div class="form-group col-md-12" id="jewelry-fields" style="display: none;">
                <label for="material">Material</label>
                <input type="text" class="form-control" id="material" name="material" placeholder="e.g. Gold, Silver">
                <div class="error" id="materialError"></div>
                <label for="weight">Weight (in grams)</label>
                <input type="number" class="form-control" id="weight" name="weight" step="0.01" min="0">
                <div class="error" id="weightError"></div>
                <label for="gemstones">Gemstones</label>
                <input type="text" class="form-control" id="gemstones" name="gemstones" placeholder="e.g. Diamond, Sapphire">
                <div class="error" id="gemstonesError"></div>
            </div>

            <!-- Antique-specific fields -->
            <div class="form-group col-md-12" id="antique-fields" style="display: none;">
                <label for="origin">Origin</label>
                <input type="text" class="form-control" id="origin" name="origin" placeholder="Country/Region">
                <div class="error" id="originError"></div>
                <label for="historicalPeriod">Historical Period</label>
                <input type="text" class="form-control" id="historicalPeriod" name="historicalPeriod" placeholder="e.g. Victorian, Ming Dynasty">
                <div class="error" id="historicalPeriodError"></div>
                <label for="conditionn">conditionn</label>
                <textarea class="form-control" id="antiqueconditionn" name="antiqueconditionn" rows="2"></textarea>
                <div class="error" id="antiqueconditionnError"></div>
            </div>
            <div class="form-group">
            <label for="subcategoryName">Subcategory Name:</label>
            <input type="text" id="subcategoryName" name="subcategoryName" class="form-control" required>
            <div class="error" id="subcategoryNameError"></div>
        </div>
        </div>

        <!-- Auction Details Section -->
        <h3>Auction Details</h3>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="startingBid">Starting Bid</label>
                <input type="number" class="form-control" id="startingBid" name="startingBid" step="1" min="0" required>
                <div class="error" id="startingBidError"></div>
            </div>
            <div class="form-group col-md-4">
                <label for="priceInterval">Minimum Price Interval</label>
                <input type="number" class="form-control" id="priceInterval" name="priceInterval" step="1" min="0" required>
                <div class="error" id="priceIntervalError"></div>
            </div>
            <div class="form-group col-md-6">
                <label for="reservePrice">Reserve Price (optional)</label>
                <input type="number" class="form-control" id="reservePrice" name="reservePrice" step="10" min="0" required>
                <div class="error" id="reservePriceError"></div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="startDate">Auction Start Date</label>
                <input type="datetime-local" class="form-control" id="startDate" name="startDate" required>
                <div class="error" id="startDateError"></div>
            </div>
            <div class="form-group col-md-6">
                <label for="endDate">Auction End Date</label>
                <input type="datetime-local" class="form-control" id="endDate" name="endDate" required>
                <div class="error" id="endDateError"></div>
            </div>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description" rows="4" placeholder="Enter a detailed description of the product" required></textarea>
            <div class="error" id="descriptionError"></div>
        </div>
        <div class="form-group">
        <label for="keywords">Keywords:</label>
        <input type="text" id="keywords" name="keywords" class="form-control" placeholder="Enter keywords separated by commas">
    </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
</main>
<script src="../public/js/add_product.js">
</script>
</body>
</html> 