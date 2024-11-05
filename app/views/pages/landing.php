<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evergreen Project</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: #f0f2f5;
        }

        .container {
            text-align: center;
            padding: 40px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #1a5d1a;
            margin-bottom: 30px;
        }

        .role-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
        }

        .role-button {
            padding: 15px 30px;
            border: none;
            border-radius: 5px;
            background: #1a5d1a;
            color: white;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.3s;
        }

        .role-button:hover {
            background: #124212;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome to Evergreen</h1>
        <div class="role-buttons">
            <a href="<?php echo URLROOT; ?>/vehiclemanager" class="role-button">Vehicle Manager</a>
            <a href="<?php echo URLROOT; ?>/vehicledriver" class="role-button">Vehicle Driver</a>
        </div>
    </div>
</body>
</html> 