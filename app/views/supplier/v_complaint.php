<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_supplier.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<!-- MAIN -->
<main>
    <div class="head-title">
        <div class="left">
            <h1>Submit Complaint</h1>
            <ul class="breadcrumb">
                <li><a href="<?php echo URLROOT; ?>/supplier">Home</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">Complaint</a></li>
            </ul>
        </div>
    </div>

    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Complaint</h3>
            </div>
            
            <div class="form-container">
                <form action="<?php echo URLROOT; ?>/supplier/submitComplaint" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="complaint-type">Complaint Type</label>
                        <select id="complaint-type" name="complaint_type" required>
                            <option value="">Select type</option>
                            <option value="quality">Quality Issues</option>
                            <option value="delivery">Delivery Problems</option>
                            <option value="payment">Payment Issues</option>
                            <option value="service">Customer Service</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="subject">Subject</label>
                        <input type="text" id="subject" name="subject" placeholder="Brief description of the issue" required>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" placeholder="Provide detailed information about your complaint" rows="4" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="image">Attach Image (Optional)</label>
                        <div class="file-upload">
                            <input type="file" id="image" name="image" accept="image/*">
                            <label for="image" class="file-label">
                                <i class='bx bx-upload'></i>
                                <span>Choose File</span>
                            </label>
                            <div id="image-preview" class="image-preview"></div>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="priority">Priority Level</label>
                        <select id="priority" name="priority" required>
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-secondary">Submit Complaint</button>
                        <button type="button" class="btn btn-secondary" onclick="window.location.href='<?php echo URLROOT; ?>/supplier'">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <br><br> 
    
    <div class="complaint-history-section">
        <div class="section-header">
        <h3>Complaints History</h3>
        </div>

        <?php if (!empty($data['complaints'])): ?>
            <?php foreach($data['complaints'] as $complaint): ?>
                <div class="complaint-card">
                    <div class="card-content">
                        <div class="card-header">
                            <div class="status-badge">
                                Complaint #<?php echo $complaint->complaint_id; ?>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="schedule-info">
                                <div class="info-item">
                                <span>Complaint ID: <?php echo $complaint->complaint_id; ?></span>
                                </div>
                                <div class="info-item">
                                <span>Complaint type: <?php echo $complaint->complaint_type; ?></span>
                                </div>
                                <div class="info-item">
                                <span>Description: <?php echo $complaint->description; ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="schedule-info">
                                <div class="info-item">
                                <span>Status: <?php echo $complaint->status; ?></span>
                                </div>
                                <div class="info-item">
                                <span>Subject: <?php echo $complaint->subject; ?></span>
                                </div>
                                <div class="info-item">
                                <span>Created: <?php echo $complaint->created_at; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
        <div class="no-schedule">
            <p>No complaints found.</p>
        </div>
        <?php endif; ?>
    </div>
</main>

<style>
    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: #333;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 16px;
    }

    .form-group textarea {
        resize: vertical;
        min-height: 100px;
    }

    .file-upload {
        position: relative;
    }

    .file-upload input[type="file"] {
        display: none;
    }

    .file-label {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px;
        background: #f5f5f5;
        border: 2px dashed #ddd;
        border-radius: 5px;
        cursor: pointer;
        text-align: center;
    }

    .file-label:hover {
        background: #eee;
    }

    .image-preview {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 10px;
    }

    .image-preview img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 5px;
    }

    .form-actions {
        display: flex;
        gap: 10px;
        margin-top: 20px;
    }

    .btn-submit,
    .btn-cancel {
        flex: 1;
        padding: 12px;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-submit {
        background: var(--main);
        color: white;
    }

    .btn-submit:hover {
        background: var(--main-dark);
        opacity: 0.9;
    }

    .btn-cancel {
        background: #f5f5f5;
        color: #333;
    }

    .btn-cancel:hover {
        background: #e0e0e0;
    }

    @media screen and (max-width: 360px) {
        .form-group input,
        .form-group select,
        .form-group textarea {
            font-size: 14px;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn-submit,
        .btn-cancel {
            width: 100%;
        }

        .image-preview img {
            width: 60px;
            height: 60px;
        }
    }

    .form-container {
        max-width: 1000px; 
        margin: 0 auto;
        background: #fff; 
        padding: 20px;  
        border-radius: 8px;
    }

    /* Complaints Section Styles */
    .complaint-history-section {
        margin-bottom: 2rem;
    }

    .complaint-history-section .section-header {
        margin-bottom: 1rem;
    }

    .complaint-history-section .section-header h3 {
        color: #2c3e50;
        font-size: 1.5rem;
        font-weight: 600;
    }

    .complaint-card {
        background-color: white;
        padding: 1.5rem;
        border-radius: 12px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        margin-bottom: 1rem;
    }

    .complaint-card .card-header {
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
    }

    .complaint-card .status-badge {
        display: inline-block;
        background-color:rgb(1, 146, 20);
        color: white;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .complaint-card .card-body {
        padding: 0.5rem 0;
        flex: 1 1 30%;
        min-width: 100px;
    }

    .complaint-card .card-content {
        display: flex;
        flex-wrap: wrap;
        gap: 2rem;
    }

    .complaint-card .schedule-info {
        display: flex;
        flex-wrap: wrap;
    }

    .complaint-card .info-item {
        display: flex;
        align-items: center;
        padding: 0.25rem 0;
        width: 100%;
    }

    .complaint-card .info-item span {
        font-size: 0.95rem;
        color: #2c3e50;
    }

    .complaint-card .info-item:has(span:contains("Description")) span {
        display: block;
        margin-top: 0.25rem;
    }

    .no-schedule {
        background-color: #f8f9fa;
        padding: 1.5rem;
        border-radius: 12px;
        text-align: center;
        color: #7f8c8d;
        border: 1px dashed #e0e0e0;
        margin-top: 1rem;
    }

    /* Media queries for responsiveness */
    @media (max-width: 768px) {
        .complaint-card {
            padding: 1rem;
        }
        
        .complaint-card .info-item {
            flex-direction: column;
            align-items: flex-start;
        }
    }

    .complaint-card .grouped-info .schedule-info {
        display: flex;
        justify-content: space-between;
        gap: 1rem;
    }

    .complaint-card .grouped-info .info-item {
        width: auto;
        flex: 1;
    }


</style>

<script>
    document.getElementById('image').addEventListener('change', function(e) {
        const preview = document.getElementById('image-preview');
        preview.innerHTML = '';

        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                preview.appendChild(img);
            };
            reader.readAsDataURL(file);
        }
    });

</script>
<script src="<?php echo URLROOT; ?>/css/script.js"></script>
