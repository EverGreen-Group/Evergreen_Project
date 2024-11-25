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
							<a class="active" href="#">Change Password</a>
						</li>
					</ul>
				</div>
                
            <div class="table-data">
                <div class="order">
                    <div class="head">
                        <h3>Change Password</h3>
                    </div>

            <form action="#" method="POST" class="complaint-form">
                <div class="form-group">
                    <label for="current-password">Current Password:</label>
                    <input type="password" id="current-password" name="current-password" required>
                </div>

                <div class="form-group">
                    <label for="new-password">New Password:</label>
                    <input type="password" id="new-password" name="new-password" required>
                </div>

                <div class="form-group">
                    <label for="confirm-password">Confirm New Password:</label>
                    <input type="password" id="confirm-password" name="confirm-password" required>
                </div>

                <button type="submit" class="button">Change Password</button>
            </form>
            <a href="Profile.php">
                <button class="button">Back</button>
            </a>
        </div>
    </section>
    <script src="<?php echo URLROOT; ?>/css/script.js"></script>
</body>
</html>