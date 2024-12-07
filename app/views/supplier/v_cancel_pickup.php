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
                                <a class="active" href="#">Cancel Pickup</a>
                            </li>
                        </ul>
                    </div>

        <div class="table-data">
        <div class="order">
        
        <div class="head">
            <h1>Pickup Request</h1>
        </div>
            <form action="#" method="POST" class="complaint-form" id="cancelPickupForm">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required>
                </div>

                <div class="form-group">
                    <label for="pickup_date">Pickup Date:</label>
                    <input type="date" id="pickup_date" name="pickup_date" required>
                </div>

                <div class="form-group">
                    <label for="pickup_time">Pickup Time:</label>
                    <input type="time" id="pickup_time" name="pickup_time" required>
                </div>

                <div class="form-group">
                    <label for="quantity">Estimated Quantity (kg):</label>
                    <input type="number" id="quantity" name="quantity" min="1" required>
                </div>

                <button type="submit" class="button" onclick="showSuccessMessage(event)">send request</button>
            </form>
                <a href="<?php echo URLROOT; ?>/Supplier/" >
                    <button class="button">Back</button>
                </a>
        </div>
    </section>
    
    <!-- Add this script before the closing body tag -->
    <script>
        function showSuccessMessage(event) {
            event.preventDefault(); // Prevent form submission
            alert("Request sent successfully!");
            document.getElementById('cancelPickupForm').submit(); // Submit the form
        }
    </script>
    <script src="<?php echo URLROOT; ?>/css/script.js"></script>
</body>
</html>
