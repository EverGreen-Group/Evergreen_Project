<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_inventory.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/inventory/payments_report.css">
<script>
    const URLROOT = '<?php echo URLROOT; ?>';
</script>

<!-- MAIN -->
<main>
  <?php 
      $totalAmount = 0;
      if(!empty($payments)) {
          foreach($payments as $payment) {
              $totalAmount += $payment->total_payment;
          }
      }
  ?>
    <div class="head-title">
        <div class="left">
            <h1>Payment Report for <?php echo date("F Y", strtotime($selectedMonth)); ?></h1>
            <ul class="breadcrumb">
                <li><a href="#">Dashboard</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">Payment Report</a></li>
            </ul>
        </div>
        <div class="head-actions">
            <button id="downloadPdf" class="btn-download">
                <i class='bx bx-download'></i> Download PDF
            </button>
        </div>
    </div>


    <div class="table-data" id="reportContent">
        <div class="order">
            <div class="head">
                <h3>Payment Details</h3>
                <p>Period: <?php echo $startDate; ?> to <?php echo $endDate; ?></p>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Supplier ID</th>
                        <th>Name</th>
                        <th>Bank Holder</th>
                        <th>Bank Name</th>
                        <th>Bank Branch</th>
                        <th>Account No</th>
                        <th>Total Bags</th>
                        <th>Total Weight (kg)</th>
                        <th>Total Payment (Rs.)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($payments)) : ?>
                        <?php foreach($payments as $payment) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($payment->supplier_id); ?></td>
                                <td><?php echo htmlspecialchars($payment->supplier_name); ?></td>
                                <td><?php echo htmlspecialchars($payment->account_holder_name); ?></td>
                                <td><?php echo htmlspecialchars($payment->bank_name); ?></td>
                                <td><?php echo htmlspecialchars($payment->branch_name); ?></td>
                                <td><?php echo htmlspecialchars($payment->account_number); ?></td>
                                <td><?php echo htmlspecialchars($payment->total_bags); ?></td>
                                <td><?php echo htmlspecialchars($payment->total_weight); ?></td>
                                <td><?php echo number_format($payment->total_payment, 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="6" class="empty-table">No records found for this period.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="6"><strong>Total</strong></td>
                        <td>
                            <?php 
                                $totalBags = 0;
                                if(!empty($payments)) {
                                    foreach($payments as $payment) {
                                        $totalBags += $payment->total_bags;
                                    }
                                }
                                echo $totalBags;
                            ?>
                        </td>
                        <td>
                            <?php 
                                $totalWeight = 0;
                                if(!empty($payments)) {
                                    foreach($payments as $payment) {
                                        $totalWeight += $payment->total_weight;
                                    }
                                }
                                echo $totalWeight;
                            ?>
                        </td>
                        <td><strong><?php echo number_format($totalAmount, 2); ?></strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</main>

<!-- Include jsPDF and html2pdf libraries -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
    document.getElementById('downloadPdf').addEventListener('click', function () {
        const element = document.getElementById('reportContent');
        const opt = {
            margin: 0.5,
            filename: 'payment_report_<?php echo $selectedMonth; ?>.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2 },
            jsPDF: { unit: 'in', format: 'letter', orientation: 'landscape' }
        };
        html2pdf().set(opt).from(element).save();
    });
</script>

<?php 
// Add additional stylesheet link for payments report specific styling
echo '<link rel="stylesheet" href="' . URLROOT . '/public/css/payments-report.css">';
require APPROOT . '/views/inc/components/footer.php'; 
?>