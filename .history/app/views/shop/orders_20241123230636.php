<?php 
require_once APPROOT . '/helpers/auth_helper.php';
require APPROOT . '/views/inc/components/header.php'; 
require APPROOT . '/views/inc/components/sidebar_shop.php';
require APPROOT . '/views/inc/components/topnavbar.php';
?>

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

.shipping-details {
    padding: 20px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    background: #f8f9fa;
    border-bottom: 1px solid var(--grey);
}

.shipping-info, .payment-info {
    padding: 15px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.shipping-info h4, .payment-info h4 {
    color: var(--dark);
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.shipping-info p, .payment-info p {
    margin: 8px 0;
    color: var(--dark-grey);
    font-size: 0.95rem;
}

.payment-status {
    padding: 3px 8px;
    border-radius: 12px;
    font-size: 0.85rem;
    font-weight: 500;
}

.payment-status.paid { background: #d1e7dd; color: #0f5132; }
.payment-status.pending { background: #fff3cd; color: #856404; }
.payment-status.failed { background: #f8d7da; color: #721c24; }

.order-actions {
    padding: 20px;
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    align-items: center;
    background: white;
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
    background: var(--blue);
    color: white;
}

.btn-edit {
    background: var(--warning);
    color: var(--dark);
}

.btn-cancel {
    background: var(--danger);
    color: white;
}

.btn-action:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.btn-view:hover {
    background: #0056b3;
}

.btn-edit:hover {
    background: #e0a800;
}

.btn-cancel:hover {
    background: #c82333;
}

/* Responsive design */
@media screen and (max-width: 768px) {
    .shipping-details {
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

.product-list {
    padding: 20px;
    border-bottom: 1px solid #eee;
}

.product-item {
    display: flex;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid #f5f5f5;
}

.product-item:last-child {
    border-bottom: none;
}

.product-image {
    width: 80px;
    height: 80px;
    margin-right: 20px;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 8px;
}

.product-details {
    flex: 1;
}

.product-details h4 {
    margin: 0 0 8px 0;
    color: var(--dark);
}

.product-details p {
    margin: 4px 0;
    color: var(--grey);
    font-size: 0.9rem;
}

/* Update button styles */
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

.btn-cancel {
    background: #dc3545;
    color: white;
}

.btn-delete {
    background: #dc3545;
    color: white;
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