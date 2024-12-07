<?php require APPROOT . '/views/inc/components/header.php'; ?>

<main>
    <div class="head-title">
        <div class="left">
            <h1>Edit Order #<?php echo $data['order']->order_number; ?></h1>
            <ul class="breadcrumb">
                <li><a href="<?php echo URLROOT; ?>/shop">Shop</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a href="<?php echo URLROOT; ?>/shop/orders">Orders</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">Edit Order</a></li>
            </ul>
        </div>
    </div>

    <div class="order-edit-form">
        <form action="<?php echo URLROOT; ?>/shop/editOrder/<?php echo $data['order']->id; ?>" method="POST">
            <div class="form-group">
                <label for="shipping_address">Shipping Address</label>
                <textarea name="shipping_address" id="shipping_address" 
                    class="form-control <?php echo (!empty($data['errors']['shipping_address'])) ? 'is-invalid' : ''; ?>"
                    rows="4"><?php echo $data['order']->shipping_address; ?></textarea>
                <span class="invalid-feedback"><?php echo $data['errors']['shipping_address'] ?? ''; ?></span>
            </div>

            <div class="form-group">
                <label for="shipping_method">Shipping Method</label>
                <select name="shipping_method" id="shipping_method" class="form-control">
                    <option value="Standard" <?php echo ($data['order']->shipping_method == 'Standard') ? 'selected' : ''; ?>>Standard Delivery</option>
                    <option value="Express" <?php echo ($data['order']->shipping_method == 'Express') ? 'selected' : ''; ?>>Express Delivery</option>
                </select>
            </div>

            <div class="form-group">
                <label for="notes">Order Notes</label>
                <textarea name="notes" id="notes" class="form-control" rows="4"><?php echo $data['order']->notes; ?></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-update">Update Order</button>
                <a href="<?php echo URLROOT; ?>/shop/orders" class="btn-cancel">Cancel</a>
            </div>
        </form>
    </div>
</main>

<style>
.order-edit-form {
    background: #ffffff;
    padding: 2rem;
    border-radius: 12px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);
    max-width: 800px;
    margin: 2rem auto;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: #333;
    font-size: 0.95rem;
}

.form-control {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 0.95rem;
    transition: border-color 0.3s ease;
}

.form-control:focus {
    outline: none;
    border-color: #006837;
    box-shadow: 0 0 0 2px rgba(0, 104, 55, 0.1);
}

textarea.form-control {
    resize: vertical;
    min-height: 100px;
}

select.form-control {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%23333' viewBox='0 0 16 16'%3E%3Cpath d='M8 11L3 6h10l-5 5z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 1rem center;
    background-size: 12px;
    padding-right: 2.5rem;
    appearance: none;
    -webkit-appearance: none;
}

.is-invalid {
    border-color: #dc3545;
}

.invalid-feedback {
    display: block;
    color: #dc3545;
    font-size: 0.85rem;
    margin-top: 0.25rem;
}

.form-actions {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
    padding-top: 1rem;
    border-top: 1px solid #eee;
}

.btn-update {
    padding: 0.75rem 1.5rem;
    background-color: #006837;
    color: white;
    border: none;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-update:hover {
    background-color: #005229;
    transform: translateY(-1px);
}

.btn-cancel {
    padding: 0.75rem 1.5rem;
    background-color: #f8f9fa;
    color: #333;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-weight: 600;
    text-decoration: none;
    text-align: center;
    transition: all 0.3s ease;
}

.btn-cancel:hover {
    background-color: #e9ecef;
    border-color: #c8c9ca;
}

/* Responsive styles */
@media (max-width: 768px) {
    .order-edit-form {
        padding: 1.5rem;
        margin: 1rem;
    }

    .form-actions {
        flex-direction: column;
        gap: 0.75rem;
    }

    .btn-update,
    .btn-cancel {
        width: 100%;
    }
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
    .order-edit-form {
        background: #1a1a1a;
    }

    .form-group label {
        color: #e1e1e1;
    }

    .form-control {
        background-color: #2d2d2d;
        border-color: #404040;
        color: #e1e1e1;
    }

    .form-control:focus {
        border-color: #006837;
        box-shadow: 0 0 0 2px rgba(0, 104, 55, 0.2);
    }

    .btn-cancel {
        background-color: #2d2d2d;
        border-color: #404040;
        color: #e1e1e1;
    }

    .btn-cancel:hover {
        background-color: #363636;
        border-color: #4a4a4a;
    }
}
</style>

<?php require APPROOT . '/views/inc/components/footer.php'; ?> 