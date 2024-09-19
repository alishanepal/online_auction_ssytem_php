<?php
include 'dashboard_flex.php'; // Assuming this is your PHP file with the sidebar
?>
<style>
  /* Ensure the form is scrollable */
  .scrollable-form-container {
    max-height: calc(100vh - 120px); /* Adjust height based on your layout */
    overflow-y: auto;
    padding: 2px;
    margin-top: 70px; /* Space after navbar */
    width: calc(100% - 100px); /* Adjust width to account for sidebar */
}

  .form-row {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
  }
  button[type="submit"] {
    margin-top: 20px; /* Adds space above the button */
    margin-bottom: 40px; /* Adds space below the button for better visibility */
  }
  .form-group {
    flex: 1;
    min-width: 200px; /* Set a minimum width for input fields */
  }
  .error {
    color: red;
    font-size: 0.875em;
  }
</style>

<div class="scrollable-form-container">
    <form id="auctionForm" action="submit_auction.php" method="post" enctype="multipart/form-data">
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
                    <option value="painting">Painting</option>
                    <option value="jewelry">Jewelry</option>
                    <option value="antique">Antique</option>
                </select>
                <div class="error" id="categoryError"></div>
            </div>
        </div>

        <!-- Dynamic Fields Based on Category Selection -->
        <div id="category-specific-fields">
            <!-- Painting-specific fields -->
            <div class="form-group col-md-12" id="painting-fields" style="display: none;">
                <label for="artist">Artist</label>
                <input type="text" class="form-control" id="artist" name="artist">
                <div class="error" id="artistError"></div>
                <label for="medium">Medium</label>
                <input type="text" class="form-control" id="medium" name="medium" placeholder="e.g. Oil, Watercolor">
                <div class="error" id="mediumError"></div>
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
                <label for="condition">Condition</label>
                <textarea class="form-control" id="antiqueCondition" name="antiqueCondition" rows="2"></textarea>
                <div class="error" id="antiqueConditionError"></div>
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

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
</main>
</body>
</html>