<?php

declare(strict_types=1);

use App\Controllers\HomeController;

// Rutas de ejemplo
$router->get('/', [HomeController::class, 'index']);
$router->get('/about', [HomeController::class, 'about']);
$router->get('/api', [HomeController::class, 'api']);

// Ejemplo con parámetros dinámicos
$router->get('/users/{id}', function ($request, $id) {
    return [
        'user_id' => $id,
        'message' => "Usuario con ID: {$id}"
    ];
});

// Ejemplo de POST
$router->post('/api/users', function ($request) {
    $data = json_decode($request->getContent(), true);
    return [
        'status' => 'created',
        'data' => $data
    ];
});