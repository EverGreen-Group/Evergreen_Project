<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_shop.php'; ?>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<main class="profile-container">
    <div class="head-title">
        <div class="left">
            <h1>My Profile</h1>
            <ul class="breadcrumb">
                <li><a href="<?php echo URLROOT; ?>/shop">Home</a></li>
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
                    <h2><?php echo $_SESSION['user_name']; ?></h2>
                    <p><?php echo $_SESSION['user_email']; ?></p>
                </div>
            </div>

            <div class="profile-details">
                <form action="<?php echo URLROOT; ?>/users/updateProfile" method="POST">
                    <div class="detail-group">
                        <label>Full Name</label>
                        <input type="text" name="name" value="<?php echo $_SESSION['user_name']; ?>" required>
                    </div>

                    <div class="detail-group">
                        <label>Email</label>
                        <input type="email" name="email" value="<?php echo $_SESSION['user_email']; ?>" required>
                    </div>

                    <div class="detail-group">
                        <label>Phone</label>
                        <input type="tel" name="phone" value="<?php echo isset($_SESSION['user_phone']) ? $_SESSION['user_phone'] : ''; ?>" 
                               pattern="[0-9]{10}" placeholder="Enter your phone number">
                    </div>

                    <div class="detail-group">
                        <label>Address</label>
                        <textarea name="address" placeholder="Enter your address"><?php echo isset($_SESSION['user_address']) ? $_SESSION['user_address'] : ''; ?></textarea>
                    </div>

                    <?php flash('profile_message'); ?>

                    <div class="button-group">
                        <button type="submit" class="btn-edit">Save Changes</button>
                        <a href="<?php echo URLROOT; ?>/users/changePassword" class="btn-password">Change Password</a>
                    </div>
                </form>
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

.breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    list-style: none;
}

.breadcrumb a {
    color: #666;
    text-decoration: none;
}

.breadcrumb .active {
    color: var(--main);
    font-weight: 500;
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
    background: white;
    transition: all 0.3s ease;
}

.detail-group input:focus,
.detail-group textarea:focus {
    border-color: var(--main);
    box-shadow: 0 0 0 2px rgba(0,104,55,0.1);
    outline: none;
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
    text-align: center;
    text-decoration: none;
}

.btn-edit {
    background: var(--main);
    color: white;
    border: none;
    flex: 1;
}

.btn-password {
    background: white;
    color: var(--main);
    border: 1px solid var(--main);
    flex: 1;
}

.btn-edit:hover {
    background: #005229;
}

.btn-password:hover {
    background: #f8f9fa;
}

/* Flash message styling */
.alert {
    padding: 12px;
    border-radius: 8px;
    margin-bottom: 20px;
    font-size: 14px;
}

.alert-success {
    background: #e6f4ea;
    color: #1e8e3e;
    border: 1px solid #1e8e3e;
}

.alert-danger {
    background: #fce8e6;
    color: #d93025;
    border: 1px solid #d93025;
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

    .btn-edit,
    .btn-password {
        width: 100%;
    }
}
</style>

<?php require APPROOT . '/views/inc/components/footer.php'; ?> 