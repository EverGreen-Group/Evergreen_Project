<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_shop.php'; ?>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<main class="profile-container">
    <div class="head-title">
        <div class="left">
            <h1>My Profile</h1>
            <ul class="breadcrumb">
                <li><a href="#">Home</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">Profile</a></li>
            </ul>
        </div>
    </div>

    <div class="profile-content">
        <div class="profile-card">
            <div class="profile-header">
                <div class="profile-avatar">
                    <img src="<?php echo URLROOT; ?>/img/default-avatar.png" alt="Profile">
                </div>
                <div class="profile-info">
                    <h2>John Doe</h2>
                    <p>john.doe@example.com</p>
                </div>
            </div>

            <div class="profile-details">
                <div class="detail-group">
                    <label>Full Name</label>
                    <input type="text" value="John Doe" disabled>
                </div>

                <div class="detail-group">
                    <label>Email</label>
                    <input type="email" value="john.doe@example.com" disabled>
                </div>

                <div class="detail-group">
                    <label>Phone</label>
                    <input type="tel" value="+1234567890" disabled>
                </div>

                <div class="detail-group">
                    <label>Address</label>
                    <textarea disabled>123 Street Name, City, Country</textarea>
                </div>

                <div class="button-group">
                    <button class="btn-edit">Edit Profile</button>
                    <button class="btn-password">Change Password</button>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
* {
    font-family: 'Poppins', sans-serif;
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

.profile-container {
    padding: 24px;
    max-width: 800px;
    margin: 0 auto;
}

.head-title {
    margin-bottom: 24px;
}

.head-title h1 {
    font-size: 24px;
    font-weight: 600;
    color: var(--dark);
    margin-bottom: 8px;
}

.profile-card {
    background: white;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}

.profile-header {
    display: flex;
    align-items: center;
    gap: 24px;
    margin-bottom: 32px;
    padding-bottom: 24px;
    border-bottom: 1px solid #eee;
}

.profile-avatar {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    overflow: hidden;
    border: 3px solid var(--main);
}

.profile-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.profile-info h2 {
    font-size: 20px;
    font-weight: 600;
    color: var(--dark);
    margin-bottom: 4px;
}

.profile-info p {
    color: #666;
}

.detail-group {
    margin-bottom: 20px;
}

.detail-group label {
    display: block;
    font-size: 14px;
    color: #666;
    margin-bottom: 8px;
}

.detail-group input,
.detail-group textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 14px;
    color: var(--dark);
    background: #f8f9fa;
}

.detail-group textarea {
    height: 100px;
    resize: none;
}

.button-group {
    display: flex;
    gap: 16px;
    margin-top: 32px;
}

.btn-edit,
.btn-password {
    padding: 12px 24px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-edit {
    background: var(--main);
    color: white;
    border: none;
}

.btn-password {
    background: white;
    color: var(--main);
    border: 1px solid var(--main);
}

.btn-edit:hover {
    background: #005229;
}

.btn-password:hover {
    background: #f8f9fa;
}

@media screen and (max-width: 768px) {
    .profile-container {
        padding: 16px;
    }

    .profile-header {
        flex-direction: column;
        text-align: center;
    }

    .button-group {
        flex-direction: column;
    }
}
</style>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>
