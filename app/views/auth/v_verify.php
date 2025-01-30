<?php require APPROOT . '/views/inc/components/header_public.php'; ?>

<main>
    <div class="auth-container">
        <div class="auth-form-section">
            <div class="auth-form-container">
                <h2>Email Verified Successfully!</h2>
            </div>
        </div>
    </div>
</main>

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