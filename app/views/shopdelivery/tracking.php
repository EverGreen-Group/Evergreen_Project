<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_shop.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/style.css">

<main>
    <div class="head-title">
        <div class="left">
            <h1>Delivery Tracking</h1>
            <ul class="breadcrumb">
                <li><a href="<?php echo URLROOT; ?>/shop">Shop</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">Delivery Tracking</a></li>
            </ul>
        </div>
    </div>

    <?php if(empty($data['deliveries'])): ?>
        <div class="alert alert-info">
            <i class='bx bx-info-circle'></i>
            No deliveries found for your account.
        </div>
    <?php else: ?>
        <div class="delivery-grid">
            <?php foreach($data['deliveries'] as $delivery): ?>
                <div class="delivery-card">
                    <div class="delivery-header">
                        <h3>Order #<?php echo $delivery['order']->order_number; ?></h3>
                        <span class="status-badge <?php echo strtolower($delivery['tracking']->status); ?>">
                            <?php echo ucfirst($delivery['tracking']->status); ?>
                        </span>
                    </div>

                    <div class="delivery-details">
                        <div class="detail-item">
                            <i class='bx bx-package'></i>
                            <span>Tracking Number:</span>
                            <strong><?php echo $delivery['tracking']->tracking_code; ?></strong>
                        </div>

                        <div class="detail-item">
                            <i class='bx bx-map'></i>
                            <span>Current Location:</span>
                            <strong><?php echo $delivery['tracking']->current_location; ?></strong>
                        </div>

                        <div class="detail-item">
                            <i class='bx bx-calendar'></i>
                            <span>Estimated Delivery:</span>
                            <strong>
                                <?php 
                                    echo $delivery['tracking']->estimated_delivery ? 
                                    date('F j, Y', strtotime($delivery['tracking']->estimated_delivery)) : 
                                    'Pending';
                                ?>
                            </strong>
                        </div>

                        <div class="detail-item">
                            <i class='bx bx-time'></i>
                            <span>Last Updated:</span>
                            <strong>
                                <?php echo date('F j, Y g:i a', strtotime($delivery['tracking']->updated_at)); ?>
                            </strong>
                        </div>
                    </div>

                    <?php if($delivery['tracking']->status_history): ?>
                        <div class="tracking-history">
                            <h4>Tracking History</h4>
                            <div class="timeline">
                                <?php foreach(json_decode($delivery['tracking']->status_history) as $history): ?>
                                    <div class="timeline-item">
                                        <div class="timeline-dot"></div>
                                        <div class="timeline-content">
                                            <div class="timeline-date">
                                                <?php echo date('F j, Y g:i a', strtotime($history->timestamp)); ?>
                                            </div>
                                            <div class="timeline-status">
                                                <?php echo ucfirst($history->status); ?>
                                            </div>
                                            <div class="timeline-location">
                                                <?php echo $history->location; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="delivery-actions">
                        <a href="<?php echo URLROOT; ?>/shopdelivery/track/<?php echo $delivery['tracking']->tracking_code; ?>" 
                           class="btn-track">
                            <i class='bx bx-map-alt'></i>
                            Track Details
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<style>
.delivery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
    padding: 1rem;
}

.delivery-card {
    background: var(--light);
    border-radius: 10px;
    padding: 1.5rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.delivery-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.status-badge {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 500;
}

.status-badge.pending { background: #fff3cd; color: #856404; }
.status-badge.in_transit { background: #cce5ff; color: #004085; }
.status-badge.delivered { background: #d4edda; color: #155724; }
.status-badge.failed { background: #f8d7da; color: #721c24; }

.detail-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.75rem;
}

.detail-item i {
    font-size: 1.25rem;
    color: var(--blue);
}

.tracking-history {
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    border-top: 1px solid #eee;
}

.timeline {
    position: relative;
    margin-top: 1rem;
    padding-left: 2rem;
}

.timeline-item {
    position: relative;
    padding-bottom: 1.5rem;
}

.timeline-dot {
    position: absolute;
    left: -2rem;
    width: 1rem;
    height: 1rem;
    border-radius: 50%;
    background: var(--blue);
}

.timeline-item:not(:last-child)::before {
    content: '';
    position: absolute;
    left: -1.5rem;
    top: 1rem;
    bottom: 0;
    width: 2px;
    background: #eee;
}

.timeline-date {
    font-size: 0.875rem;
    color: #666;
}

.timeline-status {
    font-weight: 600;
    color: var(--dark);
}

.timeline-location {
    color: #666;
}

.delivery-actions {
    margin-top: 1.5rem;
    text-align: right;
}

.btn-track {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    background: var(--blue);
    color: white;
    border-radius: 5px;
    text-decoration: none;
    transition: background 0.3s;
}

.btn-track:hover {
    background: var(--dark-blue);
}

.alert {
    padding: 1rem;
    border-radius: 5px;
    margin: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.alert-info {
    background: #cce5ff;
    color: #004085;
    border: 1px solid #b8daff;
}
</style>

<script src="<?php echo URLROOT; ?>/js/main.js"></script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>