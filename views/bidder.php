<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bidders KYC Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            max-width: 600px;
            margin-top: 50px;
        }
        .form-label {
            font-weight: bold;
        }
        .required {
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="mb-4">Bidders KYC Form</h2>
        <form action="submit_bidders.php" method="POST" enctype="multipart/form-data">
            <!-- Personal Information -->
            <div class="mb-3">
                <label for="full_name" class="form-label">Full Name <span class="required">*</span></label>
                <input type="text" class="form-control" id="full_name" name="full_name" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email <span class="required">*</span></label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Phone Number <span class="required">*</span></label>
                <input type="text" class="form-control" id="phone" name="phone" required>
            </div>
    
            
            <!-- Address Information -->
            <div class="mb-3">
                <label for="country" class="form-label">Country <span class="required">*</span></label>
                <input type="text" class="form-control" id="country" name="country" required>
            </div>
            <div class="mb-3">
                <label for="city" class="form-label">City <span class="required">*</span></label>
                <input type="text" class="form-control" id="city" name="city" required>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Address <span class="required">*</span></label>
                <input type="text" class="form-control" id="address" name="address" required>
            </div>
            
            <div class="mb-3">
                <label for="postal_code" class="form-label">Postal Code <span class="required">*</span></label>
                <input type="text" class="form-control" id="postal_code" name="postal_code" required>
            </div>
            
            
            <!-- Government ID Upload -->
            <div class="mb-3">
                <label for="gov_id_type" class="form-label">Government ID Type <span class="required">*</span></label>
                <select class="form-select" id="gov_id_type" name="gov_id_type" required>
                    <option value="">Select ID Type</option>
                    <option value="passport">Passport</option>
                    <option value="national_id">National ID</option>
                    <option value="driver_license">Driver's License</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="gov_id" class="form-label">Government ID Number <span class="required">*</span></label>
                <input type="text" class="form-control" id="gov_id" name="gov_id" required>
            </div>
            <div class="mb-3">
                <label for="id_upload" class="form-label">Upload Government ID (PDF or Image) <span class="required">*</span></label>
                <input type="file" class="form-control" id="id_upload" name="id_upload" accept="image/*,application/pdf" required>
            </div>

            <!-- Bidding Information -->
            <div class="mb-3">
                <label for="bank_account" class="form-label">Bank Account Number <span class="required">*</span></label>
                <input type="text" class="form-control" id="bank_account" name="bank_account" required>
            </div>

            <!-- Terms & Conditions -->
            <div class="mb-3">
                <input type="checkbox" id="terms" name="terms" required>
                <label for="terms" class="form-label">I agree to the <a href="#">Terms & Conditions</a></label>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary">Submit KYC</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
