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
							<a class="active" href="#">Tea Order</a>
						</li>
					</ul>
				</div>

            <div class="table-data">
                <div class="order">
                    <div class="head">
                        <h3>Tea Packet Orders</h3>
                    </div>
                    <ul class="list">
                        <li class="item">Order of 1 Tea Packets submitted at 5:10pm</li>
                        <li class="item">Order of 2 Tea Packets submitted at 6:30pm</li>
                        <li class="item">Order of 3 Tea Packets submitted at 5:15pm</li>
                        <li class="item">Order of 1 Tea Packets submitted at 6:30pm</li>
                    </ul>
                    <a href="OrderHistory.php">
                        <button class="button">View History</button>
                    </a>
                    <a href="NewOrder.php">
                        <button class="button">New Order</button>
                    </a>
                </div>
            </div>
        </main>
		</div>
    </section>
    <script src="<?php echo URLROOT; ?>/css/components/script.js"></script>
</body>
</html>
