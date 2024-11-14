
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
                                <a href="SupplyDashboard.html">Home > </a>
                            </li>
                            <li><i class='bx bx-chevron-right'></i></li>
                            <li>
                                <a class="active" href="#"> New Request</a>
                            </li>
                        </ul>
                    </div>
    
                <div class="table-data">
                    <div class="order">
                        <div class="head">
                            <h3>Request Form</h3>
                            <a href="FertilizerPage.php">
                            <button class="button">Back</button>
                            </a>
                        </div>

                        <form action="../../config/Requests.php" method="post" class="complaint-form">
                            <div class="form-group">
                            <div class="form-group">
                                <label for="supplier_id">Supplier ID:</label>
                                <input type="text" id="supplier_id" name="supplier_id" >
                            </div>
                            <div>
                                <label for="complaint-type">Total Amount: </label>
                                <select id="complaint-type" name="total_amount" required>
                                    <option value="1">1kg</option>
                                    <option value="2">2kg</option>
                                    <option value="5">5kg</option>
                                    <option value="10">10kg</option>
                                    <option value="other">other</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="address">Address:</label>
                                <input type="address" id="address" name="address" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="email" id="email" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone Number:</label>
                                <input type="text" id="phone" name="phone_number" >
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
    