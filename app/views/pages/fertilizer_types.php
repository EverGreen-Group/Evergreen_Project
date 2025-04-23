<?php require APPROOT . '/views/inc/components/header_public.php'; ?>
<?php require_once APPROOT . '/helpers/RoleHelper.php'; ?>

<main class="fertilizer-main">
    <section class="fertilizer-section">
        <h2>Our Fertilizer Types</h2>
        <div class="product-controls">
            <input type="text" id="fertilizerSearch" placeholder="Search fertilizers by name..." class="search-input">
            <select id="fertilizerSort" class="sort-select">
                <option value="name-asc">Sort by Name (A-Z)</option>
                <option value="name-desc">Sort by Name (Z-A)</option>
                <option value="price-asc">Sort by Price (Low to High)</option>
                <option value="price-desc">Sort by Price (High to Low)</option>
            </select>
        </div>
        <div class="product-grid" id="fertilizerGrid">
            <?php if (isset($data['fertilizers']) && is_array($data['fertilizers']) && !empty($data['fertilizers'])): ?>
                <?php foreach ($data['fertilizers'] as $fertilizer): ?>
                    <div class="product-card" data-fertilizer='<?php echo htmlspecialchars(json_encode($fertilizer)); ?>'>
                        <div class="product-image">
                            <img src="<?php echo URLROOT; ?>/img/default-product.png" alt="<?php echo $fertilizer->fertilizer_name; ?>">
                        </div>
                        <div class="product-info">
                            <h3><?php echo $fertilizer->fertilizer_name; ?></h3>
                            <p>Company: <?php echo $fertilizer->company_name; ?></p>
                            <p>Quantity: <?php echo $fertilizer->quantity; ?></p>
                            <p>Price: Rs.<?php echo $fertilizer->price; ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No fertilizers available at the moment.</p>
            <?php endif; ?>
        </div>
    </section>
</main>

<style>
    .fertilizer-main {
        background: none;
        min-height: 100vh;
        padding: 0 5%;
        color: #000;
        margin-top: 100px;
    }

    .fertilizer-section {
        background: #fff;
        padding: 80px 0;
        border-radius: 30px 30px 0 0;
        margin-top: 40px;
    }

    .fertilizer-section h2 {
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

        .fertilizer-section h2 {
            font-size: 1.5rem;
        }
    }
</style>

<script>
    window.URLROOT = '<?php echo URLROOT; ?>';

    // Store initial fertilizers
    const initialFertilizers = [
        <?php if (isset($data['fertilizers']) && is_array($data['fertilizers']) && !empty($data['fertilizers'])): ?>
            <?php foreach ($data['fertilizers'] as $fertilizer): ?>
                <?php echo json_encode($fertilizer); ?>,
            <?php endforeach; ?>
        <?php endif; ?>
    ];

    // Function to render fertilizers
    function renderFertilizers(fertilizers) {
        const fertilizerGrid = document.getElementById('fertilizerGrid');
        fertilizerGrid.innerHTML = '';

        if (fertilizers.length === 0) {
            fertilizerGrid.innerHTML = '<p>No fertilizers match your search.</p>';
            return;
        }

        fertilizers.forEach(fertilizer => {
            const card = document.createElement('div');
            card.className = 'product-card';
            card.dataset.fertilizer = JSON.stringify(fertilizer);
            card.onclick = () => openFertilizerModal(fertilizer);
            card.innerHTML = `
                <div class="product-image">
                    <img src="${window.URLROOT}/img/default-product.png" alt="${fertilizer.fertilizer_name}">
                </div>
                <div class="product-info">
                    <h3>${fertilizer.fertilizer_name}</h3>
                    <p>Company: ${fertilizer.company_name}</p>
                    <p>Quantity: ${fertilizer.quantity}</p>
                    <p>Price: Rs.${fertilizer.price}</p>
                </div>
            `;
            fertilizerGrid.appendChild(card);
        });
    }

    // Search fertilizers
    function searchFertilizers(query) {
        query = query.toLowerCase();
        return initialFertilizers.filter(fertilizer => 
            fertilizer.fertilizer_name.toLowerCase().includes(query)
        );
    }

    // Sort fertilizers
    function sortFertilizers(fertilizers, sortBy) {
        return [...fertilizers].sort((a, b) => {
            if (sortBy === 'name-asc') {
                return a.fertilizer_name.localeCompare(b.fertilizer_name);
            } else if (sortBy === 'name-desc') {
                return b.fertilizer_name.localeCompare(a.fertilizer_name);
            } else if (sortBy === 'price-asc') {
                return a.price - b.price;
            } else if (sortBy === 'price-desc') {
                return b.price - a.price;
            }
            return 0;
        });
    }

    // Update fertilizer grid based on search and sort
    function updateFertilizerGrid() {
        const searchQuery = document.getElementById('fertilizerSearch').value;
        const sortBy = document.getElementById('fertilizerSort').value;

        let filteredFertilizers = searchFertilizers(searchQuery);
        filteredFertilizers = sortFertilizers(filteredFertilizers, sortBy);

        renderFertilizers(filteredFertilizers);
    }

    // Event listeners
    document.getElementById('fertilizerSearch').addEventListener('input', updateFertilizerGrid);
    document.getElementById('fertilizerSort').addEventListener('change', updateFertilizerGrid);

    // Initial render
    renderFertilizers(initialFertilizers);

    // Modal functions
    function openFertilizerModal(fertilizer) {
        const modal = document.createElement('div');
        modal.classList.add('modal');
        modal.innerHTML = `
            <div class="modal-content">
                <h2>${fertilizer.fertilizer_name}</h2>
                <img src="${window.URLROOT}/img/default-product.png" alt="${fertilizer.fertilizer_name}">
                <p><strong>Company:</strong> ${fertilizer.company_name}</p>
                <p><strong>Price:</strong> Rs.${fertilizer.price}</p>
                <p><strong>Quantity:</strong> ${fertilizer.quantity}</p>
                <p><strong>Code:</strong> ${fertilizer.code}</p>
                <p><strong>Details:</strong> ${fertilizer.details}</p>
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