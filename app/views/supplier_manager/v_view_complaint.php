<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT.'/views/inc/components/sidebar_vehicle_manager.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/vehicle/vehicle.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/collection/collection.css">

<main>
    <div class="head-title">
        <div class="left">
            <h1>Complaint Details</h1>
            <ul class="breadcrumb">
                <li><a href="<?= URLROOT ?>/manager/complaints">Complaints</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">View Complaint</a></li>
            </ul>
        </div>
    </div>

    <!-- Complaint Status -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Complaint #COM<?= str_pad($data['complaint']->complaint_id, 4, '0', STR_PAD_LEFT) ?></h3>
                <div class="status-badge <?= strtolower($data['complaint']->status) ?>">
                    <?= ucfirst($data['complaint']->status) ?>
                </div>
            </div>
            <div class="section-content">
                <div class="info-row">
                    <span class="label">Filed Date:</span>
                    <span class="value"><?= date('F j, Y', strtotime($data['complaint']->created_at)) ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Priority:</span>
                    <span class="value">
                        <span class="priority-badge <?= strtolower($data['complaint']->priority) ?>">
                            <?= ucfirst($data['complaint']->priority) ?>
                        </span>
                    </span>
                </div>
                <div class="info-row">
                    <span class="label">Type:</span>
                    <span class="value"><?= ucfirst($data['complaint']->complaint_type) ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Last Updated:</span>
                    <span class="value"><?= date('F j, Y', strtotime($data['complaint']->updated_at)) ?></span>
                </div>
                
                <div class="action-buttons">
                    <form action="<?php echo URLROOT; ?>/supplier/resolveComplaint" method="POST" style="display: inline;">
                        <input type="hidden" name="complaint_id" value="<?= $data['complaint']->complaint_id ?>">
                        <?php if ($data['complaint']->status === 'Pending'): ?>
                            <button type="submit" class="btn btn-primary">
                                <i class='bx bx-check-circle'></i> Mark as Resolved
                            </button>
                        <?php endif; ?>
                    </form>

                    <?php if ($data['complaint']->status !== 'Resolved'): ?>
                        <button class="btn btn-tertiary" onclick="confirmDelete(<?= $data['complaint']->complaint_id ?>)">
                            <i class='bx bx-trash'></i> Delete
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Supplier Information -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Supplier Information</h3>
            </div>
            <div class="section-content">
                <div class="info-row">
                    <span class="label">Supplier:</span>
                    <span class="value">
                        <a href="<?= URLROOT ?>/manager/viewSupplier/<?= $data['complaint']->supplier_id ?>" class="manager-link">
                            <img src="<?= URLROOT . '/' . $data['complaint']->image_path ?>" alt="Supplier Photo" class="manager-photo">
                            <?= $data['complaint']->supplier_name ?>
                        </a>
                    </span>
                </div>
                <div class="info-row">
                    <span class="label">Phone:</span>
                    <span class="value"><?= $data['complaint']->phone ?? 'N/A' ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Email:</span>
                    <span class="value"><?= $data['complaint']->email ?? 'N/A' ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Complaint Details -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Complaint Details</h3>
            </div>
            <div class="section-content">
                <div class="info-row">
                    <span class="label">Subject:</span>
                    <span class="value"><?= $data['complaint']->subject ?></span>
                </div>
                <div class="info-row complaint-description">
                    <span class="label">Description:</span>
                    <span class="value"><?= nl2br($data['complaint']->description) ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Complaint Image -->
    <?php if (!empty($data['complaint']->image_path)): ?>
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Attached Image</h3>
            </div>
            <div class="section-content">
                <div class="complaint-image">
                    <img src="<?= URLROOT . '/' . $data['complaint']->image_path ?>" alt="Complaint Image">
                </div>
                <div class="image-actions">
                    <a href="<?= URLROOT . '/' . $data['complaint']->image_path ?>" class="btn-view" target="_blank">
                        <i class='bx bx-fullscreen'></i> View Full Image
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</main>


<script>
    function showResolveModal(id) {
        document.getElementById('complaint_id').value = id;
        document.getElementById('resolveModal').style.display = 'block';
    }

    function closeModal() {
        document.getElementById('resolveModal').style.display = 'none';
    }

    document.getElementsByClassName('close')[0].onclick = function() {
        closeModal();
    }

    window.onclick = function(event) {
        const modal = document.getElementById('resolveModal');
        if (event.target == modal) {
            closeModal();
        }
    }

    function confirmDelete(id) {
        if (confirm('Are you sure you want to delete this complaint? This action cannot be undone.')) {
            window.location.href = '<?= URLROOT ?>/manager/deleteComplaint/' + id;
        }
    }
</script>


<style>
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

    .complaint-description .value {
        white-space: pre-line;
    }

    .manager-link {
        display: flex;
        align-items: center;
        text-decoration: none;
        color: inherit;
    }

    .manager-photo {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        margin-right: 8px;
        object-fit: cover;
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

    .btn-resolve {
        background-color: #10b981;
        color: white;
    }

    .btn-resolve:hover {
        background-color: #059669;
    }

    .btn-reopen {
        background-color: #3b82f6;
        color: white;
    }

    .btn-reopen:hover {
        background-color: #2563eb;
    }

    .btn-delete {
        background-color: #ef4444;
        color: white;
    }

    .btn-delete:hover {
        background-color: #dc2626;
    }

    /* Status Badge Styles */
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

    .status-badge.resolved {
        background-color: #d1fae5;
        color: #065f46;
    }

    .status-badge.deleted {
        background-color: #fee2e2;
        color: #991b1b;
    }

    /* Priority Badge Styles */
    .priority-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 9999px;
        font-size: 13px;
        font-weight: 500;
        color: white;
    }

    .priority-badge.high {
        background-color: #F64E60;
    }

    .priority-badge.medium {
        background-color: #FFA800;
    }

    .priority-badge.low {
        background-color: #3699FF;
    }

    /* Complaint Image */
    .complaint-image {
        text-align: center;
        margin-bottom: 15px;
    }

    .complaint-image img {
        max-width: 100%;
        max-height: 400px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .image-actions {
        display: flex;
        justify-content: center;
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

    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
    }

    .modal-content {
        background-color: #fefefe;
        margin: 15% auto;
        padding: 20px;
        border-radius: 8px;
        width: 50%;
        max-width: 500px;
        animation: modalFade 0.3s;
    }

    @keyframes modalFade {
        from {opacity: 0; transform: translateY(-20px);}
        to {opacity: 1; transform: translateY(0);}
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
    }

    .modal h2 {
        margin-top: 0;
        color: #111827;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: 500;
    }

    .form-group textarea {
        width: 100%;
        padding: 8px;
        border: 1px solid #d1d5db;
        border-radius: 4px;
        resize: vertical;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .btn-cancel {
        padding: 8px 16px;
        background-color: #e5e7eb;
        color: #4b5563;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .btn-submit {
        padding: 8px 16px;
        background-color: #10b981;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .btn-cancel, .btn-submit {
        padding: 8px 16px;
        border: none;
        border-radius: 4px;
        font-size: 14px;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .btn-cancel {
        background-color: #e5e7eb;
        color: #4b5563;
    }

    .btn-cancel:hover {
        background-color: #d1d5db;
    }

    .btn-submit {
        background-color: #10b981;
        color: white;
    }

    .btn-submit:hover {
        background-color: #059669;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 20px;
    }
</style>



<?php require APPROOT . '/views/inc/components/footer.php'; ?>