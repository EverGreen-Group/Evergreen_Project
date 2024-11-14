
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
							<a class="active" href="#">Complaint</a>
						</li>
					</ul>
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
