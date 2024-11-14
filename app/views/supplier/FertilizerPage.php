
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
							<a href="SupplyDashboard.html">Home ></a>
						</li>
						<li><i class='bx bx-chevron-right'></i></li>
						<li>
							<a class="active" href="#"> Fertilizer</a>
						</li>
					</ul>
				</div>

            <div class="table-data">
                <div class="order">
                    <div class="head">
                        <h3>Fertilizer Requests</h3>
                    </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th id="table-head">Request id</th>
                                <th id="table-head">Supplier id</th>
                                <th id="table-head">Amount in kg</th>
                                <th id="table-head">Notification</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php /*if (!empty($result)):*/ ?>    
                                <?php foreach ($result as $row): ?>
                                    <tr>
                                        <td><?php echo $result['request_id']; ?></td>
                                        <td><?php echo $result['supplier_id']; ?></td>
                                        <td><?php echo $result['total_amount']; ?></td>
                                        <td>Request submitted on <?php echo $result['date_and_time']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php /*else:*/ ?>
                               <!-- <tr><td colspan="4">No data available in FertilizerPage.</td></tr>-->
                            <?php /*endif;*/ ?>
                        </tbody>
                    </table>

                    <a href="FertilizerHistory.php">
                        <button class="button">View History</button>
                    </a>
                    <a href="FertilizerRequest.php">
                        <button class="button">New Request</button>
                    </a>
                </div>
            </div>
        </main>
		</div>
    </section>

    <script src="../public/script.js"></script>
</body>
</html>











<!--<td>Fertilizer request duplicated</td>
    <td>5:10 pm</td>
</tr>
<tr>
    <td>Fertilizer request approved</td>
    <td>N/A</td>
</tr>
<tr>
    <td>Fertilizer request submitted</td>
    <td>3:10 am</td>
</tr>
<tr>
    <td>Fertilizer allowed</td>
    <td>N/A</td>-->


<tr>

<?php /*
                                    while($row = mysqli_fetch_assoc($requests))
                                    {
                                ?>
                                <td><?php echo $row['request_id'] ?></td>
                                <td><?php echo $row['supplier_id'] ?></td>
                                <td><?php echo $row['total_amount'] ?></td>
                                <td>Request submitted on <?php echo $row['date_and_time'] ?></td>
                            </tr>
                                <?php
                                    }
                                ?>*/
