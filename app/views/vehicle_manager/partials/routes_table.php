<div class="table">
    <div class="table-header">
        <h2>Routes</h2>
    </div>
    <div class="table-section">
        <table>
            <thead>
                <tr>
                    <th>Route Name</th>
                    <th>Number of Suppliers</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['allRoutes'] as $route): ?>
                    <tr>
                        <td><?php echo $route->route_name; ?></td>
                        <td><?php echo $route->number_of_suppliers; ?></td>
                        <td><?php echo $route->status; ?></td>
                        <td>
                            <button class="edit-route" data-route-id="<?php echo $route->route_id; ?>">Edit</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div> 