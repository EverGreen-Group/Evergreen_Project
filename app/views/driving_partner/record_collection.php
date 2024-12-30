<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Add the QR Scanner library in the head section -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js" integrity="sha512-r6rDA7W6ZeQhvl8S7yRVQUKVHdexq+GAlNkNNqVC7YyIV+NwqCTJe2hDWCiffTyRNOeGEzRRJ9ifvRm/HCzGYg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<?php require APPROOT . '/views/inc/components/sidebar_driving_partner.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<main>
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
                <h3>Assigned Bags</h3>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Bag Token</th>
                        <th>Capacity</th>
                        <th>Gross Weight</th>
                        <th>Actual Weight</th>
                        <th>Leaf Type</th>
                        <th>Leaf Age</th>
                        <th>Moisture</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data['bags'] as $bag): ?>
                        <tr>
                            <td>#<?php echo $bag->token; ?></td>
                            <td><?php echo number_format($bag->capacity_kg, 2); ?> kg</td>
                            <td><?php echo $bag->gross_weight_kg ? number_format($bag->gross_weight_kg, 2) . ' kg' : '-'; ?></td>
                            <td><?php echo $bag->actual_weight_kg ? number_format($bag->actual_weight_kg, 2) . ' kg' : '-'; ?></td>
                            <td><?php echo $bag->leaf_type ?? '-'; ?></td>
                            <td><?php echo $bag->leaf_age ?? '-'; ?></td>
                            <td><?php echo $bag->moisture_level ?? '-'; ?></td>
                            <td><?php echo $bag->supplier_id ? 'Pending Collection' : 'Weighing Complete'; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Confirm Collection Section -->
    <?php if (!empty($data['bags'])): ?>
    <div class="order">
        <div class="head">
            <h3>Confirm Collection</h3>
        </div>
        <div class="record-form">
            <form id="confirmCollectionForm" onsubmit="return handleCollectionConfirmation(event)">
                <div class="form-group">
                    <label for="supplierPin">Enter Supplier PIN</label>
                    <input type="password" id="supplierPin" name="supplierPin" required class="form-control">
                </div>
                <button type="submit" class="btn-confirm" style="color: white; background-color: var(--main)">
                    <i class='bx bx-check-circle'></i> Confirm PIN
                </button>
            </form>
        </div>
    </div>
    <?php endif; ?>

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

.btn-assign,
.btn-record-weight {
    padding: 6px 12px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.btn-assign {
    background: #2196F3;
    color: white;
}

.btn-record-weight {
    background: #4CAF50;
    color: white;
}

.btn-assign:hover,
.btn-record-weight:hover {
    opacity: 0.9;
    transform: translateY(-1px);
}

.status-badge {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 500;
}

.status-badge.unassigned {
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

tr.unassigned {
    background-color: rgba(227, 242, 253, 0.1);
}

tr.assigned {
    background-color: rgba(255, 243, 224, 0.1);
}

tr.collected {
    background-color: rgba(232, 245, 233, 0.1);
}

.record-form {
    padding: 20px;
    background: #fff;
    border-radius: 8px;
}

.form-group {
    margin-bottom: 15px;
}

.form-row {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 15px;
    margin-bottom: 15px;
}

.form-control {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
}

label {
    display: block;
    margin-bottom: 5px;
    color: #333;
    font-weight: 500;
}

select.form-control {
    background-color: white;
}

input[readonly] {
    background-color: #f8f9fa;
    cursor: not-allowed;
}

.btn-assign {
    width: 100%;
    padding: 10px;
    margin-top: 10px;
    justify-content: center;
}

.deductions-section {
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid #eee;
}

.deductions-section h4 {
    margin-bottom: 15px;
    color: #333;
    font-size: 1rem;
}

textarea.form-control {
    resize: vertical;
    min-height: 80px;
}

</style>

<script>
function validateForm() {
    const bagWeight = parseFloat(document.getElementById('bagWeight').value);
    const totalWeight = parseFloat(document.getElementById('totalWeight').value);
    const actualWeight = parseFloat(document.getElementById('actualWeight').value);
    const leafType = document.getElementById('leafType').value;
    const leafAge = document.getElementById('leafAge').value;
    const moistureLevel = document.getElementById('moistureLevel').value;
    const bagSelect = document.getElementById('bagSelect');
    
    let errors = [];

    // Required fields
    if (!bagSelect.value) errors.push("Please select a bag");
    if (!bagWeight) errors.push("Bag weight is required");
    if (!totalWeight) errors.push("Total weight is required");
    if (!leafType) errors.push("Leaf type is required");
    if (!leafAge) errors.push("Leaf age is required");
    if (!moistureLevel) errors.push("Moisture level is required");

    // Weight validations
    if (bagWeight <= 0) errors.push("Bag weight must be greater than 0");
    if (totalWeight <= 0) errors.push("Total weight must be greater than 0");
    if (totalWeight <= bagWeight) errors.push("Total weight must be greater than bag weight");
    if (actualWeight <= 0) errors.push("Actual weight must be greater than 0");

    // Capacity validation using bag's weight field
    const selectedOption = bagSelect.options[bagSelect.selectedIndex];
    if (selectedOption) {
        const maxCapacity = parseFloat(selectedOption.dataset.capacity);
        if (actualWeight > maxCapacity) {
            errors.push(`Weight exceeds bag capacity of ${maxCapacity} kg`);
        }
    }

    return errors;
}

function calculateWeights() {
    const bagWeight = parseFloat(document.getElementById('bagWeight').value) || 0;
    const totalWeight = parseFloat(document.getElementById('totalWeight').value) || 0;
    
    // Calculate actual weight (total - bag)
    const actualWeight = Math.max(0, totalWeight - bagWeight);
    document.getElementById('actualWeight').value = actualWeight.toFixed(2);

    // Check bag capacity
    const bagSelect = document.getElementById('bagSelect');
    const selectedOption = bagSelect.options[bagSelect.selectedIndex];
    const warning = document.querySelector('.capacity-warning');
    
    if (selectedOption && warning) {
        const maxCapacity = parseFloat(selectedOption.dataset.capacity);
        if (actualWeight > maxCapacity) {
            warning.style.display = 'block';
            warning.textContent = `Warning: Exceeds bag capacity of ${maxCapacity} kg!`;
        } else {
            warning.style.display = 'none';
        }
    }
}

async function handleBagAssignment(event) {
    event.preventDefault();
    
    // Calculate weights before validation
    calculateWeights();
    
    // Client-side validation
    const errors = validateForm();
    if (errors.length > 0) {
        alert(errors.join("\n"));
        return false;
    }
    
    const formData = {
        bagId: document.getElementById('bagSelect').value,
        bagWeight: parseFloat(document.getElementById('bagWeight').value),
        totalWeight: parseFloat(document.getElementById('totalWeight').value),
        actualWeight: parseFloat(document.getElementById('actualWeight').value),
        leafType: document.getElementById('leafType').value,
        leafAge: document.getElementById('leafAge').value,
        moistureLevel: document.getElementById('moistureLevel').value,
        supplierId: <?php echo $data['supplier']->supplier_id ?? 'null'; ?>,
        collectionId: <?php echo $data['collection']->collection_id ?? 'null'; ?>
    };

    try {
        const response = await fetch(`<?php echo URLROOT; ?>/drivingpartner/assign_bag`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(formData)
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        
        if (data.success) {
            alert('Bag assigned successfully!');
            location.reload();
        } else {
            alert(data.message || 'Failed to assign bag');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to assign bag. Please try again.');
    }

    return false;
}

async function handleCollectionConfirmation(event) {
    event.preventDefault();
    
    const pin = document.getElementById('supplierPin').value;
    
    try {
        const response = await fetch(`<?php echo URLROOT; ?>/drivingpartner/confirm_collection`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                supplierId: <?php echo $data['supplier']->supplier_id; ?>,
                collectionId: <?php echo $data['collection']->collection_id; ?>,
                pin: pin
            })
        });

        const data = await response.json();
        
        if (data.success) {
            alert('Collection confirmed successfully!');
            
            if (data.isCompleted) {
                // If collection is completed, redirect to driving partner dashboard
                window.location.href = data.redirectUrl;
            } else {
                // Otherwise, just reload the current page
                location.reload();
            }
        } else {
            alert(data.message || 'Failed to confirm collection');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to confirm collection. Please try again.');
    }

    return false;
}

// Add event listeners
document.getElementById('bagWeight').addEventListener('input', calculateWeights);
document.getElementById('totalWeight').addEventListener('input', calculateWeights);
document.getElementById('bagSelect').addEventListener('change', calculateWeights);
</script>

<script src="<?php echo URLROOT; ?>/css/components/script.js"></script>
<?php require APPROOT . '/views/inc/components/footer.php'; ?> 