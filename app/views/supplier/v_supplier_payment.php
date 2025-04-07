<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_supplier.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/supplier/supplier_payment/paymentstyles.css">

<main>
  <!-- Page Header -->

  <?php
  $data['all_payments'] = [
    (object)[
        'payment_id' => 'PAY001',
        'supplier_id' => 'SUP123',
        'payment_amount' => 25000.00,
        'payment_date' => '2024-03-15'
    ],
    (object)[
        'payment_id' => 'PAY002',
        'supplier_id' => 'SUP456',
        'payment_amount' => 18500.00,
        'payment_date' => '2024-03-12'
    ],
    (object)[
        'payment_id' => 'PAY003',
        'supplier_id' => 'SUP789',
        'payment_amount' => 32750.00,
        'payment_date' => '2024-03-10'
    ],
    (object)[
        'payment_id' => 'PAY004',
        'supplier_id' => 'SUP234',
        'payment_amount' => 15800.00,
        'payment_date' => '2024-03-08'
    ],
    (object)[
        'payment_id' => 'PAY005',
        'supplier_id' => 'SUP567',
        'payment_amount' => 42300.00,
        'payment_date' => '2024-03-05'
    ]
];

// Get the last 7 days data for normal and super leaf
$normalLeafData = [
    (object)[
        'date' => '2024-03-15',
        'quantity' => 120.50
    ],
    (object)[
        'date' => '2024-03-14',
        'quantity' => 145.75
    ],
    (object)[
        'date' => '2024-03-13',
        'quantity' => 98.25
    ],
    (object)[
        'date' => '2024-03-12',
        'quantity' => 156.00
    ],
    (object)[
        'date' => '2024-03-11',
        'quantity' => 134.50
    ],
    (object)[
        'date' => '2024-03-10',
        'quantity' => 167.25
    ],
    (object)[
        'date' => '2024-03-09',
        'quantity' => 143.75
    ]
];

$superLeafData = [
    (object)[
        'date' => '2024-03-15',
        'quantity' => 85.25
    ],
    (object)[
        'date' => '2024-03-14',
        'quantity' => 92.50
    ],
    (object)[
        'date' => '2024-03-13',
        'quantity' => 78.75
    ],
    (object)[
        'date' => '2024-03-12',
        'quantity' => 105.25
    ],
    (object)[
        'date' => '2024-03-11',
        'quantity' => 95.50
    ],
    (object)[
        'date' => '2024-03-10',
        'quantity' => 112.75
    ],
    (object)[
        'date' => '2024-03-09',
        'quantity' => 88.25
    ]
];
?>
  <div class="head-title">
    <div class="left">
      <h1>Payment History</h1>
      <ul class="breadcrumb">
        <li>
          <i class='bx bx-home'></i>
          <a href="<?php echo URLROOT; ?>/Supplier/dashboard/">Dashboard</a>
        </li>
        <li>
          <span>Payment History</span>
        </li>
      </ul>
    </div>
  </div>


  <!-- Payment Details Section -->
  <div class="payment-details-section">
    <div class="section-header">
      <h3>Payment Details</h3>
    </div>

    <div class="tab-container">
      <div class="tab-header">
        <button class="tab-btn active" data-tab="all-payments">All Payments</button>
      </div>

      <div class="tab-content">
        <!-- All Payments Tab -->
        <div class="tab-pane active" id="all-payments">
        <?php if (isset($data['all_payments']) && !empty($data['all_payments'])): ?>
          <div class="table-container">
            <table class="payment-table">
              <thead>
                <tr>
                  <th>Payment ID</th>
                  <th>Amount</th>
                  <th>Payment Date</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach($data['all_payments'] as $payment): ?>
                  <tr>
                    <td><?php echo $payment->payment_id; ?></td>
                    <td>Rs. <?php echo number_format($payment->payment_amount, 2); ?></td>
                    <td><?php echo date('M d, Y', strtotime($payment->payment_date)); ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php else: ?>
          <div class="no-records">
            <p>No payment records found!</p>
          </div>
        <?php endif; ?>
        </div>

      </div>
    </div>
  </div>
</main>



<style>

</style>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>

<script src="<?php echo URLROOT; ?>/public/css/script.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const reportCtx = document.getElementById("reportTypesChart");
    if (reportCtx) {
        const normalLeafData = <?php echo json_encode(array_column($normalLeafData, 'quantity')); ?>;
        const superLeafData = <?php echo json_encode(array_column($superLeafData, 'quantity')); ?>;
        const labels = <?php echo json_encode(array_map(function($date) {
            return date('D', strtotime($date->date));
        }, $normalLeafData)); ?>;

        new Chart(reportCtx, {
            type: "line",
            data: {
                labels: labels,
                datasets: [
                    {
                        label: "Normal Leaf",
                        data: normalLeafData,
                        backgroundColor: "rgba(54, 162, 235, 0.2)",
                        borderColor: "#36A2EB",
                        borderWidth: 2,
                        fill: false,
                        tension: 0.4,
                    },
                    {
                        label: "Super Leaf",
                        data: superLeafData,
                        backgroundColor: "rgba(255, 159, 64, 0.2)",
                        borderColor: "#FF9F40",
                        borderWidth: 2,
                        fill: false,
                        tension: 0.4,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: "top",
                        labels: { padding: 20, font: { size: 12 } },
                    },
                    title: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: "rgba(0, 0, 0, 0.1)" },
                        ticks: { callback: value => value + " kg" },
                        title: { display: true, text: "Stock (kg)" },
                    },
                    x: {
                        grid: { display: false },
                        title: { display: true, text: "Days of the Week" },
                    },
                },
            },
        });
    }
});
</script>