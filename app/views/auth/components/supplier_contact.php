<h3>Contact Details</h3>
<div class="auth-form-group">
    <label for="primaryPhone">Primary Phone Number</label>
    <input type="tel" 
           id="primaryPhone" 
           name="primaryPhone" 
           required 
           pattern="^(?:7|0)[0-9]{9}$"
           title="Enter valid Sri Lankan phone number" 
           placeholder="0771234567">
    <small class="input-help">Use Sri Lankan mobile number</small>
</div>

<div class="auth-form-group">
    <label for="secondaryPhone">Secondary Phone Number (Optional)</label>
    <input type="tel" 
           id="secondaryPhone" 
           name="secondaryPhone" 
           pattern="^(?:7|0)[0-9]{9}$"
           title="Enter valid Sri Lankan phone number" 
           placeholder="0771234567">
</div>

<div class="auth-form-group">
    <label for="whatsappNumber">WhatsApp Number (Optional)</label>
    <input type="tel" 
           id="whatsappNumber" 
           name="whatsappNumber" 
           pattern="^(?:7|0)[0-9]{9}$"
           placeholder="0771234567">
    <small class="input-help">Will be used for quick communications</small>
</div>
