<?php require APPROOT . '/views/inc/components/header_public.php'; ?>

<main>
    <div class="auth-container">
        <div class="auth-form-section">
            <div class="auth-form-container">
                <div class="form-progress">
                    <div class="progress-step active" data-step="1">
                        <div class="step-number">1</div>
                        <div class="step-title">Contact Details</div>
                    </div>
                    <div class="progress-step" data-step="2">
                        <div class="step-number">2</div>
                        <div class="step-title">Address</div>
                    </div>
                    <div class="progress-step" data-step="3">
                        <div class="step-number">3</div>
                        <div class="step-title">Ownership</div>
                    </div>
                    <div class="progress-step" data-step="4">
                        <div class="step-number">4</div>
                        <div class="step-title">Property</div>
                    </div>
                    <div class="progress-step" data-step="5">
                        <div class="step-number">5</div>
                        <div class="step-title">Infrastructure</div>
                    </div>
                    <div class="progress-step" data-step="6">
                        <div class="step-number">6</div>
                        <div class="step-title">Tea Details</div>
                    </div>
                    <div class="progress-step" data-step="7">
                        <div class="step-number">7</div>
                        <div class="step-title">Bank Details</div>
                    </div>
                    <div class="progress-step" data-step="8">
                        <div class="step-number">8</div>
                        <div class="step-title">Documents</div>
                    </div>
                </div>

                <h2>Complete Your Supplier Profile</h2>
                <?php if (isset($data['error']) && !empty($data['error'])): ?>
                    <div class="auth-error"><?php echo $data['error']; ?></div>
                <?php endif; ?>

                <form id="supplierRegForm" action="<?php echo URLROOT; ?>/auth/supplier_register" method="POST"
                    enctype="multipart/form-data">
                    <!-- Step 1: Contact Details -->
                    <div class="form-step" data-step="1">
                        <div class="auth-form-group">
                            <label for="primaryPhone">Primary Phone Number</label>
                            <input type="tel" id="primaryPhone" name="primaryPhone" required pattern="^(?:7|0)[0-9]{9}$"
                                title="Enter valid Sri Lankan phone number" placeholder="0771234567">
                            <small class="input-help">Use Sri Lankan mobile number</small>
                        </div>

                        <div class="auth-form-group">
                            <label for="secondaryPhone">Secondary Phone Number (Optional)</label>
                            <input type="tel" id="secondaryPhone" name="secondaryPhone" pattern="^(?:7|0)[0-9]{9}$"
                                title="Enter valid Sri Lankan phone number" placeholder="0771234567">
                        </div>

                        <div class="auth-form-group">
                            <label for="whatsappNumber">WhatsApp Number (Optional)</label>
                            <input type="tel" id="whatsappNumber" name="whatsappNumber" pattern="^(?:7|0)[0-9]{9}$"
                                placeholder="0771234567">
                            <small class="input-help">Will be used for quick communications</small>
                        </div>

                        <div class="form-navigation">
                            <button type="button" class="next-btn">Next</button>
                        </div>
                    </div>

                    <!-- Step 2: Address -->
                    <div class="form-step" data-step="2">
                        <div class="auth-form-group">
                            <label for="line1">Address Line 1</label>
                            <input type="text" id="line1" name="line1" required>
                        </div>

                        <div class="auth-form-group">
                            <label for="line2">Address Line 2 (Optional)</label>
                            <input type="text" id="line2" name="line2">
                        </div>

                        <div class="auth-form-row">
                            <div class="auth-form-group">
                                <label for="city">City</label>
                                <input type="text" id="city" name="city" required>
                            </div>
                            <div class="auth-form-group">
                                <label for="district">District</label>
                                <select id="district" name="district" class="auth-select" required>
                                    <option value="">Select District</option>
                                    <option value="Kandy">Kandy</option>
                                    <option value="Nuwara Eliya">Nuwara Eliya</option>
                                    <option value="Badulla">Badulla</option>
                                    <option value="Ratnapura">Ratnapura</option>
                                    <option value="Galle">Galle</option>
                                    <option value="Matara">Matara</option>
                                    <option value="Kalutara">Kalutara</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>

                        <div class="auth-form-group">
                            <label for="postalCode">Postal Code</label>
                            <input type="text" id="postalCode" name="postalCode" required
                                title="Enter valid 5-digit postal code">
                            <small class="input-help">5-digit postal code</small>
                        </div>

                        <div class="form-navigation">
                            <button type="button" class="prev-btn">Previous</button>
                            <button type="button" class="next-btn">Next</button>
                        </div>
                    </div>

                    <!-- Step 3: Ownership Details -->
                    <div class="form-step" data-step="3">
                        <div class="auth-form-row">
                            <div class="auth-form-group">
                                <label for="ownership_type">Type of Ownership</label>
                                <select id="ownership_type" name="ownership_type" class="auth-select" required>
                                    <option value="">Select Ownership Type</option>
                                    <option value="Private Owner">Private Owner</option>
                                    <option value="Joint Owner">Joint Owner</option>
                                    <option value="Lease Holder">Lease Holder</option>
                                    <option value="Government Permit">Government Permit</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="auth-form-group">
                                <label for="ownership_duration">Years of Ownership</label>
                                <input type="number" id="ownership_duration" name="ownership_duration" min="0" step="1"
                                    required>
                                <small class="input-help">Number of years owned/leased</small>
                            </div>
                        </div>

                        <!-- Add this new section for location picking -->
                        <div class="auth-form-group">
                            <label for="location">Your Location</label>
                            <div id="map" style="height: 200px; margin-bottom: 0.5rem;"></div>
                            <div class="location-inputs">
                                <input type="hidden" id="latitude" name="latitude" required>
                                <input type="hidden" id="longitude" name="longitude" required>
                                <small class="input-help">Drag the marker to set your exact location</small>
                            </div>
                        </div>

                        <div class="form-navigation">
                            <button type="button" class="prev-btn">Previous</button>
                            <button type="button" class="next-btn">Next</button>
                        </div>
                    </div>

                    <!-- Step 4: Property Details -->
                    <div class="form-step" data-step="4">
                        <div class="auth-form-row">
                            <div class="auth-form-group">
                                <label for="totalLandArea">Total Land Area (acres)</label>
                                <input type="number" id="totalLandArea" name="totalLandArea" min="0.25" step="0.25"
                                    required>
                                <small class="input-help">Minimum 0.25 acres</small>
                            </div>
                            <div class="auth-form-group">
                                <label for="teaCultivationArea">Tea Cultivation Area (acres)</label>
                                <input type="number" id="teaCultivationArea" name="teaCultivationArea" min="0.25"
                                    step="0.25" required>
                            </div>
                        </div>

                        <div class="auth-form-row">
                            <div class="auth-form-group">
                                <label for="elevation">Elevation (meters)</label>
                                <input type="number" id="elevation" name="elevation" min="0" max="2500" step="1"
                                    required>
                                <small class="input-help">Land elevation above sea level (0-2500m)</small>
                            </div>
                            <div class="auth-form-group">
                                <label for="slope">Land Slope</label>
                                <select id="slope" name="slope" class="auth-select" required>
                                    <option value="">Select Slope Type</option>
                                    <option value="Flat">Flat</option>
                                    <option value="Gentle">Gentle</option>
                                    <option value="Moderate">Moderate</option>
                                    <option value="Steep">Steep</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-navigation">
                            <button type="button" class="prev-btn">Previous</button>
                            <button type="button" class="next-btn">Next</button>
                        </div>
                    </div>

                    <!-- NEW Step 5: Infrastructure Details -->
                    <div class="form-step" data-step="5">
                        <div class="auth-form-group">
                            <label for="water_source">Water Sources</label>
                            <div class="checkbox-group">
                                <label class="checkbox-item">
                                    <input type="checkbox" name="water_source[]" value="Natural Spring">
                                    <span>Natural Spring</span>
                                </label>
                                <label class="checkbox-item">
                                    <input type="checkbox" name="water_source[]" value="Well">
                                    <span>Well</span>
                                </label>
                                <label class="checkbox-item">
                                    <input type="checkbox" name="water_source[]" value="Stream/River">
                                    <span>Stream/River</span>
                                </label>
                                <label class="checkbox-item">
                                    <input type="checkbox" name="water_source[]" value="Rain Water">
                                    <span>Rain Water</span>
                                </label>
                                <label class="checkbox-item">
                                    <input type="checkbox" name="water_source[]" value="Public Water Supply">
                                    <span>Public Water Supply</span>
                                </label>
                            </div>
                        </div>

                        <div class="auth-form-row">
                            <div class="auth-form-group">
                                <label for="access_road">Access Road Type</label>
                                <select id="access_road" name="access_road" class="auth-select" required>
                                    <option value="">Select Road Type</option>
                                    <option value="Paved Road">Paved Road</option>
                                    <option value="Gravel Road">Gravel Road</option>
                                    <option value="Estate Road">Estate Road</option>
                                    <option value="Footpath Only">Footpath Only</option>
                                </select>
                            </div>
                            <div class="auth-form-group">
                                <label for="vehicle_access">Vehicle Accessibility</label>
                                <select id="vehicle_access" name="vehicle_access" class="auth-select" required>
                                    <option value="">Select Access Type</option>
                                    <option value="All Weather Access">All Weather Access</option>
                                    <option value="Fair Weather Only">Fair Weather Only</option>
                                    <option value="Limited Access">Limited Access</option>
                                    <option value="No Vehicle Access">No Vehicle Access</option>
                                </select>
                            </div>
                        </div>

                        <div class="auth-form-group">
                            <label for="structures">Available Structures</label>
                            <div class="checkbox-group">
                                <label class="checkbox-item">
                                    <input type="checkbox" name="structures[]" value="Storage Facility">
                                    <span>Storage Facility</span>
                                </label>
                                <label class="checkbox-item">
                                    <input type="checkbox" name="structures[]" value="Worker Rest Area">
                                    <span>Worker Rest Area</span>
                                </label>
                                <label class="checkbox-item">
                                    <input type="checkbox" name="structures[]" value="Equipment Storage">
                                    <span>Equipment Storage</span>
                                </label>
                                <label class="checkbox-item">
                                    <input type="checkbox" name="structures[]" value="Living Quarters">
                                    <span>Living Quarters</span>
                                </label>
                                <label class="checkbox-item">
                                    <input type="checkbox" name="structures[]" value="None">
                                    <span>None</span>
                                </label>
                            </div>
                        </div>

                        <div class="form-navigation">
                            <button type="button" class="prev-btn">Previous</button>
                            <button type="button" class="next-btn">Next</button>
                        </div>
                    </div>

                    <!-- Step 6: Tea Cultivation Details -->
                    <div class="form-step" data-step="6">
                        <div class="auth-form-group">
                            <label for="teaVarieties">Tea Varieties</label>
                            <div class="checkbox-group">
                                <label class="checkbox-item">
                                    <input type="checkbox" name="tea_varieties[]" value="TRI 2023">
                                    <span>TRI 2023</span>
                                </label>
                                <label class="checkbox-item">
                                    <input type="checkbox" name="tea_varieties[]" value="TRI 2025">
                                    <span>TRI 2025</span>
                                </label>
                                <label class="checkbox-item">
                                    <input type="checkbox" name="tea_varieties[]" value="TRI 2026">
                                    <span>TRI 2026</span>
                                </label>
                                <label class="checkbox-item">
                                    <input type="checkbox" name="tea_varieties[]" value="TRI 3013">
                                    <span>TRI 3013</span>
                                </label>
                                <label class="checkbox-item">
                                    <input type="checkbox" name="tea_varieties[]" value="Other">
                                    <span>Other</span>
                                </label>
                            </div>
                        </div>

                        <div class="auth-form-row">
                            <div class="auth-form-group">
                                <label for="plant_age">Average Plant Age (years)</label>
                                <input type="number" id="plant_age" name="plant_age" min="0" max="100" step="0.5"
                                    required>
                                <small class="input-help">Age of tea plants (0-100 years)</small>
                            </div>
                            <div class="auth-form-group">
                                <label for="monthly_production">Monthly Production (kg)</label>
                                <input type="number" id="monthly_production" name="monthly_production" min="0"
                                    max="10000" step="0.5" required>
                                <small class="input-help">Average monthly tea leaf production in kilograms</small>
                            </div>
                        </div>

                        <div class="form-navigation">
                            <button type="button" class="prev-btn">Previous</button>
                            <button type="button" class="next-btn">Next</button>
                        </div>
                    </div>

                    <!-- Step 7: Bank Details -->
                    <div class="form-step" data-step="7">
                        <div class="form-section">
                            <h3 class="section-title">Bank Account Information</h3>

                            <div class="auth-form-group">
                                <label for="accountHolderName">Account Holder Name</label>
                                <input type="text" id="accountHolderName" name="accountHolderName" required>
                                <small class="input-help">Name as per bank account</small>
                            </div>

                            <div class="auth-form-row">
                                <div class="auth-form-group">
                                    <label for="bankName">Bank Name</label>
                                    <select id="bankName" name="bankName" class="auth-select" required>
                                        <option value="">Select Bank</option>
                                        <option value="Bank of Ceylon">Bank of Ceylon</option>
                                        <option value="People's Bank">People's Bank</option>
                                        <option value="Commercial Bank">Commercial Bank</option>
                                        <option value="Hatton National Bank">Hatton National Bank</option>
                                        <option value="Sampath Bank">Sampath Bank</option>
                                        <option value="Nations Trust Bank">Nations Trust Bank</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div class="auth-form-group">
                                    <label for="branchName">Branch Name</label>
                                    <input type="text" id="branchName" name="branchName" required>
                                </div>
                            </div>

                            <div class="auth-form-row">
                                <div class="auth-form-group">
                                    <label for="accountNumber">Account Number</label>
                                    <input type="text" id="accountNumber" name="accountNumber" pattern="^[0-9]{5,20}$"
                                        required>
                                    <small class="input-help">Enter account number without spaces</small>
                                </div>
                                <div class="auth-form-group">
                                    <label for="accountType">Account Type</label>
                                    <select id="accountType" name="accountType" class="auth-select" required>
                                        <option value="">Select Account Type</option>
                                        <option value="Savings">Savings</option>
                                        <option value="Current">Current</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-navigation">
                                <button type="button" class="prev-btn">Previous</button>
                                <button type="button" class="next-btn">Next</button>
                            </div>
                        </div>
                    </div>

                    <!-- Step 8: Documents -->
                    <div class="form-step" data-step="8">
                        <div class="form-section">
                            <h3 class="section-title">Required Documents</h3>

                            <!-- First Row: NIC and Ownership Proof -->
                            <div class="auth-form-row">
                                <div class="auth-form-group">
                                    <label for="nic">NIC Copy (Front & Back)</label>
                                    <input type="file" id="nic" name="nic" accept=".pdf,.jpg,.jpeg,.png" required>
                                    <small class="input-help">Upload clear images of both sides (Max: 5MB)</small>
                                </div>
                                <div class="auth-form-group">
                                    <label for="ownership_proof">Proof of Ownership</label>
                                    <input type="file" id="ownership_proof" name="ownership_proof" accept=".pdf"
                                        required>
                                    <small class="input-help">Deed/Lease agreement/Permit (Max: 10MB)</small>
                                </div>
                            </div>

                            <!-- Second Row: Tax Receipts and Bank Passbook -->
                            <div class="auth-form-row">
                                <div class="auth-form-group">
                                    <label for="tax_receipts">Recent Tax Receipts</label>
                                    <input type="file" id="tax_receipts" name="tax_receipts"
                                        accept=".pdf,.jpg,.jpeg,.png" required>
                                    <small class="input-help">Upload latest tax payment receipts (Max: 5MB)</small>
                                </div>
                                <div class="auth-form-group">
                                    <label for="bank_passbook">Bank Passbook Copy</label>
                                    <input type="file" id="bank_passbook" name="bank_passbook" accept=".jpg,.jpeg,.png"
                                        required>
                                    <small class="input-help">First page with account details (Max: 2MB)</small>
                                </div>
                            </div>

                            <!-- Single Row: Grama Certificate -->
                            <div class="auth-form-group">
                                <label for="grama_cert">Grama Niladhari Certificate</label>
                                <input type="file" id="grama_cert" name="grama_cert" accept=".pdf,.jpg,.jpeg,.png"
                                    required>
                                <small class="input-help">Recent certificate from Grama Niladhari (Max: 5MB)</small>
                            </div>
                        </div>

                        <div class="form-navigation">
                            <button type="button" class="prev-btn">Previous</button>
                            <button type="submit" class="submit-btn">Submit Application</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</main>

<style>
    .form-progress {
        display: flex;
        justify-content: space-between;
        margin-bottom: 2rem;
        position: relative;
        max-width: 1000px;
        margin: 0 auto 2rem;
    }

    .progress-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        flex: 1;
        position: relative;
        padding: 0 10px;
    }

    .progress-step::before {
        content: '';
        position: absolute;
        top: 15px;
        left: -50%;
        width: 100%;
        height: 2px;
        background: #ddd;
        z-index: 0;
    }

    .progress-step:first-child::before {
        display: none;
    }

    .progress-step.active .step-number {
        background: #007664;
        color: white;
    }

    .step-number {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: #fff;
        border: 2px solid #ddd;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 5px;
        position: relative;
        z-index: 1;
        transition: all 0.3s ease;
    }

    .step-title {
        font-size: 0.75rem;
        color: #666;
        text-align: center;
    }

    .form-step {
        display: none;
    }

    .form-step.active {
        display: block;
    }

    .form-navigation {
        display: flex;
        justify-content: space-between;
        margin-top: 2rem;
    }

    .next-btn,
    .prev-btn {
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-weight: 600;
    }

    .next-btn {
        background: #007664;
        color: white;
    }

    .prev-btn {
        background: #f0f0f0;
        color: #333;
    }

    .auth-form-container {
        max-width: 800px;
    }

    .input-help {
        display: block;
        font-size: 0.8rem;
        color: #666;
        margin-top: 0.25rem;
    }

    .auth-form-group input:focus,
    .auth-form-group select:focus {
        border-color: #86E211;
        box-shadow: 0 0 0 2px rgba(134, 226, 17, 0.2);
    }

    .auth-form-group input.error,
    .auth-form-group select.error {
        border-color: #dc3545;
    }

    .auth-form-group input.error+.input-help {
        color: #dc3545;
    }

    .auth-form-row {
        display: flex;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .auth-form-row .auth-form-group {
        flex: 1;
    }

    .form-section {
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #eee;
    }

    .section-title {
        font-size: 1.1rem;
        color: #333;
        margin-bottom: 1rem;
    }

    .checkbox-group {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-top: 0.5rem;
        padding: 0.5rem;
    }

    .checkbox-item {
        display: flex;
        align-items: center;
        min-width: 120px;
        background: #f8f9fa;
        padding: 0.5rem 1rem;
        border-radius: 4px;
        border: 1px solid #dee2e6;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .checkbox-item:hover {
        background: #e9ecef;
        border-color: #86E211;
    }

    .checkbox-item input[type="checkbox"] {
        margin-right: 8px;
        width: 16px;
        height: 16px;
        cursor: pointer;
    }

    .checkbox-item input[type="checkbox"]:checked+span {
        color: #2b5a00;
        font-weight: 500;
    }

    .submit-btn {
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-weight: 600;
        background: #86E211;
        color: white;
    }

    input[type="file"] {
        padding: 10px;
        border: 1px dashed #ccc;
        border-radius: 5px;
        width: 100%;
    }

    input[type="file"]:hover {
        border-color: #86E211;
    }

    .auth-select {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        background-color: white;
    }

    #accountNumber {
        letter-spacing: 1px;
        font-family: monospace;
    }

    .error {
        border-color: red !important;
    }

    .error-message {
        color: red;
        font-size: 0.8em;
        margin-top: 0.2em;
        display: block;
    }

    /* Add to your existing styles */
    .location-search-input {
        width: 100%;
        padding: 8px;
        margin-top: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    #map {
        border: 1px solid #ddd;
        border-radius: 4px;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('supplierRegForm');
        const steps = form.querySelectorAll('.form-step');
        const progressSteps = document.querySelectorAll('.progress-step');
        let currentStep = 1;

        // Show initial step
        showStep(currentStep);

        // Next button click handler
        form.querySelectorAll('.next-btn').forEach(button => {
            button.addEventListener('click', () => {
                if (validateStep(currentStep)) {
                    currentStep++;
                    showStep(currentStep);
                }
            });
        });

        // Previous button click handler
        form.querySelectorAll('.prev-btn').forEach(button => {
            button.addEventListener('click', () => {
                currentStep--;
                showStep(currentStep);
            });
        });

        function showStep(step) {
            if (step < 1) step = 1;
            if (step > 8) step = 8;

            // Hide all steps
            document.querySelectorAll('.form-step').forEach(step => {
                step.style.display = 'none';
                step.classList.remove('active');
            });

            // Remove active class from all progress steps
            document.querySelectorAll('.progress-step').forEach(step => {
                step.classList.remove('active');
            });

            // Show current step
            const currentStepElement = form.querySelector(`[data-step="${step}"]`);
            const currentProgressStep = document.querySelector(`.progress-step[data-step="${step}"]`);

            if (currentStepElement && currentProgressStep) {
                currentStepElement.style.display = 'block';
                currentStepElement.classList.add('active');
                currentProgressStep.classList.add('active');
            }

            currentStep = step;
        }

        function validateStep(step) {
            const currentStepElement = form.querySelector(`[data-step="${step}"]`);
            const inputs = currentStepElement.querySelectorAll('input, select');
            let isValid = true;

            inputs.forEach(input => {
                if (input.type === 'number') {
                    const value = parseFloat(input.value);
                    const min = parseFloat(input.min);
                    const max = parseFloat(input.max);

                    if (input.hasAttribute('required') && (isNaN(value) || value === '')) {
                        isValid = false;
                        input.classList.add('error');
                        showError(input, 'This field is required');
                    } else if (!isNaN(min) && value < min) {
                        isValid = false;
                        input.classList.add('error');
                        showError(input, `Minimum value is ${min}`);
                    } else if (!isNaN(max) && value > max) {
                        isValid = false;
                        input.classList.add('error');
                        showError(input, `Maximum value is ${max}`);
                    } else {
                        input.classList.remove('error');
                        clearError(input);
                    }
                }
                // Handle other input types
                else if (input.hasAttribute('required') && !input.value) {
                    isValid = false;
                    input.classList.add('error');
                    showError(input, 'This field is required');
                } else if (input.type === 'tel' && input.value && !validatePhoneNumber(input.value)) {
                    isValid = false;
                    input.classList.add('error');
                    showError(input, 'Invalid phone number format');
                } else if (input.id === 'accountNumber' && input.value && !validateAccountNumber(input.value)) {
                    isValid = false;
                    input.classList.add('error');
                    showError(input, 'Invalid account number format');
                } else {
                    input.classList.remove('error');
                    clearError(input);
                }
            });

            return isValid;
        }

        // Add phone number formatting
        document.querySelectorAll('input[type="tel"]').forEach(input => {
            input.addEventListener('input', function (e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 10) {
                    value = value.substr(0, 10);
                }
                e.target.value = value;
            });
        });

        // Enhanced validation for phone numbers
        function validatePhoneNumber(number) {
            const phoneRegex = /^(?:7|0)[0-9]{9}$/;
            return phoneRegex.test(number);
        }

        // Add to your existing JavaScript
        function validateFileSize(input) {
            const file = input.files[0];
            const maxSize = input.dataset.maxSize || 5; // Default 5MB if not specified

            if (file && file.size > maxSize * 1024 * 1024) {
                alert(`File size must be less than ${maxSize}MB`);
                input.value = '';
                return false;
            }
            return true;
        }

        // Add file input listeners
        document.querySelectorAll('input[type="file"]').forEach(input => {
            input.addEventListener('change', function () {
                validateFileSize(this);
            });
        });

        // Replace your existing form submit handler with this updated version
        form.addEventListener('submit', async function (e) {
            e.preventDefault();

            try {
                // Find submit button first
                const submitBtn = this.querySelector('.submit-btn');
                if (!submitBtn) {
                    throw new Error('Submit button not found');
                }

                // Disable button and show loading state
                submitBtn.disabled = true;
                submitBtn.textContent = 'Submitting...';

                const response = await fetch(this.action, {
                    method: 'POST',
                    body: new FormData(this)
                });

                const data = await response.json();

                if (data.success) {
                    window.location.href = data.redirect || '/dashboard';
                } else {
                    throw new Error(data.message || 'Submission failed');
                }

            } catch (error) {
                alert('Error: ' + error.message);
            } finally {
                // Re-enable submit button if it exists
                const submitBtn = this.querySelector('.submit-btn');
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Submit Application';
                }
            }
        });

        // Add account number validation
        function validateAccountNumber(number) {
            const accountRegex = /^[0-9]{5,20}$/;
            return accountRegex.test(number);
        }

        // Add helper functions for error messages
        function showError(input, message) {
            let errorElement = input.nextElementSibling;
            if (!errorElement || !errorElement.classList.contains('error-message')) {
                errorElement = document.createElement('small');
                errorElement.classList.add('error-message');
                input.parentNode.insertBefore(errorElement, input.nextSibling);
            }
            errorElement.textContent = message;
            errorElement.style.color = 'red';
        }

        function clearError(input) {
            const errorElement = input.nextElementSibling;
            if (errorElement && errorElement.classList.contains('error-message')) {
                errorElement.remove();
            }
        }

        // Update number input event listeners
        document.querySelectorAll('input[type="number"]').forEach(input => {
            input.addEventListener('input', function () {
                const value = parseFloat(this.value);
                const min = parseFloat(this.min);
                const step = parseFloat(this.step);

                if (!isNaN(value)) {
                    // Round to nearest step value
                    const steps = Math.round((value - min) / step);
                    const newValue = min + (steps * step);
                    this.value = newValue.toFixed(2);
                }
            });
        });

        document.getElementById('plant_age').addEventListener('input', function () {
            const value = parseFloat(this.value);
            const min = parseFloat(this.min);
            const max = parseFloat(this.max);
            const step = parseFloat(this.step);

            if (!isNaN(value)) {
                // Round to nearest 0.5
                const roundedValue = Math.round(value * 2) / 2;

                if (roundedValue < min) {
                    this.value = min;
                } else if (roundedValue > max) {
                    this.value = max;
                } else {
                    this.value = roundedValue;
                }
            }
        });

        document.getElementById('monthly_production').addEventListener('input', function () {
            const value = parseFloat(this.value);
            const min = parseFloat(this.min);
            const max = parseFloat(this.max);
            const step = parseFloat(this.step);

            if (!isNaN(value)) {
                // Round to nearest 0.5
                const roundedValue = Math.round(value * 2) / 2;

                if (roundedValue < min) {
                    this.value = min;
                } else if (roundedValue > max) {
                    this.value = max;
                } else {
                    this.value = roundedValue;
                }
            }
        });
    });
</script>

<!-- Add Google Maps JavaScript API -->
<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCdt_khahhXrKdrA8cLgKeQB2CZtde-_Vc&callback=initMap"></script>

<script>
    // Replace the existing initMap function with this version
    function initMap() {
        // Default center (Sri Lanka)
        const defaultCenter = { lat: 7.8731, lng: 80.7718 };

        // Create map
        const map = new google.maps.Map(document.getElementById('map'), {
            zoom: 8,
            center: defaultCenter,
            mapTypeId: 'roadmap', // Plain map view
            streetViewControl: false,
            mapTypeControl: false,
            zoomControl: true,
            fullscreenControl: false
        });

        // Create marker
        let marker = new google.maps.Marker({
            position: defaultCenter,
            map: map,
            draggable: true
        });

        // Try to get user's location
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const pos = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };

                    // Check if position is within Sri Lanka bounds
                    const sriLankaBounds = {
                        north: 9.9,
                        south: 5.9,
                        west: 79.5,
                        east: 81.9
                    };

                    if (pos.lat >= sriLankaBounds.south &&
                        pos.lat <= sriLankaBounds.north &&
                        pos.lng >= sriLankaBounds.west &&
                        pos.lng <= sriLankaBounds.east) {

                        map.setCenter(pos);
                        map.setZoom(15);
                        marker.setPosition(pos);
                        updateFormValues(pos);
                    }
                },
                () => {
                    // Handle location error silently
                    console.log('Location access denied or error occurred');
                }
            );
        }

        // Allow both click and drag
        map.addListener('click', (e) => {
            marker.setPosition(e.latLng);
            updateFormValues(e.latLng);
        });

        marker.addListener('dragend', () => {
            const position = marker.getPosition();
            updateFormValues(position);
        });

        // Helper function to update form values
        function updateFormValues(position) {
            document.getElementById('latitude').value = position.lat();
            document.getElementById('longitude').value = position.lng();
        }

        // Set initial form values
        updateFormValues(defaultCenter);
    }

    // Initialize map when page loads
    document.addEventListener('DOMContentLoaded', initMap);
</script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>