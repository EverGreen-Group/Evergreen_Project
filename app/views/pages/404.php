<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .error-container {
            text-align: center;
            padding: 2rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 90%;
        }

        .error-code {
            font-size: 6rem;
            font-weight: bold;
            color: #007664;
            margin: 0;
            line-height: 1;
        }

        .error-message {
            font-size: 1.5rem;
            color: #2c3e50;
            margin: 1rem 0;
        }

        .error-description {
            color: #6c757d;
            margin-bottom: 2rem;
        }

        .button-group {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 2rem;
        }

        .back-button, .home-button {
            padding: 0.8rem 1.5rem;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .back-button {
            background-color: #6c757d;
        }

        .back-button:hover {
            background-color: #5a6268;
        }

        .home-button {
            background-color: #007664;
        }

        .home-button:hover {
            background-color: #005a4d;
        }

        .error-image {
            max-width: 200px;
            margin: 2rem 0;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <h1 class="error-code">404</h1>
        <h2 class="error-message">Page Not Found</h2>
        <p class="error-description">
            The page you're looking for doesn't exist or has been moved.
        </p>
        <div class="button-group">
            <?php if (isset($_SESSION['previous_url'])): ?>
                <a href="<?php echo $_SESSION['previous_url']; ?>" class="back-button">
                    <i class="fas fa-arrow-left"></i> Go Back
                </a>
            <?php endif; ?>
            <a href="<?php echo URLROOT; ?>" class="home-button">
                <i class="fas fa-home"></i> Home
            </a>
        </div>
    </div>

    <script>
    // Add browser back button support
    document.querySelector('.back-button')?.addEventListener('click', function(e) {
        e.preventDefault();
        if (window.history.length > 1) {
            window.history.back();
        } else {
            window.location.href = '<?php echo URLROOT; ?>';
        }
    });
    </script>
</body>
</html> 