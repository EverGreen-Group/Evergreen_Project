<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_shop.php'; ?>

<main>
    <div class="head-title">
        <div class="left">
            <h1>Track Order</h1>
            <ul class="breadcrumb">
                <li><a href="<?php echo URLROOT; ?>/shop">Shop</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">Track Order</a></li>
            </ul>
        </div>
    </div>

    <div class="tracking-form-container">
        <div class="form-card">
            <h2>Enter Order Number</h2>
            <form action="<?php echo URLROOT; ?>/shop/trackOrder" method="GET">
                <div class="form-group">
                    <input type="text" 
                           name="order_number" 
                           placeholder="Enter your order number (e.g., ORD-2024-001)"
                           required>
                </div>
                <button type="submit" class="btn-track">
                    <i class='bx bx-search'></i> Track Order
                </button>
            </form>
        </div>
    </div>
</main>

<style>
.tracking-form-container {
    max-width: 600px;
    margin: 2rem auto;
    padding: 0 1rem;
}

.form-card {
    background: white;
    padding: 2rem;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.form-card h2 {
    margin-bottom: 1.5rem;
    color: var(--dark);
    text-align: center;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group input {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 1rem;
}

.btn-track {
    width: 100%;
    padding: 0.75rem;
    background: var(--main);
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 1rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    transition: background 0.3s ease;
}

.btn-track:hover {
    background: var(--dark);
}
</style>

<?php require APPROOT . '/views/inc/components/footer.php'; ?> 