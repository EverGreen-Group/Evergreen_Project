<!-- Route Name -->
<div class="form-group">
    <label for="routeName">Route Name</label>
    <input type="text" 
           id="routeName" 
           name="routeName" 
           required 
           placeholder="Enter route name"
           class="form-control">
</div>

<!-- Vehicle Selection -->
<div class="form-group">
    <label for="vehicle">Select Vehicle</label>
    <select id="vehicle" name="vehicle" required>
        <option value="" disabled selected>Choose a vehicle</option>
        <?php foreach($data['vehicles'] as $vehicle): ?>
            <option value="<?php echo $vehicle->vehicle_id; ?>" 
                    data-capacity="<?php echo $vehicle->capacity; ?>">
                <?php echo $vehicle->license_plate . ' - ' . $vehicle->vehicle_type; ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

<!-- Capacity Tracker -->
<div id="capacityTracker" class="capacity-tracker" style="display: none;">
    <h4>Capacity Overview</h4>
    <div class="capacity-details">
        <p>Vehicle Capacity: <span id="totalCapacity">-</span> kg</p>
        <p>Used Capacity: <span id="usedCapacity">0</span> kg</p>
        <p>Remaining: <span id="remainingCapacity">-</span> kg</p>
    </div>
    <div class="capacity-bar">
        <div id="capacityFill" class="capacity-fill" style="width: 0%"></div>
    </div>
</div>

<!-- Vehicle Details -->
<div id="vehicleDetails" class="vehicle-details" style="display: none;">
    <h4>Vehicle Details</h4>
    <p>Capacity: <span id="vehicleCapacity">-</span> kg</p>
</div>