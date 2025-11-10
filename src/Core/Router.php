<?php

declare(strict_types=1);

namespace App\Core;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Router
{
    private array $routes = [];

    public function get(string $path, callable|array $handler): void
    {
        $this->addRoute('GET', $path, $handler);
    }

    public function post(string $path, callable|array $handler): void
    {
        $this->addRoute('POST', $path, $handler);
    }

    public function put(string $path, callable|array $handler): void
    {
        $this->addRoute('PUT', $path, $handler);
    }

    public function delete(string $path, callable|array $handler): void
    {
        $this->addRoute('DELETE', $path, $handler);
    }

    private function addRoute(string $method, string $path, callable|array $handler): void
    {
        // Convertir parámetros dinámicos {id} a regex
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $path);
        $pattern = '#^' . $pattern . '$#';

        $this->routes[] = [
            'method' => $method,
            'pattern' => $pattern,
            'path' => $path,
            'handler' => $handler,
        ];
    }

    public function dispatch(Request $request): Response
    {
        $method = $request->getMethod();
        $path = $request->getPathInfo();

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            if (preg_match($route['pattern'], $path, $matches)) {
                // Extraer parámetros
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                return $this->callHandler($route['handler'], $request, $params);
            }
        }

        return new Response('404 - Página no encontrada', 404);
    }

    private function callHandler(callable|array $handler, Request $request, array $params): Response
    {
        if (is_array($handler)) {
            [$controllerClass, $method] = $handler;
            $controller = new $controllerClass();
            $result = $controller->$method($request, ...$params);
        } else {
            $result = $handler($request, ...$params);
        }

        // Si devuelve un Response, usarlo directamente
        if ($result instanceof Response) {
            return $result;
        }

        // Si devuelve un array, convertir a JSON
        if (is_array($result)) {
            return new Response(
                json_encode($result),
                200,
                ['Content-Type' => 'application/json']
            );
        }

        // Si devuelve string, crear Response HTML
        return new Response((string) $result);
    }
}