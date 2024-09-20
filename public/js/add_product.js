document.getElementById('category').addEventListener('change', function() {
    const category = this.value;
    document.getElementById('painting-fields').style.display = 'none';
    document.getElementById('jewelry-fields').style.display = 'none';
    document.getElementById('antique-fields').style.display = 'none';

    if (category === '1') {
        document.getElementById('painting-fields').style.display = 'block';
    } else if (category === '2') {
        document.getElementById('jewelry-fields').style.display = 'block';
    } else if (category === '3') {
        document.getElementById('antique-fields').style.display = 'block';
    }
});

document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    
    form.addEventListener('submit', function (event) {
        // Initialize an array to store validation errors
        const errors = [];

        // Product Name validation
        const productName = document.getElementById('productName').value.trim();
        if (productName === '') {
            errors.push('Product Name is required.');
        }

        // Product Images validation
        const productImages = document.getElementById('productImages').files;
        if (productImages.length === 0) {
            errors.push('At least one product image is required.');
        }

        // Category validation
        const category = document.getElementById('category').value;
        if (category === '') {
            errors.push('Category is required.');
        }

        // Starting Bid validation
        const startingBid = parseFloat(document.getElementById('startingBid').value);
        if (isNaN(startingBid) || startingBid < 0) {
            errors.push('Starting Bid must be a positive number.');
        }

        // Price Interval validation
        const priceInterval = parseFloat(document.getElementById('priceInterval').value);
        if (isNaN(priceInterval) || priceInterval < 0) {
            errors.push('Minimum Price Interval must be a positive number.');
        }

        // Reserve Price validation (optional)
        const reservePrice = parseFloat(document.getElementById('reservePrice').value);
        if (!isNaN(reservePrice) && reservePrice < 0) {
            errors.push('Reserve Price must be a positive number.');
        }

        // Auction Start Date validation
        const startDate = document.getElementById('startDate').value;
        if (startDate === '') {
            errors.push('Auction Start Date is required.');
        }

        // Auction End Date validation
        const endDate = document.getElementById('endDate').value;
        if (endDate === '') {
            errors.push('Auction End Date is required.');
        }

        // Description validation
        const description = document.getElementById('description').value.trim();
        if (description === '') {
            errors.push('Description is required.');
        }

        // Show errors if there are any
        if (errors.length > 0) {
            alert(errors.join('\n'));
            event.preventDefault(); // Prevent form submission
        }
    });
});

