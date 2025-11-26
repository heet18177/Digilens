<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Blog Application' ?></title>
    
    <!-- Tailwind CSS -->
    <link href="<?= asset('/css/style.css') ?>" rel="stylesheet">


    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="font-sans antialiased">
    <?php include __DIR__ . '/navbar.php'; ?>
    
    <?php include __DIR__ . '/flash.php'; ?>
    
    <main class="min-h-screen">
        <?= $content ?>
    </main>
    
    <?php include __DIR__ . '/footer.php'; ?>
    
    <!-- Scripts -->
    <script src="<?= asset('js/app.js') ?>"></script>
    
    <?php if (isset($scripts)): ?>
        <?= $scripts ?>
    <?php endif; ?>
</body>
</html>

