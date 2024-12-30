<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_supplier.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<!-- MAIN -->       <main>
                <div class="head-title">
                    <div class="left">
                        <h1>Tea Leaves Supplier</h1>
                        <ul class="breadcrumb">
                            <li>
                                <a href="SupplyDashboard.html">Home</a>
                            </li>
                            <li><i class='bx bx-chevron-right'></i></li>
                            <li>
                                <a class="active" href="#">New Payment</a>
                            </li>
                        </ul>
                    </div>
    
                <div class="table-data">
                    <div class="order">
                        <div class="head">
                            <h3>Payment Form</h3>
                            <a href="Payments.php">
                                <button class="button">Back</button>
                            </a>
                        </div>
                        <form action="submit_complaint.php" method="post" class="complaint-form">
                            <div class="form-group">
                            <div class="form-group">
                                <label for="description">Full Name:</label>
                                <input type="fullname" id="fullname" name="fullname" required>
                            </div>
                                <label for="complaint-type">Payment Method: </label>
                                <select id="complaint-type" name="complaint_type" required>
                                    <option value="quality">Cash on delivery</option>
                                    <option value="other">other</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="Address">Address:</label>
                                <input type="address" id="address" name="address" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="email" id="email" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone Number:</label>
                                <input type="text" id="phone" name="phone">
                            </div>
                            <div class="form-group">
                                <label for="description">Description:</label>
                                <textarea id="description" name="description" rows="4" cols="50"></textarea>
                            </div>
                            <button type="submit" class="button" onclick="submitmessage()">Submit Payment</button>
                            <button type="submit" class="button" onclick="refreshPage()">Cancel</button>
                        </form>
                    </div>
                </div>
            </main>
            </div>
        </section>
        <script src="<?php echo URLROOT; ?>/css/script.js"></script>
    </body>
    </html>
    