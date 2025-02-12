<h3>Infrastructure Details</h3>
<div class="auth-form-group">
    <label for="water_source">Water Sources</label>
    <div class="checkbox-group">
        <label class="checkbox-item">
            <input type="checkbox" name="water_source[]" value="Natural Spring">
            <span>Natural Spring</span>
        </label>
        <label class="checkbox-item">
            <input type="checkbox" name="water_source[]" value="Well">
            <span>Well</span>
        </label>
        <label class="checkbox-item">
            <input type="checkbox" name="water_source[]" value="Stream/River">
            <span>Stream/River</span>
        </label>
        <label class="checkbox-item">
            <input type="checkbox" name="water_source[]" value="Rain Water">
            <span>Rain Water</span>
        </label>
        <label class="checkbox-item">
            <input type="checkbox" name="water_source[]" value="Public Water Supply">
            <span>Public Water Supply</span>
        </label>
    </div>
</div>

<div class="auth-form-row">
    <div class="auth-form-group">
        <label for="access_road">Access Road Type</label>
        <select id="access_road" name="access_road" class="auth-select" required>
            <option value="">Select Road Type</option>
            <option value="Paved Road">Paved Road</option>
            <option value="Gravel Road">Gravel Road</option>
            <option value="Estate Road">Estate Road</option>
            <option value="Footpath Only">Footpath Only</option>
        </select>
    </div>
    <div class="auth-form-group">
        <label for="vehicle_access">Vehicle Accessibility</label>
        <select id="vehicle_access" name="vehicle_access" class="auth-select" required>
            <option value="">Select Access Type</option>
            <option value="All Weather Access">All Weather Access</option>
            <option value="Fair Weather Only">Fair Weather Only</option>
            <option value="Limited Access">Limited Access</option>
            <option value="No Vehicle Access">No Vehicle Access</option>
        </select>
    </div>
</div>

<div class="auth-form-group">
    <label for="structures">Available Structures</label>
    <div class="checkbox-group">
        <label class="checkbox-item">
            <input type="checkbox" name="structures[]" value="Storage Facility">
            <span>Storage Facility</span>
        </label>
        <label class="checkbox-item">
            <input type="checkbox" name="structures[]" value="Worker Rest Area">
            <span>Worker Rest Area</span>
        </label>
        <label class="checkbox-item">
            <input type="checkbox" name="structures[]" value="Equipment Storage">
            <span>Equipment Storage</span>
        </label>
        <label class="checkbox-item">
            <input type="checkbox" name="structures[]" value="Living Quarters">
            <span>Living Quarters</span>
        </label>
        <label class="checkbox-item">
            <input type="checkbox" name="structures[]" value="None">
            <span>None</span>
        </label>
    </div>
</div>
