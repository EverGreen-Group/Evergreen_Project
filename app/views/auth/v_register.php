<?php require APPROOT . '/views/inc/components/header_public.php'; ?>

<main>
    <div class="auth-container">
        <div class="auth-form-section">
            <div class="auth-form-container">
                <h2>Create Account</h2>
                
                <?php require APPROOT . '/views/auth/components/error_message.php'; ?>
                <?php require APPROOT . '/views/auth/components/register_form.php'; ?>
            </div>
        </div>
    </div>
</main>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>