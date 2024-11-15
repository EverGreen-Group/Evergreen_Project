<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_supplier.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<!-- MAIN -->
        <main>
            <h1>Settings</h1>
            <br/>
            <ul class="breadcrumb">
                    <li><a href="SupplyDashboard.php">Home</a>
                    <i class='bx bx-chevron-right'></i>
                    <a class="active" href="#"> > Settings</a></li>
                </ul>
            <br/>
            <div class="settings-container">    
                <section class="settings-section">
                <h3>Profile Settings</h3>
                <br/>
                    <p>Update profile</p>
                    <a href="Profile.php">
                        <button class="button">Profile</button>
                    </a>
                </section>

                <section class="settings-section">
                    <h3>Theme Settings</h3>
                    <br/>
                    <div class="theme-toggle">
                        <input type="checkbox" id="switch-mode" hidden>
                        <label for="switch-mode" class="switch-mode">Dark Mode</label>
                    </div>
                </section>

                <section class="settings-section">
                    <h3>Preferences</h3>
                        <br/>
                        <label for="language">Language</label>
                        <br/>
                        <select class="select-textbox">
                            <option value="en">English</option>
                            <option value="ot">...</option>
                        </select>
                </section>
            </div>
        </main>
    </div>

    <script src="../public/script.js"></script>
</body>
</html>