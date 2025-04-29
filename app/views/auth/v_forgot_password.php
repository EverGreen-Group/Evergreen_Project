<?php require APPROOT . '/views/inc/components/header_public.php'; ?>

<main>
  <div class="auth-container">
    <div class="auth-form-section">
      <div class="auth-form-container">
        <h2>Forgot Password</h2>

        <!-- Error / Success Messages -->
        <?php if (!empty($data['error'])): ?>
          <div class="auth-error"><?= $data['error']; ?></div>
        <?php endif; ?>
        <?php if (!empty($data['success'])): ?>
          <div class="auth-success"><?= $data['success']; ?></div>
        <?php endif; ?>

        <!-- Email Input or OTP Form -->
        <?php if (empty($data['otp_sent'])): ?>
          <form action="<?= URLROOT ?>/auth/forgotPassword" method="POST">
            <div class="auth-form-group">
              <label for="email">Email Address</label>
              <input
                type="email"
                id="email"
                name="email"
                required
                placeholder="you@example.com"
                value="<?= htmlspecialchars($data['email'] ?? '', ENT_QUOTES); ?>"
              >
            </div>
            <button type="submit" class="auth-button">Send Verification Code</button>
        <?php else: ?>
          <form action="<?= URLROOT ?>/auth/forgotPassword" method="POST">
            <div class="auth-form-group">
              <label for="otp">Verification Code</label>
              <input
                type="text"
                id="otp"
                name="otp"
                required
                placeholder="6-digit code"
              >
              <small class="form-text">A code has been sent to your email.</small>
            </div>
            <button type="submit" class="auth-button">Verify Code</button>
        <?php endif; ?>
        
        </form>

        <div class="auth-footer">
          <a href="<?= URLROOT ?>/auth/login">&larr; Back to Sign In</a>
        </div>
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
    top: 0; left: 0; right: 0; bottom: 0;
    background-image: url('<?= URLROOT; ?>/public/img/factory_landscape.png');
    background-size: cover;
    background-position: center;
    z-index: -1;
  }

  .auth-container {
    background-color: rgba(0,0,0,0.4);
    padding: 2rem;
    border-radius: 0.5rem;
    max-width: 400px;
    margin: 100px auto;
    position: relative;
    z-index: 1;
  }

  .auth-form-section h2 {
    text-align: center;
    margin-bottom: 1rem;
  }

  .auth-error, .auth-success {
    padding: 0.75rem;
    margin-bottom: 1rem;
    border-radius: 0.25rem;
    text-align: center;
  }
  .auth-error {
    background: rgba(255, 0, 0, 0.2);
    color: #ffdddd;
  }
  .auth-success {
    background: rgba(0, 255, 0, 0.2);
    color: #ddffdd;
  }

  .auth-form-group {
    margin-bottom: 1rem;
  }
  .auth-form-group label {
    display: block;
    margin-bottom: 0.25rem;
    font-size: 0.9rem;
  }
  .auth-form-group input {
    width: 100%;
    padding: 0.75rem;
    border: none;
    border-radius: 0.25rem;
    font-size: 1rem;
  }
  .auth-form-group small.form-text {
    display: block;
    margin-top: 0.25rem;
    font-size: 0.8rem;
    color: #ccc;
  }

  .auth-button {
    width: 100%;
    padding: 0.75rem;
    font-size: 1rem;
    background-color: #007bff;
    border: none;
    border-radius: 0.25rem;
    cursor: pointer;
    color: #fff;
    margin-top: 0.5rem;
  }
  .auth-button:hover {
    opacity: 0.9;
  }

  .auth-footer {
    text-align: center;
    margin-top: 1rem;
    font-size: 0.9rem;
  }
  .auth-footer a {
    color: #fff;
    text-decoration: underline;
  }
</style>

<?php require APPROOT . '/views/includes/footer.php'; ?>
