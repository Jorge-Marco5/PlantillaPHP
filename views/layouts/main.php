<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['title'] ?? 'Mi Aplicación' ?> - <?= $_ENV['APP_NAME'] ?></title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <header>
        <nav>
            <div class="container">
                <a href="/" class="logo"><?= $_ENV['APP_NAME'] ?></a>
                <ul>
                    <li><a href="/">Inicio</a></li>
                    <li><a href="/about">Acerca</a></li>
                    <li><a href="/api">API</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <main class="container">
        <?= $content ?>
    </main>

    <footer>
        <div class="container">
            <p>&copy; <?= date('Y') ?> - <?= $_ENV['APP_NAME'] ?></p>
        </div>
    </footer>

    <script src="/js/app.js"></script>
</body>
</html>