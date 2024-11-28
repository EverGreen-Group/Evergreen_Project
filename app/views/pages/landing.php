<?php require APPROOT . '/views/inc/components/header_public.php'; ?>
<?php require_once APPROOT . '/helpers/RoleHelper.php'; ?>

<main class="landing-main">
    <div class="hero-section">
        <div class="hero-content">
            <div class="hero-text">
                <h1>Connecting Tea Suppliers with Excellence</h1>
                <p>A modern platform designed for tea leaf suppliers to manage collections, track deliveries, and grow their business.</p>
                <div class="hero-cta">
                    <a href="<?php echo URLROOT; ?>/auth/supplier_register/" class="cta-button">Get Started</a>
                    <a href="<?php echo URLROOT; ?>/users/login" class="login-link">
                        Already registered? Sign in <i class='bx bx-right-arrow-alt'></i>
                    </a>
                </div>
            </div>
            <div class="hero-image">
                <img src="https://media.assettype.com/sentinelassam-english%2F2024-05%2Fba6fe76e-9f2f-4535-a183-f9d1817453d0%2Ftea_la.jpg" alt="Tea Image">
            </div>
        </div>
        <div class="hero-stats">
            <div class="stat-card">
                <span class="stat-number">500+</span>
                <span class="stat-label">Active Suppliers</span>
            </div>
            <div class="stat-card">
                <span class="stat-number">98%</span>
                <span class="stat-label">Collection Rate</span>
            </div>
        </div>
    </div>

    <section class="features-section">
        <h2>Why Choose Our Platform?</h2>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class='bx bxs-calendar-check'></i>
                </div>
                <h3>Easy Scheduling</h3>
                <p>Schedule your tea leaf collections with just a few clicks</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class='bx bxs-truck'></i>
                </div>
                <h3>Real-time Tracking</h3>
                <p>Track your collection vehicle's location in real-time</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class='bx bxs-report'></i>
                </div>
                <h3>Detailed Reports</h3>
                <p>Access comprehensive reports of your supplies and earnings</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class='bx bxs-wallet'></i>
                </div>
                <h3>Quick Payments</h3>
                <p>Receive payments quickly and track all transactions</p>
            </div>
        </div>
    </section>
</main>

<style>
    .landing-main {
        background: linear-gradient(135deg, #1b5e20 0%, #388e3c 100%);
        min-height: 100vh;
        padding: 0 5%;
        color: #fff;
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
        max-width: 100%;
        height: auto;
        border-radius: 8px;
    }

    .hero-text h1 {
        font-size: 3rem;
        margin-bottom: 1rem;
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

    .hero-text h1 {
        font-size: 3.5rem;
        font-weight: 700;
        line-height: 1.2;
        margin-bottom: 1.5rem;
    }

    .hero-text p {
        font-size: 1.2rem;
        line-height: 1.6;
        color: rgba(255, 255, 255, 0.9);
        margin-bottom: 2rem;
    }

    .hero-cta {
        display: flex;
        gap: 2rem;
        align-items: center;
    }

    .cta-button {
        background: #fff;
        color: #1b5e20;
        padding: 1rem 2rem;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .cta-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .login-link {
        color: #fff;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 0.5rem;
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
    }

    .stat-number {
        display: block;
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        color: rgba(255, 255, 255, 0.9);
        font-size: 1rem;
    }

    .features-section {
        background: #fff;
        padding: 80px 0;
        border-radius: 30px 30px 0 0;
        margin-top: -30px;
    }

    .features-section h2 {
        color: #333;
        text-align: center;
        font-size: 2.5rem;
        margin-bottom: 3rem;
    }

    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 2rem;
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
        width: 70px;
        height: 70px;
        background: #e8f5e9;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
    }

    .feature-icon i {
        font-size: 2rem;
        color: #1b5e20;
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
</style>