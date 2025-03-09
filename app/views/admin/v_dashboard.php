<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_admin.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<main>
  <div class="dashboard-container">
    <h1>Admin Dashboard</h1>
    <div class="card-grid">

    <a href="<?= URLROOT ?>/suppliermanager" class="card">
        <div class="card-icon"><i class='bx bxs-user'></i></div>
        <div class="card-title">Supplier Management</div>
      </a>

      <a href="<?= URLROOT ?>/vehiclemanager" class="card">
        <div class="card-icon"><i class='bx bxs-truck'></i></div>
        <div class="card-title">Collection Management</div>
      </a>
      
      <a href="<?= URLROOT ?>/inventory" class="card">
        <div class="card-icon"><i class='bx bxs-box'></i></div>
        <div class="card-title">Inventory Management</div>
      </a>
      

      

      
      <a href="<?= URLROOT ?>/delivery" class="card">
        <div class="card-icon"><i class='bx bxs-cart-download'></i></div>
        <div class="card-title">Order & Delivery Management</div>
      </a>
      
      <a href="<?= URLROOT ?>/admin/assignRoles" class="card">
        <div class="card-icon"><i class='bx bx-user-check'></i></div>
        <div class="card-title">Assign Roles</div>
      </a>
      
      <a href="<?= URLROOT ?>/reports" class="card">
        <div class="card-icon"><i class='bx bx-line-chart'></i></div>
        <div class="card-title">Reports</div>
      </a>
      
      <a href="<?= URLROOT ?>/admin/users" class="card">
        <div class="card-icon"><i class='bx bx-group'></i></div>
        <div class="card-title">User Management</div>
      </a>
      
      <a href="<?= URLROOT ?>/settings" class="card">
        <div class="card-icon"><i class='bx bx-cog'></i></div>
        <div class="card-title">System Settings</div>
      </a>
      
    </div>
  </div>
  <script src="<?php echo URLROOT; ?>/public/css/script.js"></script>
</main>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>

<style>
  .dashboard-container {
    padding: 3rem 2rem;
    max-width: 1400px;
    margin: auto;
  }
  .dashboard-container h1 {
    text-align: center;
    margin-bottom: 3rem;
    font-size: 2.5rem;
    color: #333;
  }
  .card-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
  }
  .card {
    background: #fff;
    padding: 2.5rem 1.5rem;
    text-align: center;
    border-radius: 10px;
    text-decoration: none;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }
  .card:hover {
    transform: translateY(-8px);
    box-shadow: 0 6px 12px rgba(0,0,0,0.15);
  }
  .card-icon {
    font-size: 4rem;
    margin-bottom: 1.5rem;
    color: var(--primary, #007bff);
  }
  .card-title {
    font-size: 1.5rem;
    font-weight: bold;
    color: var(--dark, #333);
  }
</style>


