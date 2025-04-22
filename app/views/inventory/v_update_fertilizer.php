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
            <h1>Update Fertilizer</h1>
            <ul class="breadcrumb">
                <li><a href="<?= URLROOT ?>/inventory">Inventory</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">Update Fertilizer</a></li>
            </ul>
        </div>
    </div>
    
    <!-- Error Messages -->
    <?php if (!empty($data['error'])): ?>
        <div class="alert alert-danger">
            <?php echo $data['error']; ?>
        </div>
    <?php endif; ?>

    <form id="updateFertilizerForm" method="POST" action="<?php echo URLROOT; ?>/inventory/updatefertilizer" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo isset($data['fertilizer']->id) ? $data['fertilizer']->id : ''; ?>">

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
                            value="<?php echo isset($data['fertilizer']->fertilizer_name) ? $data['fertilizer']->fertilizer_name : ''; ?>">
                    </div>
                    <div class="info-row">
                        <label class="label" for="company_name">Company Name:</label>
                        <select id="company_name" name="company_name" class="form-control" required>
                            <option value="cic" <?php echo (isset($data['fertilizer']->company_name) && $data['fertilizer']->company_name == 'cic') ? 'selected' : ''; ?>>CIC</option>
                            <option value="baur" <?php echo (isset($data['fertilizer']->company_name) && $data['fertilizer']->company_name == 'baur') ? 'selected' : ''; ?>>Baur</option>
                            <option value="brown" <?php echo (isset($data['fertilizer']->company_name) && $data['fertilizer']->company_name == 'brown') ? 'selected' : ''; ?>>Brown & Company</option>
                            <option value="hayleys" <?php echo (isset($data['fertilizer']->company_name) && $data['fertilizer']->company_name == 'hayleys') ? 'selected' : ''; ?>>Hayleys</option>
                            <option value="lankem" <?php echo (isset($data['fertilizer']->company_name) && $data['fertilizer']->company_name == 'lankem') ? 'selected' : ''; ?>>Lankem Ceylon</option>
                        </select>
                    </div>
                    <div class="info-row">
                        <label class="label" for="details">Details:</label>
                        <input type="text" id="details" name="details" class="form-control"
                            value="<?php echo isset($data['fertilizer']->details) ? $data['fertilizer']->details : ''; ?>">
                    </div>
                    <div class="info-row">
                        <label class="label" for="code">Code:</label>
                        <input type="text" id="code" name="code" class="form-control" required
                            value="<?php echo isset($data['fertilizer']->code) ? $data['fertilizer']->code : ''; ?>">
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
                            <?php if(isset($data['fertilizer']->image_path) && !empty($data['fertilizer']->image_path)): ?>
                                <img src="<?php echo '../../public/uploads/fertilizers/' . $data['fertilizer']->image_path ?>" alt="Current Image" id="preview" style="max-width:300px; display:block;">
                            <?php else: ?>
                                <img src="" alt="Image Preview" id="preview" style="max-width:300px; display:none;">
                            <?php endif;?>
                            
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
                                value="<?php echo isset($data['fertilizer']->price) ? $data['fertilizer']->price : ''; ?>">
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
                            value="<?php echo isset($data['fertilizer']->quantity) ? $data['fertilizer']->quantity : ''; ?>">
                    </div>
                    <div class="info-row">
                        <label class="label" for="unit">Unit:</label>
                        <select id="unit" name="unit" class="form-control" required>
                            <option value="kg" <?php echo (isset($data['fertilizer']->unit) && $data['fertilizer']->unit == 'kg') ? 'selected' : ''; ?>>Kg</option>
                            <option value="bag" <?php echo (isset($data['fertilizer']->unit) && $data['fertilizer']->unit == 'bag') ? 'selected' : ''; ?>>Bag(100Kg)</option>
                            <option value="item" <?php echo (isset($data['fertilizer']->unit) && $data['fertilizer']->unit == 'item') ? 'selected' : ''; ?>>Item</option>
                            <option value="ton" <?php echo (isset($data['fertilizer']->unit) && $data['fertilizer']->unit == 'ton') ? 'selected' : ''; ?>>Ton</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">Update Fertilizer</button>
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

<?php require APPROOT . '/views/inc/components/footer.php'; ?>