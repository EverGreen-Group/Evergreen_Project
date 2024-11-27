<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_shop.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<main>
    <div class="head-title">
        <div class="left">
            <h1>My Orders</h1>
            <ul class="breadcrumb">
                <li><a href="<?php echo URLROOT; ?>/shop">Shop</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">Orders</a></li>
            </ul>
        </div>
    </div>

    <?php flash('order_message'); ?>

    <div class="orders-container">
        <?php if(empty($data['orders'])): ?>
            <div class="empty-orders">
                <i class='bx bx-package'></i>
                <h2>No orders yet</h2>
                <p>You haven't placed any orders yet.</p>
                <a href="<?php echo URLROOT; ?>/shop" class="btn-shop">
                    Start Shopping
                </a>
            </div>
        <?php else: ?>
            <div class="orders-list">
                <?php foreach($data['orders'] as $order): ?>
                    <div class="order-card">
                        <div class="order-header">
                            <div class="order-info">
                                <h3>Order #<?php echo $order->order_number; ?></h3>
                                <span class="order-date">
                                    <?php echo date('F j, Y', strtotime($order->created_at)); ?>
                                </span>
                            </div>
                            <div class="order-status <?php echo strtolower($order->order_status); ?>">
                                <?php echo $order->order_status; ?>
                            </div>
                        </div>

                        <div class="shipping-payment-info">
                            <div class="info-section">
                                <h4>Shipping Information</h4>
                                <p><span>Address:</span> <?php echo $order->shipping_address; ?></p>
                                <p><span>Method:</span> <?php echo $order->shipping_method; ?></p>
                            </div>
                            <div class="info-section">
                                <h4>Payment Details</h4>
                                <p><span>Method:</span> <?php echo $order->payment_method; ?></p>
                                <p><span>Status:</span> <span class="status-badge <?php echo strtolower($order->payment_status); ?>"><?php echo $order->payment_status; ?></span></p>
                            </div>
                        </div>

                        <div class="order-summary">
                            <div class="summary-row">
                                <span class="summary-label">Subtotal:</span>
                                <span class="summary-value">Rs. <?php echo number_format($order->total_amount, 2); ?></span>
                            </div>
                            <div class="summary-row">
                                <span class="summary-label">Shipping:</span>
                                <span class="summary-value">Rs. <?php echo number_format($order->shipping_fee, 2); ?></span>
                            </div>
                            <div class="summary-row">
                                <span class="summary-label">Tax:</span>
                                <span class="summary-value">Rs. <?php echo number_format($order->tax_amount, 2); ?></span>
                            </div>
                            <div class="summary-row total">
                                <span class="summary-label">Total:</span>
                                <span class="summary-value">Rs. <?php echo number_format($order->grand_total, 2); ?></span>
                            </div>
                        </div>

                        <div class="order-actions">
                            <a href="<?php echo URLROOT; ?>/shop/orderDetails/<?php echo $order->id; ?>" 
                               class="btn-action btn-view">
                                <i class='bx bx-show'></i> View Details
                            </a>
                            <?php if (in_array($order->order_status, ['pending', 'processing'])): ?>
                                <a href="<?php echo URLROOT; ?>/shop/editOrder/<?php echo $order->id; ?>" 
                                   class="btn-action btn-edit">
                                    <i class='bx bx-edit'></i> Edit Order
                                </a>
                            <?php endif; ?>
                            <?php if ($order->order_status == 'pending'): ?>
                                <button onclick="cancelOrder(<?php echo $order->id; ?>)" 
                                        class="btn-action btn-cancel">
                                    <i class='bx bx-x'></i> Cancel Order
                                </button>
                            <?php endif; ?>
                            <?php if (isAdmin()): ?>
                                <button onclick="deleteOrder(<?php echo $order->id; ?>)" 
                                        class="btn-action btn-delete">
                                    <i class='bx bx-trash'></i> Delete
                                </button>
                            <?php endif; ?>
                        </div>

                        <?php if(!empty($order->tracking)): ?>
                            <div class="order-tracking">
                                <h4>Order Tracking</h4>
                                <div class="tracking-timeline">
                                    <?php foreach($order->tracking as $track): ?>
                                        <div class="tracking-item">
                                            <div class="tracking-status">
                                                <i class='bx bx-check-circle'></i>
                                                <span><?php echo $track->status; ?></span>
                                            </div>
                                            <div class="tracking-time">
                                                <?php echo date('M j, Y g:i A', strtotime($track->created_at)); ?>
                                            </div>
                                            <?php if($track->comment): ?>
                                                <div class="tracking-comment">
                                                    <?php echo $track->comment; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<style>
.orders-container {
    padding: 20px;
}

.order-card {
    background: var(--light);
    border-radius: 10px;
    margin-bottom: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    overflow: hidden;
}

.order-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 20px;
    border-bottom: 1px solid var(--grey);
}

.order-status {
    padding: 5px 15px;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 500;
}

.order-status.pending { background: #fff3cd; color: #856404; }
.order-status.processing { background: #cce5ff; color: #004085; }
.order-status.shipped { background: #d4edda; color: #155724; }
.order-status.delivered { background: #d1e7dd; color: #0f5132; }
.order-status.cancelled { background: #f8d7da; color: #721c24; }

.shipping-payment-info {
    padding: 20px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    background: #f8f9fa;
    border-bottom: 1px solid var(--grey);
}

.info-section {
    padding: 15px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.info-section h4 {
    color: var(--dark);
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.info-section p {
    margin: 8px 0;
    color: var(--dark-grey);
    font-size: 0.95rem;
}

.status-badge {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.85rem;
    font-weight: 500;
}

.status-badge.pending {
    background: #fff3cd;
    color: #856404;
}

.status-badge.paid {
    background: #d4edda;
    color: #155724;
}

.status-badge.failed {
    background: #f8d7da;
    color: #721c24;
}

.order-summary {
    padding: 20px;
    background: #f8f9fa;
    border-radius: 8px;
    margin: 20px;
    width: 300px;
    margin-left: auto;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid #e9ecef;
}

.summary-row:last-child {
    border-bottom: none;
}

.summary-label {
    font-weight: 500;
    color: var(--dark);
}

.summary-value {
    font-weight: 600;
    color: var(--dark);
}

.summary-row.total {
    margin-top: 10px;
    padding-top: 15px;
    border-top: 2px solid #007664;
    font-size: 1.1rem;
}

.summary-row.total .summary-value {
    color: #007664;
    font-size: 1.2rem;
    font-weight: 700;
}

.order-actions {
    padding: 20px;
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    align-items: center;
}

.btn-action {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    border-radius: 6px;
    font-size: 0.9rem;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.btn-view {
    background: #8d9f2d;
    color: white;
}

.btn-edit {
    background: #ffce26;
    color: var(--dark);
}

.btn-cancel, .btn-delete {
    background: #dc3545;
    color: white;
}

.btn-view:hover {
    background: #7a8a28;
}

.btn-edit:hover {
    background: #e5b922;
}

.btn-cancel:hover, .btn-delete:hover {
    background: #bb2d3b;
}

/* Responsive design */
@media screen and (max-width: 768px) {
    .shipping-payment-info {
        grid-template-columns: 1fr;
    }
    
    .order-actions {
        flex-direction: column;
    }
    
    .btn-action {
        width: 100%;
        justify-content: center;
    }
}
</style>

<script>
function cancelOrder(orderId) {
    if (confirm('Are you sure you want to cancel this order?')) {
        fetch(`${URLROOT}/shop/cancelOrder/${orderId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to cancel order');
        });
    }
}

function deleteOrder(orderId) {
    if (confirm('Are you sure you want to delete this order?')) {
        fetch(`${URLROOT}/shop/deleteOrder/${orderId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to delete order');
        });
    }
}

// Add more JavaScript functions...
</script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?> 