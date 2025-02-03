<h3>Address Details</h3>
<div class="auth-form-group">
    <label for="line1">Address Line 1</label>
    <input type="text" id="line1" name="line1" required>
</div>

<div class="auth-form-group">
    <label for="line2">Address Line 2 (Optional)</label>
    <input type="text" id="line2" name="line2">
</div>

<div class="auth-form-row">
    <div class="auth-form-group">
        <label for="city">City</label>
        <input type="text" id="city" name="city" required>
    </div>
    <div class="auth-form-group">
        <label for="district">District</label>
        <select id="district" name="district" class="auth-select" required>
            <option value="">Select District</option>
            <option value="Kandy">Kandy</option>
            <option value="Nuwara Eliya">Nuwara Eliya</option>
            <option value="Badulla">Badulla</option>
            <option value="Ratnapura">Ratnapura</option>
            <option value="Galle">Galle</option>
            <option value="Matara">Matara</option>
            <option value="Kalutara">Kalutara</option>
            <option value="Other">Other</option>
        </select>
    </div>
</div>

<div class="auth-form-group">
    <label for="postalCode">Postal Code</label>
    <input type="text" id="postalCode" name="postalCode" required
        title="Enter valid 5-digit postal code">
    <small class="input-help">5-digit postal code</small>
</div>
