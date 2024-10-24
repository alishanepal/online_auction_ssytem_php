
document.addEventListener('DOMContentLoaded', function() {
    const placeBidButton = document.getElementById('placeBidButton');
    placeBidButton.addEventListener('click', promptLogin);
});

function promptLogin() {
    if (confirm("You need to log in to place a bid. Would you like to go to the login page?")) {
        window.location.href = 'login.php'; // Adjust the URL as needed
    }
}
function confirmParticipation() {
    if (confirm("Are you sure you want to participate in this auction?")) {
        // Hide the Place Bid button
        document.getElementById('placeBidButton').style.display = 'none';
        // Show the bid input field
        document.getElementById('bidInputContainer').style.display = 'block';
    }
}
function submitBid(userId, productId) {
    const bidInput = document.getElementById('bidAmount');
    const currentBid = parseFloat(bidInput.getAttribute('data-current-bid')); // Get current bid from a data attribute
    const bidAmount = parseFloat(bidInput.value);
    const minimumPriceInterval = parseFloat(bidInput.getAttribute('data-minimum-price-interval')); // Get minimum price interval from a data attribute

    // Log the values for debugging
    console.log("Current Bid:", currentBid);
    console.log("Bid Amount:", bidAmount);
    console.log("Minimum Price Interval:", minimumPriceInterval);


    
    // Check if bid amount is valid
    if (isNaN(bidAmount) || bidAmount < currentBid) {
        alert("Your bid must be at least $" + currentBid.toFixed(2));
        return; // Exit the function if the bid is not valid
    }

    // Check if the interval between the current bid and new bid is less than the minimum price interval
    if (bidAmount - currentBid < minimumPriceInterval) {
        alert("Your bid must be at least $" + (currentBid + minimumPriceInterval).toFixed(2) + " to meet the minimum price interval.");
        bidInput.placeholder = "Your bid must be at least $" + (currentBid + minimumPriceInterval).toFixed(2);
        return; // Exit the function if the interval is not valid
    }

    // Make an AJAX call to submit the bid
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../process/place_bid.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        if (xhr.status === 200) {
            // Handle the server response
            const response = xhr.responseText; // or parse JSON if needed
            alert(response); // Display response or handle accordingly

            // Refresh the page to show the updated state
            location.reload(); // Refresh the page after the bid is submitted
        } else {
            alert('An error occurred while placing your bid. Please try again.');
        }
    };

    // Send the user ID, product ID, and bid amount to the server
    xhr.send('user_id=' + userId + '&product_id=' + productId + '&bid_amount=' + bidAmount);
}