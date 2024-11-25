<form action="<?php echo URLROOT; ?>/auth/register" method="POST">
    <div class="auth-form-row">
        <div class="auth-form-group">
            <label for="first_name">First Name</label>
            <input type="text" id="first_name" name="first_name" required>
        </div>
        <div class="auth-form-group">
            <label for="last_name">Last Name</label>
            <input type="text" id="last_name" name="last_name" required>
        </div>
    </div>

    <div class="auth-form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required 
               placeholder="username@email.com">
    </div>

    <div class="auth-form-group">
        <label for="date_of_birth">Date of Birth</label>
        <input type="date" id="date_of_birth" name="date_of_birth" required>
    </div>

    <div class="auth-form-group">
        <label for="password">Password</label>
        <div class="password-input">
            <input type="password" id="password" name="password" required
                   placeholder="••••••••">
            <i class='bx bx-hide password-toggle'></i>
        </div>
    </div>

    <div class="auth-form-group">
        <label for="confirm_password">Confirm Password</label>
        <div class="password-input">
            <input type="password" id="confirm_password" name="confirm_password" required
                   placeholder="••••••••">
            <i class='bx bx-hide password-toggle'></i>
        </div>
    </div>

    <button type="submit" class="auth-button">Create Account</button>
    
    <div class="auth-footer">
        Already have an account? <a href="<?php echo URLROOT; ?>/auth/login">Sign In</a>
    </div>
</form> 