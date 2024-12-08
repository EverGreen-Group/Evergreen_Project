<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_suppliermanager.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<!-- MAIN -->
        <main>
            <h1>Settings</h1>
            <br/>
            <ul class="breadcrumb">
                    <li><a href="SupplementDashboard.php">Home</a>
                    <i class='bx bx-chevron-right'></i>
                    <a class="active" href="#">Dashboard</a></li>
                </ul>
            <br/>
            <div class="settings-container">    
                <section class="settings-section">
                <h3>Profile Settings</h3>
                <br/>
                    <p>Update profile</p>
                    <a href="<?php echo URLROOT; ?>/Supplier/profile/" >
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
    <script src="<?php echo URLROOT; ?>/css/components/script.js"></script>
    
    <style>
        h1 {
            color: var(--dark);
        }
        a {
            color: var(--dark-grey);
        }
        #content main .breadcrumb .active {
            color: var(--main);
        }
        .settings-container {
            padding: 20px;
            background-color: var(--light);
            color: var(--dark);
            border-radius: 20px;
            width: 100%;
            text-align: left;
        }
        .settings-container h1 {
            font-size: 2rem;
            margin-bottom: 20px;
        }
        .settings-section {
            margin-bottom: 30px;
            margin-left: 50px;
        }
        .settings-section h2 {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }
        .select-textbox {
            width: 60%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid var(--grey);
            border-radius: 5px;
        }.button {
            background-color: #008000;
            width: 80px;
            height: 30px;
            border-radius: 5px;
            border: none;
            color: white;
        }
        .button:hover {
            background-color: #006400;
        }
    </style>
    </body>
</html>