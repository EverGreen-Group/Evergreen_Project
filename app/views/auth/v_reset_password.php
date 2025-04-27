<?php require APPROOT . '/views/inc/components/header_public.php'; ?>

<main>
    <div class="auth-container">
        <div class="auth-form-section">
            <div class="auth-form-container">
                <h2>Reset Password</h2>
                <?php if (isset($data['error']) && !empty($data['error'])): ?>
                    <div class="auth-error"><?php echo $data['error']; ?></div>
                <?php endif; ?>
                <?php if (isset($data['success']) && !empty($data['success'])): ?>
                    <div class="auth-success"><?php echo $data['success']; ?></div>
                <?php endif; ?>
                <form action="<?php echo URLROOT; ?>/auth/resetPassword" method="POST">
                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($data['token']); ?>">
                    <div class="auth-form-group">
                        <label for="password">New Password:</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <div class="auth-form-group">
                        <label for="confirm_password">Confirm Password:</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                    </div>
                    <button type="submit" class="auth-button">Reset Password</button>
                </form>
            </div>
        </div>
    </div>
</main>