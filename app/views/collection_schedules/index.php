<div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Collection Schedules</h3>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Schedule ID</th>
                        <th>Route</th>
                        <th>Team</th>
                        <th>Vehicle</th>
                        <th>Shift</th>
                        <th>Week</th>
                        <th>Days</th>
                        <th>Created At</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(isset($data['schedules']) && !empty($data['schedules'])):  var_dump($data['schedules']); ?>
                        <?php foreach($data['schedules'] as $schedule): ?>
                            <tr>
                                <td>CS<?php echo str_pad($schedule->schedule_id, 3, '0', STR_PAD_LEFT); ?></td>
                                <td><?php echo $schedule->route_name; ?></td>
                                <td><?php echo $schedule->team_name; ?></td>
                                <td><?php echo $schedule->vehicle_number; ?></td>
                                <td><?php echo $schedule->shift_name; ?></td>
                                <td>Week <?php echo $schedule->week_number; ?></td>
                                <td><?php echo ucwords(str_replace(',', ', ', $schedule->days_of_week)); ?></td>
                                <td><?php echo date('Y-m-d H:i', strtotime($schedule->created_at)); ?></td>
                                <td>
                                    <form action="<?php echo URLROOT; ?>/collectionschedules/toggleActive" method="POST" style="display: inline;">
                                        <input type="hidden" name="schedule_id" value="<?php echo $schedule->schedule_id; ?>">
                                        <button type="submit" class="status-btn <?php echo $schedule->is_active ? 'active' : 'inactive'; ?>">
                                            <?php echo $schedule->is_active ? 'Active' : 'Inactive'; ?>
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <form action="<?php echo URLROOT; ?>/collectionschedules/delete" method="POST" style="display: inline;" 
                                          onsubmit="return confirm('Are you sure you want to delete this schedule?');">
                                        <input type="hidden" name="schedule_id" value="<?php echo $schedule->schedule_id; ?>">
                                        <button type="submit" class="delete-btn">
                                            <i class='bx bx-trash'></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="10" class="text-center">No schedules found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>