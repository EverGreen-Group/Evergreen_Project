<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_vehicle_manager.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<!-- MAIN -->
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Supplement Manager Profile</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="SupplementDashboard.php">Home</a>
                        </li>
                        <li>
                            <i class='bx bx-chevron-right'></i>
                        </li>
                        <li>
                            <a class="active" href="#">Profile</a>
                        </li>
                    </ul>
                </div>
                
                <a href="<?php echo URLROOT; ?>/Supplier/changepassword/" >
                    <button class="button">Change Password</button>
                </a>
                </div>

            <div class="table-data">
                <div class="order">
                    <div class="head">
                        <h3>Profile</h3>
                    </div>
                    <div class="profile-info">
                        <label for="fullname">Full Name:</label>
                        <input type="text" id="fullname" name="fullname" class="input" readonly><br/>

                        <label for="name">Address:</label>
                        <input type="text" id="address" name="address" class="input" readonly><br/>

                        <label for="name">Email:</label>
                        <input type="text" id="email" name="email" class="input" readonly><br/>

                        <label for="name">Contact:</label>
                        <input type="text" id="contact" name="contact" class="input" readonly><br/>

                        <label for="name">Manager ID:</label>
                        <input type="text" id="supplierid" name="supplierid" class="input" readonly><br/>
                    </div> 

                    <button class="button" onclick="enableEditing()">Edit Profile</button>
                    <button class="button" onclick="enableEditing()">Save</button>
                </div> 
            </div> 
        </main>
    </div> 
    </section>
    <script src="<?php echo URLROOT; ?>/css/components/script.js"></script>
    </body>
</html>
    
