<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Add the QR Scanner library in the head section -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js" integrity="sha512-r6rDA7W6ZeQhvl8S7yRVQUKVHdexq+GAlNkNNqVC7YyIV+NwqCTJe2hDWCiffTyRNOeGEzRRJ9ifvRm/HCzGYg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<?php require APPROOT . '/views/inc/components/sidebar_driving_partner.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<main>
    <div class="head-title">
        <div class="left">
            <h1>Supplier Collection</h1>
            <ul class="breadcrumb">
                <li><a href="#">Dashboard</a></li>
            </ul>
        </div>
    </div>
    <!-- Weight Tracking Section -->
    <ul class="box-info">
        <li>
            <i class='bx bx-package'></i>
            <span class="text">
                <p>Tare Weight</p>
                <h3><?php echo number_format($data['collection']->initial_weight_bridge, 2); ?> kg</h3>
                <span>Empty Vehicle</span>
            </span>
        </li>
        <li>
            <i class='bx bx-leaf'></i>
            <span class="text">
                <p>Net Weight</p>
                <h3><?php echo number_format($data['collection']->total_quantity, 2); ?> kg</h3>
                <span>Tea Leaves</span>
            </span>
        </li>
    </ul>

    <!-- First Row: Container for Supplier Info and Collection Progress -->
        <!-- Current Supplier Info -->
        <div class="table-data">
            <?php if (!empty($data['collections'])): ?>
                <?php 
                $currentSupplier = $data['collections'][0]; // Get the first supplier 
                $hasArrived = !empty($currentSupplier['arrival_time']);
                $buttonClass = $hasArrived ? '' : 'disabled';
                ?>
                <div class="supplier-info">
                    <div class="supplier-profile">
                        <img src="<?php echo $currentSupplier['image']; ?>" alt="Supplier Image">
                        <h4><?php echo $currentSupplier['supplierName']; ?></h4>
                    </div>
                    <div class="supplier-details">
                        <p><i class='bx bx-phone'></i> <?php echo $currentSupplier['contact']; ?></p>
                        <p><i class='bx bx-leaf'></i> Expected: <?php echo $currentSupplier['estimatedCollection']; ?> kg</p>
                        <?php if ($currentSupplier['remarks']): ?>
                            <p><i class='bx bx-note'></i> <?php echo $currentSupplier['remarks']; ?></p>
                        <?php endif; ?>
                        <?php if (!$hasArrived): ?>
                            <p class="waiting-message"><i class='bx bx-time'></i> Waiting to arrive at location</p>
                        <?php endif; ?>
                    </div>
                    <div class="supplier-actions">
                        <button class="btn-call <?php echo $buttonClass; ?>" 
                                onclick="<?php echo $hasArrived ? "makeCall('{$currentSupplier['contact']}')" : ''; ?>"
                                <?php echo $hasArrived ? '' : 'disabled'; ?>>
                            <i class='bx bx-phone-call'></i> Call
                        </button>
                    </div>
                    <div class="supplier-actions">
                        <button class="btn-record <?php echo $buttonClass; ?>"
                                onclick="<?php echo $hasArrived ? "window.location.href='" . URLROOT . "/drivingpartner/record_collection/{$currentSupplier['id']}/{$data['collection']->collection_id}'" : ''; ?>"
                                <?php echo $hasArrived ? '' : 'disabled'; ?>>
                            <i class='bx bx-edit'></i> Record Collection
                        </button>
                    </div>
                </div>
            <?php else: ?>
                <div class="supplier-info">
                    <p class="no-supplier">No suppliers remaining in collection route</p>
                </div>
            <?php endif; ?>
            <div class="order collection-progress">
                <div class="head">
                    <h3>Collection Progress</h3>
                </div>
                <div class="progress-cards">
                    <?php foreach ($data['collections'] as $supplier): 
                        $statusClass = $supplier['collection_time'] ? 'completed' : 
                            ($supplier['arrival_time'] ? 'current' : 'upcoming');
                        $statusText = $supplier['collection_time'] ? 'Collected' : 
                            ($supplier['arrival_time'] ? 'At Location' : 'Pending');
                    ?>
                        <div class="progress-card <?php echo $statusClass; ?>">
                            <div class="supplier-header">
                                <span class="supplier-id">#<?php echo $supplier['id']; ?></span>
                                <span class="status <?php echo $statusClass; ?>"><?php echo $statusText; ?></span>
                            </div>
                            <div class="supplier-name"><?php echo $supplier['supplierName']; ?></div>
                            <?php if ($supplier['collection_time'] && isset($supplier['quantity']) && $supplier['quantity'] > 0): ?>
                                <div class="collected-amount">
                                    <i class='bx bx-leaf'></i>
                                    <?php echo number_format($supplier['quantity'], 2); ?> kg
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>


    </div>

</main>

<style>

/* Remove the old weight-metrics styles */
.weight-metrics {
    display: none;
}

tr.disabled {
    opacity: 0.5;
    pointer-events: none;
}

tr.current {
    background: var(--light-blue);
}

tr.completed {
    background: var(--light-green);
}

/* Rest of the existing styles from v_collection.php */


.supplier-info {
    padding: 20px;
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 20px;
}

.supplier-profile {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
}

.supplier-profile img {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid var(--light-blue);
}

.supplier-profile h4 {
    font-size: 20px;
    margin: 0;
    color: var(--dark);
}

.supplier-details {
    width: 100%;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.supplier-details p {
    margin: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    font-size: 15px;
}

.supplier-details .bx {
    font-size: 20px;
    color: var(--blue);
}

.supplier-actions {
    width: 100%;
    display: flex;
    gap: 10px;
    justify-content: center;
    margin-top: 10px;
}

.btn-record, .btn-call {
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    transition: all 0.3s ease;
    color: var(--light);
}

.btn-record {
    background: #3C91E6;  /* Using the blue color explicitly */
}

.btn-call {
    background: #27ae60;  /* Using a distinct green color */
}

.btn-record:hover, .btn-call:hover {
    opacity: 0.8;
    transform: translateY(-2px);
}

.btn-record .bx, .btn-call .bx {
    font-size: 18px;
}

.table-data {
    width: 100%;
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    margin-top: 20px;
}

.supplier-info {
    flex: 1;
    min-width: 300px;
    background: var(--light);
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.collection-progress {
    flex: 1;
    min-width: 300px;
    background: var(--light);
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

/* New styles for progress cards */
.progress-cards {
    display: grid;
    gap: 15px;
    margin-top: 15px;
}

.progress-card {
    background: white;
    border-radius: 8px;
    padding: 15px;
    border-left: 4px solid #ddd;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.progress-card.completed {
    border-left-color: #4CAF50;
}

.progress-card.current {
    border-left-color: #2196F3;
}

.progress-card.upcoming {
    border-left-color: #9E9E9E;
}

.supplier-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
}

.supplier-id {
    color: #666;
    font-size: 0.9rem;
}

.supplier-name {
    font-weight: 500;
    font-size: 1.1rem;
    margin-bottom: 8px;
}

.collected-amount {
    display: flex;
    align-items: center;
    gap: 5px;
    color: #4CAF50;
    font-weight: 500;
}

.collected-amount i {
    font-size: 1.1rem;
}

.status {
    padding: 4px 8px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 500;
}

.status.completed {
    background: #E8F5E9;
    color: #2E7D32;
}

.status.current {
    background: #E3F2FD;
    color: #1565C0;
}

.status.upcoming {
    background: #F5F5F5;
    color: #616161;
}

/* Media Queries */
@media screen and (max-width: 857px) {
    .table-data {
        flex-direction: column;
    }

    .supplier-info,
    .collection-progress {
        width: 100%;
        min-width: 100%;
    }

    .progress-cards {
        grid-template-columns: 1fr;
    }
}

@media screen and (min-width: 858px) {
    .progress-cards {
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    }
}

/* Add these styles */
.btn-call.disabled,
.btn-record.disabled {
    opacity: 0.5;
    cursor: not-allowed;
    background-color: #999 !important;
    transform: none !important;
}

.waiting-message {
    color: #f39c12;
    margin-top: 10px;
    font-style: italic;
}

.waiting-message i {
    color: #f39c12;
}

/* Update existing hover styles */
.btn-record:not(.disabled):hover,
.btn-call:not(.disabled):hover {
    opacity: 0.8;
    transform: translateY(-2px);
}

</style>
<script src="<?php echo URLROOT; ?>/css/components/script.js"></script>
<?php require APPROOT . '/views/inc/components/footer.php'; ?> 