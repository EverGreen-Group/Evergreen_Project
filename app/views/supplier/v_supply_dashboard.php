<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_supplier.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<!-- MAIN -->
<main>
    <div class="head-title">
        <div class="left">
            <h1>Tea Leaves Supplier Dashboard</h1>
            <ul class="breadcrumb">
                <li><a href="#">Dashboard</a></li>
            </ul>
        </div>
        <a href="#" class="btn-download">
            <i class='bx bxs-cloud-download'></i>
            <span class="text">Download Report</span>
        </a>
    </div>

    <!-- Box Info -->
    <ul class="box-info">
        <li>
            <i class='bx bxs-calendar-check'></i>
            <span class="text">
                <h3><?php echo isset($data['total_collections']) ? $data['total_collections'] : '0'; ?></h3>
                <p>Total Collections</p>
                <small>This Month</small>
            </span>
        </li>
        <li>
            <i class='bx bxs-leaf'></i>
            <span class="text">
                <h3><?php echo isset($data['total_quantity']) ? $data['total_quantity'] . 'kg' : '0kg'; ?></h3>
                <p>Total Tea Leaves</p>
                <small>This Month</small>
            </span>
        </li>
        <li>
            <i class='bx bxs-dollar-circle'></i>
            <span class="text">
                <h3>Rs. <?php echo isset($data['total_earnings']) ? number_format($data['total_earnings'], 2) : '0.00'; ?></h3>
                <p>Total Earnings</p>
                <small>This Month</small>
            </span>
        </li>
    </ul>

    <div class="table-data">
        <!-- Pending Confirmation Requests -->
        <div class="order">
            <div class="head">
                <h3>Pending Confirmation Requests</h3>
                <i class='bx bx-search'></i>
                <i class='bx bx-filter'></i>
            </div>
            <?php if(isset($data['pending_requests']) && !empty($data['pending_requests'])): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Request ID</th>
                            <th>Date</th>
                            <th>Quantity (kg)</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data['pending_requests'] as $request): ?>
                            <tr>
                                <td><?php echo $request['id']; ?></td>
                                <td><?php echo $request['date']; ?></td>
                                <td><?php echo $request['quantity']; ?></td>
                                <td><?php echo $request['status']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div>No pending requests</div>
            <?php endif; ?>
            <a href="ConfirmationHistory.php">
                <button class="button">View History</button>
            </a>
        </div>
        <div class="todo">
            <div class="head">
                <h3>Scheduled Collection Dates</h3>
            </div>
            <ul class="scheduled-dates">
                <li>Tomorrow <span class="time"> 05:00pm</span></li>
                <li>13/08/2024 <span class="time"> 03:00pm</span></li>
                <li>01/08/2024 <span class="time"> 06:00pm</span></li>
            </ul>
            <a href="CancelPickup.php">
                <button class="button">Cancel Pickup</button>
            </a>
        </div>
    </div>

</main>

<script src="../public/script.js"></script>
</body>
</html>
