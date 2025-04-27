<?php require APPROOT . '/views/inc/components/header_public.php'; ?>

<main>
    <div class="auth-container">
        <div class="auth-form-section">
            <div class="auth-form-container">
                <h2><?php echo isset($data['otp_sent']) && $data['otp_sent'] ? 'Verify OTP' : 'Create Account'; ?></h2>
                
                <?php if (isset($data['error']) && !empty($data['error'])): ?>
                    <div class="auth-error"><?php echo $data['error']; ?></div>
                <?php endif; ?>
                
                <?php if (isset($data['otp_sent']) && $data['otp_sent']): ?>
                    <!-- OTP Verification Form -->
                    <p class="otp-message">We've sent a verification code to your email address. Please enter the 6-digit code below.</p>
                    
                    <form action="<?php echo URLROOT; ?>/auth/register" method="POST">
                        <div class="auth-form-group">
                            <label for="otp">Verification Code</label>
                            <input type="text" id="otp" name="otp" required placeholder="Enter 6-digit code" 
                                   pattern="[0-9]{6}" maxlength="6">
                        </div>
                        
                        <button type="submit" class="auth-button">Verify & Complete Registration</button>
                        
                        <div class="auth-footer">
                            <a href="<?php echo URLROOT; ?>/auth/register">Start over</a>
                        </div>
                    </form>
                <?php else: ?>
                    <!-- Registration Form -->
                    <form action="<?php echo URLROOT; ?>/auth/register" method="POST">
                        <div class="auth-form-row">
                            <div class="auth-form-group">
                                <label for="first_name">First Name</label>
                                <input type="text" id="first_name" name="first_name" required 
                                       placeholder="First Name" value="<?php echo isset($data['first_name']) ? $data['first_name'] : ''; ?>">
                            </div>
                            <div class="auth-form-group">
                                <label for="last_name">Last Name</label>
                                <input type="text" id="last_name" name="last_name" required 
                                       placeholder="Last Name" value="<?php echo isset($data['last_name']) ? $data['last_name'] : ''; ?>">
                            </div>
                        </div>
                        
                        <div class="auth-form-row">
                            <div class="auth-form-group">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email" required 
                                       placeholder="username@email.com" 
                                       pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
                                       value="<?php echo isset($data['email']) ? $data['email'] : ''; ?>">
                            </div>
                        </div>
                        
                        <div class="auth-form-row">
                            <div class="auth-form-group">
                                <label for="nic">NIC Number</label>
                                <input type="text" id="nic" name="nic" required 
                                       placeholder="National ID Number" 
                                       value="<?php echo isset($data['nic']) ? $data['nic'] : ''; ?>">
                            </div>
                        </div>
                        
                        <div class="auth-form-row">
                            <div class="auth-form-group">
                                <label for="date_of_birth">Date of Birth</label>
                                <input type="date" id="date_of_birth" name="date_of_birth" required
                                       value="<?php echo isset($data['date_of_birth']) ? $data['date_of_birth'] : ''; ?>">
                            </div>
                        </div>
                        
                        <div class="auth-form-row">
                            <div class="auth-form-group">
                                <label for="contact_number">Contact Number</label>
                                <input type="tel" id="contact_number" name="contact_number" required 
                                       placeholder="Contact Number" 
                                       value="<?php echo isset($data['contact_number']) ? $data['contact_number'] : ''; ?>">
                            </div>
                        </div>
                        
                        <div class="auth-form-group">
                            <label for="password">Password</label>
                            <div class="password-input">
                                <input type="password" id="password" name="password" required placeholder="••••••••">
                                <i class='bx bx-hide password-toggle' onclick="togglePasswordVisibility()"></i>
                            </div>
                        </div>
                        
                        <button type="submit" class="auth-button">Continue</button>
                        
                        <div class="auth-footer">
                            Already have an account? <a href="<?php echo URLROOT; ?>/auth/login">Sign In</a>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<script>
function togglePasswordVisibility() {
    const passwordInput = document.getElementById('password');
    const passwordToggleIcon = document.querySelector('.password-toggle');

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        passwordToggleIcon.classList.remove('bx-hide');
        passwordToggleIcon.classList.add('bx-show'); // Change to show icon
    } else {
        passwordInput.type = 'password';
        passwordToggleIcon.classList.remove('bx-show');
        passwordToggleIcon.classList.add('bx-hide'); // Change to hide icon
    }
}
</script>

<style>
    body {
        position: relative; 
        overflow: hidden; 
        color: #fff; 
    }

        body::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-image: url('<?php echo URLROOT; ?>/public/img/factory_landscape.png');
        background-size: cover; 
        background-position: center; 
        background-repeat: no-repeat;
        /* filter: blur(8px);  */
        z-index: -1; 
    }

    .auth-container {
        background-color: rgba(0, 0, 0, 0.4); 
        padding: 0px;
        border-radius: 0px;
        position: relative; 
        z-index: 1; 
    }

    .auth-form-section {
        margin-top: 100px;
    }

    input[type="date"] {
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        width: 100%;
        font-size: 16px;
    }
    
    /* Ensure two column layout for name fields */
    .auth-form-row {
        display: flex;
        gap: 10px;
    }
    
    .auth-form-row .auth-form-group {
        flex: 1;
    }
    
    .otp-message {
        margin-bottom: 20px;
        text-align: center;
        color: #fff;
    }
</style>