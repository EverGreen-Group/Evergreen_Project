
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
							<a class="active" href="#">Payments</a>
						</li>
					</ul>
				</div>

            <div class="table-data">
                <div class="order">
                    <div class="head">
                        <h3>Payments</h3>
                    </div>
                    <ul class="list">
                        <li class="item">Payment of $100 for Tea Pockets submitted at 5:10pm</li>
                        <li class="item">Payment of $200 for Fertilizer submitted at 6:30pm</li>
                        <li class="item">Payment of $150 for Tea Pockets submitted at 5:15pm</li>
                        <li class="item">Payment of $250 for Fertilizer submitted at 6:30pm</li>
                    </ul>
                    <a href="PaymentHistory.php">
                        <button class="button">View Payment History</button>
                    </a>

                    <a href="NewPayment.php">
                        <button class="button">New Payment</button>
                    </a>
                </div>
            </div>
        </main>
		</div>
    </section>

    <script src="../public/script.js"></script>
</body>
</html>
