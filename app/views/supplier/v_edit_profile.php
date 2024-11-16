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
							<a href="SupplyDashboard.php">Home</a>
						</li>
						<li><i class='bx bx-chevron-right'></i></li>
						<li>
							<a class="active" href="#">Profile</a>
						</li>
					</ul>
				</div>
                
            <div class="table-data">
                <div class="order">
                    <div class="head">
                        <h3>Profile</h3>
                    </div>
                    
                    <form action="../../config/Requests.php" method="post" class="complaint-form">
                        <input type="hidden" value="<?php echo $supplier_id; ?>">
                            <div class="form-group">
                                <label for="fullname">Full Name:</label>
                                <input type="text" id="fullname" name="fullname" required>
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
                                <label for="contact">Contact:</label>
                                <input type="text" id="contact" name="contact" >
                            </div>
                            <button type="submit" class="button" onclick="submitmessage()">Submit Request</button>
                            <button type="submit" class="button" onclick="refreshPage()">Cancel</button>
                        </form>
                    
                    <!--
                    <table class="profile-info">
                        <tr>
                            <td><label for="fullname">Full Name:</label></td>
                            <td><td>
                        </tr>
                        <tr>
                            <td><label for="address">Address:</label></td>
                            <td><td>
                        </tr>
                        <tr>
                            <td><label for="email">Email:</label></td>
                            <td><td>
                        </tr>
                        <tr>
                            <td><label for="contact">Contact:</label></td>
                            <td><td>
                        </tr>
                        <tr>
                            <td><label for="supplierid">Supplier ID:</label></td>
                            <td><td>
                        </tr>
                        <tr>
                            <td><label for="route">Route No:</label></td>
                            <td><td>
                        </tr>
                        <tr>
                            <td><label for="date">Registered Date:</label></td>
                            <td><td>
                        </tr>
                    </table>-->
                    <div class="buttons">
                        <a href="Profile.php">
                            <button class="button">Back</button>
                        </a>
                    </div>
                </div>
            </div>
        </main>
        </div>
    </section>
    <script src="<?php echo URLROOT; ?>/css/components/script.js"></script>
</body>
</html>


    
