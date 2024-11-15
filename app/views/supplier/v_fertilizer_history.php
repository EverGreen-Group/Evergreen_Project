
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
                            <a href="SupplyDashboard.html">Home ></a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="#"> Fertilizer Requests History</a>
                        </li>
                    </ul>
                </div>

                <div class="table-data">
                    <div class="order">
                        <div class="head">
                            <h3>Fertilizer Requests History</h3>
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
                                <tr>

                                    <?php
                                        while($row = mysqli_fetch_assoc($result))
                                        {
                                    ?>
                                    <td><?php echo $row['request_id'] ?></td>
                                    <td><?php echo $row['supplier_id'] ?></td>
                                    <td><?php echo $row['total_amount'] ?></td>
                                    <td>Request submitted on <?php echo $row['date_and_time'] ?></td>
                                </tr>
                                    <?php
                                        }
                                    ?>ll
                            </tbody>
                        </table>

                        
                        <a href="FertilizerPage.php">
                            <button class="button">Back</button>
                        </a>
                    </div>
                </div>
            </div>
        </main>
    </div>
</section>

<script src="../public/script.js"></script>
</body>
</html>
