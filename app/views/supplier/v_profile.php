<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_supplier.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<!-- MAIN -->
        <main>
			<div class="head-title">
				<div class="left">
					<h1>Tea Leaves Supplier</h1>
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
                
            <a href="ChangePassword.php">
                <button class="button">Change Password</button>
            </a>

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

                        <label for="name">Supplier ID:</label>
                        <input type="text" id="supplierid" name="supplierid" class="input" readonly><br/>

                        <label for="name">Route No:</label>
                        <input type="text" id="route" name="route" class="input" readonly><br/>

                        <label for="name">Registered Date:</label>
                        <input type="date" id="date" name="date" class="input" readonly>
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













<!--
<td><label for="fullname">Full Name:</label></td>
                            <td> <?php echo $row['full_name'] ?><td>
                        </tr>
                        <tr>
                            <td><label for="address">Address:</label></td>
                            <td> <?php echo $row['address'] ?><td>
                        </tr>
                        <tr>
                            <td><label for="email">Email:</label></td>
                            <td> <?php echo $row['email'] ?><td>
                        </tr>
                        <tr>
                            <td><label for="contact">Contact:</label></td>
                            <td> <?php echo $row['contact'] ?><td>
                        </tr>
                        <tr>
                            <td><label for="supplierid">Supplier ID:</label></td>
                            <td> <?php echo $row['supplier_id'] ?><td> 
                                </tr>
                        <tr>
                            <td><label for="route">Route No:</label></td>
                            <td> <?php echo $row['route_number'] ?><td>
                        </tr>
                        <tr>
                            <td><label for="date">Registered Date:</label></td>
                            <td> <?php echo $row['registered_date'] ?><td>   
-->






    

<!--<div class="profile-info">
    <label for="fullname">Full Name:</label>
    <input type="text" id="fullname" name="fullname" class="input" readonly><br/>

    <label for="name">Address:</label>
    <input type="text" id="address" name="address" class="input" readonly><br/>

    <label for="name">Email:</label>
    <input type="text" id="email" name="email" class="input" readonly><br/>

    <label for="name">Contact:</label>
    <input type="text" id="contact" name="contact" class="input" readonly><br/>

    <label for="name">Supplier ID:</label>
    <input type="text" id="supplierid" name="supplierid" class="input" readonly><br/>

    <label for="name">Route No:</label>
    <input type="text" id="route" name="route" class="input" readonly><br/>

    <label for="name">Registered Date:</label>
    <input type="date" id="date" name="date" class="input" readonly>
</div>
<button class="button" onclick="enableEditing()">Edit Profile</button>
<button class="button" onclick="enableEditing()">Save</button>-->