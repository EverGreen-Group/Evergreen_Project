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
							<a class="active" href="#">Payments</a>
						</li>
					</ul>
				</div>

                <div class="table-data">
                    <div class="order">
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
                                    <td><select id="" name="">
                                        <option value="Fertilizer" default>Fertilizer</option>
                                        <option value="Tea packets">Tea Packets</option>
                                    </select></td>
                                    <td><select id="" name="">
                                        <option value="Fertilizer">Pending</option>
                                        <option value="Tea packets">Paid</option>
                                        <option value="Tea packets">Cancelled</option>
                                        <option value="Tea packets">Rejected</option>
                                    </select></td>
                                </tr>
                                <tr>
                                    <td>Rs. 4 000.00</td>
                                    <td>2024/11/10 12:32pm</td>
                                    <td><select id="" name="">
                                        <option value="Fertilizer">Fertilizer</option>
                                        <option value="Tea packets">Tea Packets</option>
                                    </select></td>
                                    <td><select id="" name="">
                                        <option value="Fertilizer">Pending</option>
                                        <option value="Tea packets">Paid</option>
                                        <option value="Tea packets">Cancelled</option>
                                        <option value="Tea packets">Rejected</option>
                                    </select></td>
                                </tr>
                                <tr>
                                    <td>Rs. 21 000.00</td>
                                    <td>2024/11/10 12:32pm</td>
                                    <td><select id="" name="">
                                        <option value="Fertilizer">Fertilizer</option>
                                        <option value="Tea packets">Tea Packets</option>
                                    </select></td>
                                    <td><select id="" name="">
                                        <option value="Fertilizer">Pending</option>
                                        <option value="Tea packets">Paid</option>
                                        <option value="Tea packets">Cancelled</option>
                                        <option value="Tea packets">Rejected</option>
                                    </select></td>
                                </tr>
                            </table>
                        </div>
                        <a href="v_all_notifications.php">
                            <button class="button">Home</button>
                        </a>
                    </div>
                </div>
        </main>
		</div>
    </section>

    <script src="../public/script.js"></script>
</body>
</html>
