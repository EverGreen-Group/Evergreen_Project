<h3>Bank Account Information</h3>

<div class="auth-form-group">
    <label for="accountHolderName">Account Holder Name</label>
    <input type="text" id="accountHolderName" name="accountHolderName" required>
    <small class="input-help">Name as per bank account</small>
</div>

<div class="auth-form-row">
    <div class="auth-form-group">
        <label for="bankName">Bank Name</label>
        <select id="bankName" name="bankName" class="auth-select" required>
            <option value="">Select Bank</option>
            <option value="Bank of Ceylon">Bank of Ceylon</option>
            <option value="People's Bank">People's Bank</option>
            <option value="Commercial Bank">Commercial Bank</option>
            <option value="Hatton National Bank">Hatton National Bank</option>
            <option value="Sampath Bank">Sampath Bank</option>
            <option value="Nations Trust Bank">Nations Trust Bank</option>
            <option value="Other">Other</option>
        </select>
    </div>
    <div class="auth-form-group">
        <label for="branchName">Branch Name</label>
        <input type="text" id="branchName" name="branchName" required>
    </div>
</div>

<div class="auth-form-row">
    <div class="auth-form-group">
        <label for="accountNumber">Account Number</label>
        <input type="text" id="accountNumber" name="accountNumber" pattern="^[0-9]{5,20}$" required>
        <small class="input-help">Enter account number without spaces</small>
    </div>
    <div class="auth-form-group">
        <label for="accountType">Account Type</label>
        <select id="accountType" name="accountType" class="auth-select" required>
            <option value="">Select Account Type</option>
            <option value="Savings">Savings</option>
            <option value="Current">Current</option>
        </select>
    </div>
</div> 