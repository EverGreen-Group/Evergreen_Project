<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_shop.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>
<?php require_once APPROOT . '/helpers/RoleHelper.php'; ?>

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

                        <div class="product-list">
                            <?php foreach($order->items as $item): ?>
                                <div class="product-item">
                                    <div class="product-image">
                                        <img src="<?php echo URLROOT; ?>/img/products/<?php echo $item->product_image; ?>" 
                                             alt="<?php echo $item->product_name; ?>">
                                    </div>
                                    <div class="product-details">
                                        <h4><?php echo $item->product_name; ?></h4>
                                        <p>Quantity: <?php echo $item->quantity; ?></p>
                                        <p>Price: Rs. <?php echo number_format($item->price, 2); ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="info-container">
                            <div class="shipping-info">
                                <h4>Shipping Information</h4>
                                <p><span>Address:</span> <?php echo $order->shipping_address; ?></p>
                                <p><span>Method:</span> <?php echo $order->shipping_method; ?></p>
                            </div>
                            <div class="payment-info">
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
                                    <i class='bx bx-x'></i> Cancel
                                </button>
                            <?php endif; ?>
                            <?php if (RoleHelper::isAdmin()): ?>
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
    padding: 24px;
    max-width: 1200px;
    margin: 0 auto;
}

.order-card {
    background: var(--light);
    border-radius: 16px;
    margin-bottom: 24px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    border: 1px solid rgba(0,0,0,0.08);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.order-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0,0,0,0.1);
}

.order-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 24px;
    border-bottom: 1px solid rgba(0,0,0,0.08);
    background: rgba(255,255,255,0.8);
    border-radius: 16px 16px 0 0;
}

.order-info h3 {
    font-size: 1.2rem;
    color: var(--dark);
    margin-bottom: 4px;
}

.order-date {
    color: #6c757d;
    font-size: 0.9rem;
}

.order-status {
    padding: 8px 16px;
    border-radius: 30px;
    font-size: 0.85rem;
    font-weight: 600;
    letter-spacing: 0.3px;
    text-transform: uppercase;
}

.order-status.pending { background: #fff8e1; color: #f57c00; }
.order-status.processing { background: #e3f2fd; color: #1976d2; }
.order-status.shipped { background: #e8f5e9; color: #2e7d32; }
.order-status.delivered { background: #e8f5e9; color: #1b5e20; }
.order-status.cancelled { background: #ffebee; color: #c62828; }

.product-list {
    padding: 24px;
    background: white;
    border-bottom: 1px solid rgba(0,0,0,0.08);
}

.product-item {
    display: flex;
    align-items: center;
    padding: 16px;
    margin-bottom: 12px;
    background: #f8f9fa;
    border-radius: 12px;
    transition: transform 0.2s ease;
}

.product-item:hover {
    transform: translateX(4px);
}

.product-item:last-child {
    margin-bottom: 0;
}

.product-image {
    width: 90px;
    height: 90px;
    margin-right: 24px;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.product-image img:hover {
    transform: scale(1.05);
}

.info-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
    padding: 24px;
    background: #f8f9fa;
}

.shipping-info, .payment-info {
    background: white;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.shipping-info h4, .payment-info h4 {
    color: var(--dark);
    margin-bottom: 16px;
    font-size: 1.1rem;
    padding-bottom: 8px;
    border-bottom: 2px solid #e9ecef;
}

.order-summary {
    background: white;
    padding: 24px;
    border-radius: 0 0 16px 16px;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    color: #495057;
}

.summary-row.total {
    margin-top: 12px;
    padding-top: 16px;
    border-top: 2px solid #e9ecef;
    font-weight: 600;
    font-size: 1.1rem;
    color: var(--dark);
}

.order-actions {
    padding: 20px 24px;
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    align-items: center;
    background: #f8f9fa;
    border-top: 1px solid rgba(0,0,0,0.08);
}

.btn-action {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    border-radius: 12px;
    font-size: 0.9rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.btn-view {
    background: #2c3e50;
    color: white;
}

.btn-edit {
    background: #f39c12;
    color: white;
}

.btn-cancel {
    background: #e74c3c;
    color: white;
}

.btn-delete {
    background: #c0392b;
    color: white;
}

.btn-action:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

/* Responsive design */
@media screen and (max-width: 768px) {
    .orders-container {
        padding: 16px;
    }

    .info-container {
        grid-template-columns: 1fr;
        gap: 16px;
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