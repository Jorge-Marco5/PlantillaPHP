<?php

declare(strict_types=1);

use App\Controllers\UserController;

$router->get('/api/users', [UserController::class, 'index']);
$router->get('/api/users/{id}', [UserController::class, 'show']);
$router->post('/api/users', [UserController::class, 'store']);
$router->put('/api/users', [UserController::class, 'update']);
$router->delete('/api/users', [UserController::class, 'destroy']);
$router->post('/api/users/compare-password', [UserController::class, 'comparePassword']);
