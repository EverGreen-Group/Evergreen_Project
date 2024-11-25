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
                            <a class="active" href="#">Payment Analysis</a>
                        </li>
                    </ul>
                </div>

                <div class="table-data">
                    <div class="order">
                        <div class="head">
                            <h3>Yearly Payment Analysis</h3>
                        </div>
                        <div class="todo">
                            <div class="head">
                                <i class='bx bx-plus'></i>
                                <i class='bx bx-filter'></i>
                            </div>
                            <canvas id="paymentAnalysisChart" width="500" height="500"></canvas>
                        </div>
                        <a href="<?php echo URLROOT; ?>/Supplier/payments/" >
                            <button class="button">Back</button>
                        </a>
                    </div>
                    <div class="order">
                        <div class="head">
                        </div>
                        <div class="todo">
                            <div class="head">
                                <i class='bx bx-plus'></i>
                                <i class='bx bx-filter'></i>
                            </div>
                            <canvas id="comparePaymentsChart" width="400" height="500"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-data">
                    <div class="order">
                        <div class="head">
                            <h3>Monthly Analysis</h3>
                        </div>
                        <div>
                            <table class="complaint-type payments-table">
                                <tr>
                                    <th></th>
                                    <th>January</th>
                                    <th>February</th>
                                    <th>March</th>
                                    <th>April</th>
                                    <th>May</th>
                                    <th>June</th>
                                    <th>July</th>
                                    <th>August</th>
                                    <th>September</th>
                                    <th>October</th>
                                    <th>November</th>
                                    <th>December</th>
                                </tr>
                                <tr>
                                    <td>Income</td>
                                    <td>25,000.00</td>
                                    <td>45,000.00</td>
                                    <td>30,000.00</td>
                                    <td>24,000.00</td>
                                    <td>41,000.00</td>
                                    <td>56,000.00</td>
                                    <td>65,000.00</td>
                                    <td>61,500.00</td>
                                    <td>65,000.00</td>
                                    <td>53,000.00</td>
                                    <td>71,350.00</td>
                                    <td>0.00</td>
                                </tr>
                                <tr>
                                    <td>Payments</td>
                                    <td>2,000.00</td>
                                    <td>47,000.00</td>
                                    <td>23,000.00</td>
                                    <td>20,000.00</td>
                                    <td>47,000.00</td>
                                    <td>3,000.00</td>
                                    <td>7,000.00</td>
                                    <td>4,500.00</td>
                                    <td>51,000.00</td>
                                    <td>8,000.00</td>
                                    <td>9,350.00</td>
                                    <td>0.00</td>
                                </tr>
                                <tr>
                                    <td>Profit</td>
                                    <td>23,000.00</td>
                                    <td>0.00</td>
                                    <td>7,000.00</td>
                                    <td>1,000.00</td>
                                    <td>21,000.00</td>
                                    <td>9,000.00</td>
                                    <td>62,000.00</td>
                                    <td>54,500.00</td>
                                    <td>65,000.00</td>
                                    <td>2,000.00</td>
                                    <td>62,350.00</td>
                                    <td>0.00</td>
                                </tr>
                                <tr>
                                    <td>Loss</td>
                                    <td>0.00</td>
                                    <td>2,000.00</td>
                                    <td>0.00</td>
                                    <td>0.00</td>
                                    <td>0.00</td>
                                    <td>0.00</td>
                                    <td>0.00</td>
                                    <td>0.00</td>
                                    <td>0.00</td>
                                    <td>0.00</td>
                                    <td>0.00</td>
                                    <td>0.00</td>
                                </tr>
                            </table>
                        </div>
                        <a href="<?php echo URLROOT; ?>/Supplier/" >
                            <button class="button">Home</button>
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
