<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_shop.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<main>
    <div class="head-title">
        <div class="left">
            <h1>Shopping Cart</h1>
            <ul class="breadcrumb">
                <li><a href="<?php echo URLROOT; ?>/shop">Shop</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">Cart</a></li>
            </ul>
        </div>
    </div>

    <?php flash('cart_message'); ?>

    <div class="cart-container">
        <?php if(empty($data['cart_items'])): ?>
            <div class="empty-cart">
                <i class='bx bx-cart'></i>
                <h2>Your cart is empty</h2>
                <p>Browse our collection and add some tea to your cart!</p>
                <a href="<?php echo URLROOT; ?>/shop" class="btn-continue">Continue Shopping</a>
            </div>
        <?php else: ?>
            <div class="cart-content">
                <div class="cart-items">
                    <?php foreach($data['cart_items'] as $item): ?>
                        <div class="cart-item" data-product-id="<?php echo $item->product_id; ?>">
                            <div class="item-image">
                                <img src="<?php echo URLROOT; ?>/img/products/<?php echo $item->product_image; ?>" 
                                     alt="<?php echo $item->product_name; ?>">
                            </div>
                            <div class="item-details">
                                <h3><?php echo $item->product_name; ?></h3>
                                <p class="weight"><?php echo $item->weight . $item->weight_unit; ?></p>
                                <div class="price">LKR <?php echo number_format($item->price, 2); ?></div>
                                <div class="quantity-controls">
                                    <button class="btn-quantity decrease" <?php echo $item->quantity <= 1 ? 'disabled' : ''; ?>>
                                        <i class='bx bx-minus'></i>
                                    </button>
                                    <span class="quantity"><?php echo $item->quantity; ?></span>
                                    <button class="btn-quantity increase">
                                        <i class='bx bx-plus'></i>
                                    </button>
                                </div>
                                <div class="item-total">
                                    LKR <?php echo number_format($item->price * $item->quantity, 2); ?>
                                </div>
                                <button class="btn-remove" title="Remove item">
                                    <i class='bx bx-trash'></i>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="cart-summary">
                    <h3>Order Summary</h3>
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span>LKR <?php echo number_format($data['cart_total'], 2); ?></span>
                    </div>
                    <div class="summary-row">
                        <span>Shipping</span>
                        <span>LKR <?php echo number_format(500.00, 2); ?></span>
                    </div>
                    <div class="summary-row">
                        <span>Tax (10%)</span>
                        <span>LKR <?php echo number_format($data['cart_total'] * 0.10, 2); ?></span>
                    </div>
                    <div class="summary-row total">
                        <span>Total</span>
                        <span>LKR <?php echo number_format($data['cart_total'] + 500 + ($data['cart_total'] * 0.10), 2); ?></span>
                    </div>
                    
                    <a href="<?php echo URLROOT; ?>/shop/payment" class="btn-checkout">
                        Proceed to Payment
                    </a>
                    
                    <a href="<?php echo URLROOT; ?>/shop" class="btn-continue">
                        <i class='bx bx-arrow-back'></i>
                        Continue Shopping
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>

<script src="<?php echo URLROOT; ?>/js/main.js"></script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?> 