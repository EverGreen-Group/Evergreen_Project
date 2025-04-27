<?php require APPROOT . '/views/inc/components/header.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<?php require APPROOT.'/views/inc/components/sidebar_vehicle_manager.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>



<main>
  <div class="head-title">
    <div class="left">
        <h1>All Appointments</h1>
        <ul class="breadcrumb">
            <li><a href="<?php echo URLROOT; ?>/manager/dashboard">Dashboard</a></li>
            <li><i class='bx bx-chevron-right'></i></li>
            <li><a href="<?php echo URLROOT; ?>/manager/appointments">Supplier Management</a></li>
            <li><i class='bx bx-chevron-right'></i></li>
            <li><a class="active" href="#">Removed Suppliers</a></li>
        </ul>
    </div>
    <div>
        <a href="<?php echo URLROOT; ?>/manager/supplier" class="btn">
            <i class='bx bx-arrow-back'></i>
            <span class="text">Back</span>
        </a>
    </div>
  </div>
  
  <div class="table-data">
    <div class="order">
      <div class="head">
        <h3>Removed Suppliers</h3>
      </div>
      <table id="appointmentTable">
        <thead>
          <tr>
            <th>Supplier ID</th>
            <th>Supplier Name</th>
            <th>Application ID</th>
            <th>Approved On</th>
            <th>Last Updated</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
            <?php if(isset($removed_suppliers) && !empty($removed_suppliers)): ?>
                <?php foreach ($removed_suppliers as $supplier): ?>
                    <tr>
                        <td><?php echo $supplier->supplier_id; ?></td>
                        <td><?php echo $supplier->supplier_name; ?></td>
                        <td><?php echo $supplier->application_id; ?></td>
                        <td><?php echo $supplier->approved_at; ?></td>
                        <td><?php echo $supplier->last_updated; ?></td>
                        <td>
                            <a 
                                href="<?php echo URLROOT; ?>/manager/restoreSupplier/<?php echo $supplier->supplier_id; ?>" 
                                class="btn btn-tertiary" 
                                style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border: none; background: none;" 
                                title="Restore Supplier"
                            >
                                <i class='bx bx-user-check' style="font-size: 24px; color:green;"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach;?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align: center;">No removed suppliers found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</main>

<style>
    /* Improved Table Styling */
    .table-data {
        width: 100%;
        margin-top: 1.5rem;
    }
    
    .order {
        width: 100%;
        background: #fff;
        padding: 24px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    
    .head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
    }
    
    .search-box {
        position: relative;
        width: 250px;
    }
    
    .search-box input {
        width: 100%;
        height: 36px;
        padding: 0 15px 0 40px;
        border: 1px solid #ddd;
        border-radius: 36px;
        outline: none;
    }
    
    .search-box i {
        position: absolute;
        left: 15px;
        top: 10px;
        color: #777;
    }
    
    table {
        width: 100%;
        border-collapse: collapse;
    }
    
    table th, table td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid #eee;
    }
    
    table th {
        font-weight: 600;
        background-color: #f8f9fa;
        color: #495057;
    }
    
    table tbody tr:hover {
        background-color: #f6f9ff;
    }
    
    /* Status Badge Styling */
    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        display: inline-block;
    }
    
    .accepted, .approved {
        background-color: #E8FFF3;
        color: #1BC5BD;
    }
    
    .pending {
        background-color: #FFF4DE;
        color: #FFA800;
    }
    
    .rejected {
        background-color: #FFE2E5;
        color: #F64E60;
    }
    
    /* Button Styling */
    .btn-view {
        display: inline-block;
        padding: 6px 10px;
        background-color: #3B5D50;
        color: white;
        border-radius: 4px;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .btn-view:hover {
        background-color: #2a4438;
    }
    
    .btn-download {
        display: flex;
        align-items: center;
    }
    
    .btn {
        display: flex;
        align-items: center;
        padding: 0.6rem 1rem;
        background: #3B5D50;
        color: white;
        border-radius: 5px;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .btn:hover {
        background: #2a4438;
    }
    
    .btn i {
        margin-right: 0.5rem;
    }
    
    /* Supplier Link Styling */
    .supplier-link {
        color: #3B5D50;
        text-decoration: none;
        font-weight: 500;
        display: flex;
        align-items: center;
    }
    
    .supplier-link i {
        margin-right: 5px;
    }
    
    .supplier-link:hover {
        text-decoration: underline;
    }
</style>


<?php require APPROOT . '/views/inc/components/footer.php'; ?>