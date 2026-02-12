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
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-layout-sidebar"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 6a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2l0 -12" /><path d="M9 4l0 16" /></svg>
                </button>
                <a href="/" class="logo"><?= $_ENV['APP_NAME'] ?></a>
                <ul id="main-nav" class="nav-links">
                    <button id="btn-close" class="btn-close"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-x"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 6l-12 12" /><path d="M6 6l12 12" /></svg></button>
                    <br>
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