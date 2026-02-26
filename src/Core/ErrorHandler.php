<?php

declare(strict_types=1);

namespace App\Core;

use App\Core\Logger;
use Symfony\Component\HttpFoundation\Response;

class ErrorHandler
{
    public static function register(): void
    {
        set_error_handler([self::class , 'handleError']);
        set_exception_handler([self::class , 'handleException']);
    }

    public static function handleError(int $level, string $message, string $file, int $line): bool
    {
        if (error_reporting()& $level) {
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

        // Determine response based on environment
        $isDev = ($_ENV['APP_ENV'] ?? 'production') === 'development';

        if ($isDev) {
            $content = "<h1>Error 500</h1>";
            $content .= "<p>" . $exception->getMessage() . "</p>";
            $content .= "<pre>" . $exception->getTraceAsString() . "</pre>";
        }
        else {
            // Generic message for production
            $content = json_encode([
                'status' => 'error',
                'message' => 'Internal Server Error',
                'code' => 500
            ]);

            // If request expects JSON (simple check for now, can be improved)
            // Ideally we check Accept header, but for now we output JSON if it looks like API or HTML otherwise.
            // Let's stick to JSON for API context or simple HTML for web.
            // Since this is a hybrid app, let's output JSON if the URL contains /api/
            if (str_contains($_SERVER['REQUEST_URI'] ?? '', '/api/')) {
                header('Content-Type: application/json');
                echo $content;
                exit;
            }
            else {
                $content = "<h1>Error 500</h1><p>Internal Server Error</p>";
            }
        }

        $response = new Response($content, 500);
        $response->send();
        exit;
    }
}