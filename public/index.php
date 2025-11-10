<?php

declare(strict_types=1);

// Autoload de Composer
require_once __DIR__ . '/../vendor/autoload.php';

// Cargar variables de entorno
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Importar dependencias
use App\Core\Router;
use App\Core\Database;
use Symfony\Component\HttpFoundation\Request;

// Inicializar base de datos (si se usa)
try {
    Database::getInstance();
} catch (\Exception $e) {
    if ($_ENV['APP_ENV'] === 'development') {
        die("Error de conexión: " . $e->getMessage());
    }
    die("Error del servidor");
}

// Crear request desde globals
$request = Request::createFromGlobals();

// Cargar rutas
$router = new Router();
require_once __DIR__ . '/../routes/web.php';

// Despachar la petición
try {
    $response = $router->dispatch($request);
    $response->send();
} catch (\Exception $e) {
    if ($_ENV['APP_ENV'] === 'development') {
        http_response_code(500);
        echo "<h1>Error 500</h1>";
        echo "<pre>" . $e->getMessage() . "</pre>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
    } else {
        http_response_code(500);
        echo "<h1>Error del servidor</h1>";
    }
}