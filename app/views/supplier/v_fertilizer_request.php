<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_supplier.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<!-- MAIN -->
<main>
    <div class="head-title">
        <div class="left">
            <h1>Fertilizer Requests</h1>
            <ul class="breadcrumb">
                <li>
                    <a href="SupplyDashboard.html">Home </a>
                </li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li>
                    <a class="active" href="#"> Fertilizer Requests</a>
                </li>
            </ul>
        </div>

        <!-- <div class="table-data">
                        <div class="todo">
                            <div class="head">
                                <h3>Fertilizer Request History</h3>
                                <h5>Last 6 months</h5>
                                <i class='bx bx-plus'></i>
                                <i class='bx bx-filter'></i>
                            </div>
                            <canvas id="fertilizerRequestChart" ></canvas>
                        </div>
                        <div class="todo">
                            <div class="head">
                                <h5>This year</h5>
                                <i class='bx bx-plus'></i>
                                <i class='bx bx-filter'></i>
                            </div>
                            <canvas id="fertilizerChart" width="500" height="400"></canvas>
                        </div>
                    </div> -->


    <div class="table-data">
        <div class="order">
            
            <ul class="box-info">
                <li>
                    <i class='bx bxs-paper-plane'></i>
                    <span class="text">
                        <h3><?php echo $data['request_counts']['total']; ?></h3>
                        <p>Total requests</p>
                    </span>
                </li>
                <li>
                    <i class='bx bxs-check-circle'></i>
                    <span class="text">
                        <h3><?php echo $data['request_counts']['accepted']; ?></h3>
                        <p>Accepted</p>
                    </span>
                </li>
                <li>
                    <i class='bx bxs-x-circle'></i>
                    <span class="text">
                        <h3><?php echo $data['request_counts']['rejected']; ?></h3>
                        <p>Rejected</p>
                    </span>
                </li>
            </ul>

            <div class="head">
                <h3>Available Fertilizer Types</h3>
            </div>
            
            <div class="fertilizer-selector">
                <select id="fertilizerSelect" class="fertilizer-dropdown">
                    <option value="">Select Fertilizer Type</option>
                    <?php foreach ($data['fertilizer_types'] as $index => $fertilizer): ?>
                        <option value="<?php echo $index; ?>"><?php echo htmlspecialchars($fertilizer->name); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="fertilizer-details">
                <?php foreach ($data['fertilizer_types'] as $index => $fertilizer): ?>
                    <div class="detail-card" id="fertilizer-<?php echo $index; ?>" style="display: none;">
                        <div class="detail-row">
                            <div class="detail-label">Description:</div>
                            <div class="detail-value"><?php echo htmlspecialchars($fertilizer->description); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Usage:</div>
                            <div class="detail-value"><?php echo htmlspecialchars($fertilizer->recommended_usage); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Price (kg):</div>
                            <div class="detail-value">Rs. <?php echo number_format($fertilizer->unit_price_kg, 2); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Price (Pack):</div>
                            <div class="detail-value">Rs. <?php echo number_format($fertilizer->unit_price_packs, 2); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Price (Box):</div>
                            <div class="detail-value">Rs. <?php echo number_format($fertilizer->unit_price_box, 2); ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <style>
                .fertilizer-selector {
                    margin-bottom: 15px;
                }

                .fertilizer-dropdown {
                    width: 100%;
                    padding: 8px;
                    border: 1px solid #ddd;
                    border-radius: 5px;
                    font-size: 0.9rem;
                }

                .detail-card {
                    background: white;
                    border-radius: 8px;
                    padding: 10px;
                }

                .detail-row {
                    display: flex;
                    padding: 8px 0;
                    border-bottom: 1px solid #eee;
                }

                .detail-row:last-child {
                    border-bottom: none;
                }

                .detail-label {
                    flex: 1;
                    color: #666;
                    font-size: 0.9rem;
                }

                .detail-value {
                    flex: 2;
                    font-size: 0.9rem;
                }

                @media screen and (max-width: 360px) {
                    .detail-row {
                        flex-direction: column;
                        gap: 4px;
                    }

                    .detail-label {
                        font-weight: 500;
                    }
                }
            </style>

            <script>
                document.getElementById('fertilizerSelect').addEventListener('change', function() {
                    // Hide all detail cards
                    document.querySelectorAll('.detail-card').forEach(card => {
                        card.style.display = 'none';
                    });

                    // Show selected card
                    const selectedIndex = this.value;
                    if (selectedIndex !== '') {
                        document.getElementById('fertilizer-' + selectedIndex).style.display = 'block';
                    }
                });
            </script>
        </div>
        <div class="table-data">
            <h4>Request Fertilizers</h4>
            
                <form action="<?php echo URLROOT; ?>/supplier/createFertilizerOrder" method="POST" class="request-form" id="fertilizerForm">
                    <div class="form-container">
                        <div class="form-group">
                            <label for="type_id">Fertilizer Type:</label>
                            <select id="type_id" name="type_id" required>
                                <option value="">Select Fertilizer</option>
                                <?php foreach ($data['fertilizer_types'] as $type): ?>
                                    <option value="<?php echo $type->type_id; ?>"
                                        data-unit-price-kg="<?php echo $type->unit_price_kg; ?>"
                                        data-pack-price="<?php echo $type->unit_price_packs; ?>"
                                        data-box-price="<?php echo $type->unit_price_box; ?>">
                                        <?php echo $type->name; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-row">
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
                                <label for="total_amount">Amount:</label>
                                <input type="number" id="total_amount" name="total_amount" min="1" max="50" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="price_per_unit">Price Per Unit:</label>
                                <input type="number" id="price_per_unit" name="price_per_unit" readonly>
                            </div>

                            <div class="form-group">
                                <label for="total_price">Total Price:</label>
                                <input type="number" id="total_price" name="total_price" readonly>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn-submit">Submit Request</button>
                            <button type="button" class="btn-cancel" 
                                onclick="document.getElementById('fertilizerForm').reset()">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
            
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
                        <th>Unit</th>
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
                            <td><?php echo $order->total_amount; ?></td>
                            <td><?php echo $order->unit; ?></td>
                            <td><?php echo $order->total_price; ?></td>
                            <td><?php echo isset($order->payment_status) ? $order->payment_status : 'Pending'; ?></td>
                            <td>
                                <a href="<?php echo URLROOT; ?>/Supplier/editFertilizerRequest/<?php echo $order->order_id; ?>" >
                                    <button class="btn-edit btn-primary">Edit</button>
                                </a>
                            </td>
                            <td>
                                <a href="<?php echo URLROOT; ?>/Supplier/deleteFertilizerRequest/<?php echo $order->order_id; ?>" >
                                    <button class="btn-delete btn-primary" data-id="<?php echo $order->order_id; ?>">Delete</button>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                </tbody>
            </table>
        </div>
    </div>
</main>
</section>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="<?php echo URLROOT; ?>/css/script.js"></script>
<script>
    window.FERTILIZER_TYPES = <?php echo json_encode($data['fertilizer_types']); ?>;
</script>
<script>

    /* FERTILIZER REQUESTS */
    /* ORDER POPUP */
    document.getElementById('fertilizerForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(this);

        fetch('<?php echo URLROOT; ?>/supplier/createFertilizerOrder', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                showNotification(data.message, data.success);
                if (data.success) {
                    setTimeout(() => {
                        window.location.reload();
                    }, 3000);
                }
            })
            .catch(error => {
                showNotification('An error occurred. Please try again.', false);
            });
    });

    function showNotification(message, isSuccess) {
        const notification = document.getElementById('notification');
        notification.textContent = message;
        notification.className = 'notification ' + (isSuccess ? 'success' : 'error');
        notification.style.display = 'block';

        // Fade out after 3 seconds
        setTimeout(() => {
            notification.style.animation = 'fadeOut 0.5s ease-out';
            setTimeout(() => {
                notification.style.display = 'none';
                notification.style.animation = '';
            }, 500);
        }, 3000);
    }

</script>


<style>
    /* Request Form Styles */
    .request-form {
        background: white;
        border-radius: 10px;
        padding: 15px;
    }

    .form-container {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .form-row {
        display: flex;
        gap: 15px;
    }

    .form-group {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .form-group label {
        font-size: 0.9rem;
        color: #666;
    }

    .form-group select,
    .form-group input {
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 0.9rem;
        width: 100%;
    }

    .form-group input[readonly] {
        background-color: #f5f5f5;
    }

    .form-actions {
        display: flex;
        gap: 10px;
        margin-top: 10px;
    }

    .btn-submit,
    .btn-cancel {
        padding: 8px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 0.9rem;
        flex: 1;
        transition: all 0.3s ease;
    }

    .btn-submit {
        background: #008000;
        color: white;
    }

    .btn-submit:hover {
        background: #006400;
    }

    .btn-cancel {
        background: #f5f5f5;
        color: #666;
    }

    .btn-cancel:hover {
        background: #e0e0e0;
    }

    @media screen and (max-width: 360px) {
        .request-form {
            padding: 10px;
        }

        .form-row {
            flex-direction: column;
            gap: 10px;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn-submit,
        .btn-cancel {
            width: 100%;
        }
    }
    .box-info .text h3 {
        color: #012970;
    }
    
    .box-info .text h3.zero-count {
        color: #999;
    }
    
    .box-info li:nth-child(2) .text h3 { /* Accepted count */
        color: #2ecc71;
    }
    
    .box-info li:nth-child(3) .text h3 { /* Rejected count */
        color: #e74c3c;
    }

    .table tbody td {
        text-align: center; /* Center-align text in table cells */
        vertical-align: middle; /* Center-align content vertically */
    }

    .table tbody td button {
        margin: auto; /* Ensure buttons are centered within their cells */
        display: block; /* Ensure they are treated as block elements for alignment */
    }

</style>
<!-- Update the box-info HTML to use the new classes 
<h3 class="<?php echo $data['request_counts']['total'] === 0 ? 'zero-count' : ''; ?>">
    <?php echo $data['request_counts']['total']; ?>
</h3>-->


</body>

</html>