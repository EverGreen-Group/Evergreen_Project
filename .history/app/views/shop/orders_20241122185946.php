<?php
// Add debugging at the top of the file
echo "<pre>";
echo "Debug information:\n";
echo "Session user ID: " . $_SESSION['user_id'] . "\n";
echo "Orders count: " . (isset($data['orders']) ? count($data['orders']) : 'No orders set') . "\n";
print_r($data);
echo "</pre>";
?>

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
        <?php if(isset($data['orders']) && !empty($data['orders'])) : ?>
            <?php foreach($data['orders'] as $order) : ?>
                <div class="order-item">
                    <h3>Order #<?php echo $order->order_number; ?></h3>
                    <p>Total: Rs. <?php echo number_format($order->grand_total, 2); ?></p>
                    <p>Status: <?php echo ucfirst($order->order_status); ?></p>
                    <p>Date: <?php echo date('M d, Y', strtotime($order->created_at)); ?></p>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <div class="no-orders">
                <h2>No orders yet</h2>
                <p>You haven't placed any orders yet.</p>
                <a href="<?php echo URLROOT; ?>/shop" class="btn btn-primary">Start Shopping</a>
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
async function cancelOrder(orderId) {
    if (!confirm('Are you sure you want to cancel this order?')) return;

    try {
        const response = await fetch(`${URLROOT}/shop/cancelOrder`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ order_id: orderId })
        });

        const data = await response.json();
        if (data.success) {
            location.reload();
        } else {
            showToast(data.message || 'Failed to cancel order', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('An error occurred while cancelling the order', 'error');
    }
}

// Add more JavaScript functions...
</script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?> 