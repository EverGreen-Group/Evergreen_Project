<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Add the QR Scanner library in the head section -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js" integrity="sha512-r6rDA7W6ZeQhvl8S7yRVQUKVHdexq+GAlNkNNqVC7YyIV+NwqCTJe2hDWCiffTyRNOeGEzRRJ9ifvRm/HCzGYg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<?php require APPROOT . '/views/inc/components/sidebar_driving_partner.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<main>
    <?php print_r($data); ?>
    <div class="head-title">
        <div class="left">
            <h1>Record Collection</h1>
            <ul class="breadcrumb">
                <li><a href="#">Dashboard</a></li>
            </ul>
        </div>
    </div>



    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Select Collection Bag</h3>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Bag ID</th>
                        <th>Capacity</th>
                        <th>Actual Weight</th>
                        <th>Bag Status</th>
                        <th>Supplier</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($data['bags'])): ?>
                        <?php foreach ($data['bags'] as $bag): ?>
                            <tr class="<?php echo strtolower($bag->bag_status); ?>">
                                <td>#<?php echo $bag->bag_id; ?></td>
                                <td><?php echo number_format($bag->capacity_kg, 2); ?> kg</td>
                                <td><?php echo !empty($bag->actual_weight_kg) ? number_format($bag->actual_weight_kg, 2) . ' kg' : '-'; ?></td>
                                <td>
                                    <span class="status-badge <?php echo strtolower($bag->bag_status); ?>">
                                        <?php echo $bag->bag_status; ?>
                                    </span>
                                </td>
                                <td><?php echo !empty($bag->supplier_name) ? $bag->supplier_name : 'Unassigned'; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="no-bags">No bags assigned to this collection</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="order">
            <div class="head">
                <h3>Assign Bag</h3>
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
.status-badge {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 500;
}

.status-badge.available {
    background-color: #E3F2FD;
    color: #1976D2;
}

.status-badge.assigned {
    background-color: #FFF3E0;
    color: #F57C00;
}

.status-badge.collected {
    background-color: #E8F5E9;
    color: #388E3C;
}

tr.available {
    background-color: rgba(227, 242, 253, 0.1);
}

tr.assigned {
    background-color: rgba(255, 243, 224, 0.1);
}

tr.collected {
    background-color: rgba(232, 245, 233, 0.1);
}

.no-bags {
    text-align: center;
    padding: 20px;
    color: #666;
    font-style: italic;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #eee;
}

th {
    font-weight: 600;
    color: #333;
    background-color: #f8f9fa;
}

td {
    color: #444;
}

</style>
<script src="<?php echo URLROOT; ?>/css/components/script.js"></script>
<?php require APPROOT . '/views/inc/components/footer.php'; ?> 