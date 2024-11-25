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
                        <label for="complaint-type">Complaint Type:</label>
                        <select id="complaint-type" name="complaint_type" required>
                            <option value="quality">Quality</option>
                            <option value="service">Service</option>
                            <option value="delivery">Delivery</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="description">Description:</label>
                        <textarea id="description" name="description" rows="5" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="email">Your Email:</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number:</label>
                        <input type="text" id="phone" name="phone">
                    </div>
                    <button type="submit" class="button" onclick="submitmessage()">Submit Complaint</button>
                    <button type="button" class="button" onclick="refreshPage()">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</main>

<script>document.addEventListener('DOMContentLoaded', () => {
    const bars = document.querySelectorAll('.bar');

    bars.forEach(bar => {
        const percentage = bar.dataset.percentage;
        const fill = bar.querySelector('.fill');
        fill.style.width = percentage + '%';
    });
});
</script>
<script src="<?php echo URLROOT; ?>/css/script.js"></script>
</body>
</html>
