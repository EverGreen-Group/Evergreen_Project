<?php require APPROOT . '/views/inc/components/header_public.php'; ?>
<?php require_once APPROOT . '/helpers/RoleHelper.php'; ?>

<main class="landing-main">
    <div class="hero-section">
        <div class="hero-content">
            <div class="hero-text">
                <h1>Connecting <span class="highlight">Tea Leaves</span> Suppliers with Excellence</h1>
                <p>A modern platform designed for tea leaf suppliers to manage collections, track deliveries, and ensure quick communication with the factory for assistance.</p>
                <div class="hero-cta">
                    <a href="<?php echo URLROOT; ?>/auth/supplier_register/" class="cta-button">
                        <?php if ($hasSubmittedApplication): ?>
                            View Application Status
                        <?php else: ?>
                            Become a Supplier
                        <?php endif; ?>
                    </a>
                    <?php if (!isLoggedIn()): ?>
                    <a href="<?php echo URLROOT; ?>/auth/login" class="login-link">
                        Already registered? Sign in <i class='bx bx-right-arrow-alt'></i>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="hero-image">
                <img src="<?php echo URLROOT; ?>/public/uploads/hero-image2.png" alt="Tea Image">
            </div>
        </div>
    </div>

    <section class="features-section">
        <h2>Why Choose Our Platform?</h2>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <img src="<?php echo URLROOT; ?>/public/img/icons/announcement-icon.png" alt="Factory Announcements">
                </div>
                <h3>Factory Announcements</h3>
                <p>Stay updated with important factory announcements and policy changes</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <img src="<?php echo URLROOT; ?>/public/img/icons/payment-icon.png" alt="Payment Reports">
                </div>
                <h3>Detailed Payment Reports</h3>
                <p>Access comprehensive factory collection payment breakdowns and history</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <img src="<?php echo URLROOT; ?>/public/img/icons/chat-icon.png" alt="Direct Communication">
                </div>
                <h3>Direct Communication</h3>
                <p>Chat directly with factory managers for immediate assistance</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <img src="<?php echo URLROOT; ?>/public/img/icons/appointment-icon.png" alt="Appointment Booking">
                </div>
                <h3>Appointment Booking</h3>
                <p>Seamless booking system for factory appointments and consultations</p>
            </div>
        </div>
    </section>
</main>

<style>
    .landing-main {
        background: none;
        min-height: 100vh;
        padding: 0 5%;
        color: #000;
        margin-top: 100px;
    }

    .hero-section {
        padding: 120px 0 80px;
        max-width: 1200px;
        margin: 0 auto;
    }

    .hero-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
    }

    .hero-text {
        flex: 1;
        max-width: 50%;
    }

    .hero-image {
        flex: 1;
        text-align: center;
    }

    .hero-image img {
        transform: translateY(-15px);
        max-width: 100%;
        height: auto;
        border-radius: 8px;
    }

    .hero-text h1 {
        color: #000;
        font-size: 3.5rem;
        font-weight: 700;
        line-height: 1.2;
        margin-bottom: 1.5rem;
    }

    .hero-cta {
        display: flex;
        gap: 1rem;
        margin-top: 1.5rem;
    }

    .cta-button, .login-link {
        text-align: center;
    }

    .hero-stats {
        display: flex;
        gap: 2rem;
        margin-top: 2rem;
        justify-content: center;
    }

    .hero-text p {
        color: rgba(0, 0, 0, 0.9);
        font-size: 1.2rem;
        line-height: 1.6;
        margin-bottom: 2rem;
    }

    .hero-cta {
        display: flex;
        gap: 2rem;
        align-items: center;
    }

    .cta-button {
        background-color: #22a45d;
        color: #fff;
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        transition: background-color 0.3s ease;
    }

    .cta-button:hover {
        background-color: #1b8e4a;
    }

    .login-link {
        background-color: transparent;
        color: #22a45d;
        padding: 10px 20px;
        border: 2px solid #22a45d;
        border-radius: 8px;
        text-decoration: none;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    .login-link:hover {
        background-color: #22a45d;
        color: #fff;
    }

    .hero-stats {
        display: flex;
        gap: 2rem;
        margin-top: 2rem;
    }

    .stat-card {
        background: rgba(255, 255, 255, 0.1);
        padding: 1.5rem;
        border-radius: 12px;
        backdrop-filter: blur(10px);
        flex: 1;
        border: 2px solid #22a45d;
    }

    .stat-number {
        display: block;
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        color: #000;
        font-size: 1rem;
    }

    .features-section {
        background: url('<?php echo URLROOT; ?>/public/img/factory_landscape.png') center center/cover no-repeat;
        padding: 80px 0;
        border-radius: 30px 30px 0 0;
        margin-top: 40px;
        position: relative;
    }

    .features-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 30px 30px 0 0;
    }

    .features-section h2 {
        color: white;
        text-align: center;
        font-size: 2.5rem;
        margin-bottom: 3rem;
        position: relative;
        z-index: 1;
    }

    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 2rem;
        position: relative;
        z-index: 1;
    }

    .feature-card {
        background: #fff;
        padding: 2rem;
        border-radius: 16px;
        text-align: center;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .feature-card:hover {
        transform: translateY(-5px);
    }

    .feature-icon {
        width: 200px;
        height: 200px;
        margin: 0 auto 1.5rem;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .feature-icon img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .feature-card h3 {
        color: #333;
        font-size: 1.3rem;
        margin-bottom: 1rem;
    }

    .feature-card p {
        color: #666;
        line-height: 1.6;
    }

    @media (max-width: 768px) {
        .hero-text h1 {
            font-size: 2.5rem;
        }

        .hero-cta {
            flex-direction: column;
            align-items: stretch;
            gap: 1rem;
        }

        .hero-stats {
            flex-direction: column;
        }

        .features-grid {
            grid-template-columns: 1fr;
            padding: 0 1rem;
        }

        .cta-button, .login-link {
            text-align: center;
        }
    }

    .highlight {
        color: #22a45d; /* Green color for "Tea Leaves" */
    }

    @media (max-width: 1222px) {
        .hero-image {
            display: none; /* Hide the hero image */
        }

        .hero-content {
            display: flex; /* Use flexbox for alignment */
            flex-direction: column; /* Stack items vertically */
            align-items: center; /* Center items horizontally */
            text-align: center; /* Center text */
        }

        .hero-text {
            max-width: 100%;
            margin: 0; /* Remove any margin if needed */
        }

        /* Responsive font sizes */
        .hero-text h1 {
            font-size: 2rem; /* Adjust as needed for smaller screens */
        }

        .hero-text p {
            font-size: 1rem; /* Adjust as needed for smaller screens */
        }

        h2 {
            font-size: 1.5rem; /* Adjust as needed for smaller screens */
        }
    }
</style>