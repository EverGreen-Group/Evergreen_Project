
<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_supplier.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<!-- MAIN -->
        <main><div class="head-title">
			<div class="left">
				<h1>Tea Leaves Supplier</h1>
				<ul class="breadcrumb">
					<li>
						<a href="SupplyDashboard.html">Home</a>
					</li>
					<li><i class='bx bx-chevron-right'></i></li>
					<li>
						<a class="active" href="#">Notifications</a>
					</li>
				</ul>
			</div>

            <div class="table-data">
                <div class="order">
                    <div class="head">
                        <h3>Notifications</h3>
                    </div>
                    <div class="list">
                        <div class="item">
                            <p>Driver sent you a collection late message</p>
                            <span class="time">5 minutes ago</span>
                        </div>
                        <div class="item">
                            <p>Reminder of leaf collection today at 10:00pm</p>
                            <span class="time">1 hour ago</span>
                        </div>
                        <div class="item">
                            <p>Reminder for leaf collection 2 days from now</p>
                            <span class="time">Yesterday</span>
                        </div>
                        <div class="item">
                            <p>Leaf collection confirmed</p>
                            <span class="time">2 days ago</span>
                        </div>
                        <div class="item">
                            <p>Leaf collection confirm request</p>
                            <span class="time">3 days ago</span>
                        </div>
                    </div>
                    <a href="AllNotifications.php">
                        <button class="button">View All Notifications</button>
                    </a>
                </div>
            </div>
        </main>
        </div>
    </section>
    <script src="<?php echo URLROOT; ?>/css/script.js"></script>
</body>
</html>
