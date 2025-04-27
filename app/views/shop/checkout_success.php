<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_shop.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<main>
    <div class="success-container">
        <div class="success-message">
            <i class='bx bx-check-circle'></i>
            <h2>Order Successful!</h2>
            <p>Thank you for your purchase. Your order has been confirmed.</p>
            <p>Order ID: <?php echo $data['session_id']; ?></p>
            <a href="<?php echo URLROOT; ?>/shop" class="btn-continue">Continue Shopping</a>
        </div>
    </div>
</main>

<style>
.success-container {
    padding: 40px;
    text-align: center;
}

.success-message {
    background: var(--light);
    padding: 40px;
    border-radius: 15px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    max-width: 600px;
    margin: 0 auto;
}

.success-message i {
    font-size: 64px;
    color: var(--success);
    margin-bottom: 20px;
}

.btn-continue {
    display: inline-block;
    padding: 12px 24px;
    background: var(--success);
    color: var(--light);
    text-decoration: none;
    border-radius: 8px;
    margin-top: 20px;
    transition: all 0.3s ease;
}

.btn-continue:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}
</style>

<?php require APPROOT . '/views/inc/components/footer.php'; ?> 