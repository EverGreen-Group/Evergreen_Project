<?php require APPROOT . '/views/inc/components/header.php'; ?>

<div class="tracking-container">
    <div class="tracking-header">
        <h2>Order #<?php echo $data['order']->order_number; ?></h2>
        <p class="order-date">Ordered on <?php echo date('F j, Y', strtotime($data['order']->created_at)); ?></p>
    </div>

    <div class="tracking-status">
        <div class="status-steps">
            <div class="step <?php echo in_array($data['order']->status, ['pending', 'processing', 'shipped', 'delivered']) ? 'active' : ''; ?>">
                <i class='bx bx-check-circle'></i>
                <span>Order Confirmed</span>
                <small><?php echo date('M j, g:i A', strtotime($data['order']->created_at)); ?></small>
            </div>
            <div class="step <?php echo in_array($data['order']->status, ['processing', 'shipped', 'delivered']) ? 'active' : ''; ?>">
                <i class='bx bx-package'></i>
                <span>Processing</span>
                <small><?php echo $data['order']->processing_date ? date('M j, g:i A', strtotime($data['order']->processing_date)) : ''; ?></small>
            </div>
            <div class="step <?php echo in_array($data['order']->status, ['shipped', 'delivered']) ? 'active' : ''; ?>">
                <i class='bx bx-car'></i>
                <span>Shipped</span>
                <small><?php echo $data['order']->shipped_date ? date('M j, g:i A', strtotime($data['order']->shipped_date)) : ''; ?></small>
            </div>
            <div class="step <?php echo $data['order']->status === 'delivered' ? 'active' : ''; ?>">
                <i class='bx bx-home'></i>
                <span>Delivered</span>
                <small><?php echo $data['order']->delivered_date ? date('M j, g:i A', strtotime($data['order']->delivered_date)) : ''; ?></small>
            </div>
        </div>
    </div>

    <div class="order-details">
        <div class="order-items">
            <h3>Order Items</h3>
            <?php foreach($data['order_items'] as $item): ?>
                <div class="order-item">
                    <img src="<?php echo URLROOT; ?>/img/products/<?php echo $item->image; ?>" alt="<?php echo $item->product_name; ?>">
                    <div class="item-details">
                        <h4><?php echo $item->product_name; ?></h4>
                        <p>Quantity: <?php echo $item->quantity; ?></p>
                        <p>Price: LKR <?php echo number_format($item->price, 2); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="shipping-details">
            <h3>Shipping Details</h3>
            <p><strong>Address:</strong> <?php echo $data['order']->shipping_address; ?></p>
            <p><strong>Contact:</strong> <?php echo $data['order']->phone; ?></p>
            <p><strong>Tracking Number:</strong> <?php echo $data['order']->tracking_number; ?></p>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/components/footer.php'; ?> 