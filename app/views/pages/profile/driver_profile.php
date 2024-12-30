<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_vehicle_manager.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<main>
    <div class="head-title">
        <div class="left">
            <h1>Driver Profile</h1>
        </div>
    </div>

    <div class="table-data">
        <div class="order">
            <div class="profile-container">
                <div class="profile-header">
                    <div class="profile-image">
                        <img src="<?php echo URLROOT; ?>/uploads/user_photos/150.jpeg" alt="Profile Image" width="150" height="150">
                    </div>

                    <div class="head">
                        <h3><?php echo htmlspecialchars($data['userInfo']->first_name . ' ' . $data['userInfo']->last_name); ?></h3>
                        <span class="user-id">#<?php echo htmlspecialchars($data['userInfo']->user_id); ?></span>
                        <span class="status-badge <?php echo strtolower($data['userInfo']->driver_status); ?>">
                            <?php echo htmlspecialchars($data['userInfo']->driver_status); ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Personal Information</h3>
            </div>
            <div class="profile-container">
                <div class="profile-details">

                    <ul>
                        <li>
                            <span class="field-name">First Name:</span>
                            <span class="field-value"><?php echo htmlspecialchars($data['userInfo']->first_name); ?></span>
                        </li>
                        <li>
                            <span class="field-name">Last Name:</span>
                            <span class="field-value"><?php echo htmlspecialchars($data['userInfo']->last_name); ?></span>
                        </li>
                        <li>
                            <span class="field-name">Email:</span>
                            <span class="field-value"><?php echo htmlspecialchars($data['userInfo']->email); ?></span>
                        </li>
                        <li>
                            <span class="field-name">Phone:</span>
                            <span class="field-value"><?php echo htmlspecialchars($data['userInfo']->contact_number); ?></span>
                        </li>
                        <li>
                            <span class="field-name">Bio:</span>
                            <span class="field-value"><?php echo htmlspecialchars($data['userInfo']->driver_status); ?></span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Driver Address</h3>
            </div>
            <div class="profile-container">
                <div class="profile-details">

                    <ul>
                        <li>
                            <span class="field-name">Address Line 1:</span>
                            <span class="field-value"><?= htmlspecialchars($data['userInfo']->address_line1); ?></span>
                        </li>
                        <li>
                            <span class="field-name">Address Line 2:</span>
                            <span class="field-value"><?= htmlspecialchars($data['userInfo']->address_line2); ?></span>
                        </li>
                        <li>
                            <span class="field-name">City:</span>
                            <span class="field-value"><?= htmlspecialchars($data['userInfo']->city); ?></span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</main>

<style>

.profile-details {
    margin-top: 10px;
}

.profile-details h2 {
    font-size: 20px;
    margin-bottom: 10px;
    color: #2c3e50;
}

.profile-details ul {
    list-style-type: none;
    padding: 0;
}

.profile-details li {
    margin-bottom: 8px;
    font-size: 16px;
}

.profile-details li strong {
    color: #3498db;
}



.field-name {
    color: #000000;
    font-weight: bold;
    margin-right: 10px;
    display: inline-block;
    width: 150px;
}

.field-value {
    display: inline-block;
}

.label {
    font-weight: bold;
}

.value {
    margin-left: 10px;
}

.section-content {
    display: flex;
    flex-direction: column;
}

.info-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
}

.table-data {
    margin-bottom: 20px;
}

.status-badge {
    display: inline-block;
    padding: 2px 6px;
    border-radius: 8px;
    color: white;
    font-weight: bold;
    margin-top: 2px; /* Space between name and status */
}

.available {
    background-color: var(--main); /* Green for available */
}

.on-route {
    background-color: #ffc107; /* Yellow for on route */
}

.off-duty {
    background-color: #dc3545; /* Red for off duty */
}

.user-id {
    display: block;
    font-size: 14px;
    color: #6c757d;
    margin-top: 5px;
}

.profile-image {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 150px; /* Set the container width */
    height: 150px; /* Set the container height */
    overflow: hidden; /* Hide overflow */
    border-radius: 50%; /* Optional: circular shape */
}

.profile-image img {
    width: 100%; /* Make the image fill the container */
    height: 100%; /* Make the image fill the container */
    object-fit: cover; /* Cover the area without distortion */
}

.profile-header {
    display: flex; /* Use flexbox for layout */
    align-items: center; /* Center items vertically */
}

.user-id {
    display: block; /* Make it a block element */
    font-size: 14px; /* Adjust font size */
    color: #6c757d; /* Gray color for user ID */
    margin-top: 5px; /* Space between name and user ID */
}

</style>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>