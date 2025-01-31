<?php require APPROOT . '/views/inc/components/header_public.php'; ?>

<main>
    <div class="auth-container">
        <div class="auth-form-section">
            <div class="auth-form-container">
                <h2>Create Account</h2>
                <?php if (isset($data['error']) && !empty($data['error'])): ?>
                    <div class="auth-error"><?php echo $data['error']; ?></div>
                <?php endif; ?>
                <form action="<?php echo URLROOT; ?>/auth/register" method="POST">

                    <div class="auth-form-row">
                        <div class="auth-form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" required placeholder="username@email.com" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$">
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
                    <div class="auth-form-row">
                        <div class="auth-form-group">
                            <label for="nic">NIC</label>
                            <input type="text" id="nic" name="nic" required>
                        </div>
                        <div class="auth-form-group">
                            <label for="date_of_birth">Date of Birth</label>
                            <input type="date" id="date_of_birth" name="date_of_birth" required placeholder="Select your date of birth" onfocus="this.showPicker()">
                        </div>
                    </div>
                    <div class="auth-form-group">
                        <label for="password">Password</label>
                        <div class="password-input">
                            <input type="password" id="password" name="password" required placeholder="••••••••">
                            <i class='bx bx-hide password-toggle' onclick="togglePasswordVisibility()"></i>
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
        position: relative; /* Position relative for the pseudo-element */
        overflow: hidden; /* Prevent overflow */
        color: #fff; /* Change text color to white for better contrast */
    }

    /* Pseudo-element for the background blur */
    body::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        /* background-image: url('https://www.kalukandahouse.com/static/3b61ff11b8f19c6bb4e3e4ebfb50dac1/47498/tea.jpg');  */
        background-size: cover; /* Cover the entire background */
        background-position: center; /* Center the image */
        background-repeat: no-repeat; /* Prevent the image from repeating */
        filter: blur(8px); /* Apply blur effect */
        z-index: -1; /* Place behind other content */
    }

    /* Optional: Style the form container for better visibility */
    .auth-container {
        background-color: rgba(0, 0, 0, 0.4); /* Semi-transparent background for the form */
        padding: 0px;
        border-radius: 0px;
        position: relative; /* Ensure it is above the blurred background */
        z-index: 1; /* Bring it above the blurred background */
    }

    /* Optional: Style the date input for better visibility */
    input[type="date"] {
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        width: 100%;
        font-size: 16px;
    }
</style>