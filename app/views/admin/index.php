<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_shop.php'; ?>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<main class="admin-container">
    <div class="head-title">
        <div class="left">
            <h1>Admin Dashboard</h1>
            <ul class="breadcrumb">
                <li><a href="<?php echo URLROOT; ?>">Home</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">Dashboard</a></li>
            </ul>
        </div>
    </div>

    <div class="dashboard-stats">
        <div class="stat-card">
            <div class="stat-content">
                <i class='bx bx-shopping-bag'></i>
                <div class="stat-info">
                    <h3>Total Orders</h3>
                    <p>2,150</p>
                </div>
            </div>
            <div class="stat-footer">
                <span class="trend positive">
                    <i class='bx bx-up-arrow-alt'></i>15%
                </span>
                <span class="period">vs last month</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-content">
                <i class='bx bx-user'></i>
                <div class="stat-info">
                    <h3>Total Users</h3>
                    <p>850</p>
                </div>
            </div>
            <div class="stat-footer">
                <span class="trend positive">
                    <i class='bx bx-up-arrow-alt'></i>10%
                </span>
                <span class="period">vs last month</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-content">
                <i class='bx bx-dollar'></i>
                <div class="stat-info">
                    <h3>Revenue</h3>
                    <p>$24,500</p>
                </div>
            </div>
            <div class="stat-footer">
                <span class="trend positive">
                    <i class='bx bx-up-arrow-alt'></i>20%
                </span>
                <span class="period">vs last month</span>
            </div>
        </div>
    </div>

    <div class="dashboard-content">
        <div class="content-card recent-orders">
            <div class="card-header">
                <h2>Recent Orders</h2>
                <a href="#" class="btn-view">View All</a>
            </div>
            <div class="card-body">
                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>#ORD-2024-001</td>
                            <td>John Doe</td>
                            <td>$150.00</td>
                            <td><span class="status completed">Completed</span></td>
                            <td>2024-03-15</td>
                        </tr>
                        <tr>
                            <td>#ORD-2024-002</td>
                            <td>Jane Smith</td>
                            <td>$250.00</td>
                            <td><span class="status pending">Pending</span></td>
                            <td>2024-03-14</td>
                        </tr>
                    </tbody>
                </table>
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

.admin-container {
    padding: 24px;
    max-width: 1200px;
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
}

.breadcrumb a {
    color: #666;
    text-decoration: none;
}

.breadcrumb .active {
    color: var(--main);
}

.dashboard-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 24px;
    margin-bottom: 24px;
}

.stat-card {
    background: white;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.stat-content {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 16px;
}

.stat-content i {
    font-size: 32px;
    color: var(--main);
}

.stat-info h3 {
    font-size: 14px;
    color: #666;
    margin-bottom: 4px;
}

.stat-info p {
    font-size: 24px;
    font-weight: 600;
    color: var(--dark);
}

.stat-footer {
    display: flex;
    align-items: center;
    gap: 8px;
}

.trend {
    display: flex;
    align-items: center;
    font-size: 12px;
    font-weight: 500;
}

.trend.positive {
    color: #2ecc71;
}

.period {
    font-size: 12px;
    color: #666;
}

.content-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.card-header h2 {
    font-size: 18px;
    font-weight: 600;
}

.btn-view {
    padding: 6px 12px;
    background: var(--main);
    color: white;
    border-radius: 6px;
    text-decoration: none;
    font-size: 14px;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #eee;
}

th {
    font-weight: 600;
    color: #666;
}

.status {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
}

.status.completed {
    background: #e6f4ea;
    color: #1e8e3e;
}

.status.pending {
    background: #fef7e6;
    color: #f9a825;
}

@media screen and (max-width: 768px) {
    .admin-container {
        padding: 16px;
    }

    .dashboard-stats {
        grid-template-columns: 1fr;
    }

    table {
        display: block;
        overflow-x: auto;
    }
}
</style>

<?php require APPROOT . '/views/inc/components/footer.php'; ?> 