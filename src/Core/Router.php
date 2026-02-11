<?php

declare(strict_types=1);

namespace App\Core;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Router
{
    /**
     * @var array<int, array<string, mixed>>
     */
    private array $routes = [];

    /**
     * Summary of get
     * @param string $path
     * @param callable|array<string, mixed> $handler
     * @return void
     */
    public function get(string $path, callable|array $handler): void
    {
        $this->addRoute('GET', $path, $handler);
    }

    /**
     * Summary of post
     * @param string $path
     * @param callable|array<string, mixed> $handler
     * @return void
     */
    public function post(string $path, callable|array $handler): void
    {
        $this->addRoute('POST', $path, $handler);
    }

    /**
     * Summary of put
     * @param string $path
     * @param callable|array<string, mixed> $handler
     * @return void
     */
    public function put(string $path, callable|array $handler): void
    {
        $this->addRoute('PUT', $path, $handler);
    }

    /**
     * Summary of delete
     * @param string $path
     * @param callable|array<string, mixed> $handler
     * @return void
     */
    public function delete(string $path, callable|array $handler): void
    {
        $this->addRoute('DELETE', $path, $handler);
    }

    /**
     * Summary of addRoute
     * @param string $method
     * @param string $path
     * @param callable|array<string, mixed> $handler
     * @return void
     */
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

    /**
     * Summary of dispatch
     * @param Request $request
     * @return Response
     */
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

    /**
     * Summary of callHandler
     * @param callable|array<string, mixed> $handler
     * @param Request $request
     * @param array<string, mixed> $params
     * @return Response
     */
    private function callHandler(callable|array $handler, Request $request, array $params): Response
    {
        try {
            if (is_array($handler)) {
                [$controllerClass, $method] = $handler;
                $controller = new $controllerClass();
                $reflection = new \ReflectionMethod($controllerClass, $method);
                $args = $this->resolveParameters($reflection, $request, $params);
                $result = $controller->$method(...$args);
            } elseif (is_object($handler) && !$handler instanceof \Closure) {
                $reflection = new \ReflectionMethod($handler, '__invoke');
                $args = $this->resolveParameters($reflection, $request, $params);
                $result = $handler(...$args);
            } else {
                $reflection = new \ReflectionFunction($handler);
                $args = $this->resolveParameters($reflection, $request, $params);
                $result = $handler(...$args);
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

        } catch (\Throwable $e) {
             return new Response('Internal Server Error: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Summary of resolveParameters
     * @param \ReflectionFunctionAbstract $reflection
     * @param Request $request
     * @param array<string, mixed> $routeParams
     * @return array<mixed>
     */
    private function resolveParameters(\ReflectionFunctionAbstract $reflection, Request $request, array $routeParams): array
    {
        $args = [];
        $params = $reflection->getParameters();

        foreach ($params as $param) {
            $name = $param->getName();
            $type = $param->getType();

            // 1. Inyectar Request si el tipo coincide
            if ($type instanceof \ReflectionNamedType && !$type->isBuiltin() && $type->getName() === Request::class) {
                $args[] = $request;
                continue;
            }

            // 2. Inyectar parámetro de ruta si existe (casting al tipo esperado)
            if (array_key_exists($name, $routeParams)) {
                $val = $routeParams[$name];
                if ($type instanceof \ReflectionNamedType && $type->getName() === 'int') {
                    $args[] = (int) $val;
                } else {
                    $args[] = $val;
                }
                continue;
            }

            // 3. Inyectar cuerpo de la petición si el argumento se llama $data
            if ($name === 'data') {
                $content = $request->getContent();
                $data = json_decode($content, true);
                $args[] = is_array($data) ? $data : [];
                continue;
            }
            
            // 4. Parámetros opcionales
            if ($param->isDefaultValueAvailable()) {
                $args[] = $param->getDefaultValue();
                continue;
            }

             // 5. Null si permite nulos
            if ($type && $type->allowsNull()) {
                 $args[] = null;
                 continue;
            }

            // Fallback: lanzar error o ignorar (esto causará error de argumentos faltantes)
        }

        return $args;
    }
}