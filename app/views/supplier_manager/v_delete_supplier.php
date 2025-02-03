<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_suppliermanager.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<!-- MAIN -->
<main>
    <div class="head-title">
        <div class="left">
            <h1>Update Supplier Details</h1>
            <ul class="breadcrumb">
                <li><a href="#">Dashboard</a></li>
                <li><i class='bx bx-chevron-right' ></i></li>
                <li><a class="active" href="#">Update Supplier</a></li>
            </ul>
        </div>
    </div>

    <div class="update-form-container">

	    <div class="warning-message">
            <i class='bx bx-error-circle'></i>
            <p>Are you sure you want to delete this supplier? This action cannot be undone.</p>
        </div>
        <?php flash('supplier_error'); ?>
        
        <form action="<?php echo URLROOT; ?>/suppliermanager/deleteSupplier/<?php echo $data['user_id']; ?>" method="POST" class="request-form">
            <div class="form-container">
                <input type="hidden" name="user_id" value="<?php echo $data['user_id']; ?>">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="first_name">First Name:</label>
                        <input type="text" id="first_name" name="first_name" 
                            value="<?php echo $data['first_name']; ?>" 
                            class="<?php echo (!empty($data['first_name_err'])) ? 'is-invalid' : ''; ?>" readonly>
                        <span class="invalid-feedback"><?php echo $data['first_name_err']; ?></span>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="last_name">Last Name:</label>
                        <input type="text" id="last_name" name="last_name" 
                            value="<?php echo $data['last_name']; ?>"
                            class="<?php echo (!empty($data['last_name_err'])) ? 'is-invalid' : ''; ?>" readonly>
                        <span class="invalid-feedback"><?php echo $data['last_name_err']; ?></span>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="nic">NIC:</label>
                        <input type="text" id="nic" name="nic" 
                            value="<?php echo $data['nic']; ?>"
                            class="<?php echo (!empty($data['nic_err'])) ? 'is-invalid' : ''; ?>" readonly>
                        <span class="invalid-feedback"><?php echo $data['nic_err']; ?></span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" 
                        value="<?php echo $data['email']; ?>"
                        class="<?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" readonly>
                    <span class="invalid-feedback"><?php echo $data['email_err']; ?></span>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-delete"><i class='bx bx-trash'></i> Delete Supplier</button>
                    <a href="<?php echo URLROOT; ?>/suppliermanager/suppliers" class="btn-cancel">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</main>

<style>
    .btn-delete,
    .btn-cancel {
        padding: 8px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 0.9rem;
        flex: 1;
        transition: all 0.3s ease;
    }

    .btn-delete {
        background: #880000;
        color: white;
    }

    .btn-delete:hover {
        background: #bb0000;
    }

    .btn-cancel {
        background: #f5f5f5;
        color: #666;
    }

    .btn-cancel:hover {
        background: #e0e0e0;
    }
    
    .update-form-container {
        max-width: 800px;
        margin: 2rem auto;
        padding: 2rem;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        border: 2px solid #d33d00;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .form-group label {
        font-size: 0.9rem;
        color: #555;
        font-weight: 500;
    }

    .form-group input {
        padding: 0.75rem;
        border: 2px solid #eee;
        border-radius: 8px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .form-group input[readonly] {
        background-color: #f8f9fa;
        cursor: not-allowed;
    }

    .warning-message {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        background-color: #fff3cd;
        border: 1px solid #ffeeba;
        border-radius: 8px;
        margin-bottom: 2rem;
    }

    .warning-message i {
        font-size: 1.5rem;
        color: #F06E6E;
    }

    .warning-message p {
        color: #856404;
        margin: 0;
    }

</style>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>