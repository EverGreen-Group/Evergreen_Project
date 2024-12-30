<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_suppliermanager.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<!-- MAIN -->
        <main class="supplier-requests-container">
        <div class="head-title">
                <div class="left">
                    <h1>Supplier Collection Readiness</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="SupplementDashboard.html">Home</a>
                        </li>
                        <li>
                            <i class='bx bx-chevron-right'></i>
                        </li>
                        <li>
                            <a class="active" href="#">Early Collection Requests</a>
                        </li>
                    </ul>
                </div>
            </div>

                <!-- Box Info -->
            <ul class="box-info">
                <li>
                    <i class='bx bx-bell'></i>
                    <span class="text">
                        <h3>8</h3>
                        <p>Ready Now</p>
                        <small>Ahead of Schedule</small>
                    </span>
                </li>
                <li>
                    <i class='bx bx-check-circle'></i>
                    <span class="text">
                        <h3>15</h3>
                        <p>Accommodated Today</p>
                        <small>Early Collections</small>
                    </span>
                </li>
                <li>
                    <i class='bx bx-route'></i>
                    <span class="text">
                        <h3>4</h3>
                        <p>Available Routes</p>
                        <small>With Capacity</small>
                    </span>
                </li>
            </ul>

            <!-- Suppliers Table -->
            <div class="table-data">
                <div class="order" style="width: 100%;">
                    <div class="head">
                        <h3>Suppliers Ready for Collection</h3>
                        <div class="head-actions">
                            <button class="request-route-btn" id="requestRouteBtn" disabled>
                                <i class='bx bx-plus'></i>
                                Request New Route
                            </button>
                        </div>
                    </div>
                    <div class="table-container">
                        <table class="table-body">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" id="selectAll" class="checkbox-input">
                                    </th>
                                    <th>Supplier ID</th>
                                    <th>Ready Since</th>
                                    <th>Location</th>
                                    <th>Collection Volume</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <input type="checkbox" class="supplier-checkbox checkbox-input" 
                                               data-supplier-id="000567"
                                               data-location="Kandy Central"
                                               data-zone="Zone B"
                                               data-volume="30">
                                    </td>
                                    <td>Supplier 000567<br><small>High Priority</small></td>
                                    <td>8:30 AM<br><small>Morning Collection</small></td>
                                    <td>Kandy Central<br><small>Zone B</small></td>
                                    <td>30kg<br><small>Regular Load</small></td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="three-dots-btn"><i class='bx bx-dots-vertical-rounded'></i></button>
                                            <div class="dropdown-content">
                                                <div class="route-group">
                                                    <span class="route-header">Morning Routes</span>
                                                    <a href="#" onclick="assignRoute('000567', 'M12')">Route M12 (200kg left)</a>
                                                    <a href="#" onclick="assignRoute('000567', 'M15')">Route M15 (400kg left)</a>
                                                </div>
                                                <div class="route-group">
                                                    <span class="route-header">Afternoon Routes</span>
                                                    <a href="#" onclick="assignRoute('000567', 'A05')">Route A05 (300kg left)</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <input type="checkbox" class="supplier-checkbox checkbox-input" 
                                               data-supplier-id="000892"
                                               data-location="Peradeniya"
                                               data-zone="Zone A"
                                               data-volume="25">
                                    </td>
                                    <td>Supplier 000892<br><small>Medium Priority</small></td>
                                    <td>11:45 AM<br><small>Afternoon Collection</small></td>
                                    <td>Peradeniya<br><small>Zone A</small></td>
                                    <td>25kg<br><small>Regular Load</small></td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="three-dots-btn"><i class='bx bx-dots-vertical-rounded'></i></button>
                                            <div class="dropdown-content">
                                                <div class="route-group">
                                                    <span class="route-header">Morning Routes</span>
                                                    <a href="#" onclick="assignRoute('000892', 'M12')">Route M12 (200kg left)</a>
                                                    <a href="#" onclick="assignRoute('000892', 'M15')">Route M15 (400kg left)</a>
                                                </div>
                                                <div class="route-group">
                                                    <span class="route-header">Afternoon Routes</span>
                                                    <a href="#" onclick="assignRoute('000892', 'A05')">Route A05 (300kg left)</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Route Capacity Overview -->
            <div class="table-data">
                <div class="order">
                    <div class="head">
                        <h3>Route Capacity Overview</h3>
                    </div>
                    <div class="shifts-overview">
                        <!-- Morning Shift -->
                        <div class="shift-block">
                            <div class="shift-header">
                                <i class='bx bx-sun'></i>
                                <h4>Morning Shift (6AM-2PM)</h4>
                            </div>
                            <div class="routes-list">
                                <div class="route-card">
                                    <div class="route-info">
                                        <span class="route-name">Route M12</span>
                                        <span class="route-path">Kandy Central → Peradeniya</span>
                                    </div>
                                    <div class="capacity-wrapper">
                                        <div class="capacity-bar">
                                            <div class="capacity-fill" style="width: 60%"></div>
                                        </div>
                                        <span class="capacity-text">600/1000kg</span>
                                    </div>
                                </div>
                                <div class="route-card">
                                    <div class="route-info">
                                        <span class="route-name">Route M15</span>
                                        <span class="route-path">Gampola → Katugastota</span>
                                    </div>
                                    <div class="capacity-wrapper">
                                        <div class="capacity-bar">
                                            <div class="capacity-fill" style="width: 85%"></div>
                                        </div>
                                        <span class="capacity-text">850/1000kg</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Afternoon Shift -->
                        <div class="shift-block">
                            <div class="shift-header">
                                <i class='bx bx-sun'></i>
                                <h4>Afternoon Shift (2PM-10PM)</h4>
                            </div>
                            <div class="routes-list">
                                <div class="route-card">
                                    <div class="route-info">
                                        <span class="route-name">Route A05</span>
                                        <span class="route-path">Peradeniya → Gampola</span>
                                    </div>
                                    <div class="capacity-wrapper">
                                        <div class="capacity-bar">
                                            <div class="capacity-fill" style="width: 45%"></div>
                                        </div>
                                        <span class="capacity-text">450/1000kg</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Night Shift -->
                        <div class="shift-block">
                            <div class="shift-header">
                                <i class='bx bx-moon'></i>
                                <h4>Night Shift (10PM-6AM)</h4>
                            </div>
                            <div class="routes-list">
                                <div class="route-card">
                                    <div class="route-info">
                                        <span class="route-name">Route N03</span>
                                        <span class="route-path">Katugastota → Kandy</span>
                                    </div>
                                    <div class="capacity-wrapper">
                                        <div class="capacity-bar">
                                            <div class="capacity-fill" style="width: 25%"></div>
                                        </div>
                                        <span class="capacity-text">250/1000kg</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

                        
    </main>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="<?php echo URLROOT; ?>/css/components/script.js"></script>
    <style>
    .constraints-container {
        padding: 1.5rem;
        background: #fff;
    }

    .constraint-group {
        margin-bottom: 1.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid #eee;
    }

    .constraint-group:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }

    .constraint-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.5rem;
    }

    .constraint-label {
        color: #333;
        font-weight: 500;
        font-size: 0.95rem;
    }

    .constraint-input {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .constraint-input input,
    .constraint-input select {
        padding: 0.5rem;
        border: 1px solid #ddd;
        border-radius: 4px;
        width: 120px;
        font-size: 0.9rem;
    }

    .constraint-input .unit {
        color: #666;
        font-size: 0.9rem;
    }

    .constraint-info {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #666;
        font-size: 0.85rem;
        margin-left: 1rem;
    }

    .constraint-info i {
        color: var(--main);
        font-size: 1rem;
    }

    .btn-save {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background-color: var(--main);
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 0.9rem;
    }

    .btn-save:hover {
        background-color: var(--main-dark);
    }

    .accept-btn, .reject-btn {
        padding: 6px 12px;
        border: none;
        border-radius: 4px;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .accept-btn {
        background-color: #e8f5e9;
        color: #2e7d32;
        margin-right: 8px;
    }

    .accept-btn:hover {
        background-color: #2e7d32;
        color: white;
    }

    .reject-btn {
        background-color: #ffebee;
        color: #c62828;
    }

    .reject-btn:hover {
        background-color: #c62828;
        color: white;
    }

    /* Optional: Add disabled state styles */
    .accept-btn:disabled, .reject-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    /* Optional: Add active state styles */
    .accept-btn:active, .reject-btn:active {
        transform: scale(0.98);
    }

    /* Add styles for small text */
    small {
        color: #666;
        display: block;
        font-size: 0.8rem;
        margin-top: 2px;
    }

    /* Add priority indicator styles */
    .high-priority {
        color: #c62828;
        font-weight: bold;
    }

    /* Priority colors */
    tr small:contains("High Priority") {
        color: #c62828;
    }

    tr small:contains("Medium Priority") {
        color: #f57c00;
    }

    tr small:contains("Low Priority") {
        color: #2e7d32;
    }

    /* Volume indicators */
    td small:contains("Full Capacity") {
        color: #c62828;
        font-weight: bold;
    }

    td small:contains("Partial Load") {
        color: #f57c00;
    }

    /* Add to existing styles */
    .chart-container {
        height: 400px;
        padding: 20px;
        background: #fff;
        border-radius: 8px;
        margin: 20px 0;
    }

    .request-route-btn {
        background-color: var(--main);
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 4px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .request-route-btn:hover {
        background-color: var(--main-dark);
    }

    .route-select {
        width: 100%;
        padding: 6px;
        border-radius: 4px;
        border: 1px solid #ddd;
    }

    .capacity-bar {
        width: 100%;
        height: 8px;
        background-color: #eee;
        border-radius: 4px;
        overflow: hidden;
        margin: 4px 0;
    }

    .capacity-fill {
        height: 100%;
        background-color: var(--main);
        border-radius: 4px;
    }

    .routes-summary {
        background: white;
        padding: 15px;
        border-radius: 8px;
    }

    .shift-group {
        margin-bottom: 20px;
    }

    .shift-group h4 {
        color: #333;
        margin-bottom: 10px;
    }

    .route-item {
        padding: 10px;
        border: 1px solid #eee;
        border-radius: 4px;
        margin-bottom: 8px;
    }

    .route-item span {
        font-weight: 500;
    }

    .assign-btn {
        background-color: var(--main);
        color: white;
        border: none;
        padding: 6px 12px;
        border-radius: 4px;
        cursor: pointer;
    }

    .assign-btn:hover {
        background-color: var(--main-dark);
    }

    /* Add warning colors for capacity */
    .capacity-fill[style*="width: 8"] {
        background-color: #f44336;
    }

    .capacity-fill[style*="width: 7"] {
        background-color: #ff9800;
    }

    /* Update existing styles */
    .table-data {
        width: 100%;
    }

    .table-data .order {
        width: 100%;
        padding: 20px;
        background: var(--light);
        border-radius: 8px;
    }

    .table-container {
        width: 100%;
        overflow-x: auto;
        height: 400px;
        overflow-y: auto;
    }

    .table-body {
        width: 100%;
        border-collapse: collapse;
    }

    .table-body thead {
        position: sticky;
        top: 0;
        background: var(--light);
        z-index: 1;
    }

    .table-body thead tr {
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .table-body th,
    .table-body td {
        padding: 12px 15px;
        white-space: nowrap;
    }

    /* Ensure columns have appropriate widths */
    .table-body th:nth-child(1),
    .table-body td:nth-child(1) {
        width: 20%;
    }

    .table-body th:nth-child(2),
    .table-body td:nth-child(2) {
        width: 20%;
    }

    .table-body th:nth-child(3),
    .table-body td:nth-child(3) {
        width: 25%;
    }

    .table-body th:nth-child(4),
    .table-body td:nth-child(4) {
        width: 25%;
    }

    .table-body th:nth-child(5),
    .table-body td:nth-child(5) {
        width: 10%;
    }

    .routes-summary {
        background: white;
        padding: 12px;
        border-radius: 8px;
    }

    .route-item {
        padding: 8px;
        border: 1px solid #eee;
        border-radius: 4px;
        margin-bottom: 6px;
    }

    .route-item span {
        font-size: 0.9rem;
        font-weight: 500;
    }

    .shift-group h4 {
        font-size: 0.95rem;
        margin-bottom: 8px;
    }

    /* Make capacity bars slightly smaller */
    .capacity-bar {
        height: 6px;
        margin: 3px 0;
    }

    .route-item small {
        font-size: 0.75rem;
    }

    /* Add these new styles */
    .dropdown {
        position: relative;
        display: inline-block;
    }

    .three-dots-btn {
        background: none;
        border: none;
        font-size: 1.2rem;
        cursor: pointer;
        padding: 5px;
        color: var(--dark);
    }

    .three-dots-btn:hover {
        color: var(--main);
    }

    .dropdown-content {
        display: none;
        position: absolute;
        top: 100%;
        right: 0;
        background-color: #fff;
        min-width: 200px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.2);
        border-radius: 4px;
        z-index: 1000;
        padding: 8px 0;
        margin-top: 5px;
    }

    .dropdown-content {
        max-height: 300px;
        overflow-y: auto;
    }

    .dropdown:hover .dropdown-content {
        display: block;
    }

    .route-group {
        padding: 8px 0;
        border-bottom: 1px solid #eee;
    }

    .route-group:last-child {
        border-bottom: none;
    }

    .route-header {
        display: block;
        padding: 5px 15px;
        font-size: 0.8rem;
        color: #666;
        font-weight: 500;
    }

    .dropdown-content a {
        color: var(--dark);
        padding: 8px 15px;
        text-decoration: none;
        display: block;
        font-size: 0.9rem;
    }

    .dropdown-content a:hover {
        background-color: #f5f5f5;
        color: var(--main);
    }

    /* Position the dropdown based on button click */
    .show-dropdown {
        display: block;
    }

    /* Add this JavaScript to position the dropdown */
    <script>
    document.querySelectorAll('.three-dots-btn').forEach(button => {
        button.addEventListener('click', (e) => {
            e.preventDefault();
            const dropdown = button.nextElementSibling;
            const rect = button.getBoundingClientRect();
            
            // Position the dropdown
            dropdown.style.top = `${rect.bottom + window.scrollY + 5}px`;
            
            // If dropdown would go off the right edge, align it to the right
            if (rect.right + dropdown.offsetWidth > window.innerWidth) {
                dropdown.style.right = '0';
                dropdown.style.left = 'auto';
            } else {
                dropdown.style.left = `${rect.left}px`;
                dropdown.style.right = 'auto';
            }
            
            // Toggle the dropdown
            dropdown.classList.toggle('show-dropdown');
            
            // Close dropdown when clicking outside
            const closeDropdown = (event) => {
                if (!dropdown.contains(event.target) && !button.contains(event.target)) {
                    dropdown.classList.remove('show-dropdown');
                    document.removeEventListener('click', closeDropdown);
                }
            };
            
            document.addEventListener('click', closeDropdown);
        });
    });

    // Prevent dropdown from closing when clicking inside it
    document.querySelectorAll('.dropdown-content').forEach(dropdown => {
        dropdown.addEventListener('click', (e) => {
            e.stopPropagation();
        });
    });
    </script>

    /* Add these new styles */
    .checkbox-input {
        width: 16px;
        height: 16px;
        cursor: pointer;
    }

    .head-actions {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .request-route-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    /* Update existing table styles */
    .table-body th:first-child,
    .table-body td:first-child {
        width: 40px;
        text-align: center;
    }

    /* Add these new styles */
    .shifts-overview {
        display: flex;
        gap: 20px;
        padding: 15px;
    }

    .shift-block {
        flex: 1;
        background: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
    }

    .shift-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 15px;
    }

    .shift-header i {
        font-size: 1.2rem;
        color: var(--main);
    }

    .shift-header h4 {
        font-size: 1rem;
        color: #333;
        margin: 0;
    }

    .routes-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .route-card {
        background: white;
        border-radius: 6px;
        padding: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    .route-info {
        margin-bottom: 8px;
    }

    .route-name {
        font-weight: 600;
        color: #2c3e50;
        display: block;
        margin-bottom: 4px;
    }

    .route-path {
        font-size: 0.85rem;
        color: #666;
        display: block;
    }

    .capacity-wrapper {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .capacity-bar {
        flex: 1;
        height: 8px;
        background-color: #e9ecef;
        border-radius: 4px;
        overflow: hidden;
    }

    .capacity-text {
        font-size: 0.85rem;
        color: #666;
        min-width: 90px;
        text-align: right;
    }

    /* Capacity fill colors based on percentage */
    .capacity-fill {
        height: 100%;
        background-color: #4CAF50;
        transition: width 0.3s ease;
    }

    .capacity-fill[style*="width: 7"], 
    .capacity-fill[style*="width: 8"] {
        background-color: #ff9800;
    }

    .capacity-fill[style*="width: 9"] {
        background-color: #f44336;
    }

    @media screen and (max-width: 1200px) {
        .shifts-overview {
            flex-direction: column;
        }

        .shift-block {
            width: 100%;
        }
    }
    </style>
    <script>
    function saveConstraints() {
        const constraints = {
            minNotice: document.getElementById('minNotice').value,
            maxRequests: document.getElementById('maxRequests').value,
            blackoutDays: Array.from(document.getElementById('blackoutDays').selectedOptions).map(opt => opt.value)
        };
        
        console.log('Saving constraints:', constraints);
        alert('Shift change constraints saved successfully!');
    }
    </script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('shiftDistributionChart').getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Morning Shift (6AM-2PM)', 'Afternoon Shift (2PM-10PM)', 'Night Shift (10PM-6AM)'],
                datasets: [{
                    data: [35, 45, 20],
                    backgroundColor: [
                        '#4CAF50',
                        '#2196F3',
                        '#9C27B0'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    title: {
                        display: true,
                        text: 'Current Supplier Shift Distribution',
                        padding: 20
                    }
                }
            }
        });
    });
    </script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAllCheckbox = document.getElementById('selectAll');
        const supplierCheckboxes = document.querySelectorAll('.supplier-checkbox');
        const requestRouteBtn = document.getElementById('requestRouteBtn');

        // Handle "Select All" checkbox
        selectAllCheckbox.addEventListener('change', function() {
            supplierCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateRequestButton();
        });

        // Handle individual checkboxes
        supplierCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                updateRequestButton();
                // Update "Select All" checkbox state
                selectAllCheckbox.checked = [...supplierCheckboxes].every(cb => cb.checked);
                selectAllCheckbox.indeterminate = [...supplierCheckboxes].some(cb => cb.checked) && 
                                                ![...supplierCheckboxes].every(cb => cb.checked);
            });
        });

        function updateRequestButton() {
            const selectedCount = [...supplierCheckboxes].filter(cb => cb.checked).length;
            requestRouteBtn.disabled = selectedCount === 0;
        }

        // Update the requestNewRoute function
        document.querySelector('.request-route-btn').addEventListener('click', function() {
            const selectedSuppliers = [...supplierCheckboxes]
                .filter(cb => cb.checked)
                .map(cb => ({
                    id: cb.dataset.supplierId,
                    location: cb.dataset.location,
                    zone: cb.dataset.zone,
                    volume: cb.dataset.volume
                }));

            if (selectedSuppliers.length === 0) {
                alert('Please select at least one supplier');
                return;
            }

            const shiftTime = prompt("Enter shift time (Morning/Afternoon/Evening):");
            if (!shiftTime) return;

            // Calculate total volume
            const totalVolume = selectedSuppliers.reduce((sum, supplier) => 
                sum + parseInt(supplier.volume), 0);

            // Group suppliers by zone
            const zoneGroups = selectedSuppliers.reduce((groups, supplier) => {
                const zone = supplier.zone;
                if (!groups[zone]) groups[zone] = [];
                groups[zone].push(supplier);
                return groups;
            }, {});

            // Create summary message
            const summary = `
                New Route Request Summary:
                - Shift: ${shiftTime}
                - Total Suppliers: ${selectedSuppliers.length}
                - Total Volume: ${totalVolume}kg
                - Zones: ${Object.keys(zoneGroups).join(', ')}
            `;

            if (confirm(summary + '\n\nProceed with route request?')) {
                // Here you would make an API call to the vehicle manager
                alert('Route request sent to vehicle manager');
                // Optionally uncheck all boxes after successful request
                selectAllCheckbox.checked = false;
                supplierCheckboxes.forEach(cb => cb.checked = false);
                updateRequestButton();
            }
        });
    });
    </script>
    <script>
    function assignRoute(supplierId, routeId) {
        // Here you would typically make an API call to assign the route
        alert(`Assigning supplier ${supplierId} to route ${routeId}`);
        // Refresh the table or update the UI as needed
    }
    </script>
    </body>
</html>