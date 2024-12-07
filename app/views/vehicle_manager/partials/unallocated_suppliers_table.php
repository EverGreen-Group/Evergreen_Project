<div class="table">
    <div class="table-header">
        <h2>Unallocated Suppliers</h2>
    </div>
    <div class="table-section">
        <table>
            <thead>
                <tr>
                    <th>Supplier Name</th>
                    <th>Contact Number</th>
                    <th>Address</th>
                    <th>Daily Capacity</th>
                    <th>Preferred Day</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($unallocatedSuppliers as $supplier): ?>
                    <tr>
                        <td><?php echo $supplier->name; ?></td>
                        <td><?php echo $supplier->contact_number; ?></td>
                        <td><?php echo $supplier->address; ?></td>
                        <td><?php echo $supplier->daily_capacity; ?> kg</td>
                        <td><?php echo $supplier->preferred_day; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div> 