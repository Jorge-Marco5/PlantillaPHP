<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['title'] ?? 'Mi Aplicación' ?> - <?= $_ENV['APP_NAME'] ?></title>
    <link rel="stylesheet" href="/public/css/style.css">
</head>

<body>
    <header>
        <nav>
            <div class="container">
                <button id="btn-open" class="btn-menu">
                    <img src="/public/icons/dock_to_right.svg" alt="Menu">
                </button>
                <a href="/" class="logo"><?= $_ENV['APP_NAME'] ?></a>
                <ul id="main-nav" class="nav-links">
                    <button id="btn-close" class="btn-close"><img src="/public/icons/close.svg" alt="Cerrar"></button>
                    <a href="/" class="logo"><?= $_ENV['APP_NAME'] ?></a>
                    <li><a href="/">Inicio</a></li>
                    <li><a href="/about">Acerca</a></li>
                    <li><a href="/health">API</a></li>
                    <label class="switch">
                        <input type="checkbox" id="theme-toggle">
                        <span class="slider"></span>
                    </label>
                </ul>
            </div>
            <!-- Overlay para oscurecer el fondo al abrir el menú -->
            <div id="overlay" class="overlay"></div>
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

    <script src="/public/js/theme.js"></script>
    <script src="/public/js/header.js"></script>
</body>

</html>