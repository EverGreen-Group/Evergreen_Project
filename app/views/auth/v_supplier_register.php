<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Existing Leaflet and script inclusions remain the same -->

<?php require APPROOT . '/views/inc/components/sidebar_suppliermanager.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<main>
    <form action="<?= URLROOT ?>/auth/supplier_register" method="POST" enctype="multipart/form-data" id="supplierForm">
        <div class="head-title">
            <div class="left">
                <h1>Supplier Application Form</h1>
                <ul class="breadcrumb">
                    <li><a href="<?= URLROOT ?>/suppliermanager/applications">Applications</a></li>
                    <li><i class='bx bx-chevron-right'></i></li>
                    <li><a class="active" href="#">New Application</a></li>
                </ul>
            </div>
            <div class="action-buttons">
                <button type="submit" class="btn auth-button">
                    <i class='bx bx-save'></i> Submit Application
                </button>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="table-data">
            <div class="order">
                <div class="head">
                    <h3>Contact Information</h3>
                </div>
                <div class="section-content">
                    <div class="info-row">
                        <span class="label">Primary Phone:</span>
                        <input type="tel" name="primary_phone" class="form-control" required>
                    </div>
                    <div class="info-row">
                        <span class="label">Secondary Phone:</span>
                        <input type="tel" name="secondary_phone" class="form-control">
                    </div>
                </div>
            </div>
        </div>

        <!-- Bank Information -->
        <div class="table-data">
            <div class="order">
                <div class="head">
                    <h3>Bank Information</h3>
                </div>
                <div class="section-content">
                    <div class="info-row">
                        <span class="label">Account Holder:</span>
                        <input type="text" name="account_holder_name" class="form-control" required>
                    </div>
                    <div class="info-row">
                        <span class="label">Bank Name:</span>
                        <input type="text" name="bank_name" class="form-control" required>
                    </div>
                    <div class="info-row">
                        <span class="label">Branch Name:</span>
                        <input type="text" name="branch_name" class="form-control" required>
                    </div>
                    <div class="info-row">
                        <span class="label">Account Number:</span>
                        <input type="text" name="account_number" class="form-control" required>
                    </div>
                    <div class="info-row">
                        <span class="label">Account Type:</span>
                        <select name="account_type" class="form-control" required>
                            <option value="">Select Account Type</option>
                            <option value="savings">Savings</option>
                            <option value="current">Current</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Property Details -->
        <div class="table-data">
            <div class="order">
                <div class="head">
                    <h3>Property Details</h3>
                </div>
                <div class="section-content">
                    <div class="info-row">
                        <span class="label">Total Land Area (acres):</span>
                        <input type="number" step="0.01" name="total_land_area" class="form-control" required>
                    </div>
                    <div class="info-row">
                        <span class="label">Tea Cultivation Area (acres):</span>
                        <input type="number" step="0.01" name="tea_cultivation_area" class="form-control" required>
                    </div>
                    <div class="info-row">
                        <span class="label">Elevation (meters):</span>
                        <input type="number" name="elevation" class="form-control" required>
                    </div>
                    <div class="info-row">
                        <span class="label">Slope:</span>
                        <select name="slope" class="form-control" required>
                            <option value="">Select Slope</option>
                            <option value="flat">Flat</option>
                            <option value="gentle">Gentle</option>
                            <option value="steep">Steep</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Water Sources -->
        <div class="table-data">
            <div class="order">
                <div class="head">
                    <h3>Water Sources</h3>
                </div>
                <div class="section-content">
                    <div class="info-row">
                        <span class="label">Water Sources:</span>
                        <div class="checkbox-group horizontal">
                            <?php 
                            $waterSources = [
                                'river' => 'Stream/River',
                                'well' => 'Well',
                                'rainwater' => 'Rain Water',
                                'other' => 'Public Water Supply'
                            ];
                            ?>
                            <?php foreach($waterSources as $value => $label): ?>
                                <label class="checkbox-item">
                                    <input type="checkbox" name="water_sources[]" value="<?= $value ?>">
                                    <span><?= $label ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Infrastructure -->
        <div class="table-data">
            <div class="order">
                <div class="head">
                    <h3>Infrastructure</h3>
                </div>
                <div class="section-content">
                    <div class="info-row">
                        <span class="label">Vehicle Access:</span>
                        <select name="vehicle_access" class="form-control" required>
                            <option value="">Select Vehicle Access</option>
                            <option value="full">All Weather Access</option>
                            <option value="partial">Fair Weather Only</option>
                            <option value="limited">Limited Access</option>
                            <option value="none">No Vehicle Access</option>
                        </select>
                    </div>
                    <div class="info-row">
                        <span class="label">Available Structures:</span>
                        <div class="checkbox-group horizontal">
                            <?php 
                            $structures = [
                                'storage' => 'Storage Facility',
                                'processing' => 'Equipment Storage',
                                'office' => 'Worker Rest Area',
                                'residence' => 'Living Quarters',
                                'other' => 'None'
                            ];
                            ?>
                            <?php foreach($structures as $value => $label): ?>
                                <label class="checkbox-item">
                                    <input type="checkbox" name="structures[]" value="<?= $value ?>">
                                    <span><?= $label ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Address Information -->
        <div class="table-data">
            <div class="order">
                <div class="head">
                    <h3>Address Information</h3>
                </div>
                <div class="section-content">
                    <div class="address-container">
                        <div class="address-details">
                            <div class="info-row">
                                <span class="label">Address Line 1:</span>
                                <input type="text" name="line1" class="form-control" required>
                            </div>
                            <div class="info-row">
                                <span class="label">Address Line 2:</span>
                                <input type="text" name="line2" class="form-control">
                            </div>
                            <div class="info-row">
                                <span class="label">City:</span>
                                <input type="text" name="city" class="form-control" required>
                            </div>
                            <div class="info-row">
                                <span class="label">District:</span>
                                <input type="text" name="district" class="form-control" required>
                            </div>
                            <div class="info-row">
                                <span class="label">Postal Code:</span>
                                <input type="text" name="postal_code" class="form-control" required>
                            </div>
                            <div class="info-row">
                                <span class="label">Location:</span>
                                <div class="location-container">
                                    <input type="text" name="latitude" id="latitude" class="form-control" required readonly>
                                    <input type="text" name="longitude" id="longitude" class="form-control" required readonly>
                                    <button type="button" id="getLocation" class="btn-location">
                                        <i class='bx bx-current-location'></i> Get Location
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div id="map"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Documents Upload -->
        <div class="table-data">
            <div class="order">
                <div class="head">
                    <h3>Upload Documents</h3>
                </div>
                <div class="section-content">
                    <div class="documents-grid">
                        <?php 
                        $documentTypes = [
                            'land_deed' => 'Land Deed',
                            'tax_receipt' => 'Tax Receipt',
                            'tea_cultivation_certificate' => 'Tea Cultivation Certificate',
                            'id_proof' => 'ID Proof',
                            'bank_statement' => 'Bank Statement'
                        ];
                        ?>
                        <?php foreach($documentTypes as $type => $label): ?>
                            <div class="document-card">
                                <div class="document-content">
                                    <i class='bx bxs-file-pdf document-icon'></i>
                                </div>
                                <div class="document-info">
                                    <span class="document-type"><?= $label ?></span>
                                    <input type="file" name="documents[<?= $type ?>]" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </form>
</main>

<!-- Add this in the head section or after your other CSS includes -->
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/supplier_register.css">

<script>
document.getElementById('getLocation').addEventListener('click', function() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            // Success callback
            function(position) {
                document.getElementById('latitude').value = position.coords.latitude;
                document.getElementById('longitude').value = position.coords.longitude;
                
                // Optional: Visual feedback
                document.getElementById('getLocation').innerHTML = '<i class="bx bx-check"></i> Location Set';
                document.getElementById('getLocation').classList.add('location-set');
            },
            // Error callback
            function(error) {
                let message;
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                        message = "Location permission was denied";
                        break;
                    case error.POSITION_UNAVAILABLE:
                        message = "Location information is unavailable";
                        break;
                    case error.TIMEOUT:
                        message = "Location request timed out";
                        break;
                    default:
                        message = "An unknown error occurred";
                }
                alert(message);
            }
        );
    } else {
        alert("Geolocation is not supported by this browser");
    }
});
</script>

<script>
document.getElementById('supplierForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    try {
        const formData = new FormData(this);
        
        const response = await fetch('<?= URLROOT ?>/auth/supplier_register', {
            method: 'POST',
            body: formData
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const result = await response.json();
        
        if (result.success) {
            // Instead of showing alert, directly redirect to the URL provided in the response
            window.location.href = result.redirect;
        } else {
            alert(result.message || 'Error submitting application');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error submitting application. Please try again.');
    }
});
</script>

<script src="<?php echo URLROOT; ?>/css/components/script.js"></script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>