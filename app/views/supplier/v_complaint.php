<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_supplier.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<!-- MAIN -->
<main>
    <div class="head-title">
        <div class="left">
            <h1>Tea Leaves Supplier</h1>
            <ul class="breadcrumb">
                <li>
                    <a href="SupplyDashboard.html">Home</a>
                </li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li>
                    <a class="active" href="#">Complaint</a>
                </li>
            </ul>
        </div>


                <?php
        // Mock data for ratings
        $ratings = [
            5 => 50,
            4 => 30,
            3 => 15,
            2 => 10,
            1 => 5
        ];

        // Calculate the total number of ratings
        $totalRatings = array_sum($ratings);
        ?>

        <!-- Ratings Section -->
        <div class="rating">
            <div>
                <h4>Average Rating:</h4>
                <span class="stars">
                    <i class="bx bxs-star" style="color: gold;"></i>
                    <i class="bx bxs-star" style="color: gold;"></i>
                    <i class="bx bxs-star" style="color: gold;"></i>
                    <i class="bx bxs-star" style="color: gold;"></i>
                    <i class="bx bxs-star-half" style="color: gold;"></i>
                </span>
                <p>4.5 out of 5 stars</p>
            </div>
        </div>
        <div id="review-container">
            <!-- Feedback Section -->
            <div class="reviews">
                <ul>
                    <h4>Reviews:</h4>
                    <li><strong>Jane Doe:</strong> "Excellent service and timely delivery! Couldn't be happier."</li>
                    <li><strong>John Smith:</strong> "The quality of tea leaves is top-notch. Highly recommend this supplier."</li>
                    <li><strong>Emily White:</strong> "Had a minor issue, but the customer service resolved it promptly. Great experience!"</li>
                </ul>
            </div>
        </div>
        <div class="table-data">
            <div class="order">
                <div class="head">
                    <h3>Complaint Form</h3>
                </div>
                <form action="submit_complaint.php" method="post" class="complaint-form">
                    <div class="form-group">
                        <label>Complaint Type:</label>
                        <select id="complaint-type" name="complaint_type" required onchange="toggleImageUpload()">
                            <option value="quality">Quality</option>
                            <option value="delivery">Orders</option>
                            <option value="service">Service</option>
                            <option value="delivery">Delivery</option>
                            <option value="delivery">Inquiry</option>
                            <option value="other">Other(State in the description)</option>
                        </select>
                    </div>
                    <div class="form-group file-input-wrapper">
                        <label for="complaint-image">Upload Image:</label>
                        <input type="file" 
                            id="complaint-image" 
                            name="complaint_image" 
                            accept="image/jpeg,image/png,image/gif">
                        <small>Supported formats: JPG, PNG, GIF (Max size: 5MB)</small>
                    </div>
                    <div class="form-group">
                        <textarea id="description" name="description" rows="5" placeholder="Enter Description" required></textarea>
                    </div>
                    <div class="form-group">
                        <input type="email" id="email" name="email" placeholder="Enter Email" required>
                    </div>
                    <div class="form-group">
                        <input type="text" id="phone" name="phone" placeholder="Enter Phone Number">
                    </div>
                    <button type="submit" class="button" onclick="submitmessage()">Submit Complaint</button>
                    <button type="button" class="button" onclick="refreshPage()">Cancel</button>
                </form>
            </div>
        </div>
        <!-- After the complaint form, add this -->
        <div class="chat-container" id="complaint-chat-container" style="display:none;">
            <div class="chat-header">
                <span>Complaint Chat</span>
                <button id="close-chat">×</button>
            </div>
            <div class="chat-messages" id="chat-messages">
                <!-- Messages will be dynamically loaded here -->
            </div>
            <div class="chat-input-container">
                <input type="text" id="chat-input" placeholder="Type your message...">
                <button id="chat-send-button">Send</button>
            </div>
            <input type="hidden" id="complaint-id" value="<?php echo $complaintId; ?>">
        </div>
    </div>
</main>
<style>
        .form-container {
            max-width: 600px;
            margin: 0 auto;
        }
        .form-group {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        .form-group label {
            width: 130px; 
            margin-right: 10px;
            text-align: right;
        }
        .form-group input, 
        .form-group select, 
        .form-group textarea {
            flex: 1;
            padding: 8px;
        }
        .file-input-container {
            display: flex;
            align-items: center;
        }
        .file-input-container small {
            margin-left: 10px;
            color: #666;
        }
        .button-group {
            display: flex;
            justify-content: center;
            gap: 10px;
        }
        .file-input-wrapper {
            display: flex;
            align-items: center;
        }
        .file-input-wrapper input[type="file"] {
            margin-left: 20px; 
        }
        .file-input-wrapper small {
            margin-left: 10px;
        }
    </style>
<script>
    document.addEventListener('DOMContentLoaded', () => {
    const bars = document.querySelectorAll('.bar');

    bars.forEach(bar => {
        const percentage = bar.dataset.percentage;
        const fill = bar.querySelector('.fill');
        fill.style.width = percentage + '%';
    });
});
</script>
<script src="<?php echo URLROOT; ?>/css/script.js"></script>
<script>
function toggleImageUpload() {
    const complaintType = document.getElementById('complaint-type');
    const imageSection = document.getElementById('image-upload-section');
    
    // Show image upload only for certain complaint types
    if (complaintType.value === 'quality' || complaintType.value === 'other') {
        imageSection.style.display = 'block';
    } else {
        imageSection.style.display = 'none';
        // Clear the file input when hidden
        document.getElementById('complaint-image').value = '';
    }
}

// Add this event listener to ensure the function runs on page load
document.addEventListener('DOMContentLoaded', () => {
    toggleImageUpload();
});

function validateImageUpload(input) {
    const errorDisplay = document.getElementById('image-error');
    errorDisplay.textContent = '';

    if (input.files && input.files[0]) {
        const fileSize = input.files[0].size / 1024 / 1024; // in MB
        const fileType = input.files[0].type;

        // Check file size (max 5MB)
        if (fileSize > 5) {
            errorDisplay.textContent = 'File size exceeds 5MB limit.';
            input.value = ''; // Clear the input
            return false;
        }

        // Check file type
        const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!allowedTypes.includes(fileType)) {
            errorDisplay.textContent = 'Invalid file type. Please upload JPG, PNG, or GIF.';
            input.value = ''; // Clear the input
            return false;
        }

        return true;
    }
}

function resetForm() {
    document.getElementById('complaintForm').reset();
    document.getElementById('image-upload-section').style.display = 'none';
}
</script>
</body>
</html>
