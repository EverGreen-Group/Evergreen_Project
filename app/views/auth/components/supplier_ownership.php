<h3>Ownership Details</h3>
<div class="auth-form-row">
    <div class="auth-form-group">
        <label for="ownership_type">Type of Ownership</label>
        <select id="ownership_type" name="ownership_type" class="auth-select" required>
            <option value="">Select Ownership Type</option>
            <option value="Private Owner">Private Owner</option>
            <option value="Joint Owner">Joint Owner</option>
            <option value="Lease Holder">Lease Holder</option>
            <option value="Government Permit">Government Permit</option>
            <option value="Other">Other</option>
        </select>
    </div>
    <div class="auth-form-group">
        <label for="ownership_duration">Years of Ownership</label>
        <input type="number" id="ownership_duration" name="ownership_duration" min="0" step="1" required>
        <small class="input-help">Number of years owned/leased</small>
    </div>
</div>

