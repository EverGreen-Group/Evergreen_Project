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

                        <div class="order-items">
                            <?php foreach($order->items as $item): ?>
                                <div class="order-item">
                                    <div class="item-image">
                                        <img src="<?php echo URLROOT; ?>/img/products/<?php echo $item->product_image; ?>" 
                                             alt="<?php echo $item->product_name; ?>">
                                    </div>
                                    <div class="item-details">
                                        <h4><?php echo $item->product_name; ?></h4>
                                        <div class="item-meta">
                                            <span>Quantity: <?php echo $item->quantity; ?></span>
                                            <span>Price: Rs. <?php echo number_format($item->price, 2); ?></span>
                                        </div>
                                    </div>
                                    <div class="item-total">
                                        Rs. <?php echo number_format($item->subtotal, 2); ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="order-footer">
                            <div class="order-summary">
                                <div class="summary-row">
                                    <span>Subtotal:</span>
                                    <span>Rs. <?php echo number_format($order->total_amount, 2); ?></span>
                                </div>
                                <div class="summary-row">
                                    <span>Shipping:</span>
                                    <span>Rs. <?php echo number_format($order->shipping_fee, 2); ?></span>
                                </div>
                                <div class="summary-row">
                                    <span>Tax:</span>
                                    <span>Rs. <?php echo number_format($order->tax_amount, 2); ?></span>
                                </div>
                                <div class="summary-row total">
                                    <span>Total:</span>
                                    <span>Rs. <?php echo number_format($order->grand_total, 2); ?></span>
                                </div>
                            </div>

                            <div class="order-actions">
                                <a href="<?php echo URLROOT; ?>/shop/orderDetails/<?php echo $order->id; ?>" 
                                   class="btn-view-details">
                                    View Details
                                </a>
                                <?php if (in_array($order->order_status, ['pending', 'processing'])): ?>
                                    <a href="<?php echo URLROOT; ?>/shop/editOrder/<?php echo $order->id; ?>" 
                                       class="btn btn-primary">Edit Order</a>
                                <?php endif; ?>
                                <?php if ($order->order_status == 'pending'): ?>
                                    <button onclick="cancelOrder(<?php echo $order->id; ?>)" 
                                            class="btn btn-danger">Cancel Order</button>
                                <?php endif; ?>
                            </div>
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

/* Add more styles... */
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

// Add more JavaScript functions...
</script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?> 