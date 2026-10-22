<?php

declare(strict_types=1);

namespace App\Core;

use App\Core\Logger;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ErrorHandler
{
    public static function register(): void
    {
        set_error_handler([self::class, 'handleError']);
        set_exception_handler([self::class, 'handleException']);
    }

    public static function handleError(int $level, string $message, string $file, int $line): bool
    {
        if (error_reporting() & $level) {
            throw new \ErrorException($message, 0, $level, $file, $line);
        }
        return false;
    }

    public static function handleException(\Throwable $exception): void
    {
        // Log the full error details
        Logger::get()->error($exception->getMessage(), [
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString()
        ]);

        // Determine response based on environment and context
        $isDev = ($_ENV['APP_ENV'] ?? 'production') === 'development';
        $isApi = str_contains($_SERVER['REQUEST_URI'] ?? '', '/api/');

        if ($isApi) {
            $payload = [
                'status' => 'error',
                'message' => $isDev ? $exception->getMessage() : 'Internal Server Error',
                'code' => 500
            ];
            if ($isDev) {
                $payload['file'] = $exception->getFile();
                $payload['line'] = $exception->getLine();
            }
            $response = new JsonResponse($payload, 500);
            $response->send();
            exit;
        }

        if ($isDev) {
            $content = "<h1>Error 500</h1>";
            $content .= "<p>" . htmlspecialchars($exception->getMessage(), ENT_QUOTES, 'UTF-8') . "</p>";
            $content .= "<pre>" . htmlspecialchars($exception->getTraceAsString(), ENT_QUOTES, 'UTF-8') . "</pre>";
        } else {
            $content = "<h1>Error 500</h1><p>Internal Server Error</p>";
        }

        $response = new Response($content, 500);
        $response->send();
        exit;
    }
}
