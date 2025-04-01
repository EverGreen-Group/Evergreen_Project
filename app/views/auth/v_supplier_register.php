<?php require APPROOT . '/views/inc/components/header_public.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/pages/supplier_registration.css">

<main class="supplier-registration-main">
    <div class="container mt-5 pt-5">
        <div class="auth-form-section">
            <div class="auth-form-container">
                <h2>Complete Your Supplier Profile</h2>
                <?php if (isset($data['error']) && !empty($data['error'])): ?>
                    <div class="auth-error"><?php echo $data['error']; ?></div>
                <?php endif; ?>

                <form id="supplierRegForm" action="<?php echo URLROOT; ?>/auth/supplier_register" method="POST" enctype="multipart/form-data">
                    
                    <!-- Personal Information -->
                    <div class="form-section">
                        <h3>Personal Information</h3>
                        
                        <!-- Profile Photo -->
                        <div class="profile-photo-container mb-4">
                            <label>Profile Photo</label>
                            <div class="profile-photo-upload">
                                <div class="profile-photo-preview" id="photoPreview">
                                    <i class='bx bx-user-circle'></i>
                                </div>
                                <div class="profile-photo-input">
                                    <!-- <label for="profile_photo" class="custom-file-upload">
                                        <i class='bx bx-upload'></i> Upload Photo
                                    </label> -->
                                    <input type="file" id="profile_photo" name="profile_photo" accept="image/*" required>
                                    <p class="helper-text">Upload a clear photo of yourself (JPG or PNG, max 5MB)</p>
                                    <p id="photo-selected" class="file-selected"></p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="first_name">First Name:</label>
                                    <input type="text" id="first_name" name="first_name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="last_name">Last Name:</label>
                                    <input type="text" id="last_name" name="last_name" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nic">NIC Number:</label>
                                    <input type="text" id="nic" name="nic_number" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date_of_birth">Date of Birth:</label>
                                    <input type="date" id="date_of_birth" name="date_of_birth" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="contact_number">Contact Number:</label>
                                    <input type="text" id="contact_number" name="contact_number" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="emergency_contact">Emergency Contact:</label>
                                    <input type="text" id="emergency_contact" name="emergency_contact">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Address Information -->
                    <div class="form-section">
                        <h3>Address Information</h3>
                        
                        <div class="form-group">
                            <label for="address_line1">Address Line 1:</label>
                            <input type="text" id="address_line1" name="address_line1" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="address_line2">Address Line 2:</label>
                            <input type="text" id="address_line2" name="address_line2">
                        </div>
                        
                        <div class="form-group">
                            <label for="city">City:</label>
                            <input type="text" id="city" name="city" required>
                        </div>
                    </div>

                    <!-- Location Information -->
                    <div class="form-section">
                        <h3>Property Location</h3>
                        <div class="form-desc">Please mark your tea cultivation area on the map below.</div>
                        
                        <div id="map-container">
                            <div id="map" style="height: 400px; width: 100%; margin-bottom: 15px;"></div>
                            <p class="helper-text">Click on the map to set your location or drag the marker to adjust.</p>
                            
                            <div class="form-group">
                                <label>Coordinates:</label>
                                <div class="coord-inputs">
                                    <input type="text" id="latitude" name="latitude" placeholder="Latitude" readonly required>
                                    <input type="text" id="longitude" name="longitude" placeholder="Longitude" readonly required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cultivation Details -->
                    <div class="form-section">
                        <h3>Cultivation Details</h3>
                        
                        <div class="form-group">
                            <label for="teaCultivationArea">Tea Cultivation Area (acres):</label>
                            <input type="number" id="teaCultivationArea" name="teaCultivationArea" min="0.1" step="0.1" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="plant_age">Average Plant Age (years):</label>
                            <input type="number" id="plant_age" name="plant_age" min="1" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="monthly_production">Estimated Monthly Production (kg):</label>
                            <input type="number" id="monthly_production" name="monthly_production" min="1" required>
                        </div>
                    </div>

                    <!-- Bank Details -->
                    <div class="form-section">
                        <h3>Bank Information</h3>
                        <div class="form-desc">Please provide your bank account details for payments.</div>
                        
                        <div class="form-group">
                            <label for="accountHolderName">Account Holder Name:</label>
                            <input type="text" id="accountHolderName" name="accountHolderName" required>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="bankName">Bank Name:</label>
                                    <input type="text" id="bankName" name="bankName" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="branchName">Branch Name:</label>
                                    <input type="text" id="branchName" name="branchName" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="accountNumber">Account Number:</label>
                                    <input type="text" id="accountNumber" name="accountNumber" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="accountType">Account Type:</label>
                                    <select id="accountType" name="accountType" required>
                                        <option value="">Select Account Type</option>
                                        <option value="Savings">Savings</option>
                                        <option value="Current">Current</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Required Documents -->
                    <div class="form-section">
                        <h3>Required Documents</h3>
                        <div class="form-desc">Please upload the following required documents. All documents must be in JPG, PNG, or PDF format and less than 5MB.</div>
                        
                        <div class="document-upload">
                            <label for="nic-upload">
                                <i class='bx bx-id-card'></i>
                                National Identity Card (NIC)
                            </label>
                            <input type="file" id="nic-upload" name="nic_document" accept=".jpg,.jpeg,.png,.pdf" required>
                            <p id="nic-selected" class="file-selected"></p>
                        </div>
                        
                        <div class="document-upload">
                            <label for="ownership_proof-upload">
                                <i class='bx bx-file'></i>
                                Proof of Land Ownership
                            </label>
                            <input type="file" id="ownership_proof-upload" name="ownership_proof" accept=".jpg,.jpeg,.png,.pdf" required>
                            <p id="ownership_proof-selected" class="file-selected"></p>
                        </div>
                    
                    </div>
                    
                    <button type="submit" class="auth-button">Submit Application</button>
                </form>
            </div>
        </div>
    </div>
</main>

<script>
// File upload previews
document.querySelectorAll('input[type="file"]').forEach(input => {
    input.addEventListener('change', function() {
        const fileId = this.id;
        const fileName = this.files[0]?.name || '';
        
        if (fileId === 'profile_photo') {
            const previewEl = document.getElementById('photo-selected');
            const previewContainer = document.getElementById('photoPreview');
            
            if (fileName) {
                previewEl.textContent = `Selected: ${fileName}`;
                previewEl.classList.add('file-selected-active');
                
                // Create image preview
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewContainer.innerHTML = `<img src="${e.target.result}" alt="Profile Preview">`;
                    }
                    reader.readAsDataURL(file);
                }
            } else {
                previewEl.textContent = '';
                previewEl.classList.remove('file-selected-active');
                previewContainer.innerHTML = `<i class='bx bx-user-circle'></i>`;
            }
        } else {
            const previewId = fileId.replace('-upload', '-selected');
            const previewEl = document.getElementById(previewId);
            
            if (fileName) {
                previewEl.textContent = `Selected: ${fileName}`;
                previewEl.classList.add('file-selected-active');
            } else {
                previewEl.textContent = '';
                previewEl.classList.remove('file-selected-active');
            }
        }
    });
});

let map, marker;
function initMap() {
    // must set to factory using env file
    const defaultCenter = { lat: 7.8731, lng: 80.7718 };
    
    map = new google.maps.Map(document.getElementById("map"), {
        zoom: 8,
        center: defaultCenter,
    });
    

    marker = new google.maps.Marker({
        position: defaultCenter,
        map: map,
        draggable: true,
        title: "Your tea cultivation location"
    });
    

    google.maps.event.addListener(marker, 'dragend', function() {
        updateCoordinates(marker.getPosition());
    });
    

    google.maps.event.addListener(map, 'click', function(event) {
        marker.setPosition(event.latLng);
        updateCoordinates(event.latLng);
    });
}

function updateCoordinates(position) {
    document.getElementById('latitude').value = position.lat().toFixed(6);
    document.getElementById('longitude').value = position.lng().toFixed(6);
}
</script>

<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAC8AYYCYuMkIUAjQWsAwQDiqbMmLa-7eo&callback=initMap">
</script>

<style>
    .supplier-registration-main {
        padding-top: 80px; /* Make space for the fixed navbar */
        background-color: #f8f9fa;
    }
    
    .container {
        max-width: 1000px;
        margin: 0 auto;
    }
    
    .auth-form-section {
        padding: 20px 0 60px;
    }
    
    .auth-form-container {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
        padding: 30px;
    }
    
    .auth-form-container h2 {
        text-align: center;
        margin-bottom: 30px;
        color: #28a745;
    }
    
    .form-section {
        margin-bottom: 30px;
        background-color: #f9f9f9;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }
    
    .form-section h3 {
        margin-top: 0;
        color: #333;
        border-bottom: 1px solid #eee;
        padding-bottom: 10px;
        margin-bottom: 20px;
    }
    
    .form-desc {
        margin-bottom: 15px;
        color: #555;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: 500;
    }
    
    .form-group input, .form-group select {
        width: 100%;
        padding: 10px;
        border: 1px solid #ced4da;
        border-radius: 4px;
    }
    
    .row {
        display: flex;
        margin-left: -10px;
        margin-right: -10px;
        flex-wrap: wrap;
    }
    
    .col-md-6 {
        flex: 0 0 50%;
        max-width: 50%;
        padding: 0 10px;
    }
    
    @media (max-width: 768px) {
        .col-md-6 {
            flex: 0 0 100%;
            max-width: 100%;
        }
    }
    
    .coord-inputs {
        display: flex;
        gap: 10px;
    }
    
    .coord-inputs input {
        flex: 1;
    }
    
    /* Profile Photo Styles */
    .profile-photo-container {
        margin-bottom: 25px;
    }
    
    .profile-photo-upload {
        display: flex;
        align-items: center;
        gap: 20px;
    }
    
    .profile-photo-preview {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background-color: #f0f0f0;
        display: flex;
        justify-content: center;
        align-items: center;
        overflow: hidden;
    }
    
    .profile-photo-preview i {
        font-size: 60px;
        color: #ccc;
    }
    
    .profile-photo-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .profile-photo-input {
        flex: 1;
    }
    
    .custom-file-upload {
        display: inline-block;
        padding: 8px 16px;
        background-color: #007bff;
        color: white;
        border-radius: 4px;
        cursor: pointer;
        margin-bottom: 8px;
    }
    
    .custom-file-upload:hover {
        background-color: #0069d9;
    }
    
    /* Document Upload Styles */
    .document-upload {
        margin-bottom: 15px;
        padding: 15px;
        border: 1px dashed #ced4da;
        border-radius: 4px;
        background-color: #fff;
    }
    
    .document-upload label {
        display: flex;
        align-items: center;
        cursor: pointer;
    }
    
    .document-upload i {
        margin-right: 10px;
        font-size: 24px;
        color: #007bff;
    }
    
    .document-upload input[type="file"] {
        display: none;
    }
    
    .file-selected {
        display: block;
        margin-top: 5px;
        font-size: 12px;
        color: #6c757d;
    }
    
    .file-selected-active {
        color: #28a745;
        font-weight: 500;
    }
    
    .helper-text {
        font-size: 12px;
        color: #6c757d;
        margin: 5px 0 15px;
    }
    
    .auth-button {
        width: 100%;
        padding: 12px;
        background-color: #28a745;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
        font-weight: 500;
        margin-top: 20px;
    }
    
    .auth-button:hover {
        background-color: #218838;
    }
</style>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>