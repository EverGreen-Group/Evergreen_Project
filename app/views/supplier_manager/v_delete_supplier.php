<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_suppliermanager.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<!-- MAIN -->
<main>
    <div class="head-title">
        <div class="left">
            <h1>Delete Supplier</h1>
            <ul class="breadcrumb">
                <li><a href="#">Dashboard</a></li>
                <li><i class='bx bx-chevron-right' ></i></li>
                <li><a class="active" href="#">Delete Supplier</a></li>
            </ul>
        </div>
    </div>

    <div class="delete-form-container">
        <div class="warning-message">
            <i class='bx bx-error-circle'></i>
            <p>Are you sure you want to delete this supplier? This action cannot be undone.</p>
        </div>

        <form class="delete-form" method="POST" action="">
            <div class="form-group">
                <label for="supplier_id">Supplier ID</label>
                <input type="text" id="supplier_id" name="supplier_id" value="<?php echo $data['supplier']->supplier_id ?? ''; ?>" readonly>
            </div>

            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" id="first_name" name="first_name" value="<?php echo $data['supplier']->first_name ?? ''; ?>" readonly>
            </div>

            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" id="last_name" name="last_name" value="<?php echo $data['supplier']->last_name ?? ''; ?>" readonly>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo $data['supplier']->email ?? ''; ?>" readonly>
            </div>

            <div class="form-group">
                <label for="nic">NIC</label>
                <input type="text" id="nic" name="nic" value="<?php echo $data['supplier']->nic ?? ''; ?>" readonly>
            </div>

            <div class="form-buttons">
                <a href="<?php echo URLROOT; ?>/suppliermanager/suppliers" class="btn btn-back">
                    <i class='bx bx-arrow-back'></i> Back
                </a>
                <button type="submit" class="btn btn-delete">
                    <i class='bx bx-trash'></i> Delete Supplier
                </button>
            </div>
        </form>
    </div>
</main>

<style>
    .delete-form-container {
        max-width: 800px;
        margin: 2rem auto;
        padding: 2rem;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        border: 2px solid #F06E6E;
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

    .delete-form {
        display: grid;
        gap: 1.5rem;
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
        background-color: #f8f9fa;
        cursor: not-allowed;
    }

    .form-buttons {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        margin-top: 1rem;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-size: 0.9rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        border: none;
    }

    .btn i {
        font-size: 1.1rem;
    }

    .btn-back {
        background-color: #f8f9fa;
        color: #333;
        text-decoration: none;
    }

    .btn-back:hover {
        background-color: #e9ecef;
    }

    .btn-delete {
        background-color: #880000;
        color: white;
    }

    .btn-delete:hover {
        background-color: #880088;
    }
</style>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>