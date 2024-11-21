<?php

require APPROOT . '/views/inc/components/header.php';
require APPROOT . '/views/inc/components/sidebar_driver_collections.php';
require APPROOT . '/views/inc/components/topnavbar.php';
?>

<main>
    <div class="head-title">
        <div class="left">
            <h1>Ongoing Collection</h1>
            <ul class="breadcrumb">
                <li><a href="#">Collections</a></li>
                <li>Current</li>
            </ul>
        </div>
    </div>



    <!-- Supplier Lists Section -->
    <div class="table-data">
        <!-- Current Supplier -->
        <section class="current-supplier">
            <h2>Current Supplier</h2>
            <?php if ($data['currentSupplier']): ?>
                <div class="supplier-card">
                    <div class="supplier-info">
                        <div class="supplier-profile">
                            <img src="<?php echo URLROOT; ?>/public/img/default-user.png" alt="Supplier">
                            <h4><?php echo $data['currentSupplier']->supplier_name; ?></h4>
                            <span class="supplier-id">#<?php echo $data['currentSupplier']->supplier_id; ?></span>
                        </div>
                        <div class="supplier-actions">
                            <button class="btn-location">
                                <i class='bx bx-map'></i>
                                Location
                            </button>
                            <button class="btn-call">
                                <i class='bx bx-phone'></i>
                                Contact
                            </button>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <p class="no-supplier">No current supplier assigned</p>
            <?php endif; ?>
        </section>

        <!-- Suppliers List -->
        <section class="suppliers-list">
            <h2>Collection List</h2>
            <div class="suppliers-cards">
                <?php foreach ($data['collectionSupplierRecords'] as $record): ?>
                    <div class="supplier-card <?php echo $record->collection_time ? 'completed' : ''; ?>">
                        <div class="supplier-detail">
                            <strong>ID:</strong> #<?php echo $record->supplier_id; ?>
                        </div>
                        <div class="supplier-detail">
                            <strong>Supplier:</strong> <?php echo $record->supplier_name; ?>
                        </div>
                        <div class="supplier-detail">
                            <strong>Status:</strong> 
                            <span class="status <?php echo strtolower($record->status); ?>">
                                <?php echo $record->status; ?>
                            </span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </div>
</main>

<style>
/* Existing styles */

/* Box Info Cards */
.box-info {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    margin-bottom: 24px;
}

.box-info li {
    flex: 1 1 calc(50% - 10px) !important;
    margin: 5px !important;
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    transition: all 0.3s ease;
}

.box-info li i {
    font-size: 2rem !important;
    margin-right: 15px;
    color: var(--primary);
    flex-shrink: 0;
}

.box-info .text {
    flex: 1;
}

.box-info .text p {
    margin: 0;
    font-size: 1rem;
    color: var(--dark);
}

.box-info .text h3 {
    margin: 5px 0;
    font-size: 1.25rem;
    color: var(--primary);
}

.box-info .text span {
    font-size: 0.875rem !important;
    color: var(--secondary);
}

.head-title {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
}

.head-title .left {
    display: flex;
    flex-direction: column;
}

.head-title h1 {
    margin: 0;
    font-size: 1.5rem;
}

.head-title .breadcrumb {
    list-style: none;
    padding: 0;
    display: flex;
}

.head-title .breadcrumb li {
    margin-right: 5px;
}

.head-title .breadcrumb li::after {
    content: '>';
    margin-left: 5px;
}

.head-title .breadcrumb li:last-child::after {
    content: '';
}

.table-data {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

/* Current Supplier & Suppliers List */
.current-supplier, .suppliers-list {
    background: var(--light);
    padding: 20px;
    border-radius: 10px;
}

.supplier-card {
    background: white;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.supplier-profile {
    text-align: center;
    margin-bottom: 20px;
}

.supplier-profile img {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    margin-bottom: 10px;
}

.supplier-profile h4 {
    margin: 0;
    color: var(--dark);
}

.supplier-id {
    color: var(--dark);
    font-weight: 600;
    font-size: 0.9rem;
}

.supplier-actions {
    display: flex;
    gap: 10px;
    justify-content: center;
}

.btn-location, .btn-call {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 0.9rem;
}

.btn-location {
    background: #2196F3;
    color: white;
}

.btn-call {
    background: #4CAF50;
    color: white;
}

.suppliers-list .suppliers-cards {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.supplier-card.completed {
    background: #f1f8e9;
}

.status {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.85rem;
}

.status.added {
    background: #e3f2fd;
    color: #1976D2;
}

.status.completed {
    background: #f1f8e9;
    color: #43A047;
}

/* Mobile responsiveness */
@media screen and (max-width: 768px) {
    .box-info li {
        flex: 1 1 100% !important;
        margin: 5px !important;
        padding: 15px;
    }

    .box-info li i {
        font-size: 1.5rem;
        margin-right: 10px;
    }

    .box-info .text p {
        font-size: 0.9rem;
    }

    .box-info .text h3 {
        font-size: 1.1rem;
    }

    .box-info .text span {
        font-size: 0.8rem;
    }

    .supplier-actions {
        flex-direction: column;
    }

    .btn-location, .btn-call {
        width: 100% !important;
    }

    .suppliers-list .suppliers-table {
        display: none;
    }

    .suppliers-list .suppliers-cards {
        display: flex;
        flex-direction: column;
    }

    .supplier-card {
        padding: 15px;
    }

    .head-title h1 {
        font-size: 1.25rem;
    }

    .breadcrumb {
        flex-wrap: wrap;
    }

    .breadcrumb li {
        margin-bottom: 5px;
    }
}

@media screen and (max-width: 360px) {
    .box-info li {
        padding: 10px;
    }

    .box-info li i {
        font-size: 1.2rem;
        margin-right: 8px;
    }

    .box-info .text p {
        font-size: 0.85rem;
    }

    .box-info .text h3 {
        font-size: 1rem;
    }

    .box-info .text span {
        font-size: 0.75rem;
    }
}
</style>

<script src="<?php echo URLROOT; ?>/css/components/script.js"></script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>