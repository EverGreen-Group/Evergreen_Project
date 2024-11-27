<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_suppliermanager.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<script>
    const URLROOT = '<?php echo URLROOT; ?>';
</script>

<!-- MAIN -->
<main>


    
    <div class="head-title">
        <div class="left">
            <h1>Manage Suppliers</h1>
            <ul class="breadcrumb">
                <li><a href="#">Dashboard</a></li>
                <li><a href="#">Applications</a></li>
            </ul>
        </div>
    </div>

    <!-- Box Info -->
    <ul class="box-info">
        <li>
            <i class='bx bxs-file'></i>
            <span class="text">
                <h3>10</h3>
                <p>Total Suppliers</p>
            </span>
        </li>
        <li>
            <i class='bx bxs-check-circle'></i>
            <span class="text">
                <h3>5</h3>
                <p>Total Assigned Suppliers</p>
            </span>
        </li>
        <li>
            <i class='bx bxs-x-circle'></i>
            <span class="text">
                <h3>3</h3>
                <p>Total Inactive Suppliers</p>
            </span>
        </li>
    </ul>



    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Supplier Locations</h3>
            </div>
            <div class="map-container">
                <div id="map"></div>
                <div class="map-legend">
                    <div class="legend-item">
                        <i class='bx bxs-factory'></i>
                        <span>Factory Location</span>
                    </div>
                    <div class="legend-item">
                        <i class='bx bxs-map'></i>
                        <span>Supplier Location</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="table-data">
            <div class="suppliers-grid">
                <?php foreach ($data['suppliers'] as $supplier): ?>
                    <div class="supplier-card">
                        <div class="supplier-info">
                            <div class="profile-image">
                                <img src="<?= URLROOT ?>/public/uploads/supplier_photos/<?= $supplier->profile_image ?? 'default-supplier.png' ?>" 
                                     alt="<?= $supplier->first_name . ' ' . $supplier->last_name ?>">
                            </div>
                            <h3><?= $supplier->first_name . ' ' . $supplier->last_name ?></h3>
                            <p class="supplier-id">SUP<?= str_pad($supplier->supplier_id, 4, '0', STR_PAD_LEFT) ?></p>
                            <span class="status-badge <?= $supplier->is_active ? 'active' : 'inactive' ?>">
                                <?= $supplier->is_active ? 'Active' : 'Inactive' ?>
                            </span>
                            <div class="supplier-actions">
                                <a href="<?= URLROOT ?>/suppliermanager/viewSupplier/<?= $supplier->supplier_id ?>" class="btn-view">
                                    <i class='bx bx-user'></i> View Profile
                                </a>
                                <a href="<?= URLROOT ?>/suppliermanager/setInactive/<?= $supplier->supplier_id ?>" class="btn-inactive">
                                    <i class='bx bx-power-off'></i> Set Inactive
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
    </div>


</main>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>

<style>
    .status-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 500;
        margin: 0 0 12px 0;
    }

    .status-badge.active {
        background-color: #e3fcef;
        color: #00a650;
    }

    .status-badge.inactive {
        background-color: #ffe5e5;
        color: #dc3545;
    }

    .btn-view, .btn-approve, .btn-reject {
        padding: 5px 10px;
        border-radius: 4px;
        margin: 0 2px;
        text-decoration: none;
        font-size: 0.9em;
    }

    .btn-view {
        background-color: #007bff;
        color: white;
    }

    .btn-approve {
        background-color: #4CAF50;
        color: white;
    }

    .btn-reject {
        background-color: #f44336;
        color: white;
    }

    .btn-view:hover, .btn-approve:hover, .btn-reject:hover {
        opacity: 0.8;
    }


    .table-data .order {
        background: var(--light);
        padding: 24px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .table-data .head {
        display: flex;
        align-items: center;
        margin-bottom: 14px;
    }

    .table-data .head h3 {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--dark);
    }

    .table-data table {
        width: 100%;
        border-collapse: collapse;
    }

    .table-data table th {
        padding: 12px;
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--dark);
        text-align: left;
        border-bottom: 1px solid #eee;
        background: #f8f9fa;
    }

    .table-data table td {
        padding: 12px;
        font-size: 0.9rem;
        color: var(--dark);
        border-bottom: 1px solid #eee;
    }

    .table-data table tr:hover {
        background: #f8f9fa;
    }

    .btn-confirm {
        background-color: #007bff;
        color: white;
        padding: 5px 10px;
        border-radius: 4px;
        margin: 0 2px;
        text-decoration: none;
        font-size: 0.9em;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }

    .btn-confirm:hover {
        opacity: 0.8;
    }

    .btn-confirm i {
        font-size: 1.1rem;
    }

    .suppliers-container {
        padding: 20px;
        margin: 20px;
        min-height: 40vh;
    }

    .suppliers-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 15px;
    }

    .supplier-card {
        background: var(--light);
        border-radius: 8px;
        padding: 15px;
        transition: transform 0.2s;
    }

    .supplier-card:hover {
        transform: translateY(-2px);
    }

    .supplier-info {
        text-align: center;
    }

    .supplier-info h3 {
        margin: 0;
        color: var(--dark);
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 4px;
    }

    .supplier-id {
        margin: 0 0 8px 0;
        color: #6c757d;
        font-size: 0.8rem;
        font-weight: 500;
    }

    .supplier-actions {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .btn-view, .btn-inactive {
        padding: 6px 12px;
        border-radius: 4px;
        text-decoration: none;
        font-size: 0.75rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
        transition: all 0.2s;
    }

    .btn-view {
        background-color: #007bff;
        color: white;
    }

    .btn-inactive {
        background-color: #dc3545;
        color: white;
    }

    .btn-view:hover, .btn-inactive:hover {
        opacity: 0.9;
    }

    .btn-view i, .btn-inactive i {
        font-size: 0.9rem;
    }

    /* Responsive adjustments */
    @media screen and (max-width: 768px) {
        .suppliers-container {
            margin: 10px;
            padding: 15px;
        }

        .suppliers-grid {
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 10px;
        }
    }

    .content-container {
        background: var(--light);
        border-radius: 8px;
        padding: 20px;
        margin: 20px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .suppliers-container {
        padding: 15px;
        background: white;
        border-radius: 8px;
    }

    .table-data {
        width: 100%;
        padding: 20px;
        background: var(--light);
        border-radius: 8px;
    }

    .suppliers-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 15px;
        width: 100%;
    }

    .supplier-card {
        background: white;
        border-radius: 8px;
        padding: 15px;
        transition: transform 0.2s;
        width: 100%;
    }

    /* Responsive adjustments */
    @media screen and (max-width: 768px) {
        .table-data {
            padding: 15px;
        }
        
        .suppliers-grid {
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 10px;
        }
    }

    .profile-image {
        width: 60px;
        height: 60px;
        margin: 0 auto 12px;
        border-radius: 50%;
        overflow: hidden;
        border: 2px solid #e0e0e0;
    }

    .profile-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1000;
    }

    .modal-content {
        position: relative;
        background-color: #fff;
        margin: 50px auto;
        padding: 20px;
        width: 90%;
        max-width: 600px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .close-modal {
        font-size: 24px;
        cursor: pointer;
        color: #666;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        color: var(--dark);
        font-size: 0.9rem;
    }

    .form-group input {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 0.9rem;
    }

    .form-group small {
        display: block;
        color: #666;
        font-size: 0.8rem;
        margin-top: 4px;
    }

    .form-row {
        display: flex;
        gap: 15px;
    }

    .form-row .form-group {
        flex: 1;
    }

    .form-actions {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        margin-top: 20px;
    }

    .btn-add-supplier {
        background: var(--blue);
        color: white;
        padding: 8px 16px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 5px;
        margin-bottom: 20px;
    }

    .btn-submit {
        background: #86E211;
        color: white;
        padding: 8px 16px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .btn-cancel {
        background: #f0f0f0;
        color: #333;
        padding: 8px 16px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .map-container {
        width: 100%;
        margin-top: 20px;
    }

    #map {
        width: 100%;
        height: 300px;
        border-radius: 8px;
    }

    .map-legend {
        margin-top: 1rem;
        display: flex;
        gap: 2rem;
        padding: 1rem;
        background: white;
        border-radius: 10px;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .legend-item i {
        font-size: 1.2rem;
        color: var(--main);
    }

</style>

<script>
function openSupplierRegistration() {
    document.getElementById('supplierRegistrationModal').style.display = 'block';
}

function closeSupplierRegistration() {
    document.getElementById('supplierRegistrationModal').style.display = 'none';
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('supplierRegistrationModal');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}

// Form validation
document.getElementById('supplierRegForm').addEventListener('submit', function(e) {
    e.preventDefault();
    // Add your validation logic here
    // If validation passes, submit the form
    this.submit();
});
</script>

<!-- Add Google Maps JavaScript API -->
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCdt_khahhXrKdrA8cLgKeQB2CZtde-_Vc&callback=initMap"></script>
<script>
function initMap() {
    // Factory location
    const factoryLocation = { lat: 6.2173037, lng: 80.2564385 };

    // Hardcoded supplier locations around the factory
    const suppliers = [
        { lat: 6.2273037, lng: 80.2664385, name: "John Doe", id: "1" },
        { lat: 6.2073037, lng: 80.2464385, name: "Jane Smith", id: "2" },
        { lat: 6.2373037, lng: 80.2564385, name: "Bob Wilson", id: "3" },
        { lat: 6.2173037, lng: 80.2764385, name: "Alice Brown", id: "4" },
        { lat: 6.1973037, lng: 80.2564385, name: "Charlie Davis", id: "5" },
        { lat: 6.2273037, lng: 80.2364385, name: "Eva Green", id: "6" },
        { lat: 6.2073037, lng: 80.2664385, name: "Frank White", id: "7" },
        { lat: 6.2373037, lng: 80.2464385, name: "Grace Lee", id: "8" },
        { lat: 6.2173037, lng: 80.2364385, name: "Henry Ford", id: "9" },
        { lat: 6.2273037, lng: 80.2764385, name: "Iris Chen", id: "10" }
    ];

    // Create map centered on factory
    const map = new google.maps.Map(document.getElementById("map"), {
        zoom: 13.5,
        center: factoryLocation,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        mapTypeControl: false,
        streetViewControl: false,
        fullscreenControl: false
    });

    // Add factory marker
    new google.maps.Marker({
        position: factoryLocation,
        map: map,
        title: "Factory Location",
        icon: {
            url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent('<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"><path fill="#007664" d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5S10.62 6.5 12 6.5s2.5 1.12 2.5 2.5S13.38 11.5 12 11.5z"/></svg>'),
            scaledSize: new google.maps.Size(32, 32)
        }
    });

    // Add supplier markers
    suppliers.forEach(supplier => {
        new google.maps.Marker({
            position: { lat: supplier.lat, lng: supplier.lng },
            map: map,
            title: supplier.name,
            icon: {
                url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent('<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"><path fill="#FF0000" d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5S10.62 6.5 12 6.5s2.5 1.12 2.5 2.5S13.38 11.5 12 11.5z"/></svg>'),
                scaledSize: new google.maps.Size(32, 32)
            }
        });
    });
}

// Initialize map when page loads
window.onload = initMap;
</script>