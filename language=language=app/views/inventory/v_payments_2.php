<div class="table-data">
    <div class="order">
        <div class="head">
            <h3>Factory Payments Report</h3>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Year</th>
                    <th>Month</th>
                    <th>No. of Suppliers</th>
                    <th>Total Kg Supplied</th>
                    <th>Total Payment</th>
                    <th>Normal Leaf Rate</th>
                    <th>Super Leaf Rate</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($payment_summary as $payment): ?>
                    <tr class="payment-row" data-payment-id="<?php echo htmlspecialchars($payment->id); ?>">
                        <td><?php echo htmlspecialchars($payment->year); ?></td>
                        <td><?php echo htmlspecialchars($payment->month_name); ?></td>
                        <td><?php echo htmlspecialchars($payment->total_suppliers); ?></td>
                        <td><?php echo htmlspecialchars($payment->total_kg); ?> kg</td>
                        <td>Rs. <?php echo htmlspecialchars(number_format($payment->total_payment, 2)); ?></td>
                        <td>Rs. <?php echo htmlspecialchars(number_format($payment->normal_leaf_rate, 2)); ?></td>
                        <td>Rs. <?php echo htmlspecialchars(number_format($payment->super_leaf_rate, 2)); ?></td>
                        <td>
                            <a 
                                href="<?php echo URLROOT; ?>/inventory/viewPaymentReport/<?php echo $payment->id; ?>" 
                                class="btn btn-primary"
                            >
                                <i class='bx bx-show'></i>
                                View
                            </a>
                            <a 
                                href="<?php echo URLROOT; ?>/inventory/deletePaymentReport/<?php echo $payment->id; ?>" 
                                class="btn btn-danger delete-payment"
                                onclick="return confirm('Are you sure you want to delete this payment report? This will also delete all associated payment details.');"
                            >
                                <i class='bx bx-trash'></i>
                                Delete
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div> 