<?php require APPROOT . '/views/inc/components/header_public.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/pages/supplier_registration.css">

<main>
    <div class="auth-container">
        <div class="auth-form-section">
            <div class="auth-form-container">
                <h2>Complete Your Supplier Profile</h2>
                <?php if (isset($data['error']) && !empty($data['error'])): ?>
                    <div class="auth-error"><?php echo $data['error']; ?></div>
                <?php endif; ?>

                <form id="supplierRegForm" action="<?php echo URLROOT; ?>/auth/supplier_register" method="POST" enctype="multipart/form-data">
                    <?php 
                    require 'components/supplier_contact.php';
                    require 'components/supplier_address.php';
                    require 'components/supplier_location.php';
                    require 'components/supplier_ownership.php';
                    require 'components/supplier_property.php';
                    require 'components/supplier_infrastructure.php';
                    require 'components/supplier_tea_details.php';
                    require 'components/supplier_bank_details.php';
                    require 'components/supplier_documents.php';
                    ?>

                    <button type="submit" class="auth-button">Submit Application</button>
                </form>
            </div>
        </div>
    </div>
</main>

<script src="<?php echo URLROOT; ?>/public/js/supplier/form_validation.js"></script>
<script src="<?php echo URLROOT; ?>/public/js/supplier/map_handler.js"></script>

<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAC8AYYCYuMkIUAjQWsAwQDiqbMmLa-7eo&callback=initMap">
</script>

<style>
    /* Reset default margins */
    html, body {
        margin: 0;
        padding: 0;
        overflow-x: hidden;
    }

    /* Main content wrapper */
    main {
        padding-top: 60px; /* Header height */
    }

    /* Container styles */
    .auth-container {
        padding: 20px;
        display: flex;
        justify-content: center;
        align-items: flex-start;
    }

    .auth-form-section {
        width: 100%;
        max-width: 800px;
        margin-top: 20px;
        padding: 30px;
        border-radius: 8px;
    }
</style>


<?php require APPROOT . '/views/inc/components/footer.php'; ?>