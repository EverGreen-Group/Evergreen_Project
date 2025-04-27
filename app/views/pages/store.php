<?php require APPROOT . '/views/inc/components/header_public.php'; ?>
<?php require_once APPROOT . '/helpers/RoleHelper.php'; ?>

<main class="store-main">
    <section class="product-section">
        <h2>Our Products</h2>
        <div class="product-controls">
            <input type="text" id="productSearch" placeholder="Search products by name..." class="search-input">
            <select id="productSort" class="sort-select">
                <option value="name-asc">Sort by Name (A-Z)</option>
                <option value="name-desc">Sort by Name (Z-A)</option>
                <option value="price-asc">Sort by Price (Low to High)</option>
                <option value="price-desc">Sort by Price (High to Low)</option>
            </select>
        </div>
        <div class="product-grid" id="productGrid">
            <?php if (isset($data['products']) && is_array($data['products']) && !empty($data['products'])): ?>
                <?php foreach ($data['products'] as $product): ?>
                    <div class="product-card" data-product='<?php echo htmlspecialchars(json_encode($product)); ?>'>
                        <div class="product-image">
                            <?php if (!empty($product->image_path)): ?>
                                <img src="<?php echo URLROOT; ?>/public/uploads/products/<?php echo $product->image_path; ?>" alt="<?php echo $product->product_name; ?>">
                            <?php else: ?>
                                <img src="<?php echo URLROOT; ?>/img/default-product.png" alt="Default Product Image">
                            <?php endif; ?>
                        </div>
                        <div class="product-info">
                            <h3><?php echo $product->product_name; ?></h3>
                            <p>Quantity: <?php echo $product->quantity; ?> <?php echo $product->unit; ?></p>
                            <p>Price: Rs.<?php echo $product->price; ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No products available at the moment.</p>
            <?php endif; ?>
        </div>
    </section>
</main>

<style>
    .store-main {
        background: none;
        min-height: 100vh;
        padding: 0 5%;
        color: #000;
        margin-top: 100px;
    }

    .product-section {
        background: #fff;
        padding: 80px 0;
        border-radius: 30px 30px 0 0;
        margin-top: 40px;
    }

    .product-section h2 {
        color: #333;
        text-align: center;
        font-size: 2.5rem;
        margin-bottom: 3rem;
    }

    .product-controls {
        display: flex;
        justify-content: space-between;
        align-items: center;
        max-width: 1200px;
        margin: 20px auto;
        padding: 0 2rem;
        gap: 20px;
    }

    .search-input {
        padding: 10px;
        width: 100%;
        max-width: 300px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 1rem;
    }

    .sort-select {
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 1rem;
        cursor: pointer;
    }

    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
        max-width: 1200px;
        margin: 40px auto;
        padding: 0 2rem;
    }

    .product-card {
        border: 1px solid #ddd;
        border-radius: 8px;
        overflow: hidden;
        cursor: pointer;
        transition: transform 0.2s;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .product-card:hover {
        transform: scale(1.05);
    }

    .product-image img {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }

    .product-info {
        padding: 15px;
        text-align: center;
    }

    .product-info h3 {
        margin: 0;
        font-size: 1.2em;
        color: #333;
    }

    .product-info p {
        margin: 5px 0;
        color: #555;
    }

    /* Modal Styles */
    .modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1000;
    }

    .modal-content {
        background: white;
        padding: 20px;
        border-radius: 8px;
        max-width: 500px;
        text-align: center;
    }

    .modal-content img {
        max-width: 100%;
        height: auto;
        margin: 10px 0;
    }

    .modal-content button {
        background-color: #22a45d;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .modal-content button:hover {
        background-color: #1b8e4a;
    }

    @media (max-width: 768px) {
        .product-grid {
            grid-template-columns: 1fr;
        }

        .product-controls {
            flex-direction: column;
            align-items: stretch;
        }

        .search-input, .sort-select {
            max-width: 100%;
        }

        .product-section h2 {
            font-size: 1.5rem;
        }
    }
</style>

<script>
    window.URLROOT = '<?php echo URLROOT; ?>';

    // Store initial products
    const initialProducts = [
        <?php if (isset($data['products']) && is_array($data['products']) && !empty($data['products'])): ?>
            <?php foreach ($data['products'] as $product): ?>
                <?php echo json_encode($product); ?>,
            <?php endforeach; ?>
        <?php endif; ?>
    ];

    // Function to render products
    function renderProducts(products) {
        const productGrid = document.getElementById('productGrid');
        productGrid.innerHTML = '';

        if (products.length === 0) {
            productGrid.innerHTML = '<p>No products match your search.</p>';
            return;
        }

        products.forEach(product => {
            const card = document.createElement('div');
            card.className = 'product-card';
            card.dataset.product = JSON.stringify(product);
            card.onclick = () => openProductModal(product);
            card.innerHTML = `
                <div class="product-image">
                    <img src="${product.image_path ? `${window.URLROOT}/uploads/products/${product.image_path}` : `${window.URLROOT}/img/default-product.png`}" alt="${product.product_name}">
                </div>
                <div class="product-info">
                    <h3>${product.product_name}</h3>
                    <p>Quantity: ${product.quantity} ${product.unit}</p>
                    <p>Price: Rs.${product.price}</p>
                </div>
            `;
            productGrid.appendChild(card);
        });
    }

    // Search products
    function searchProducts(query) {
        query = query.toLowerCase();
        return initialProducts.filter(product => 
            product.product_name.toLowerCase().includes(query)
        );
    }

    // Sort products
    function sortProducts(products, sortBy) {
        return [...products].sort((a, b) => {
            if (sortBy === 'name-asc') {
                return a.product_name.localeCompare(b.product_name);
            } else if (sortBy === 'name-desc') {
                return b.product_name.localeCompare(a.product_name);
            } else if (sortBy === 'price-asc') {
                return a.price - b.price;
            } else if (sortBy === 'price-desc') {
                return b.price - a.price;
            }
            return 0;
        });
    }

    // Update product grid based on search and sort
    function updateProductGrid() {
        const searchQuery = document.getElementById('productSearch').value;
        const sortBy = document.getElementById('productSort').value;

        let filteredProducts = searchProducts(searchQuery);
        filteredProducts = sortProducts(filteredProducts, sortBy);

        renderProducts(filteredProducts);
    }

    // Event listeners
    document.getElementById('productSearch').addEventListener('input', updateProductGrid);
    document.getElementById('productSort').addEventListener('change', updateProductGrid);

    // Initial render
    renderProducts(initialProducts);

    // Modal functions
    function openProductModal(product) {
        const modal = document.createElement('div');
        modal.classList.add('modal');
        modal.innerHTML = `
            <div class="modal-content">
                <h2>${product.product_name}</h2>
                <img src="${product.image_path ? `${window.URLROOT}/uploads/products/${product.image_path}` : `${window.URLROOT}/img/default-product.png`}" alt="${product.product_name}">
                <p><strong>Price:</strong> Rs.${product.price}</p>
                <p><strong>Quantity:</strong> ${product.quantity} ${product.unit}</p>
                <p><strong>Location:</strong> ${product.location}</p>
                <p><strong>Details:</strong> ${product.details}</p>
                <button onclick="closeModal()">Close</button>
            </div>
        `;
        document.body.appendChild(modal);
    }

    function closeModal() {
        const modal = document.querySelector('.modal');
        if (modal) {
            modal.remove();
        }
    }
</script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>