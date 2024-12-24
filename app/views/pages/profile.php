<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_vehicle_driver.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<main>
    <div class="head-title">
        <div class="left">
            <h1>My Profile</h1>
        </div>
    </div>

    <div class="table-data">
        <div class="order">
            <div class="profile-container">
                <div class="profile-header">
                    <div class="profile-image">
                        <img src="https://storage.googleapis.com/a1aa/image/s57c3tY6lhYmCtggGMBum1e2pWUGb5a3uaSrKcwbdsM10y8JA.jpg" alt="Profile Image">
                    </div>
                    <div class="profile-info">
                        <h2>Jack Adams</h2>
                        <p>Product Designer</p>
                        <p>Los Angeles, California, USA</p>
                        <a href="#" class="edit-profile">Edit Profile</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="table-data">
        <div class="order">
            <div class="profile-container">
                <div class="profile-details">
                    <h2>Personal Information</h2>
                    <ul>
                        <li>First Name: Jack</li>
                        <li>Last Name: Adams</li>
                        <li>Email: jackadams@gmail.com</li>
                        <li>Phone: (213) 555-1234</li>
                        <li>Bio: Product Designer</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="table-data">
        <div class="order">
            <div class="profile-container">
                <div class="profile-details">
                    <h2>Address</h2>
                    <ul>
                        <li>Country: United States of America</li>
                        <li>City/State: California, USA</li>
                        <li>Postal Code: 90210</li>
                        <li>TAX ID: AS564178969</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    



 
</main>

<style>


.profile-container {
    margin-bottom: 20px;
}

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

.profile-image {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 120px;
    height: 120px;
    overflow: hidden;
    border-radius: 50%;
}

.profile-image img {
    width: 100%;
    height: auto;
    object-fit: cover;
}
</style>




<?php require APPROOT . '/views/inc/components/footer.php'; ?>