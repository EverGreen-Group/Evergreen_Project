<?php require APPROOT . '/views/inc/components/header_public.php'; ?>

<main>
    <div class="auth-container">
        <div class="auth-form-section">
            <div class="auth-form-container">
                <h2>Forgot Password</h2>
                <form action="<?php echo URLROOT; ?>/auth/forgotPassword" method="POST">
                <div class="auth-form-row">
                    <div class="auth-form-group">
                        <label for="email">Enter your email address:</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                </div>
                <button type="submit" class="auth-button">Send Reset Link</button>

                </form>
                <?php if (!empty($data['error'])): ?>
                    <div class="error"><?php echo $data['error']; ?></div>
                <?php endif; ?>
                <?php if (!empty($data['success'])): ?>
                    <div class="success"><?php echo $data['success']; ?></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main> 