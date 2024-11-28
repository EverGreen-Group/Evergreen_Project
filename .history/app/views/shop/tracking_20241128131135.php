<?php require APPROOT . '/views/inc/components/header.php'; ?>

<main class="tracking-main">
    <div class="tracking-container">
        <div class="tracking-header">
            <h1>Order Tracking</h1>
            <div class="order-info">
                <span>Order #<?php echo $data['order']->order_number; ?></span>
                <span>Placed on <?php echo date('F j, Y', strtotime($data['order']->created_at)); ?></span>
            </div>
        </div>

        <div class="tracking-status">
            <div class="status-timeline">
                <div class="status-step <?php echo in_array($data['order']->status, ['pending', 'processing', 'shipped', 'delivered']) ? 'completed' : ''; ?>">
                    <div class="step-icon">
                        <i class='bx bx-check-circle'></i>
                    </div>
                    <div class="step-info">
                        <h3>Order Confirmed</h3>
                        <p><?php echo date('M j, g:i A', strtotime($data['order']->created_at)); ?></p>
                    </div>
                </div>

                <div class="status-step <?php echo in_array($data['order']->status, ['processing', 'shipped', 'delivered']) ? 'completed' : ''; ?>">
                    <div class="step-icon">
                        <i class='bx bx-package'></i>
                    </div>
                    <div class="step-info">
                        <h3>Processing</h3>
                        <p><?php echo $data['order']->processing_date ? date('M j, g:i A', strtotime($data['order']->processing_date)) : 'Pending'; ?></p>
                    </div>
                </div>

                <div class="status-step <?php echo in_array($data['order']->status, ['shipped', 'delivered']) ? 'completed' : ''; ?>">
                    <div class="step-icon">
                        <i class='bx bx-car'></i>
                    </div>
                    <div class="step-info">
                        <h3>Out for Delivery</h3>
                        <p><?php echo $data['order']->shipped_date ? date('M j, g:i A', strtotime($data['order']->shipped_date)) : 'Pending'; ?></p>
                    </div>
                </div>

                <div class="status-step <?php echo $data['order']->status === 'delivered' ? 'completed' : ''; ?>">
                    <div class="step-icon">
                        <i class='bx bx-home'></i>
                    </div>
                    <div class="step-info">
                        <h3>Delivered</h3>
                        <p><?php echo $data['order']->delivered_date ? date('M j, g:i A', strtotime($data['order']->delivered_date)) : 'Pending'; ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="order-details">
            <div class="delivery-info">
                <h2>Delivery Information</h2>
                <div class="info-grid">
                    <div class="info-item">
                        <h3>Shipping Address</h3>
                        <p><?php echo $data['order']->shipping_address; ?></p>
                    </div>
                    <div class="info-item">
                        <h3>Contact Details</h3>
                        <p>Phone: <?php echo $data['order']->phone; ?></p>
                    </div>
                    <div class="info-item">
                        <h3>Tracking Number</h3>
                        <p><?php echo $data['order']->tracking_number; ?></p>
                    </div>
                </div>
            </div>

            <div class="order-items">
                <h2>Order Items</h2>
                <?php foreach($data['order_items'] as $item): ?>
                    <div class="order-item">
                        <img src="<?php echo URLROOT; ?>/img/products/<?php echo $item->image; ?>" 
                             alt="<?php echo $item->product_name; ?>">
                        <div class="item-details">
                            <h3><?php echo $item->product_name; ?></h3>
                            <p>Quantity: <?php echo $item->quantity; ?></p>
                            <p class="item-price">LKR <?php echo number_format($item->price, 2); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</main>

<?php require APPROOT . '/views/inc/components/footer.php'; ?> 