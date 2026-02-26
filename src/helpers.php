<?php

if (!function_exists('asset')) {
    function asset(string $path): string
    {
        $baseUrl = $_ENV['APP_URL'] ?? '';
        return rtrim($baseUrl, '/') . '/' . ltrim($path, '/');
    }
}