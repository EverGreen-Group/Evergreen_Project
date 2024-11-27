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
                <textarea name="shipping_address" id="shipping_address" class="form-control <?php echo (!empty($data['errors']['shipping_address'])) ? 'is-invalid' : ''; ?>"><?php echo $data['order']->shipping_address; ?></textarea>
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
                <textarea name="notes" id="notes" class="form-control"><?php echo $data['order']->notes; ?></textarea>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">Update Order</button>
                <a href="<?php echo URLROOT; ?>/shop/orders" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</main>

<?php require APPROOT . '/views/inc/components/footer.php'; ?> 