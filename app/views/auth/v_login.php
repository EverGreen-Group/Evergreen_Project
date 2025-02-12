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