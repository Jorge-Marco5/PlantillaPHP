<?php

declare(strict_types=1);

use App\Controllers\HomeController;

// Rutas de ejemplo
$router->get('/', [HomeController::class, 'index']);
$router->get('/about', [HomeController::class, 'about']);

$router->get('/health', [HomeController::class, 'health']);
