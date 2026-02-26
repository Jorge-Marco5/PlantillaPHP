<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/helpers.php';


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/');
$dotenv->load();

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-CSRF-TOKEN");
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: SAMEORIGIN");
header("X-XSS-Protection: 1; mode=block");
header("Referrer-Policy: strict-origin-when-cross-origin");
header("Content-Security-Policy: default-src 'self' 'unsafe-inline' 'unsafe-eval';");
header("Strict-Transport-Security: max-age=31536000; includeSubDomains");

use App\Core\Router;
use App\Core\Database;
use App\Core\RateLimiter;
use App\Core\ErrorHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

ErrorHandler::register();

Database::getInstance();

$request = Request::createFromGlobals();
$rateLimiter = new RateLimiter();
if (!$rateLimiter->check($request->getClientIp(), 60, 1)) {
    $response = new Response('Too Many Requests', 429);
    $response->send();
    exit;
}

if (session_status() === PHP_SESSION_NONE) {
    // Definimos 30 días en segundos
    $lifetime = 60 * 60 * 24 * 30;
    session_start([
        'cookie_lifetime' => $lifetime,
        'gc_maxlifetime' => $lifetime,
        'cookie_httponly' => true,
        'cookie_secure' => ($_ENV['APP_ENV'] ?? 'development') === 'production' && isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
        'cookie_samesite' => 'Lax',
        'use_strict_mode' => true
    ]);
}
$router = new Router();
require_once __DIR__ . '/routes/web.php';

$response = $router->dispatch($request);
$response->send();