<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php if(RoleHelper::hasRole(5)): ?>
<?php require APPROOT . '/views/inc/components/sidebar_supplier.php'; ?>
<?php elseif(RoleHelper::hasRole(6)): ?>
<?php require APPROOT . '/views/inc/components/sidebar_vehicle_driver.php'; ?>
<?php elseif(RoleHelper::hasRole(12)): ?>
<?php require APPROOT . '/views/inc/components/sidebar_vehicle_manager.php'; ?>
<?php elseif(RoleHelper::hasRole(11)): ?>
<?php require APPROOT . '/views/inc/components/sidebar_inventory.php'; ?>
<?php endif; ?>

<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/supplier/profile/styles.css">

<main>
  <div class="head-title">
    <div class="left">
    <?php if(isset($data['supplier'])): ?>
      <h1>Supplier Profile</h1>
    <?php else: ?>
      <h1>Profile</h1>
      <?php endif; ?>
      <ul class="breadcrumb">
        <li>
          <i class='bx bx-home'></i>
          <a href="<?php echo URLROOT; ?>/Supplier/dashboard/">Dashboard</a>
        </li>
        <li>
          <span>Profile</span>
        </li>
      </ul>
    </div>
  </div>

  <?php flash('profile_message'); ?>

  <!-- Profile Photo Section -->
  <div class="profile-section">
    <div class="section-header">
      <h3>Profile Photo</h3>
    </div>
    <div class="profile-photo-container">
      <?php if(!empty($data['profile']->image_path)): ?>
        <div class="profile-image">
          <img src="<?php echo URLROOT . '/' . $data['profile']->image_path; ?>" alt="Profile Image">
        </div>
      <?php else: ?>
        <div class="profile-image no-image">
          <i class='bx bx-user'></i>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Profile Information Form -->
  <form method="POST" action="<?php echo URLROOT; ?>/auth/updateProfile" enctype="multipart/form-data">
    <!-- Personal Information Section -->
    <div class="profile-section">
      <div class="section-header">
        <h3>Personal Information</h3>
      </div>
      <div class="profile-container">
        <div class="table-wrapper">
          <table class="profile-table">
            <tbody>
              <tr>
                <td data-label="First Name">
                  <label for="first_name">First Name</label>
                  <input type="text" id="first_name" name="first_name" class="form-control" value="<?php echo $data['profile']->first_name; ?>" readonly>
                </td>
              </tr>
              <tr>
                <td data-label="Last Name">
                  <label for="last_name">Last Name</label>
                  <input type="text" id="last_name" name="last_name" class="form-control" value="<?php echo $data['profile']->last_name; ?>" readonly>
                </td>
              </tr>
              <tr>
                <td data-label="NIC">
                  <label for="nic">NIC</label>
                  <input type="text" id="nic" name="nic" class="form-control" value="<?php echo $data['profile']->nic; ?>" readonly>
                </td>
              </tr>
              <tr>
                <td data-label="Date of Birth">
                  <label for="date_of_birth">Date of Birth</label>
                  <input type="date" id="date_of_birth" name="date_of_birth" class="form-control" value="<?php echo $data['profile']->date_of_birth; ?>" readonly>
                </td>
              </tr>
              <?php if(isset($data['supplier'])): ?>
              <tr>
                <td data-label="Address">
                  <label for="address">Address</label>
                  <input type="text" id="address" name="address" class="form-control" value="<?php echo $data['supplier']->address; ?>" readonly>
                </td>
              </tr>
              <?php endif; ?>
              <tr>
                <td data-label="Profile Image">
                  <label for="image">Update Profile Image</label>
                  <input type="file" id="image" name="profile_image" class="form-control" accept="image/*">
                  <small class="form-text">Select a new image to update your profile photo</small>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Supplier Information Section -->
    <?php if(isset($data['supplier'])): ?>
    <div class="profile-section">
      <div class="section-header">
        <h3>Supplier Information</h3>
      </div>
      <div class="profile-container">
        <div class="table-wrapper">
          <table class="profile-table">
            <tbody>
              <tr>
                <td data-label="Contact Number">
                  <label for="supplier_contact">Supplier Contact Number</label>
                  <input type="tel" id="supplier_contact" name="supplier_contact" class="form-control" value="<?php echo $data['supplier']->contact_number; ?>" required>
                </td>
              </tr>
              <tr>
                <td data-label="Latitude">
                  <label for="latitude">Latitude</label>
                  <input type="text" id="latitude" name="latitude" class="form-control" value="<?php echo $data['supplier']->latitude; ?>" readonly>
                </td>
              </tr>
              <tr>
                <td data-label="Longitude">
                  <label for="longitude">Longitude</label>
                  <input type="text" id="longitude" name="longitude" class="form-control" value="<?php echo $data['supplier']->longitude; ?>" readonly>
                </td>
              </tr>
              <tr>
                <td data-label="Email">
                  <label for="email">Email</label>
                  <input type="email" id="email" name="email" class="form-control" value="<?php echo $data['user']->email; ?>" readonly>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <?php endif; ?>


    <!-- Submit Button for Profile Update -->
    <div class="form-actions">
      <button type="submit" class="update-btn">Update Profile</button>
    </div>
  </form>

  <!-- Password Change Section -->
  <div class="profile-section">
    <div class="section-header">
      <h3>Change Password</h3>
    </div>
    <div class="profile-container">
      <form method="POST" action="<?php echo URLROOT; ?>/auth/resetPassword">
        <div class="table-wrapper">
          <table class="profile-table">
            <tbody>
              <tr>
                <td data-label="Current Password">
                  <label for="current_password">Current Password</label>
                  <input type="password" id="current_password" name="current_password" class="form-control" required>
                </td>
              </tr>
              <tr>
                <td data-label="New Password">
                  <label for="new_password">New Password</label>
                  <input type="password" id="new_password" name="new_password" class="form-control" required>
                  <small class="form-text">Password must be at least 8 characters long and include letters and numbers</small>
                </td>
              </tr>
              <tr>
                <td data-label="Confirm New Password">
                  <label for="confirm_password">Confirm New Password</label>
                  <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="form-actions">
          <button type="submit" class="btn btn-secondary">Change Password</button>
        </div>
      </form>
    </div>
  </div>
</main>

<script src="<?php echo URLROOT; ?>/public/css/script.js"></script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>

<style>
  /* Root Variables */
  :root {
    --primary-color: var(--mainn);
    --secondary-color: #2ecc71;
    --text-primary: #2c3e50;
    --text-secondary: #7f8c8d;
    --background-light: #f8f9fa;
    --border-color: #e0e0e0;
    --completed-color: #2ecc71;
    --pending-color: #f39c12;
    --finalized-color: #3498db;
    --table-header-bg: #f5f5f5;
    --card-background: white;
    --spacing-xs: 0.25rem;
    --spacing-sm: 0.5rem;
    --spacing-md: 1rem;
    --spacing-lg: 1.5rem;
    --spacing-xl: 2rem;
    --border-radius-sm: 4px;
    --border-radius-md: 8px;
    --border-radius-lg: 12px;
    --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.1);
  }

  /* Layout & Common Styles */
  main {
    padding: var(--spacing-lg);
    max-width: 1200px;
    margin: 0 auto;
  }

  .head-title {
    margin-bottom: var(--spacing-xl);
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
  }

  .head-title h1 {
    color: var(--text-primary);
    font-size: 1.75rem;
    margin-bottom: var(--spacing-sm);
  }

  .breadcrumb {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    list-style: none;
    padding: 0;
  }

  .breadcrumb a {
    color: var(--text-secondary);
    text-decoration: none;
  }

  .breadcrumb i {
    color: var(--primary-color);
  }

  .section-header {
    margin-top: var(--spacing-lg);
    margin-bottom: var(--spacing-md);
  }

  .section-header h3 {
    font-size: 1.25rem;
    color: var(--text-primary);
  }

  /* Flash Messages */
  .alert {
    padding: var(--spacing-md);
    margin-bottom: var(--spacing-lg);
    border-radius: var(--border-radius-md);
    background-color: var(--secondary-color);
    color: white;
  }

  .alert-error {
    background-color: #e74c3c;
  }

  /* Profile Section */
  .profile-section {
    margin-bottom: var(--spacing-xl);
  }

  .profile-container {
    background-color: white;
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow-sm);
    overflow: hidden;
  }

  /* Profile Photo Container */
  .profile-photo-container {
    display: flex;
    justify-content: center;
    margin: var(--spacing-md) 0;
  }

  .profile-image {
    width: 200px;
    height: 200px;
    border-radius: 50%;
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: var(--background-light);
  }

  .profile-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  .profile-image.no-image {
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 5rem;
    color: var(--text-secondary);
  }

  .table-wrapper {
    width: 100%;
    overflow-x: auto;
  }

  .profile-table {
    width: 100%;
    border-collapse: collapse;
  }

  .profile-table td {
    padding: var(--spacing-md);
    text-align: left;
    border-bottom: 1px solid var(--border-color);
  }

  .profile-table tr:last-child td {
    border-bottom: none;
  }

  .form-control {
    width: 100%;
    padding: var(--spacing-sm);
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius-sm);
    font-size: 1rem;
    margin-top: var(--spacing-xs);
  }

  .form-control:focus {
    outline: none;
    border-color: var(--primary-color);
  }

  .form-control[readonly] {
    background-color: var(--background-light);
    cursor: not-allowed;
  }

  label {
    display: block;
    font-weight: 500;
    color: var(--text-primary);
    margin-bottom: var(--spacing-xs);
  }

  .form-text {
    display: block;
    margin-top: var(--spacing-xs);
    color: var(--text-secondary);
    font-size: 0.875rem;
  }

  .form-actions {
    display: flex;
    justify-content: flex-end;
    margin-top: var(--spacing-lg);
    margin-bottom: var(--spacing-lg);
  }

  .update-btn {
    background-color: var(--primary-color);
    color: white;
    border: none;
    padding: var(--spacing-sm) var(--spacing-lg);
    border-radius: var(--border-radius-sm);
    font-size: 1rem;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.3s;
  }

  .update-btn:hover {
    background-color: #219653;
  }

  .password-btn {
    background-color: #3498db;
  }

  .password-btn:hover {
    background-color: #2980b9;
  }

  /* Mobile Cards View */
  @media (max-width: 768px) {
    main {
      padding: var(--spacing-md);
    }
    
    .head-title {
      flex-direction: column;
      align-items: flex-start;
    }

    .profile-table, .profile-table tbody, .profile-table tr, .profile-table td {
      display: block;
      width: 100%;
    }

    .profile-table td {
      padding: var(--spacing-sm);
      text-align: left;
      position: relative;
      padding-left: 50%;
    }

    .profile-table td:before {
      content: attr(data-label);
      position: absolute;
      left: var(--spacing-sm);
      width: 45%;
      white-space: nowrap;
      font-weight: 600;
      color: var(--text-primary);
    }

    .form-actions {
      justify-content: center;
    }
  }

  /* Extra small devices */
  @media (max-width: 480px) {
    main {
      padding: var(--spacing-sm);
    }

    .head-title {
      margin-bottom: var(--spacing-md);
    }

    .head-title h1 {
      font-size: 1.5rem;
    }

    .section-header h3 {
      font-size: 1.125rem;
    }
  }
</style>