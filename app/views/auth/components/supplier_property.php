<h3>Property Details</h3>
<div class="auth-form-row">
    <div class="auth-form-group">
        <label for="totalLandArea">Total Land Area (acres)</label>
        <input type="number" id="totalLandArea" name="totalLandArea" min="0.25" step="0.25" required>
        <small class="input-help">Minimum 0.25 acres</small>
    </div>
    <div class="auth-form-group">
        <label for="teaCultivationArea">Tea Cultivation Area (acres)</label>
        <input type="number" id="teaCultivationArea" name="teaCultivationArea" min="0.25" step="0.25" required>
    </div>
</div>

<div class="auth-form-row">
    <div class="auth-form-group">
        <label for="elevation">Elevation (meters)</label>
        <input type="number" id="elevation" name="elevation" min="0" max="2500" step="1" required>
        <small class="input-help">Land elevation above sea level (0-2500m)</small>
    </div>
    <div class="auth-form-group">
        <label for="slope">Land Slope</label>
        <select id="slope" name="slope" class="auth-select" required>
            <option value="">Select Slope Type</option>
            <option value="Flat">Flat</option>
            <option value="Gentle">Gentle</option>
            <option value="Moderate">Moderate</option>
            <option value="Steep">Steep</option>
        </select>
    </div>
</div>
