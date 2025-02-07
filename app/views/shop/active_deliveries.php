<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_shop.php'; ?>


<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/main.css">
<main class="tracking-dashboard">
    <div class="head-title">
        <div class="left">
            <h1>Active Deliveries</h1>
            <ul class="breadcrumb">
                <li><a href="<?php echo URLROOT; ?>/shop">Shop</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">Active Deliveries</a></li>
            </ul>
        </div>
    </div>

    <div class="tracking-content">
        <?php if (!empty($data['active_orders'])): ?>
            <?php foreach ($data['active_orders'] as $order): ?>
                <div class="delivery-card">
                    <div class="card-header">
                        <div class="order-info">
                            <div class="order-id">
                                <h3>Order #<?php echo $order->order_number; ?></h3>
                                <span class="status <?php echo strtolower($order->order_status); ?>">
                                    <?php echo ucfirst(str_replace('_', ' ', $order->order_status)); ?>
                                </span>
                            </div>
                            <div class="order-meta">
                                <span class="date">
                                    <i class='bx bx-calendar'></i>
                                    <?php echo date('M j, Y', strtotime($order->created_at)); ?>
                                </span>
                                <span class="amount">
                                    <i class='bx bx-money'></i>
                                    Rs. <?php echo number_format($order->grand_total, 2); ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="tracking-progress">
                        <div class="progress-steps">
                            <div class="step <?php echo in_array($order->order_status, ['processing', 'shipped', 'out_for_delivery']) ? 'active' : ''; ?>">
                                <i class='bx bx-package'></i>
                                <span>Processing</span>
                            </div>
                            <div class="step <?php echo in_array($order->order_status, ['shipped', 'out_for_delivery']) ? 'active' : ''; ?>">
                                <i class='bx bx-car'></i>
                                <span>Shipped</span>
                            </div>
                            <div class="step <?php echo $order->order_status == 'out_for_delivery' ? 'active' : ''; ?>">
                                <i class='bx bx-map'></i>
                                <span>Out for Delivery</span>
                            </div>
                        </div>
                    </div>

                    <div class="card-actions">
                        <a href="<?php echo URLROOT; ?>/shop/trackOrder/<?php echo $order->order_number; ?>" class="btn-track">
                            Track Order
                            <i class='bx bx-right-arrow-alt'></i>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-state">
                <i class='bx bx-package'></i>
                <h3>No Active Deliveries</h3>
                <p>You don't have any active deliveries at the moment.</p>
                <a href="<?php echo URLROOT; ?>/shop" class="btn">Continue Shopping</a>
            </div>
        <?php endif; ?>
    </div>
</main>

<style>
.tracking-dashboard {
    position: relative;
    width: calc(100% - 280px);
    left: 280px;
    padding: 24px;
    font-family: var(--poppins);
    transition: all 0.3s ease;
}

#sidebar.hide ~ .tracking-dashboard {
    width: calc(100% - 60px);
    left: 60px;
}

.delivery-card {
    background: var(--light);
    border-radius: 10px;
    padding: 24px;
    margin-bottom: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.order-id {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}

.order-id h3 {
    font-size: 1.1rem;
    color: var(--dark);
    font-weight: 600;
}

.status {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
    color: var(--light);
}

.status.processing { background: var(--yellow); }
.status.shipped { background: var(--main); }
.status.out_for_delivery { background: var(--green); }

.order-meta {
    display: flex;
    gap: 24px;
    color: var(--dark-grey);
    font-size: 0.9rem;
}

.order-meta span {
    display: flex;
    align-items: center;
    gap: 8px;
}

.tracking-progress {
    margin: 24px 0;
    padding: 20px 0;
    border-top: 1px solid var(--grey);
    border-bottom: 1px solid var(--grey);
}

.progress-steps {
    display: flex;
    justify-content: space-between;
}

.step {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    color: var(--dark-grey);
    position: relative;
    flex: 1;
}

.step.active {
    color: var(--main);
}

.step i {
    font-size: 24px;
    z-index: 2;
    background: var(--light);
    padding: 8px;
    border-radius: 50%;
}

.step.active i {
    background: var(--light-main);
}

.progress-steps .step::before {
    content: '';
    position: absolute;
    top: 20px;
    left: -50%;
    width: 100%;
    height: 2px;
    background: var(--grey);
    z-index: 1;
}

.progress-steps .step:first-child::before {
    display: none;
}

.progress-steps .step.active::before {
    background: var(--main);
}

.card-actions {
    display: flex;
    justify-content: flex-end;
}

.btn-track {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    background: var(--main);
    color: var(--light);
    border-radius: 6px;
    text-decoration: none;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.btn-track:hover {
    background: var(--dark);
    transform: translateY(-2px);
}

.empty-state {
    text-align: center;
    padding: 48px;
    background: var(--light);
    border-radius: 10px;
}

.empty-state i {
    font-size: 48px;
    color: var(--dark-grey);
    margin-bottom: 16px;
}

.empty-state h3 {
    color: var(--dark);
    margin-bottom: 8px;
}

.empty-state p {
    color: var(--dark-grey);
    margin-bottom: 24px;
}

.empty-state .btn {
    display: inline-block;
    padding: 8px 24px;
    background: var(--main);
    color: var(--light);
    border-radius: 6px;
    text-decoration: none;
    transition: all 0.3s ease;
}

.empty-state .btn:hover {
    background: var(--dark);
}

@media screen and (max-width: 768px) {
    .tracking-dashboard {
        width: 100%;
        left: 0;
        padding: 16px;
    }

    #sidebar.hide ~ .tracking-dashboard {
        width: 100%;
        left: 0;
    }
    
    .order-meta {
        flex-direction: column;
        gap: 8px;
    }
    
    .progress-steps {
        flex-direction: column;
        gap: 24px;
    }
    
    .progress-steps .step::before {
        top: -12px;
        left: 20px;
        width: 2px;
        height: 24px;
    }
}
</style>

<?php require APPROOT . '/views/inc/components/footer.php'; ?> 