<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Add Leaflet CSS and JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<!-- Add this JavaScript block right after header -->
<script>
    // Define URLROOT constant
    const URLROOT = '<?= URLROOT ?>';

    function approveApplication(applicationId) {
        if (confirm('Are you sure you want to approve this application? This action cannot be undone.')) {
            window.location.href = `${URLROOT}/suppliermanager/approveApplication/${applicationId}`;
        }
    }

    function rejectApplication(applicationId) {
        if (confirm('Are you sure you want to reject this application? This action cannot be undone.')) {
            window.location.href = `${URLROOT}/suppliermanager/rejectApplication/${applicationId}`;
        }
    }

    function confirmSupplierRole(applicationId) {
        if (confirm('Are you sure you want to assign the supplier role to this user?')) {
            window.location.href = `${URLROOT}/suppliermanager/confirmSupplierRole/${applicationId}`;
        }
    }
</script>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_supplier_manager.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<!-- MAIN -->
<main>
    <div class="head-title">
        <div class="left">
            <h1>Application Details</h1>
            <ul class="breadcrumb">
                <li><a href="<?= URLROOT ?>/suppliermanager/applications">Applications</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">View Application</a></li>
            </ul>
        </div>
    </div>

    <!-- Application Status -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Application #APP<?= str_pad($data['application']['application_id'], 4, '0', STR_PAD_LEFT) ?></h3>
            </div>
            <div class="section-content">
                <div class="info-row">
                    <span class="label">Submitted Date:</span>
                    <span class="value"><?= date('F j, Y', strtotime($data['application']['created_at'])) ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Status:</span>
                    <span class="value"><?= ucfirst($data['application']['status']) ?></span>
                </div>
                <?php if ($data['application']['status'] === 'pending'): ?>
                    <div class="action-buttons">
                        <button class="btn-approve" onclick="approveApplication('<?= $data['application']['application_id'] ?>')">
                            <i class='bx bx-check'></i> Approve
                        </button>
                        <button class="btn-reject" onclick="rejectApplication('<?= $data['application']['application_id'] ?>')">
                            <i class='bx bx-x'></i> Reject
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Contact Information -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Contact Information</h3>
            </div>
            <div class="section-content">
                <div class="info-row">
                    <span class="label">Primary Phone:</span>
                    <span class="value"><?= $data['application']['primary_phone'] ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Secondary Phone:</span>
                    <span class="value"><?= $data['application']['secondary_phone'] ?? 'Not provided' ?></span>
                </div>
                <div class="info-row">
                    <span class="label">WhatsApp:</span>
                    <span class="value"><?= $data['application']['whatsapp_number'] ?? 'Not provided' ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Bank Information -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Bank Information</h3>
            </div>
            <div class="section-content">
                <div class="info-row">
                    <span class="label">Account Holder:</span>
                    <span class="value"><?= $data['bank_info']->account_holder_name ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Bank Name:</span>
                    <span class="value"><?= $data['bank_info']->bank_name ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Branch Name:</span>
                    <span class="value"><?= $data['bank_info']->branch_name ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Account Number:</span>
                    <span class="value"><?= $data['bank_info']->account_number ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Account Type:</span>
                    <span class="value"><?= $data['bank_info']->account_type ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Property Details -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Property Details</h3>
            </div>
            <div class="section-content">
                <div class="info-row">
                    <span class="label">Total Land Area:</span>
                    <span class="value"><?= $data['property']->total_land_area ?> acres</span>
                </div>
                <div class="info-row">
                    <span class="label">Tea Cultivation Area:</span>
                    <span class="value"><?= $data['property']->tea_cultivation_area ?> acres</span>
                </div>
                <div class="info-row">
                    <span class="label">Elevation:</span>
                    <span class="value"><?= $data['property']->elevation ?> meters</span>
                </div>
                <div class="info-row">
                    <span class="label">Slope:</span>
                    <span class="value"><?= ucfirst($data['property']->slope) ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Water Sources -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Water Sources</h3>
            </div>
            <div class="section-content">
                <?php foreach ($data['water_sources'] as $source): ?>
                    <div class="info-row">
                        <span class="value"><?= $source->source_type ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Tea Information -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Tea Information</h3>
            </div>
            <div class="section-content">
                <div class="info-row">
                    <span class="label">Plant Age:</span>
                    <span class="value"><?= $data['tea_details']->plant_age ?> years</span>
                </div>
                <div class="info-row">
                    <span class="label">Monthly Production:</span>
                    <span class="value"><?= $data['tea_details']->monthly_production ?> kg</span>
                </div>
                <div class="info-row">
                    <span class="label">Tea Varieties:</span>
                    <span class="value">
                        <?php 
                        $varieties = array_map(function($variety) {
                            return $variety->variety_name;
                        }, $data['tea_varieties']);
                        echo implode(', ', $varieties);
                        ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Infrastructure -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Infrastructure</h3>
            </div>
            <div class="section-content">
                <div class="info-row">
                    <span class="label">Access Road:</span>
                    <span class="value"><?= $data['infrastructure']->access_road ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Vehicle Access:</span>
                    <span class="value"><?= $data['infrastructure']->vehicle_access ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Available Structures:</span>
                    <span class="value">
                        <?php 
                        $structures = array_map(function($structure) {
                            return $structure->structure_type;
                        }, $data['structures']);
                        echo implode(', ', $structures);
                        ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Address Information -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Address Information</h3>
            </div>
            <div class="section-content">
                <div class="address-container">
                    <div class="address-details">
                        <div class="info-row">
                            <span class="label">Address Line 1:</span>
                            <span class="value"><?= $data['address']->line1 ?></span>
                        </div>
                        <?php if (!empty($data['address']->line2)): ?>
                            <div class="info-row">
                                <span class="label">Address Line 2:</span>
                                <span class="value"><?= $data['address']->line2 ?></span>
                            </div>
                        <?php endif; ?>
                        <div class="info-row">
                            <span class="label">City:</span>
                            <span class="value"><?= $data['address']->city ?></span>
                        </div>
                        <div class="info-row">
                            <span class="label">District:</span>
                            <span class="value"><?= $data['address']->district ?></span>
                        </div>
                        <div class="info-row">
                            <span class="label">Postal Code:</span>
                            <span class="value"><?= $data['address']->postal_code ?></span>
                        </div>
                        <div class="info-row">
                            <span class="label">Coordinates:</span>
                            <span class="value"><?= $data['address']->latitude ?>, <?= $data['address']->longitude ?></span>
                        </div>
                    </div>
                    <div id="map"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Documents -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Submitted Documents</h3>
            </div>
            <div class="section-content">
                <div class="documents-grid">
                    <?php if (!empty($data['documents'])): ?>
                        <?php foreach ($data['documents'] as $document): ?>
                            <div class="document-card">
                                <div class="document-content">
                                    <?php 
                                    $fileExtension = strtolower(pathinfo($document->file_path, PATHINFO_EXTENSION));
                                    if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif'])): 
                                    ?>
                                        <img src="<?= URLROOT . '/' . $document->file_path ?>" alt="Document Preview" class="document-preview">
                                    <?php else: ?>
                                        <i class='bx bxs-file-pdf document-icon'></i>
                                    <?php endif; ?>
                                </div>
                                <div class="document-info">
                                    <span class="document-type"><?= ucwords(str_replace('_', ' ', $document->document_type)) ?></span>
                                    <a href="<?= URLROOT . '/' . $document->file_path ?>" target="_blank" class="btn-view">
                                        <i class='bx bx-show'></i> View
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="no-documents">
                            <p>No documents have been submitted yet.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
// Define URLROOT if not already defined
const URLROOT = '<?= URLROOT ?>';

function approveApplication(applicationId) {
    if (confirm('Are you sure you want to approve this application? This action cannot be undone.')) {
        window.location.href = `${URLROOT}/suppliermanager/approveApplication/${applicationId}`;
    }
}

function rejectApplication(applicationId) {
    if (confirm('Are you sure you want to reject this application? This action cannot be undone.')) {
        window.location.href = `${URLROOT}/suppliermanager/rejectApplication/${applicationId}`;
    }
}

function confirmSupplierRole(applicationId) {
    if (confirm('Are you sure you want to assign the supplier role to this user?')) {
        window.location.href = `${URLROOT}/suppliermanager/confirmSupplierRole/${applicationId}`;
    }
}

// Add this temporarily to debug
<script>
    console.log("Latitude:", <?= $data['address']->latitude ?>);
    console.log("Longitude:", <?= $data['address']->longitude ?>);
</script>

// Map initialization
<script>
    // Add this before map initialization
    if (typeof L === 'undefined') {
        console.error('Leaflet is not loaded!');
    } else {
        console.log('Leaflet is loaded successfully');
    }

    // Initialize the map with Sri Lanka's center coordinates as default
    const defaultLat = 7.8731;
    const defaultLng = 80.7718;
    
    // Get coordinates from PHP, with fallback to default
    const lat = <?= !empty($data['address']->latitude) ? $data['address']->latitude : 'defaultLat' ?>;
    const lng = <?= !empty($data['address']->longitude) ? $data['address']->longitude : 'defaultLng' ?>;

    // Initialize the map
    const map = L.map('map').setView([lat, lng], 13);

    // Add OpenStreetMap tiles
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Add marker
    const marker = L.marker([lat, lng]).addTo(map);
    
    // Add popup
    marker.bindPopup(`<b>Property Location</b><br>${<?= json_encode($data['address']->line1) ?>}`).openPopup();

    // Force map to update its size container
    setTimeout(() => {
        map.invalidateSize();
    }, 100);
</script>
</script>

<style>
    /* Table Data Container */
    .table-data {
        margin-bottom: 24px;
    }

    .order {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    /* Section Headers */
    .head {
        padding: 16px 20px;
        border-bottom: 1px solid #f0f0f0;
    }

    .head h3 {
        font-size: 16px;
        font-weight: 600;
        color: #2c3e50;
    }

    /* Content Sections */
    .section-content {
        padding: 8px 0;
    }

    /* Info Rows */
    .info-row {
        display: flex;
        align-items: center;
        padding: 12px 20px;
        transition: background-color 0.2s;
    }

    .info-row:hover {
        background-color: #f8f9fa;
    }

    .info-row .label {
        flex: 0 0 200px;
        font-size: 14px;
        color: #6c757d;
    }

    .info-row .value {
        flex: 1;
        font-size: 14px;
        color: #2c3e50;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 12px;
        padding: 16px 20px;
        border-top: 1px solid #f0f0f0;
    }

    .btn-approve,
    .btn-reject {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        border: none;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-approve {
        background-color: #10b981;
        color: white;
    }

    .btn-approve:hover {
        background-color: #059669;
    }

    .btn-reject {
        background-color: #ef4444;
        color: white;
    }

    .btn-reject:hover {
        background-color: #dc2626;
    }

    /* Document Grid Styles */
    .documents-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 20px;
        padding: 20px;
    }

    .document-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        overflow: hidden;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .document-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .document-content {
        position: relative;
        padding-top: 75%; /* 4:3 Aspect Ratio */
        background: #f8f9fa;
    }

    .document-preview {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .document-icon {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 48px;
        color: #dc3545;
    }

    .document-info {
        padding: 12px;
        background: #fff;
    }

    .document-type {
        display: block;
        margin-bottom: 8px;
        font-size: 14px;
        font-weight: 500;
        color: #374151;
    }

    .btn-view {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        background-color: #3b82f6;
        color: white;
        text-decoration: none;
        border-radius: 4px;
        font-size: 13px;
        transition: background-color 0.2s;
    }

    .btn-view:hover {
        background-color: #2563eb;
    }

    .no-documents {
        grid-column: 1 / -1;
        text-align: center;
        padding: 40px;
        color: #6b7280;
        font-size: 14px;
    }

    /* Status Badge */
    .status-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 9999px;
        font-size: 13px;
        font-weight: 500;
    }

    .status-badge.pending {
        background-color: #fef3c7;
        color: #92400e;
    }

    .status-badge.approved {
        background-color: #d1fae5;
        color: #065f46;
    }

    .status-badge.rejected {
        background-color: #fee2e2;
        color: #991b1b;
    }

    /* Breadcrumb Refinements */
    .breadcrumb {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 8px;
    }

    .breadcrumb a {
        color: #6b7280;
        text-decoration: none;
        font-size: 14px;
        transition: color 0.2s;
    }

    .breadcrumb a:hover {
        color: #3b82f6;
    }

    .breadcrumb a.active {
        color: #2c3e50;
        pointer-events: none;
    }

    .breadcrumb i {
        color: #9ca3af;
        font-size: 14px;
    }

    /* Add to your existing style block */
    .address-container {
        display: flex;
        gap: 20px;
        padding: 20px;
        min-height: 300px;
    }

    .address-details {
        flex: 1;
    }

    #map {
        flex: 1;
        min-height: 300px;
        height: 300px;
        width: 100%;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        z-index: 1;
    }

    /* Make it responsive */
    @media (max-width: 768px) {
        .address-container {
            flex-direction: column;
        }
        
        #map {
            height: 250px;
        }
    }

    /* Update these styles in your CSS section */

    /* Table styles */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1rem;
        background-color: white;
    }

    thead tr {
        background-color: #f8f9fa;
        border-bottom: 2px solid #e9ecef;
    }

    th {
        color: #2c3e50;
        font-weight: 600;
        padding: 12px 15px;
        text-align: left;
    }

    td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid #e9ecef;
        color: #000000;
    }

    tbody tr {
        background-color: white;
    }

    tbody tr:hover {
        background-color: #f8f9fa;
    }

    /* Update button style to match approve button */
    .btn-confirm {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        background-color: #10b981;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        text-decoration: none;
        transition: background-color 0.2s;
    }

    .btn-confirm:hover {
        background-color: #059669;
    }

    .btn-confirm i {
        font-size: 16px;
    }
</style>

<?php require APPROOT . '/views/inc/components/footer.php'; ?> 