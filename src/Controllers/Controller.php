<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

abstract class Controller
{
    protected function view(string $view, array $data = [], ?string $layout = 'main'): Response
    {
        $content = View::render($view, $data, $layout);
        return new Response($content);
    }

    protected function json(array $data, int $status = 200): JsonResponse
    {
        return new JsonResponse($data, $status);
    }

    protected function redirect(string $url, int $status = 302): Response
    {
        return new Response('', $status, ['Location' => $url]);
    }
}