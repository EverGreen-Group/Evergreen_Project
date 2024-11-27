<?php require APPROOT . '/views/inc/components/header.php'; ?>

<div class="container">
    <h1><?php echo $data['title']; ?></h1>
    
    <?php if(empty($data['orders'])) : ?>
        <p>No orders found.</p>
    <?php else : ?>
        <div class="orders-list">
            <?php foreach($data['orders'] as $order) : ?>
                <div class="order-card">
                    <div class="order-header">
                        <h3>Order #<?php echo $order->order_number; ?></h3>
                        <span class="order-date">
                            <?php echo date('M d, Y', strtotime($order->created_at)); ?>
                        </span>
                    </div>
                    <div class="order-details">
                        <p>Status: <span class="status-<?php echo $order->order_status; ?>">
                            <?php echo ucfirst($order->order_status); ?>
                        </span></p>
                        <p>Total: $<?php echo number_format($order->grand_total, 2); ?></p>
                    </div>
                    <div class="order-actions">
                        <?php if($order->payment_status === 'pending') : ?>
                            <a href="<?php echo URLROOT; ?>/orders/checkout/<?php echo $order->id; ?>" 
                               class="btn btn-primary">Complete Payment</a>
                        <?php endif; ?>
                        <a href="<?php echo URLROOT; ?>/orders/details/<?php echo $order->id; ?>" 
                           class="btn btn-secondary">View Details</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<style>
.orders-list {
    display: grid;
    gap: 1rem;
    padding: 1rem 0;
}

.order-card {
    background: white;
    border-radius: 8px;
    padding: 1rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.order-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.order-date {
    color: #666;
    font-size: 0.9rem;
}

.order-details {
    margin-bottom: 1rem;
}

.order-actions {
    display: flex;
    gap: 0.5rem;
}

.status-pending { color: #f59e0b; }
.status-paid { color: #10b981; }
.status-failed { color: #ef4444; }
</style>

<?php require APPROOT . '/views/inc/components/footer.php'; ?> 