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
                            <option value="Quality Issue">Quality Issues</option>
                            <option value="Delivery Problems">Delivery Problems</option>
                            <option value="Payment Issues">Payment Issues</option>
                            <option value="Customer Service">Customer Service</option>
                            <option value="Other">Other</option>
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


                    <div class="form-actions">
                        <button type="submit" class="btn btn-secondary">Submit Complaint</button>
                        <button type="button" class="btn btn-secondary" onclick="window.location.href='<?php echo URLROOT; ?>/supplier'">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <br><br> 
    
    <!-- Improved Complaint History Section -->
    <div class="complaints-history-container">
        <div class="history-header">
            <h3>Complaints History</h3>
            <div class="history-counter"><?php echo !empty($data['complaints']) ? count($data['complaints']) : '0'; ?> complaints</div>
        </div>

        <?php if (!empty($data['complaints'])): ?>
            <div class="complaints-grid">
                <?php foreach($data['complaints'] as $complaint): ?>
                    <div class="complaint-card">
                        <div class="complaint-header">
                            <div class="complaint-type-badge"><?php echo $complaint->complaint_type; ?></div>
                            <div class="complaint-status status-<?php echo strtolower(str_replace(' ', '-', $complaint->status)); ?>">
                                <?php echo $complaint->status; ?>
                            </div>
                        </div>
                        
                        <div class="complaint-body">
                            <h4 class="complaint-subject"><?php echo $complaint->subject; ?></h4>
                            <div class="complaint-meta">
                                <span class="complaint-id">ID: #<?php echo $complaint->complaint_id; ?></span>
                                <span class="complaint-date"><?php echo date('M d, Y', strtotime($complaint->created_at)); ?></span>
                            </div>
                            <div class="complaint-description">
                                <p><?php echo $complaint->description; ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="no-complaints">
                <div class="empty-icon">
                    <i class='bx bx-message-square-x'></i>
                </div>
                <p>No complaints found</p>
                <span>Your submitted complaints will appear here</span>
            </div>
        <?php endif; ?>
    </div>
</main>

<style>
    /* Original Form Styles */
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

    .form-container {
        max-width: 1000px; 
        margin: 0 auto;
        background: #fff; 
        padding: 20px;  
        border-radius: 8px;
    }

    /* Improved Complaint History Styles */
    .complaints-history-container {
        background: #fff;
        border-radius: 8px;
        padding: 25px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        margin-bottom: 30px;
    }

    .history-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eaeaea;
    }

    .history-header h3 {
        color: #2c3e50;
        font-size: 1.5rem;
        font-weight: 600;
        margin: 0;
    }

    .history-counter {
        background-color: #f1f2f6;
        color: #606060;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 500;
    }

    .complaints-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 20px;
    }

    .complaint-card {
        background-color: #f8f9fa;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.05);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .complaint-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    }

    .complaint-header {
        display: flex;
        justify-content: space-between;
        padding: 12px 15px;
        background-color: #f1f2f6;
        border-bottom: 1px solid #eaeaea;
    }

    .complaint-type-badge {
        background-color: var(--main);
        color: white;
        font-size: 0.8rem;
        padding: 3px 10px;
        border-radius: 20px;
        font-weight: 500;
    }

    .complaint-status {
        font-size: 0.8rem;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 20px;
        text-align: center;
    }

    .status-pending {
        color: #f39c12;
        background-color: rgba(243, 156, 18, 0.1);
    }

    .status-resolved {
        color: #27ae60;
        background-color: rgba(39, 174, 96, 0.1);
    }

    .status-processing {
        color: #3498db;
        background-color: rgba(52, 152, 219, 0.1);
    }

    .status-rejected {
        color: #e74c3c;
        background-color: rgba(231, 76, 60, 0.1);
    }

    .complaint-body {
        padding: 15px;
    }

    .complaint-subject {
        margin: 0 0 10px 0;
        color: #2c3e50;
        font-size: 1.1rem;
        font-weight: 600;
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 1;
        overflow: hidden;
    }

    .complaint-meta {
        display: flex;
        justify-content: space-between;
        margin-bottom: 12px;
        font-size: 0.85rem;
        color: #7f8c8d;
    }

    .complaint-id {
        font-weight: 500;
    }

    .complaint-description {
        background-color: #fff;
        border-radius: 6px;
        padding: 12px;
        margin-top: 10px;
        border: 1px solid #eaeaea;
        max-height: 100px;
        overflow-y: auto;
    }

    .complaint-description p {
        margin: 0;
        color: #34495e;
        line-height: 1.5;
        font-size: 0.95rem;
    }

    .no-complaints {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 40px 20px;
        text-align: center;
        background-color: #f8f9fa;
        border-radius: 8px;
        border: 1px dashed #e0e0e0;
    }

    .no-complaints .empty-icon {
        font-size: 40px;
        color: #bdc3c7;
        margin-bottom: 15px;
    }

    .no-complaints p {
        margin: 0 0 5px 0;
        font-size: 1.1rem;
        color: #7f8c8d;
        font-weight: 500;
    }

    .no-complaints span {
        color: #95a5a6;
        font-size: 0.9rem;
    }

    /* Media Queries for Responsiveness */
    @media screen and (max-width: 768px) {
        .complaints-grid {
            grid-template-columns: 1fr;
        }
        
        .history-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }
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
        
        .complaint-header {
            flex-direction: column;
            gap: 8px;
        }
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