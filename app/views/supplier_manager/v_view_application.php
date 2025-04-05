<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_vehicle_manager.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<!-- MAIN -->
<main>
    <!-- <?php print_r($_SESSION); print_r($data) ?> -->
    <div class="head-title">
        <div class="left">
            <h1>Application Details</h1>
            <ul class="breadcrumb">
                <li><a href="<?= URLROOT ?>/manager/applications">Applications</a></li>
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
                <div class="status-badge <?= strtolower($data['application']['status']) ?>">
                    <?= ucfirst(str_replace('_', ' ', $data['application']['status'])) ?>
                </div>
            </div>
            <div class="section-content">
                <div class="info-row">
                    <span class="label">Submitted Date:</span>
                    <span class="value"><?= date('F j, Y', strtotime($data['application']['created_at'])) ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Email:</span>
                    <span class="value"><?= $data['application']['email'] ?></span>
                </div>
                
                <div class="info-row">
                    <?php if (!empty($data['application']['reviewed_by'])): ?>
                        <span class="label">Assigned To:</span>
                        <span class="value"><?= htmlspecialchars($data['application']['manager_name']) ?></span>
                    <?php else: ?>
                        <span class="label">Assigned To:</span>
                        <span class="value">Not assigned</span>
                    <?php endif; ?>
                </div>
                
                <div class="action-buttons">
                    <?php if ($data['application']['status'] === 'pending' && is_null($data['application']['reviewed_by'])): ?>
                        <button class="btn-assign" onclick="window.location.href='<?php echo URLROOT; ?>/manager/assignApplication/<?php echo $data['application']['application_id']; ?>'">
                            Assign to Me
                        </button>
                    <?php elseif ($data['application']['reviewed_by'] == $_SESSION['manager_id']): ?>
                        <?php if ($data['application']['status'] === 'under_review'): ?>
                            <button class="btn-primary" onclick="window.location.href='<?php echo URLROOT; ?>/manager/approveApplication/<?php echo $data['application']['application_id']; ?>'">
                             <i class='bx bx-user-check'></i>Approve and Create a Supplier Profile
                            </button>
                            <button class="btn-tertiary" onclick="window.location.href='<?php echo URLROOT; ?>/manager/rejectApplication/<?php echo $data['application']['application_id']; ?>'">
                            <i class='bx bx-user-x'></i>Reject
                            </button>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="info-message">
                            <i class='bx bx-info-circle'></i>
                            This application is assigned to another reviewer.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Personal Information -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Personal Information</h3>
            </div>
            <div class="section-content">
                <div class="info-row">
                    <span class="label">Full Name:</span>
                    <span class="value"><?= $data['profile']->first_name . ' ' . $data['profile']->last_name ?></span>
                </div>
                <div class="info-row">
                    <span class="label">NIC:</span>
                    <span class="value"><?= $data['profile']->nic ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Date of Birth:</span>
                    <span class="value"><?= date('F j, Y', strtotime($data['profile']->date_of_birth)) ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Contact Number:</span>
                    <span class="value"><?= $data['profile']->contact_number ?></span>
                </div>
                <?php if (!empty($data['profile']->emergency_contact)): ?>
                <div class="info-row">
                    <span class="label">Emergency Contact:</span>
                    <span class="value"><?= $data['profile']->emergency_contact ?></span>
                </div>
                <?php endif; ?>
                <div class="info-row">
                    <span class="label">Address:</span>
                    <span class="value">
                        <?= $data['profile']->address_line1 ?><br>
                        <?php if (!empty($data['profile']->address_line2)): ?>
                            <?= $data['profile']->address_line2 ?><br>
                        <?php endif; ?>
                        <?= $data['profile']->city ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Cultivation Information -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Tea Cultivation Details</h3>
            </div>
            <div class="section-content">
                <div class="info-row">
                    <span class="label">Cultivation Area:</span>
                    <span class="value"><?= $data['cultivation']->tea_cultivation_area ?> acres</span>
                </div>
                <div class="info-row">
                    <span class="label">Plant Age:</span>
                    <span class="value"><?= $data['cultivation']->plant_age ?> years</span>
                </div>
                <div class="info-row">
                    <span class="label">Monthly Production:</span>
                    <span class="value"><?= $data['cultivation']->monthly_production ?> kg</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Location Information -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Location Information</h3>
            </div>
            <div class="section-content">
                <div class="info-row">
                    <span class="label">Latitude:</span>
                    <span class="value"><?= $data['location']->latitude ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Longitude:</span>
                    <span class="value"><?= $data['location']->longitude ?></span>
                </div>
                <div class="map-container">
                    <div id="map" style="height: 300px; width: 100%; border-radius: 8px;"></div>
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
                    <span class="label">Branch:</span>
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

    <!-- Documents -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Submitted Documents</h3>
            </div>
            <div class="documents-grid">
                <?php if (!empty($data['documents'])): ?>
                    <?php foreach ($data['documents'] as $document): ?>
                        <div class="document-card">
                            <div class="document-content">
                                <?php 
                                $fileExt = pathinfo($document->file_path, PATHINFO_EXTENSION);
                                if (in_array(strtolower($fileExt), ['jpg', 'jpeg', 'png', 'gif'])): 
                                ?>
                                    <img src="<?= URLROOT . '/' . $document->file_path ?>" class="document-preview" alt="<?= ucfirst(str_replace('_', ' ', $document->document_type)) ?>">
                                <?php else: ?>
                                    <i class='bx bxs-file-pdf document-icon'></i>
                                <?php endif; ?>
                            </div>
                            <div class="document-info">
                                <span class="document-type"><?= ucfirst(str_replace('_', ' ', $document->document_type)) ?></span>
                                <a href="<?= URLROOT . '/' . $document->file_path ?>" class="btn-view" target="_blank">
                                    <i class='bx bx-show'></i> View Document
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="no-documents">
                        <i class='bx bx-file'></i>
                        <p>No documents found for this application.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<script>
    // Initialize map when the page loads
    function initMap() {
        const lat = <?= $data['location']->latitude ?>;
        const lng = <?= $data['location']->longitude ?>;
        
        const mapOptions = {
            center: { lat: lat, lng: lng },
            zoom: 20
        };
        
        const map = new google.maps.Map(document.getElementById('map'), mapOptions);
        
        // Add marker for the supplier location
        new google.maps.Marker({
            position: { lat: lat, lng: lng },
            map: map,
            title: 'Supplier Location'
        });
    }
</script>

<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAC8AYYCYuMkIUAjQWsAwQDiqbMmLa-7eo&callback=initMap">
</script>

<style>
    /* Section Styles */
    .section-content {
        padding: 20px;
    }

    .info-row {
        display: flex;
        margin-bottom: 12px;
        align-items: flex-start;
    }

    .info-row .label {
        width: 150px;
        font-weight: 500;
        color: #4b5563;
    }

    .info-row .value {
        flex: 1;
        color: #1f2937;
    }

    .reviewer {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .reviewer-avatar {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        object-fit: cover;
    }

    .rejection-reason {
        color: #ef4444;
        font-style: italic;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 10px;
        margin-top: 20px;
    }

    .action-buttons button {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        border: none;
        border-radius: 4px;
        font-size: 14px;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .btn-assign {
        background-color: #3b82f6;
        color: white;
    }

    .btn-assign:hover {
        background-color: #2563eb;
    }

    .btn-review {
        background-color: #8b5cf6;
        color: white;
    }

    .btn-review:hover {
        background-color: #7c3aed;
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

    .info-message {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 16px;
        background-color: #e0f2fe;
        border-radius: 4px;
        color: #0369a1;
    }

    .info-message i {
        font-size: 18px;
        color: #3498db;
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

    .status-badge.under-review {
        background-color: #e0f2fe;
        color: #0369a1;
    }

    .status-badge.approved {
        background-color: #d1fae5;
        color: #065f46;
    }

    .status-badge.rejected, .status-badge.auto-rejected {
        background-color: #fee2e2;
        color: #991b1b;
    }

    /* Map container */
    .map-container {
        margin-top: 15px;
    }
</style>

<?php require APPROOT . '/views/inc/components/footer.php'; ?> 