<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_shop.php'; ?>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<main class="tracking-container">
    <div class="head-title">
        <div class="left">
            <h1>Track Your Order</h1>
            <ul class="breadcrumb">
                <li><a href="<?php echo URLROOT; ?>/shop">Shop</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">Track Order</a></li>
            </ul>
        </div>
    </div>

    <div class="tracking-content">
        <div class="status-card">
            <div class="status-header">
                <h2>Order Status</h2>
                <span class="status-badge <?php echo strtolower($data['order']->order_status); ?>">
                    <?php echo ucfirst($data['order']->order_status); ?>
                </span>
            </div>

            <div class="order-info">
                <div class="info-grid">
                    <div class="info-item">
                        <span class="label">Order Number:</span>
                        <span class="value"><?php echo $data['order']->order_number; ?></span>
                    </div>
                    <div class="info-item">
                        <span class="label">Order Date:</span>
                        <span class="value"><?php echo date('F j, Y', strtotime($data['order']->created_at)); ?></span>
                    </div>
                </div>
            </div>

            <div class="tracking-timeline">
                <?php 
                $statuses = ['pending', 'processing', 'shipped', 'delivered'];
                $currentStatusIndex = array_search($data['order']->order_status, $statuses);
                
                foreach ($data['tracking'] as $index => $track): 
                    $isCompleted = $index <= $currentStatusIndex;
                    $isActive = $index === $currentStatusIndex;
                ?>
                    <div class="timeline-step <?php echo $isCompleted ? 'completed' : ($isActive ? 'active' : ''); ?>">
                        <div class="step-icon">
                            <i class='bx bx-<?php echo getStatusIcon($track->status); ?>'></i>
                        </div>
                        <div class="step-info">
                            <h4><?php echo ucfirst($track->status); ?></h4>
                            <p><?php echo date('M j, Y', strtotime($track->created_at)); ?><br>
                               <?php echo date('h:i A', strtotime($track->created_at)); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="details-grid">
            <div class="detail-card">
                <div class="card-icon">
                    <i class='bx bx-map'></i>
                </div>
                <div class="card-content">
                    <h3>Delivery Address</h3>
                    <p><?php echo $data['shipping_address']->address; ?></p>
                </div>
            </div>

            <div class="detail-card">
                <div class="card-icon">
                    <i class='bx bx-package'></i>
                </div>
                <div class="card-content">
                    <h3>Shipping Method</h3>
                    <p><?php echo $data['order']->shipping_method; ?></p>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
* {
    font-family: 'Poppins', sans-serif;
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

.tracking-container {
    padding: 2rem;
    max-width: 1000px;
    margin: 0 auto;
}

.head-title {
    text-align: center;
    margin-bottom: 2rem;
}

.head-title h1 {
    font-size: 1.8rem;
    font-weight: 600;
    color: var(--dark);
    margin-bottom: 0.5rem;
}

.breadcrumb {
    display: flex;
    justify-content: center;
    gap: 0.5rem;
    list-style: none;
}

.tracking-content {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.status-card {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 8px 24px rgba(0,0,0,0.05);
}

.status-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.status-badge {
    background: var(--main);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 30px;
    font-size: 0.9rem;
    font-weight: 500;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 2rem;
    margin-bottom: 3rem;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.info-item .label {
    color: #666;
    font-size: 0.9rem;
}

.info-item .value {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--dark);
}

.tracking-timeline {
    display: flex;
    justify-content: space-between;
    position: relative;
    padding: 1rem 0;
}

.tracking-timeline::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    height: 2px;
    background: #e0e0e0;
    transform: translateY(-50%);
}

.timeline-step {
    position: relative;
    z-index: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    flex: 1;
}

.step-icon {
    width: 60px;
    height: 60px;
    background: white;
    border: 2px solid #e0e0e0;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1rem;
    transition: all 0.3s ease;
}

.step-icon i {
    font-size: 24px;
    color: #999;
}

.step-info {
    text-align: center;
}

.step-info h4 {
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 0.3rem;
}

.step-info p {
    font-size: 0.8rem;
    color: #666;
}

.timeline-step.completed .step-icon {
    background: var(--main);
    border-color: var(--main);
}

.timeline-step.completed .step-icon i {
    color: white;
}

.timeline-step.active .step-icon {
    border-color: var(--main);
    box-shadow: 0 0 0 4px rgba(0,104,55,0.1);
}

.timeline-step.active .step-icon i {
    color: var(--main);
}

.details-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 2rem;
}

.detail-card {
    background: white;
    border-radius: 20px;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1.5rem;
    box-shadow: 0 8px 24px rgba(0,0,0,0.05);
    transition: transform 0.3s ease;
}

.detail-card:hover {
    transform: translateY(-5px);
}

.card-icon {
    width: 50px;
    height: 50px;
    background: var(--main);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.card-icon i {
    font-size: 24px;
    color: white;
}

.card-content h3 {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: var(--dark);
}

.card-content p {
    font-size: 0.9rem;
    color: #666;
}

@media screen and (max-width: 768px) {
    .tracking-container {
        padding: 1rem;
    }

    .info-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }

    .tracking-timeline {
        flex-direction: column;
        gap: 2rem;
    }

    .tracking-timeline::before {
        top: 0;
        bottom: 0;
        left: 30px;
        width: 2px;
        height: auto;
        transform: none;
    }

    .timeline-step {
        flex-direction: row;
        gap: 1.5rem;
    }

    .step-info {
        text-align: left;
    }

    .details-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<?php require APPROOT . '/views/inc/components/footer.php'; ?> 