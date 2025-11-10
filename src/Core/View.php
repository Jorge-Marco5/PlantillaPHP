<?php

declare(strict_types=1);

namespace App\Core;

class View
{
    private static string $viewsPath = __DIR__ . '/../../views';
    private static string $layoutsPath = __DIR__ . '/../../views/layouts';

    public static function render(string $view, array $data = [], ?string $layout = 'main'): string
    {
        // Extraer variables para la vista
        extract($data);

        // Capturar el contenido de la vista
        ob_start();
        $viewFile = self::$viewsPath . '/' . $view . '.php';

        if (!file_exists($viewFile)) {
            throw new \Exception("Vista no encontrada: {$view}");
        }

        require $viewFile;
        $content = ob_get_clean();

        // Si hay layout, renderizarlo con el contenido
        if ($layout !== null) {
            ob_start();
            $layoutFile = self::$layoutsPath . '/' . $layout . '.php';

            if (!file_exists($layoutFile)) {
                throw new \Exception("Layout no encontrado: {$layout}");
            }

            require $layoutFile;
            $content = ob_get_clean();
        }

        return $content;
    }

    public static function json(array $data, int $status = 200): string
    {
        http_response_code($status);
        header('Content-Type: application/json');
        return json_encode($data, JSON_PRETTY_PRINT);
    }

    public static function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
}