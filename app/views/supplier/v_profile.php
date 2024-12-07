<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_supplier.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<!-- MAIN -->
    <main>
        <div class="head-title">
            <div class="left">
                <h1>Supplier Profile</h1>
                <ul class="breadcrumb">
                    <li>
                        <a href="SupplyDashboard.php">Home ></a>
                    </li>
                    <li><i class='bx bx-chevron-right'></i></li>
                    <li>
                        <a class="active" href="#"> Profile</a>
                    </li>
                </ul>
            </div>
                
            <a href="<?php echo URLROOT; ?>/Supplier/changepassword/" >
                <button class="button">Change Password</button>
            </a>

        <div class="table-data">
            <div class="order">
                <div class="head">
                    <h3>Profile</h3>
                </div>
                <div class="profile-section">
                    <!-- Profile Picture Section -->
                    <div class="profile-picture-section">
                        <div class="profile-image-container">
                            <img src="../img/people.png" alt="Profile Picture" id="profileImage">
                        </div>
                    </div>
                    <div class="profile-info">
                        <label for="fullname">Full Name:</label>
                        <input type="text" id="fullname" name="fullname" class="input readonly" value="John Doe" readonly><br/>

                        <label for="name">Address:</label>
                        <input type="text" id="address" name="address" class="input" value="123 Tea Estate, Kandy" readonly><br/>

                        <label for="name">Email:</label>
                        <input type="text" id="email" name="email" class="input readonly" value="john.doe@teacraft.com" readonly><br/>

                        <label for="name">Contact:</label>
                        <input type="text" id="contact" name="contact" class="input" value="077-1234567" readonly><br/>

                        <label for="name">Supplier ID:</label>
                        <input type="text" id="supplierid" name="supplierid" class="input readonly" value="SUP001" readonly><br/>

                        <label for="name">Route No:</label>
                        <input type="text" id="route" name="route" class="input readonly" value="R123" readonly><br/>

                        <label for="name">Registered Date:</label>
                        <input type="text" id="date" name="date" class="input readonly" value="2024-01-15" readonly>
                    </div>
                    
                    <div class="accept-btn">
                        <a href="<?php echo URLROOT; ?>/Supplier/" class="button">
                            Cancel
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </main>
</section>
<style>
    .table-data .order {
        background: var(--light);
        padding: 24px;
        border-radius: 20px;
        margin-bottom: 24px;
    }

    .profile-picture-section {
        display: flex;
        justify-content: center;
        margin-bottom: 20px;
    }

    .profile-image-container {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        overflow: hidden;
        border: 3px solid var(--main);
    }

    .profile-image-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .profile-info label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: var(--dark);
    }

    .profile-info input {
        width: 100%;
        padding: 10px;
        border: 1px solid var(--grey);
        border-radius: 5px;
        font-size: 14px;
        background: var(--light);
        margin-bottom: 10px;
    }

    .profile-info .readonly {
        background-color: #f5f5f5;
        color: #666;
        cursor: not-allowed;
    }

    .button-container {
        display: flex;
        justify-content: center;
        margin-top: 20px;
    }

    .button {
        background: var(--main);
        color: var(--light);
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 14px;
        text-decoration: none;
        text-align: center;
    }

    .button:hover {
        background: var(--main-dark);
    }

    .qr-section {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 20px;
        text-align: center;
    }

    .qr-image {
        width: 200px;
        height: 200px;
        margin-bottom: 15px;
    }

    .qr-section p {
        color: var(--dark);
        font-size: 14px;
    }

    @media screen and (max-width: 360px) {
        .table-data .order {
            padding: 15px;
        }

        .profile-image-container {
            width: 120px;
            height: 120px;
        }

        .profile-info input {
            font-size: 12px;
        }

        .button {
            width: 100%;
            max-width: 200px;
        }

        .qr-image {
            width: 150px;
            height: 150px;
        }
    }
</style>
<script src="<?php echo URLROOT; ?>/css/script.js"></script>
</body>
</html>
