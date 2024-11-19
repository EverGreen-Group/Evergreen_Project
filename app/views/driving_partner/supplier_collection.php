<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Add the QR Scanner library in the head section -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js" integrity="sha512-r6rDA7W6ZeQhvl8S7yRVQUKVHdexq+GAlNkNNqVC7YyIV+NwqCTJe2hDWCiffTyRNOeGEzRRJ9ifvRm/HCzGYg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<?php require APPROOT . '/views/inc/components/sidebar_driving_partner.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<main>
    <!-- Weight Tracking Section -->
    <ul class="route-box-info">
        <li>
            <i class='bx bx-package'></i>
            <span class="text">
                <p>Tare Weight</p>
                <h3>3,500 kg</h3>
                <span>Empty Vehicle</span>
            </span>
        </li>
        <li>
            <i class='bx bx-weight'></i>
            <span class="text">
                <p>Gross Weight</p>
                <h3>4,250 kg</h3>
                <span>Current Total</span>
            </span>
        </li>
        <li>
            <i class='bx bx-leaf'></i>
            <span class="text">
                <p>Net Weight</p>
                <h3>750 kg</h3>
                <span>Tea Leaves</span>
            </span>
        </li>
        <li>
            <i class='bx bx-trending-up'></i>
            <span class="text">
                <p>Remaining</p>
                <h3>750 kg</h3>
                <span>Available Capacity</span>
            </span>
        </li>
    </ul>

    <!-- First Row: Container for Supplier Info and Collection Progress -->
    <div class="info-progress-container">
        <!-- Current Supplier Info -->
        <div class="order supplier-card">
            <div class="head">
                <h3>Current Supplier</h3>
                <i class='bx bx-user'></i>
            </div>
            <div class="supplier-info">
                <div class="supplier-profile">
                    <img src="https://randomuser.me/api/portraits/men/58.jpg" alt="Supplier Image">
                    <h4>Nimal Silva</h4>
                </div>
                <div class="supplier-details">
                    <p><i class='bx bx-map'></i> Kahawatta, Sri Lanka</p>
                    <p><i class='bx bx-phone'></i> 071-9876543</p>
                    <p><i class='bx bx-id-card'></i> 199934567890</p>
                    <p><i class='bx bx-leaf'></i> Expected: 150 kg</p>
                </div>
                <div class="supplier-actions">
                    <button class="btn-call" onclick="makeCall('0719876543')">
                        <i class='bx bx-phone-call'></i> Call
                    </button>
                </div>
            </div>
        </div>

        <!-- Collection Progress Table -->
        <div class="table-data">
        <div class="order progress-card">
            <div class="head">
                <h3>Collection Progress</h3>
                <i class='bx bx-list-check'></i>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Supplier</th>
                        <th>Location</th>
                        <th>Expected</th>
                        <th>Collected</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="completed">
                        <td>Kamal Perera</td>
                        <td>Ratnapura</td>
                        <td>200 kg</td>
                        <td>195 kg</td>
                        <td><span class="status completed">Collected</span></td>
                    </tr>
                    <tr class="current">
                        <td>Nimal Silva</td>
                        <td>Kahawatta</td>
                        <td>150 kg</td>
                        <td>-</td>
                        <td><span class="status pending">At Location</span></td>
                    </tr>
                    <tr class="upcoming disabled">
                        <td>Saman Fernando</td>
                        <td>Pelmadulla</td>
                        <td>300 kg</td>
                        <td>-</td>
                        <td><span class="status inactive">Pending</span></td>
                    </tr>
                </tbody>
            </table>
            </div>
        </div>
    </div>

    <!-- Collection Form in next row -->
    <div class="table-data">
        <div class="order" style="width: 100%;">
            <div class="head">
                <h3>Record Collection</h3>
                <i class='bx bx-notepad'></i>
            </div>
            <form id="weightForm" class="collection-form" onsubmit="submitWeight(event)">
                <!-- QR Scanner section -->
                <div class="form-group">
                    <label>Scan Supplier QR Code</label>
                    <div id="qr-reader" style="width: 100%; max-width: 500px; margin: 0 auto;"></div>
                    <div id="qr-reader-results" style="text-align: center; margin-top: 10px;"></div>
                    <div style="text-align: center; margin-top: 10px;">
                        <button type="button" id="start-scanner" class="btn-submit">
                            <i class='bx bx-camera'></i> Start Scanner
                        </button>
                    </div>
                </div>

                <!-- Weight Details -->
                <div class="form-row">
                    <div class="form-group">
                        <label>Supplier NIC</label>
                        <input type="text" required name="supplier_nic" pattern="^\d{9}[vVxX]$|^\d{12}$" placeholder="199934567890">
                    </div>
                    <div class="form-group">
                        <label>Gross Weight (kg)</label>
                        <input type="number" step="0.01" required name="gross_weight">
                    </div>
                    <div class="form-group">
                        <label>Net Weight (kg)</label>
                        <input type="number" step="0.01" readonly name="net_weight">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Odometer Reading (km)</label>
                        <input type="number" required name="odometer_reading">
                    </div>
                </div>

                <!-- Quality Assessment -->
                <div class="form-row">
                    <div class="form-group">
                        <label>Leaf Condition</label>
                        <select name="leaf_condition" required>
                            <option value="">Select condition</option>
                            <option value="excellent">Excellent</option>
                            <option value="good">Good</option>
                            <option value="fair">Fair</option>
                            <option value="poor">Poor</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Wetness Level</label>
                        <select name="wetness" required>
                            <option value="">Select wetness</option>
                            <option value="dry">Dry</option>
                            <option value="slightly_wet">Slightly Wet</option>
                            <option value="wet">Wet</option>
                            <option value="very_wet">Very Wet</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Notes</label>
                    <textarea name="notes" rows="2"></textarea>
                </div>

                <!-- Signature -->
                <div class="form-group">
                    <label>Supplier's Signature</label>
                    <div class="signature-pad">
                        <canvas id="signaturePad"></canvas>
                        <button type="button" class="btn-clear" onclick="clearSignature()">
                            <i class='bx bx-refresh'></i> Clear
                        </button>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-submit">
                        <i class='bx bx-check'></i> Submit Collection
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

<style>
.route-box-info {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    grid-gap: 24px;
    margin-top: 24px;
    margin-bottom: 24px;
}

.route-box-info li {
    padding: 24px;
    background: var(--light);
    border-radius: 20px;
    display: flex;
    align-items: center;
    grid-gap: 24px;
    cursor: pointer;
}

.route-box-info li:hover {
    transform: scale(1.01);
    transition: all 0.2s ease;
}

.route-box-info li .bx {
    width: 80px;
    height: 80px;
    border-radius: 10px;
    font-size: 36px;
    display: flex;
    justify-content: center;
    align-items: center;
}

.route-box-info li:nth-child(1) .bx {
    background: var(--light-blue);
    color: var(--blue);
}

.route-box-info li:nth-child(2) .bx {
    background: var(--light-yellow);
    color: var(--yellow);
}

.route-box-info li:nth-child(3) .bx {
    background: var(--light-green);
    color: var(--green);
}

.route-box-info li:nth-child(4) .bx {
    background: var(--light-orange);
    color: var(--orange);
}

.route-box-info li .text h3 {
    font-size: 24px;
    font-weight: 600;
    color: var(--dark);
}

.route-box-info li .text p {
    color: var(--dark);
}

.route-box-info li .text span {
    font-size: 12px;
    color: var(--dark-grey);
}

/* Remove the old weight-metrics styles */
.weight-metrics {
    display: none;
}

tr.disabled {
    opacity: 0.5;
    pointer-events: none;
}

tr.current {
    background: var(--light-blue);
}

tr.completed {
    background: var(--light-green);
}

/* Rest of the existing styles from v_collection.php */

.table-data {
    display: flex;
    flex-direction: row;
    gap: 24px;
    margin-top: 24px;
    width: 100%;
    color: var(--dark);
}

.table-data .order {
    background: var(--light);
    padding: 24px;
    border-radius: 20px;
}

.table-data .order:nth-child(1) {
    flex: 0 0 30%;
    margin: 0;
}

.table-data .order:nth-child(2) {
    flex: 0 0 70%;
    margin: 0;
}

.table-data:nth-child(4) .order {
    flex: 0 0 100%;
}

.table-data .order table {
    width: 100%;
}

.supplier-info {
    padding: 20px;
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 20px;
}

.supplier-profile {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
}

.supplier-profile img {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid var(--light-blue);
}

.supplier-profile h4 {
    font-size: 20px;
    margin: 0;
    color: var(--dark);
}

.supplier-details {
    width: 100%;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.supplier-details p {
    margin: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    font-size: 15px;
}

.supplier-details .bx {
    font-size: 20px;
    color: var(--blue);
}

.supplier-actions {
    width: 100%;
    display: flex;
    gap: 10px;
    justify-content: center;
    margin-top: 10px;
}

.btn-record, .btn-call {
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    transition: all 0.3s ease;
    color: var(--light);
}

.btn-record {
    background: #3C91E6;  /* Using the blue color explicitly */
}

.btn-call {
    background: #27ae60;  /* Using a distinct green color */
}

.btn-record:hover, .btn-call:hover {
    opacity: 0.8;
    transform: translateY(-2px);
}

.btn-record .bx, .btn-call .bx {
    font-size: 18px;
}

.collection-form {
    padding: 20px;
}

.form-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin-bottom: 15px;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    color: var(--dark);
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 8px;
    border: 1px solid var(--grey);
    border-radius: 5px;
    background: var(--light);
}

.signature-pad {
    border: 1px solid var(--grey);
    border-radius: 5px;
    padding: 10px;
    margin-top: 5px;
}

.signature-pad canvas {
    width: 100%;
    height: 150px;
    border: 1px solid var(--grey);
    margin-bottom: 10px;
}

.btn-submit, .btn-clear {
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 5px;
}

.btn-submit {
    background: var(--blue);
    color: var(--light);
}

.btn-clear {
    background: var(--grey);
    color: var(--dark);
}

/* Make the collection form take full width */
.table-data:nth-child(3) {
    display: block;
}

/* Container for supplier info and collection progress */
.info-progress-container {
    display: flex;
    gap: 24px;
    margin-top: 24px;
    width: 100%;
    min-height: 400px;
}

/* Supplier card */
.info-progress-container .supplier-card {
    flex: 0 0 30%;
    background: var(--light);
    padding: 24px;
    border-radius: 20px;
    height: 100%;
    display: flex;
    flex-direction: column;
}

/* Progress table card */
.info-progress-container .progress-card {
    flex: 0 0 70%;
    background: var(--light);
    padding: 24px;
    border-radius: 20px;
    height: 100%;
    display: flex;
    flex-direction: column;
}

/* Ensure table takes remaining space */
.info-progress-container .progress-card table {
    flex: 1;
    margin-top: 20px;
}

/* Style for NIC input */
input[name="supplier_nic"] {
    font-family: monospace;
    letter-spacing: 1px;
}

/* Validation style for NIC */
input[name="supplier_nic"]:invalid {
    border-color: var(--red);
}

input[name="supplier_nic"]:valid {
    border-color: var(--green);
}

/* Update submit button styles */
.btn-submit {
    padding: 12px 24px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 15px;
    background: #27ae60;  /* Green color */
    color: var(--light);
    transition: all 0.3s ease;
    margin-top: 20px;
}

.btn-submit:hover {
    background: #219a52;  /* Darker green on hover */
    transform: translateY(-2px);
}

.btn-submit .bx {
    font-size: 20px;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    margin-top: 20px;
    padding: 0 20px 20px 20px;
}

#qr-reader {
    border: 1px solid var(--grey);
    border-radius: 8px;
    overflow: hidden;
}

#qr-reader video {
    width: 100%;
    height: auto;
}

#qr-reader-results {
    font-weight: bold;
    color: var(--main);
}
</style>

<script>
function openWeightModal(supplierName) {
    document.getElementById('weightModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('weightModal').style.display = 'none';
}

function submitWeight(event) {
    event.preventDefault();
    // Add your weight submission logic here
    closeModal();
    // Refresh the page or update the UI
}

function makeCall(phoneNumber) {
    window.location.href = `tel:${phoneNumber}`;
}

// Add signature pad initialization
let signaturePad;

function initSignaturePad() {
    const canvas = document.getElementById('signaturePad');
    signaturePad = new SignaturePad(canvas);
}

function clearSignature() {
    signaturePad.clear();
}

// Calculate net weight automatically
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('weightForm');
    const grossInput = form.querySelector('[name="gross_weight"]');
    const netInput = form.querySelector('[name="net_weight"]');
    const tareWeight = parseFloat(document.getElementById('tareWeight').textContent);

    function calculateNet() {
        const gross = parseFloat(grossInput.value) || 0;
        netInput.value = (gross - tareWeight).toFixed(2);
    }

    grossInput.addEventListener('input', calculateNet);
});

// Wait for the page to fully load
window.onload = function() {
    console.log('Window loaded');
    // Check if Html5Qrcode is available
    if (typeof Html5Qrcode === 'undefined') {
        console.error('Html5Qrcode library not loaded, attempting to reload');
        // Attempt to load the library again
        const script = document.createElement('script');
        script.src = "https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js";
        script.onload = function() {
            console.log('Html5Qrcode library loaded successfully');
            initializeScanner();
        };
        script.onerror = function() {
            console.error('Failed to load Html5Qrcode library');
        };
        document.head.appendChild(script);
    } else {
        console.log('Html5Qrcode library already loaded');
        initializeScanner();
    }
};

function initializeScanner() {
    try {
        const qrReader = new Html5Qrcode("qr-reader");
        console.log('QR Reader initialized');

        document.getElementById('start-scanner').addEventListener('click', function() {
            console.log('Start Scanner clicked');
            
            navigator.mediaDevices.getUserMedia({ video: true })
                .then(function(stream) {
                    console.log('Camera permission granted');
                    stream.getTracks().forEach(track => track.stop());

                    qrReader.start(
                        { facingMode: "user" },
                        {
                            fps: 10,
                            qrbox: { width: 250, height: 250 }
                        },
                        qrCodeMessage => {
                            console.log('QR Code scanned:', qrCodeMessage);
                            document.getElementById('qr-reader-results').innerText = `Scanned Code: ${qrCodeMessage}`;
                            document.querySelector('[name="supplier_nic"]').value = qrCodeMessage;
                            qrReader.stop();
                        },
                        errorMessage => {
                            console.warn('QR Code scan error:', errorMessage);
                        }
                    ).catch(err => {
                        console.error('Unable to start scanning:', err);
                    });
                })
                .catch(function(err) {
                    console.error('Camera access denied:', err);
                    alert("Please allow camera access to use the scanner.");
                });
        });
    } catch (error) {
        console.error('Error initializing QR scanner:', error);
    }
}
</script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?> 