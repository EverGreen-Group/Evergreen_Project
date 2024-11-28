<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_supplier.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<!-- MAIN -->
         <main>
			<div class="head-title">
				<div class="left">
					<h1>Supplier Income</h1>
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

                
                <!-- <div class="table-data">
                    <div class="order">
                    <div class="chart-container">
                        <canvas id="incomeCostChart" width="400" height="100"></canvas>
                    </div>
                    </div>
                </div> -->
                <div class="table-data">
                <div class="order">
                        <div class="head">
                            <h3>Income</h3>
                        </div>
                        <div>
                            <table class="complaint-type">
                                <tr>
                                    <th>Income</th>
                                    <th>Date and Time</th>
                                    <th>Payment mode</th>
                                    <th>Status</th>
                                </tr>
                                <tr>
                                    <td>Rs. 60 000.00</td>
                                    <td>2024/11/09 12:32pm</td>
                                    <td>Cash</td>
                                    <td><button class="pending-btn">Pending</button></td>
                                </tr>
                                <tr>
                                    <td>Rs. 54 000.00</td>
                                    <td>2024/10/10 12:30pm</td>
                                    <td>Bank</td>
                                    <td><button class="accept-btn">Paid</button></td>

                                </tr>
                                <tr>
                                    <td>Rs. 53 000.00</td>
                                    <td>2024/09/10 11:40pm</td>
                                    <td>Cash</td>
                                    <td><button class="accept-btn">Paid</button></td>
                                </tr>
                                <tr>
                                    <td>Rs. 59 500.00</td>
                                    <td>2024/08/09 06:25pm</td>
                                    <td>Cash</td>
                                    <td><button class="accept-btn">Paid</button></td>
                                </tr>
                                <tr>
                                    <td>Rs. 71 000.00</td>
                                    <td>2024/07/10 08:41am</td>
                                    <td>Bank</td>
                                    <td><button class="accept-btn">Paid</button></td>

                                </tr>
                                <tr>
                                    <td>Rs. 59 000.00</td>
                                    <td>2024/06/11 10:56pm</td>
                                    <td>Bank</td>
                                    <td><button class="accept-btn">Paid</button></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="table-data">
                    <div class="order">
                        <div class="head">
                            <h3>Order History</h3>
                        </div>
                        <div>
                            <table class="complaint-type">
                                <tr>
                                    <th>Payment</th>
                                    <th>Date and Time</th>
                                    <th>Order type</th>
                                    <th>Status</th>
                                </tr>
                                <tr>
                                    <td>Rs. 21 000.00</td>
                                    <td>2024/11/10 12:32pm</td>
                                    <td>Fertilizer</td>
                                    <td><button class="pending-btn">Pending</button></td>
                                </tr>
                                <tr>
                                    <td>Rs. 4 000.00</td>
                                    <td>2024/11/10 12:32pm</td>
                                    <td>Tea Packets</td>
                                    <td><button class="accept-btn">Paid</button></td>

                                </tr>
                                <tr>
                                    <td>Rs. 21 000.00</td>
                                    <td>2024/11/10 12:32pm</td>
                                    <td>Tea Packets</td>
                                    <td><button class="accept-btn">Paid</button></td>
                                </tr>
                            </table>
                        </div>
                        <a href="<?php echo URLROOT; ?>/Supplier/" >
                            <button class="button">Home</button>
                        </a>
                        <a href="<?php echo URLROOT; ?>/Supplier/paymentanalysis" >
                            <button class="button">Analysis</button>
                        </a>
                    </div>
                </div>



        </main>
		</div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="<?php echo URLROOT; ?>/css/script.js"></script>
</body>
</html>
