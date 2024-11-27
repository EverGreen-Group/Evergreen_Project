<?php require APPROOT . '/views/inc/components/header_public.php'; ?>
<?php require_once APPROOT . '/helpers/RoleHelper.php'; ?>

<!-- Add Navigation Menu -->
<nav class="main-nav">
    <div class="nav-wrapper">
        <div class="logo">
            <a href="<?php echo URLROOT; ?>">
                <img src="<?php echo URLROOT; ?>/public/img/logo.png" alt="Logo">
            </a>
        </div>
        <div class="nav-menu">
            <a href="<?php echo URLROOT; ?>" class="nav-link">Home</a>
            <a href="<?php echo URLROOT; ?>/pages/about" class="nav-link">About Us</a>
            <a href="<?php echo URLROOT; ?>/vehicles" class="nav-link">Vehicle Management</a>
            <a href="<?php echo URLROOT; ?>/supply" class="nav-link">Supply Management</a>
            <a href="<?php echo URLROOT; ?>/inventory" class="nav-link">Inventory</a>
            <a href="<?php echo URLROOT; ?>/marketplace" class="nav-link">Marketplace</a>
            <a href="<?php echo URLROOT; ?>/contact" class="nav-link">Contact Us</a>
        </div>
        <div class="nav-buttons">
            <a href="<?php echo URLROOT; ?>/auth/login" class="login-btn">Login</a>
            <a href="<?php echo URLROOT; ?>/auth/supplier_register" class="register-btn">Register</a>
        </div>
    </div>
</nav>

<main class="landing-main">
    <section class="hero-section">
        <div class="content-wrapper">
            <div class="hero-content">
                <h1 class="hero-title">Streamline Your Supply Chain with Our Tools</h1>
                <p class="hero-subtitle">Join our tea factory network and leverage our innovative system to manage your workflow efficiently. Simplify your processes and enhance productivity as a valued supplier.</p>
                <div class="hero-buttons">
                    <a href="auth/supplier_register/" class="btn-primary">Become a Supplier</a>
                    <a href="#" class="btn-secondary">Discover Our Tools →</a>
                </div>
            </div>
            <div class="hero-image-container">
                <img src="<?php echo URLROOT; ?>/public/img/heroImage.png" alt="Hero Image" class="hero-image">
            </div>
        </div>
    </section>

</main>

<section class="offers-section">
    <div class="offers-header">
        <p class="offers-subtitle">Our services</p>
        <h2 class="offers-title">Why choose <span class="highlight">us</span></h2>
    </div>
    
    <div class="offers-grid">
        <div class="offer-card">
            <div class="offer-icon">
                <i class='bx bxs-dollar-circle'></i>
            </div>
            <h3>Track Supply & Earnings</h3>
            <p>Suppliers can easily track their supply and earnings, ensuring transparency in all transactions.</p>
        </div>
        
        <div class="offer-card">
            <div class="offer-icon">
                <i class='bx bxs-briefcase'></i>
            </div>
            <h3>Transparency in Operations</h3>
            <p>Gain insights into what's going into the factory, ensuring a transparent supply chain.</p>
        </div>
        
        <div class="offer-card">
            <div class="offer-icon">
                <i class='bx bxs-calendar-check'></i>
            </div>
            <h3>Schedule Collections</h3>
            <p>Effortlessly schedule your collections to optimize your supply chain operations.</p>
        </div>
        
        <div class="offer-card">
            <div class="offer-icon">
                <i class='bx bxs-bell-ring'></i>
            </div>
            <h3>Real-time Notifications</h3>
            <p>Receive real-time notifications and track your vehicle for enhanced operational efficiency.</p>
        </div>
    </div>
</section>

<style>
    .landing-main {
        width: 100%;
        min-height: calc(100vh - 20vh);
        background: #66ACFF;
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .landing-main::before {
        content: '';
        position: absolute;
        width: 100%;
        height: 100%;
        background-image: 
            linear-gradient(rgba(235, 240, 255, 0.2) 1px, transparent 1px),
            linear-gradient(90deg, rgba(235, 240, 255, 0.2) 1px, transparent 1px);
        background-size: 80px 80px;
        z-index: 1;
    }

    .hero-section {
        position: relative;
        z-index: 2;
        padding: 2rem 5%;
        width: 100%;
        margin-top: 60px;
        overflow: visible;
    }

    .content-wrapper {
        width: 100%;
        margin: 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 4rem;
        padding: 4rem 0;
        overflow: visible;
    }

    .hero-content {
        flex: 2;
        max-width: 650px;
        margin-left: 100px;
    }

    .hero-title {
        font-family: 'Funnel Display', sans-serif;
        font-size: 4rem;
        font-weight: 550;
        color: var(--light);
        line-height: 1.2;
        margin-bottom: 1.5rem;
    }

    .hero-subtitle {
        font-size: 1.1rem;
        color: #000;
        margin-bottom: 2.5rem;
        line-height: 1.6;
    }

    .hero-buttons {
        display: flex;
        gap: 1.5rem;
        align-items: center;
    }

    .btn-primary {
        background: #000;
        color: white;
        padding: 1rem 2rem;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background: #222;
        transform: translateY(-2px);
    }

    .btn-secondary {
        color: #000;
        text-decoration: none;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 1rem 0;
    }

    .btn-secondary:hover {
        color: #444;
    }

    .hero-image-container {
        flex: 1;
        display: flex;
        justify-content: flex-end;
        overflow: visible;
        position: relative;
        height: 600px;
        width: 800px;
    }

    .hero-image {
        width: 150%;
        height: 100%;
        object-fit: cover;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        position: relative;
        transform: translateX(35%);
    }

    @media (min-width: 1440px) {
        .hero-content {
            margin-left: 200px;
        }

        .hero-image {
            width: 180%;
            transform: translateX(40%);
        }
    }

    @media (max-width: 1200px) {
        .hero-content {
            margin-left: 50px;
        }

        .hero-image {
            width: 140%;
            transform: translateX(30%);
        }
    }

    @media (max-width: 1024px) {
        .content-wrapper {
            flex-direction: column;
            text-align: center;
            padding: 2rem;
        }

        .hero-content {
            margin-left: 0;
            max-width: 100%;
            padding: 0 20px;
        }

        .hero-image-container {
            justify-content: center;
            margin-right: 0;
            width: 100%;
            height: 400px;
        }

        .hero-image {
            width: 100%;
            transform: translateX(0);
        }
    }

    @media (max-width: 768px) {
        .hero-section {
            margin-top: 40px;
            padding: 1rem;
        }

        .hero-title {
            font-size: 3rem;
        }

        .hero-subtitle {
            font-size: 1rem;
            padding: 0 10px;
        }

        .hero-image-container {
            height: 350px;
        }

        .hero-buttons {
            flex-direction: column;
            gap: 1rem;
        }

        .btn-secondary {
            justify-content: center;
        }
    }

    @media (max-width: 480px) {
        .hero-section {
            margin-top: 20px;
        }

        .content-wrapper {
            padding: 1rem 0;
        }

        .hero-title {
            font-size: 2.2rem;
        }

        .hero-subtitle {
            font-size: 0.9rem;
            margin-bottom: 2rem;
        }

        .hero-image-container {
            height: 250px;
        }

        .btn-primary {
            padding: 0.8rem 1.5rem;
            font-size: 0.9rem;
        }

        .btn-secondary {
            font-size: 0.9rem;
        }

        .landing-main::before {
            background-size: 40px 40px; /* Smaller grid for mobile */
        }
    }

    @media (max-width: 320px) {
        .hero-title {
            font-size: 1.8rem;
        }

        .hero-subtitle {
            font-size: 0.8rem;
        }

        .hero-image-container {
            height: 200px;
        }
    }

    .offers-section {
        background: #fff;
        padding: 5rem 8%;
        text-align: center;
    }

    .offers-header {
        margin-bottom: 4rem;
    }

    .offers-subtitle {
        color: #666;
        font-size: 1.1rem;
        margin-bottom: 1rem;
    }

    .offers-title {
        font-size: 2.5rem;
        color: #333;
        font-weight: 500;
    }

    .highlight {
        color: #007bff;
    }

    .offers-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.5rem;
        max-width: 1200px;
        margin: 0 auto;
    }

    .offer-card {
        background: #fff;
        padding: 2rem;
        border-radius: 20px;
        transition: transform 0.3s ease;
        border: 1px solid #007bff;
    }

    .offer-card:hover {
        transform: translateY(-5px);
    }

    .offer-icon {
        width: 60px;
        height: 60px;
        background: #f0f7ff;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
    }

    .offer-icon i {
        font-size: 4.5rem;
        color: #007bff;
    }

    .offer-card h3 {
        color: #333;
        font-size: 1.2rem;
        margin-bottom: 0.8rem;
        font-weight: 600;
    }

    .offer-card p {
        color: #666;
        line-height: 1.5;
        font-size: 0.95rem;
    }

    @media (max-width: 1024px) {
        .offers-grid {
            grid-template-columns: repeat(2, 1fr);
            padding: 0 20px;
        }
    }

    @media (max-width: 768px) {
        .offers-section {
            padding: 3rem 5%;
        }

        .offers-title {
            font-size: 2rem;
        }
    }

    @media (max-width: 640px) {
        .offers-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .offer-card {
            padding: 1.5rem;
        }
    }

    .main-nav {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        background: rgba(255, 255, 255, 0.95);
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        z-index: 1000;
        padding: 1rem 0;
    }

    .nav-wrapper {
        max-width: 1400px;
        margin: 0 auto;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 2rem;
    }

    .logo img {
        height: 40px;
    }

    .nav-menu {
        display: flex;
        gap: 2rem;
        align-items: center;
    }

    .nav-link {
        color: #333;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.3s ease;
    }

    .nav-link:hover {
        color: #007bff;
    }

    .nav-buttons {
        display: flex;
        gap: 1rem;
    }

    .login-btn, .register-btn {
        padding: 0.5rem 1.5rem;
        border-radius: 25px;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .login-btn {
        color: #007bff;
        border: 1px solid #007bff;
    }

    .register-btn {
        background: #007bff;
        color: white;
    }

    .login-btn:hover {
        background: #f0f7ff;
    }

    .register-btn:hover {
        background: #0056b3;
    }

    /* Mobile menu styles */
    @media (max-width: 1024px) {
        .nav-menu {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            width: 100%;
            background: white;
            padding: 1rem;
            flex-direction: column;
            gap: 1rem;
        }

        .nav-menu.active {
            display: flex;
        }

        .nav-buttons {
            gap: 0.5rem;
        }

        .login-btn, .register-btn {
            padding: 0.4rem 1rem;
            font-size: 0.9rem;
        }
    }
</style>