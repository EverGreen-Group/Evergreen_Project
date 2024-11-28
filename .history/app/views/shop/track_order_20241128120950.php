<?php require APPROOT . '/views/inc/components/header.php'; ?>

<div class="tracking-container">
    <h1>Track Your Order</h1>
    <div class="order-info">
        <div class="order-header">
            <div>
                <h2>Order #<?php echo $data['order']->order_number; ?></h2>
                <p>Placed on <?php echo date('F j, Y', strtotime($data['order']->created_at)); ?></p>
            </div>
            <div class="estimated-delivery">
                <i class='bx bx-time'></i>
                <span>Estimated Delivery: <?php echo date('F j', strtotime('+3 days')); ?></span>
            </div>
        </div>

        <div class="tracking-status">
            <div class="status-steps">
                <div class="status-step <?php echo $data['order']->status >= 1 ? 'completed' : ''; ?>">
                    <div class="step-icon">
                        <i class='bx bx-check-circle'></i>
                    </div>
                    <div class="step-content">
                        <h4>Order Confirmed</h4>
                        <p>Your order has been placed</p>
                    </div>
                </div>

                <div class="status-step <?php echo $data['order']->status >= 2 ? 'completed' : ''; ?>">
                    <div class="step-icon">
                        <i class='bx bx-package'></i>
                    </div>
                    <div class="step-content">
                        <h4>Processing</h4>
                        <p>Your order is being processed</p>
                    </div>
                </div>

                <div class="status-step <?php echo $data['order']->status >= 3 ? 'completed' : ''; ?>">
                    <div class="step-icon">
                        <i class='bx bx-car'></i>
                    </div>
                    <div class="step-content">
                        <h4>Out for Delivery</h4>
                        <p>Your order is on the way</p>
                    </div>
                </div>

                <div class="status-step <?php echo $data['order']->status >= 4 ? 'completed' : ''; ?>">
                    <div class="step-icon">
                        <i class='bx bx-home'></i>
                    </div>
                    <div class="step-content">
                        <h4>Delivered</h4>
                        <p>Your order has been delivered</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="order-details">
            <h3>Order Details</h3>
            <div class="order-items">
                <?php foreach($data['order_items'] as $item): ?>
                    <div class="order-item">
                        <img src="<?php echo URLROOT; ?>/img/products/<?php echo $item->image; ?>" alt="<?php echo $item->product_name; ?>">
                        <div class="item-details">
                            <h4><?php echo $item->product_name; ?></h4>
                            <p>Quantity: <?php echo $item->quantity; ?></p>
                            <p>LKR <?php echo number_format($item->price, 2); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/components/footer.php'; ?> 