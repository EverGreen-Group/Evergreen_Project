<h3>Tea Cultivation Details</h3>
<div class="auth-form-group">
    <label for="teaVarieties">Tea Varieties</label>
    <div class="checkbox-group">
        <label class="checkbox-item">
            <input type="checkbox" name="tea_varieties[]" value="TRI 2023">
            <span>TRI 2023</span>
        </label>
        <label class="checkbox-item">
            <input type="checkbox" name="tea_varieties[]" value="TRI 2025">
            <span>TRI 2025</span>
        </label>
        <label class="checkbox-item">
            <input type="checkbox" name="tea_varieties[]" value="TRI 2026">
            <span>TRI 2026</span>
        </label>
        <label class="checkbox-item">
            <input type="checkbox" name="tea_varieties[]" value="TRI 3013">
            <span>TRI 3013</span>
        </label>
        <label class="checkbox-item">
            <input type="checkbox" name="tea_varieties[]" value="Other">
            <span>Other</span>
        </label>
    </div>
</div>

<div class="auth-form-row">
    <div class="auth-form-group">
        <label for="plant_age">Average Plant Age (years)</label>
        <input type="number" id="plant_age" name="plant_age" min="0" max="100" step="0.5" required>
        <small class="input-help">Age of tea plants (0-100 years)</small>
    </div>
    <div class="auth-form-group">
        <label for="monthly_production">Monthly Production (kg)</label>
        <input type="number" id="monthly_production" name="monthly_production" min="0" max="10000" step="0.5" required>
        <small class="input-help">Average monthly tea leaf production in kilograms</small>
    </div>
</div> 