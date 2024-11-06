<?php require APPROOT . '/views/inc/components/header_public.php'; ?>

<main>
    <div class="auth-container">
        <div class="auth-split">
            <!-- Left side with image and text -->
            <div class="auth-image-section" style="background-image: url('<?php echo URLROOT; ?>/public/img/factory_landscape.png');">

            </div>
            
            <!-- Right side with registration form -->
            <div class="auth-form-section">
                <div class="auth-form-container">
                    <h2>Create Account</h2>
                    <?php if (isset($data['error']) && !empty($data['error'])): ?>
                        <div class="auth-error"><?php echo $data['error']; ?></div>
                    <?php endif; ?>
                    <form action="<?php echo URLROOT; ?>/auth/register" method="POST">
                        <div class="auth-form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" required 
                                   placeholder="username@email.com">
                        </div>
                        <div class="auth-form-row">
                            <div class="auth-form-group">
                                <label for="title">Title</label>
                                <select id="title" name="title" class="auth-select" required>
                                    <option value="Mr.">Mr.</option>
                                    <option value="Mrs.">Mrs.</option>
                                    <option value="Ms.">Ms.</option>
                                </select>
                            </div>
                            <div class="auth-form-group">
                                <label for="gender">Gender</label>
                                <select id="gender" name="gender" class="auth-select" required>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                        </div>
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
                            <label for="nic">NIC</label>
                            <input type="text" id="nic" name="nic" required>
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
                        <button type="submit" class="auth-button">Create Account</button>
                        
                        <div class="auth-footer">
                            Already have an account? <a href="<?php echo URLROOT; ?>/auth/login">Sign In</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>