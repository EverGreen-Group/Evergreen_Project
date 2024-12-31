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
        <form class="update-form" method="POST" action="">
            <div class="form-group">
                <label for="supplier_id">Supplier ID</label>
                <input type="text" id="supplier_id" name="supplier_id" value="<?php echo $data['supplier']->supplier_id ?? ''; ?>" readonly>
            </div>

            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" id="first_name" name="first_name" value="<?php echo $data['supplier']->first_name ?? ''; ?>" required>
            </div>

            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" id="last_name" name="last_name" value="<?php echo $data['supplier']->last_name ?? ''; ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo $data['supplier']->email ?? ''; ?>" required>
            </div>

            <div class="form-group">
                <label for="nic">NIC</label>
                <input type="text" id="nic" name="nic" value="<?php echo $data['supplier']->nic ?? ''; ?>" required>
            </div>

            <div class="form-buttons">
                <a href="<?php echo URLROOT; ?>/suppliermanager/suppliers" class="btn btn-back">
                    <i class='bx bx-arrow-back'></i> Back
                </a>
                <button type="submit" class="btn btn-save">
                    <i class='bx bx-save'></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</main>

<style>
    .update-form-container {
        max-width: 800px;
        margin: 2rem auto;
        padding: 2rem;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        border: 2px solid #86E211;
    }

    .update-form {
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
        transition: all 0.3s ease;
    }

    .form-group input:focus {
        border-color: #86E211;
        outline: none;
    }

    .form-group input[readonly] {
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

    .btn-save {
        background-color: #005a4d;
        color: white;
    }

    .btn-save:hover {
        background-color: #78cc0f;
    }
</style>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>