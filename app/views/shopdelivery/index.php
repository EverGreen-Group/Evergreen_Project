<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_shop.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/style.css">

<main>
    <div class="head-title">
        <div class="left">
            <h1>Tracking Details</h1>
            <ul class="breadcrumb">
                <li><a href="<?php echo URLROOT; ?>/shop">Shop</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a href="<?php echo URLROOT; ?>/shopdelivery">Deliveries</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">Tracking Details</a></li>
            </ul>
        </div>
        <div class="right">
            <a href="<?php echo URLROOT; ?>/shopdelivery" class="btn-download">
                <i class='bx bx-arrow-back'></i>
                <span>Back to Deliveries</span>
            </a>
        </div>
    </div>

    <?php if(isset($data['tracking']) && isset($data['order'])): ?>
        <div class="tracking-details-container">
            <!-- Order Information -->
            <div class="info-card">
                <h3>Order Information</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <span>Order Number:</span>
                        <strong><?php echo $data['order']->order_number; ?></strong>
                    </div>
                    <div class="info-item">
                        <span>Order Date:</span>
                        <strong><?php echo date('F j, Y', strtotime($data['order']->created_at)); ?></strong>
                    </div>
                    <div class="info-item">
                        <span>Status:</span>
                        <strong class="status-badge <?php echo strtolower($data['tracking']->status); ?>">
                            <?php echo ucfirst($data['tracking']->status); ?>
                        </strong>
                    </div>
                </div>
            </div>

            <!-- Tracking Information -->
            <div class="info-card">
                <h3>Tracking Information</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <span>Tracking Number:</span>
                        <strong><?php echo $data['tracking']->tracking_code; ?></strong>
                    </div>
                    <div class="info-item">
                        <span>Current Location:</span>
                        <strong><?php echo $data['tracking']->current_location; ?></strong>
                    </div>
                    <div class="info-item">
                        <span>Estimated Delivery:</span>
                        <strong>
                            <?php 
                                echo $data['tracking']->estimated_delivery ? 
                                date('F j, Y', strtotime($data['tracking']->estimated_delivery)) : 
                                'Pending';
                            ?>
                        </strong>
                    </div>
                </div>
            </div>

            <!-- Tracking Timeline -->
            <?php if($data['tracking']->status_history): ?>
                <div class="info-card">
                    <h3>Tracking Timeline</h3>
                    <div class="timeline-detailed">
                        <?php foreach(json_decode($data['tracking']->status_history) as $history): ?>
                            <div class="timeline-item">
                                <div class="timeline-dot"></div>
                                <div class="timeline-content">
                                    <div class="timeline-header">
                                        <div class="timeline-status">
                                            <?php echo ucfirst($history->status); ?>
                                        </div>
                                        <div class="timeline-date">
                                            <?php echo date('F j, Y g:i a', strtotime($history->timestamp)); ?>
                                        </div>
                                    </div>
                                    <div class="timeline-location">
                                        <i class='bx bx-map'></i>
                                        <?php echo $history->location; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-danger">
            <i class='bx bx-error-circle'></i>
            Invalid tracking information.
        </div>
    <?php endif; ?>
</main>

<style>
.tracking-details-container {
    padding: 1rem;
    display: grid;
    gap: 1.5rem;
}

.info-card {
    background: var(--light);
    border-radius: 10px;
    padding: 1.5rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.info-card h3 {
    margin-bottom: 1rem;
    color: var(--dark);
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.info-item span {
    color: #666;
    font-size: 0.875rem;
}

.timeline-detailed {
    margin-top: 1rem;
}

.timeline-detailed .timeline-item {
    position: relative;
    padding-left: 2rem;
    padding-bottom: 2rem;
}

.timeline-detailed .timeline-dot {
    position: absolute;
    left: 0;
    width: 1rem;
    height: 1rem;
    border-radius: 50%;
    background: var(--blue);
}

.timeline-detailed .timeline-item:not(:last-child)::before {
    content: '';
    position: absolute;
    left: 0.4rem;
    top: 1rem;
    bottom: 0;
    width: 2px;
    background: #eee;
}

.timeline-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
}

.timeline-status {
    font-weight: 600;
    color: var(--dark);
}

.timeline-date {
    color: #666;
    font-size: 0.875rem;
}

.timeline-location {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #666;
}

.alert {
    padding: 1rem;
    border-radius: 5px;
    margin: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.alert-danger {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}
</style>

<script src="<?php echo URLROOT; ?>/js/main.js"></script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?> 