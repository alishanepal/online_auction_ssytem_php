function updateCountdown() {
    var countdownElements = document.querySelectorAll('.countdown');
    countdownElements.forEach(function (element) {
        var startTime = parseInt(element.getAttribute('data-start')); // Start time in seconds (for upcoming auctions)
        var endTime = parseInt(element.getAttribute('data-end')); // End time in seconds (for live auctions)
        var currentTime = Math.floor(Date.now() / 1000); // Current time in seconds

        if (startTime && currentTime < startTime) {
            // "Starts in" countdown for upcoming auctions
            var timeLeft = startTime - currentTime;
            element.innerHTML = `Starts in: ${formatTimeLeft(timeLeft)}`;
        } else if (endTime && currentTime < endTime) {
            // "Ends in" countdown for live auctions
            var timeLeft = endTime - currentTime;
            element.innerHTML = `Ends in: ${formatTimeLeft(timeLeft)}`;
        } else {
            // Auction has ended
            element.innerHTML = "Auction ended";
        }
    });
}

// Helper function to format the remaining time
function formatTimeLeft(seconds) {
    var days = Math.floor(seconds / (60 * 60 * 24));
    var hours = Math.floor((seconds % (60 * 60 * 24)) / (60 * 60));
    var minutes = Math.floor((seconds % (60 * 60)) / 60);
    var seconds = seconds % 60;

    return `${days}d ${hours}h ${minutes}m ${seconds}s`;
}

// Update countdown every second
setInterval(updateCountdown, 1000);

// Initial call to display the countdown on page load
updateCountdown();
