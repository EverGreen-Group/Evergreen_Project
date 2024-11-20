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
                                <a href="SupplyDashboard.html">Home > </a>
                            </li>
                            <li><i class='bx bx-chevron-right'></i></li>
                            <li>
                                <a class="active" href="#"> Fertilizer Requests</a>
                            </li>
                        </ul>
                    </div>
    
                    <div class="table-data">
                        <div class="todo">
                            <div class="head">
                                <h3>Fertilizer Request History</h3>
                                <i class='bx bx-plus'></i>
                                <i class='bx bx-filter'></i>
                            </div>
                            <canvas id="fertilizerRequestChart" ></canvas>
                        </div>
                        <div class="tea-order-history">
                            <div class="head">
                                <i class='bx bx-plus'></i>
                                <i class='bx bx-filter'></i>
                            </div>
                            <canvas id="fertilizerChart" width="500" height="400"></canvas>
                        </div>
                    </div>

                
                <div class="table-data">
                    <div class="order">
                        <div class="head">
                            <h3>Fertilizer Requests History</h3>
                        </div>
                        <table class="table"> 
                            <thead>
                                <tr>
                                    <th>Order id</th>
                                    <th>Fertilizer Type</th>
                                    <th>Order Date</th>
                                    <th>Order Time</th>
                                    <th>Amount</th>
                                    <th>Price</th>
                                    <th>Payment Status</th>
                                    <th>Update order</th>
                                    <th>Cancel order</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                    <?php foreach ($data['orders'] as $order): ?>
                                        <tr>
                                            <td><?php echo $order->order_id; ?></td> 
                                            <td><?php echo $order->fertilizer_name; ?></td>
                                            <td><?php echo $order->order_date; ?></td>
                                            <td><?php echo $order->order_time; ?></td>
                                            <td><?php echo $order->total_amount . ' ' . $order->unit; ?></td>
                                            <td><?php echo $order->total_price; ?></td>
                                            <td><?php echo isset($order->payment_status) ? $order->payment_status : 'Pending'; ?></td>
                                            <td>
                                                <a href="<?php echo URLROOT; ?>/Supplier/editFertilizerRequest/<?php echo $order->order_id; ?>" class="btn-edit btn-primary">
                                                    Edit
                                                </a>
                                            </td>
                                            <td>
                                                <a href="<?php echo URLROOT; ?>/Supplier/deleteFertilizerRequest/<?php echo $order->order_id; ?>" class="btn-delete btn-primary">
                                                    Delete
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                
                            </tbody>
                        </table>
                    </div>


                    <div class="table-data">
                        <div class="order">
                            <div class="head">
                                <h3>Request Form</h3>
                                <a href="FertilizerPage.php">
                                <button class="button">Dashboard</button>
                                </a>
                            </div>

                            <form action="<?php echo URLROOT; ?>/supplier/createFertilizerOrder" method="POST" class="complaint-form" id="fertilizerForm">
                                <div class="form-group">
                                    <input type="hidden" id="supplier_id" name="supplier_id" value="<?php echo $_SESSION['supplier_id']; ?>">
                                    
                                    <div class="form-group">
                                        <label for="type_id">Fertilizer Type:</label>
                                        <select id="type_id" name="type_id" required>
                                            <option value="">Select Fertilizer</option>
                                            <?php foreach($data['fertilizer_types'] as $type): ?>
                                                <option value="<?php echo $type->type_id; ?>" 
                                                        data-unit-price-kg="<?php echo $type->unit_price_kg; ?>"
                                                        data-pack-price="<?php echo $type->unit_price_packs; ?>"
                                                        data-box-price="<?php echo $type->unit_price_box; ?>">
                                                    <?php echo $type->name; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="unit">Unit:</label>
                                        <select id="unit" name="unit" required>
                                            <option value="">Select Unit</option>
                                            <option value="kg">Kilograms (kg)</option>
                                            <option value="packs">Packs</option>
                                            <option value="box">Box</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="total_amount">Total Amount:</label>
                                        <input type="number" id="total_amount" name="total_amount" min="1" max="50" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="price_per_unit">Price Per Unit:</label>
                                        <input type="number" id="price_per_unit" name="price_per_unit" readonly>
                                    </div>

                                    <div class="form-group">
                                        <label for="total_price">Total Price:</label>
                                        <input type="number" id="total_price" name="total_price" readonly>
                                    </div>
                                    <button type="submit" class="button">Submit Request</button>
                                    <button type="button" class="button" onclick="document.getElementById('fertilizerForm').reset()">Cancel</button>
                                </div>
                            </form>
                            
                        </div>
                    </div>
                </div>
            </main>
        </section>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        window.FERTILIZER_TYPES = <?php echo json_encode($data['fertilizer_types']); ?>;
    </script>
    <script src="<?php echo URLROOT; ?>/css/components/script.js"></script>
</html>
</body>
    