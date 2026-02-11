<?php

declare(strict_types=1);

use App\Controllers\UserController;
use App\Controllers\HomeController;

// Rutas de ejemplo
$router->get('/', [HomeController::class, 'index']);
$router->get('/about', [HomeController::class, 'about']);

$router->get('/health', [HomeController::class, 'health']);

// Rutas de Usuarios (API)
$router->get('/api/users', [UserController::class, 'index']);
$router->get('/api/users/{id}', [UserController::class, 'show']);
$router->post('/api/users', [UserController::class, 'store']);
$router->put('/api/users/{id}', [UserController::class, 'update']);
$router->delete('/api/users/{id}', [UserController::class, 'destroy']);