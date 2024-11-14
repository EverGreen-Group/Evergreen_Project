<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../project-root/public/css/style.css">
    <title><?= $title ?? 'EVERGREEN' ?></title>
</head>
<body>
    <?php include BASE_PATH . 'views/components/sidebar.php'; ?>

    <section id="content">
        <?php include BASE_PATH . 'views/components/navbar.php'; ?>

        <main>
            <?= $content ?>
        </main>
    </section>

    <script src="../project-root/public/js/script.js"></script>
</body>
</html>