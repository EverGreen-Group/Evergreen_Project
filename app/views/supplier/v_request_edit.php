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
                            <a href="SupplyDashboard.html">Home </a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="#"> Edit Fertilizer Requests</a>
                        </li>
                    </ul>
                </div>

                <div class="table-data">
                    <div class="order">
                        <h2>Edit Fertilizer Request</h2>
                        <div class="head">
                            <h4>Current Order Details</h4>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Fertilizer Type</th>
                                        <th>Amount</th>
                                        <th>Unit</th>
                                        <th>Price Per Unit</th>
                                        <th>Total Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><?php echo $data['order']->order_id; ?></td>
                                        <td><?php echo $data['order']->fertilizer_name; ?></td>
                                        <td><?php echo $data['order']->total_amount; ?></td>
                                        <td><?php echo $data['order']->unit; ?></td>
                                        <td><?php echo $data['order']->price_per_unit; ?></td>
                                        <td><?php echo $data['order']->total_price; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <form id="fertilizerForm" method="POST" class="complaint-form" action="<?php echo URLROOT . '/supplier/editFertilizerRequest/' . $data['order']->order_id; ?>">
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="type_id">Fertilizer Type:</label>

                                    <div>
                                        <select id="type_id" name="type_id" required>
                                            <option value="">Select Fertilizer</option>
                                            <?php foreach($data['fertilizer_types'] as $type): ?>
                                                <option value="<?php echo $type->type_id; ?>" 
                                                        <?php echo ($data['order']->type_id == $type->type_id) ? 'selected' : ''; ?>
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
                                            <option value="kg" <?php echo ($data['order']->unit == 'kg') ? 'selected' : ''; ?>>Kilograms (kg)</option>
                                            <option value="packs" <?php echo ($data['order']->unit == 'packs') ? 'selected' : ''; ?>>Packs</option>
                                            <option value="box" <?php echo ($data['order']->unit == 'box') ? 'selected' : ''; ?>>Box</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="total_amount">Total Amount:</label>
                                        <input type="number" id="total_amount" name="total_amount" max="50" min="1" value="<?php echo $data['order']->total_amount; ?>" required>
                                    </div>
                                    <input type="hidden" id="price_per_unit" name="price_per_unit">
                                    <input type="hidden" id="total_price" name="total_price">
                                <button type="submit" class="button">Update Request</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
</section>
<div id="notification" class="notification" style="display: none;"></div>
<script src="<?php echo URLROOT; ?>/css/components/script.js"></script>
</body>
</html>