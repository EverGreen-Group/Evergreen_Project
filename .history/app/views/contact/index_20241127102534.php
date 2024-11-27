<?php require APPROOT . '/views/inc/landing_header.php'; ?>

<main class="contact-page">
    <!-- Page Header -->
    <section class="page-header" style="background-image: url('<?php echo URLROOT; ?>/img/categories/bg.jpg');">
        <div class="header-content">
            <h1>Contact</h1>
            <div class="breadcrumb">
                <a href="<?php echo URLROOT; ?>">Home</a>
                <span>Contact</span>
            </div>
        </div>
    </section>

    <!-- Contact Form Section -->
    <section class="contact-section">
        <div class="container">
            <div class="section-header text-center">
                <h2>Get In Touch With Us</h2>
                <p>For More Information About Our Products & Services, Please Feel Free To Drop Us An Email. 
                   Our Staff Always Be There To Help You Out. Do Not Hesitate!</p>
            </div>

            <div class="contact-wrapper">
                <!-- Contact Information -->
                <div class="contact-info">
                    <div class="info-item">
                        <i class='bx bx-map-pin'></i>
                        <h3>Address</h3>
                        <p>123 Tea Estate Road</p>
                        <p>Nuwara Eliya, Sri Lanka</p>
                    </div>

                    <div class="info-item">
                        <i class='bx bx-phone'></i>
                        <h3>Phone</h3>
                        <p>Mobile: +94 77 123 4567</p>
                        <p>Hotline: +94 11 234 5678</p>
                    </div>

                    <div class="info-item">
                        <i class='bx bx-time'></i>
                        <h3>Working Time</h3>
                        <p>Monday-Friday: 9:00 - 18:00</p>
                        <p>Saturday-Sunday: 9:00 - 14:00</p>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="contact-form">
                    <form action="<?php echo URLROOT; ?>/contact/submit" method="POST" id="contactForm">
                        <div class="form-group">
                            <label for="name">Your Name</label>
                            <input type="text" id="name" name="name" placeholder="Enter your name" required>
                        </div>

                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" placeholder="Enter your email" required>
                        </div>

                        <div class="form-group">
                            <label for="subject">Subject</label>
                            <input type="text" id="subject" name="subject" placeholder="Enter subject">
                        </div>

                        <div class="form-group">
                            <label for="message">Message</label>
                            <textarea id="message" name="message" rows="5" placeholder="Enter your message" required></textarea>
                        </div>

                        <button type="submit" class="btn-submit">Submit Message</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section">
        <div class="container">
            <div class="features-grid">
                <div class="feature-item">
                    <i class='bx bx-medal'></i>
                    <h3>High Quality</h3>
                    <p>Crafted from premium Ceylon tea</p>
                </div>

                <div class="feature-item">
                    <i class='bx bx-check-shield'></i>
                    <h3>Warranty Protection</h3>
                    <p>100% satisfaction guaranteed</p>
                </div>

                <div class="feature-item">
                    <i class='bx bx-package'></i>
                    <h3>Free Shipping</h3>
                    <p>On orders over Rs. 5000</p>
                </div>

                <div class="feature-item">
                    <i class='bx bx-support'></i>
                    <h3>24/7 Support</h3>
                    <p>Dedicated customer service</p>
                </div>
            </div>
        </div>
    </section>
</main>

<?php require APPROOT . '/views/inc/landing_footer.php'; ?> 