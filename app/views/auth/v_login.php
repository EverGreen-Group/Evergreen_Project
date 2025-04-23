<?php require APPROOT . '/views/inc/components/header_public.php'; ?>

<main>
    <div class="auth-container">
        <div class="auth-form-section">
            <div class="auth-form-container">
                <h2>Welcome Back!</h2>
                <?php if (isset($data['error']) && !empty($data['error'])): ?>
                    <div class="auth-error"><?php echo $data['error']; ?></div>
                <?php endif; ?>
                <form action="<?php echo URLROOT; ?>/auth/login" method="POST">
                    <div class="auth-form-group">
                        <label for="username">Email</label>
                        <input type="email" id="username" name="username" required 
                               placeholder="username@email.com">
                    </div>
                    <div class="auth-form-group">
                        <label for="password">Password</label>
                        <div class="password-input">
                            <input type="password" id="password" name="password" required
                                   placeholder="••••••••">
                            <i class='bx bx-hide password-toggle'></i>
                        </div>
                    </div>
                    <div class="auth-options">
                        <div class="remember-me">
                            <input type="checkbox" id="remember" name="remember">
                            <label for="remember">Remember me</label>
                        </div>
                        <a href="<?php echo URLROOT; ?>/auth/forgotPassword" class="forgot-password">Forgot password?</a>
                    </div>
                    <button type="submit" class="auth-button">Sign In</button>
                    
                    <div class="auth-footer">
                        Don't have an account? <a href="<?php echo URLROOT; ?>/auth/register">Sign Up</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>


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