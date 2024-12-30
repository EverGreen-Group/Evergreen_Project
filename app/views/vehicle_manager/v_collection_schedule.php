<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_vehicle_manager.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<!-- MAIN -->
<main>
    <div class="head-title">
        <div class="left">
            <h1>Collection Schedule Details</h1>
            <ul class="breadcrumb">
                <li><a href="#">Dashboard</a></li>
                <li><a href="#">Collection Schedule</a></li>
            </ul>
        </div>
    </div>

    <!-- Route Information -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Route Information</h3>
            </div>
            <div class="section-content">
                <div class="info-row">
                    <span class="label">Route Name:</span>
                    <span class="value"><?= htmlspecialchars($schedule->route_name); ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Week Number:</span>
                    <span class="value"><?= htmlspecialchars($schedule->week_number); ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Day:</span>
                    <span class="value"><?= htmlspecialchars($schedule->day); ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Vehicle Information -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Vehicle Information</h3>
            </div>
            <div class="section-content">
                <div class="info-row">
                    <span class="label">Vehicle Type:</span>
                    <span class="value"><?= htmlspecialchars($schedule->vehicle_type); ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Vehicle Number:</span>
                    <span class="value"><?= htmlspecialchars($schedule->license_plate); ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Vehicle Make:</span>
                    <span class="value"><?= htmlspecialchars($schedule->make); ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Vehicle Model:</span>
                    <span class="value"><?= htmlspecialchars($schedule->model); ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Manufacturing Year:</span>
                    <span class="value"><?= htmlspecialchars($schedule->manufacturing_year); ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Vehicle Color:</span>
                    <span class="value"><?= htmlspecialchars($schedule->color); ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Team Information -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Team Information</h3>
            </div>
            <div class="section-content">
                <div class="info-row">
                    <span class="label">Driver Names:</span>
                    <span class="value"><?= htmlspecialchars($schedule->driver_names); ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Partner Names:</span>
                    <span class="value"><?= htmlspecialchars($schedule->partner_names); ?></span>
                </div>
            </div>
        </div>
    </div>


</main>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>

<style>
    .table-data {
        margin-bottom: 24px;
    }

    .order {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .head {
        padding: 16px 20px;
        border-bottom: 1px solid #f0f0f0;
    }

    .head h3 {
        font-size: 16px;
        font-weight: 600;
        color: #2c3e50;
    }

    .section-content {
        padding: 8px 20px;
    }

    .info-row {
        display: flex;
        align-items: center;
        padding: 12px 0;
        transition: background-color 0.2s;
    }

    .info-row:hover {
        background-color: #f8f9fa;
    }

    .info-row .label {
        flex: 0 0 200px;
        font-size: 14px;
        color: #6c757d;
    }

    .info-row .value {
        flex: 1;
        font-size: 14px;
        color: #2c3e50;
    }
</style>