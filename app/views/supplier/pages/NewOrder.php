
<section id="content">
        
        <div class="content-wrapper">
        <?php include '../components/navbar.php'; ?>
        <?php include '../components/sidebar.php'; ?>
    
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
                                <a class="active" href="#">New Order</a>
                            </li>
                        </ul>
                    </div>
    
                <div class="table-data">
                    <div class="order">
                        <div class="head">
                            <h3>Order Form</h3>
                            <a href="OrderPage.php">
                            <button class="button">Back</button>
                        </div>
                        <form action="submit_complaint.php" method="post" class="complaint-form">
                            <div class="form-group">
                            <div class="form-group">
                                <label for="description">Full Name:</label>
                                <input type="fullname" id="fullname" name="fullname" required>
                            </div>
                                <label for="complaint-type">Packet Quantity: </label>
                                <select id="complaint-type" name="complaint_type" required>
                                    <option value="quality">1</option>
                                    <option value="service">2</option>
                                    <option value="delivery">5</option>
                                    <option value="other">10</option>
                                    <option value="delivery">20</option>
                                    <option value="other">30</option>
                                    <option value="other">other</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="description">Address:</label>
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
                            <button type="submit" class="button" onclick="submitmessage()">Submit Request</button>
                            <button type="submit" class="button" onclick="refreshPage()">Cancel</button>
                        </form>
                    </div>
                </div>
            </main>
            </div>
        </section>
    
        <script src="../public/script.js"></script>
    </body>
    </html>
    