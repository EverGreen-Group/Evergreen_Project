<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_inventory.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/pages/createfertilizer.css" />
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/components/topnavbar_style.css" />
<script>
    const URLROOT = '<?php echo URLROOT; ?>';
    const UPLOADROOT = '<?php echo UPLOADROOT; ?>';
</script>

<!-- MAIN -->
<main>
    <div class="head-title">
        <div class="left">
            <h1>Add New Fertilizer</h1>
            <ul class="breadcrumb">
                <li><a href="<?= URLROOT ?>/inventory">Inventory</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">Add New Fertilizer</a></li>
            </ul>
        </div>
    </div>
    
    <!-- Error Messages -->
    <?php if (!empty($data['error'])): ?>
        <div class="alert alert-danger">
            <?php echo $data['error']; ?>
        </div>
    <?php endif; ?>

    <form id="createFertilizerForm" method="POST" action="<?php echo URLROOT; ?>/inventory/createfertilizer" enctype="multipart/form-data">

        <!-- Basic Information -->
        <div class="table-data">
            <div class="order">
                <div class="head">
                    <h3>Basic Information</h3>
                </div>
                <div class="section-content">
                    <div class="info-row">
                        <label class="label" for="fertilizer_name">Fertilizer Name:</label>
                        <input type="text" id="fertilizer_name" name="fertilizer_name" class="form-control" required
                            value="<?php echo isset($data['fertilizer_name']) ? $data['fertilizer_name'] : ''; ?>">
                    </div>
                    <div class="info-row">
                        <label class="label" for="company_name">Company Name:</label>
                        <select id="company_name" name="company_name" class="form-control" required>
                            <option value="cic" <?php echo (isset($data['company_name']) && $data['company_name'] == 'cic') ? 'selected' : ''; ?>>CIC</option>
                            <option value="baur" <?php echo (isset($data['company_name']) && $data['company_name'] == 'baur') ? 'selected' : ''; ?>>Baur</option>
                            <option value="brown" <?php echo (isset($data['company_name']) && $data['company_name'] == 'brown') ? 'selected' : ''; ?>>Brown & Company</option>
                            <option value="hayleys" <?php echo (isset($data['company_name']) && $data['company_name'] == 'hayleys') ? 'selected' : ''; ?>>Hayleys</option>
                            <option value="lankem" <?php echo (isset($data['company_name']) && $data['company_name'] == 'lankem') ? 'selected' : ''; ?>>Lankem Ceylon</option>
                        </select>
                    </div>
                    <div class="info-row">
                        <label class="label" for="details">Details:</label>
                        <input type="text" id="details" name="details" class="form-control"
                            value="<?php echo isset($data['details']) ? $data['details'] : ''; ?>">
                    </div>
                    <div class="info-row">
                        <label class="label" for="code">Code:</label>
                        <input type="text" id="code" name="code" class="form-control" required
                            value="<?php echo isset($data['code']) ? $data['code'] : ''; ?>">
                    </div>
                </div>
            </div>
        </div>

        <!-- Media Section -->
        <div class="table-data">
            <div class="order">
                <div class="head">
                    <h3>Media (Images)</h3>
                </div>
                <div class="section-content">
                    <div class="info-row">
                        <label class="label" for="fertilizer_image">Fertilizer Image:</label>
                        <input type="file" id="fertilizer_image" name="fertilizer_image" class="form-control" accept="image/*"
                            onchange="previewImage(this)">
                    </div>
                    <div class="info-row">
                        <div id="imagePreview" style="width:100%; text-align:center;">
                            <img src="" alt="Image Preview" id="preview" style="max-width:300px; display:none;">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sale Information -->
        <div class="table-data">
            <div class="order">
                <div class="head">
                    <h3>Sale Information</h3>
                </div>
                <div class="section-content">
                    <div class="info-row">
                        <label class="label" for="price">Price:</label>
                        <div class="input-with-prefix" style="display:flex; align-items:center;">
                            <span class="prefix" style="margin-right:5px;">Rs</span>
                            <input type="number" id="price" name="price" class="form-control" required
                                value="<?php echo isset($data['price']) ? $data['price'] : ''; ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inventory Information -->
        <div class="table-data">
            <div class="order">
                <div class="head">
                    <h3>Inventory Information</h3>
                </div>
                <div class="section-content">
                    <div class="info-row">
                        <label class="label" for="quantity">Quantity:</label>
                        <input type="number" id="quantity" name="quantity" class="form-control" required
                            value="<?php echo isset($data['quantity']) ? $data['quantity'] : ''; ?>">
                    </div>
                    <div class="info-row">
                        <label class="label" for="unit">Unit:</label>
                        <select id="unit" name="unit" class="form-control" required>
                            <option value="kg" <?php echo (isset($data['unit']) && $data['unit'] == 'kg') ? 'selected' : ''; ?>>Kg</option>
                            <option value="bag" <?php echo (isset($data['unit']) && $data['unit'] == 'bag') ? 'selected' : ''; ?>>Bag(100Kg)</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">Add Fertilizer</button>
    </form>
</main>

<script>
    function previewImage(input) {
        const preview = document.getElementById('preview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

<style>
    
</style>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>