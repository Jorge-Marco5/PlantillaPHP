<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use ErrorException;


class HomeController extends Controller
{
    public function index(Request $request): Response
    {
        $data = [
            'title' => 'Bienvenido',
            'message' => '¡Tu aplicación PHP moderna está funcionando!',
            'env' => $_ENV['APP_ENV'] ?? 'production'
        ];

        return $this->view('home/index', $data, 'main');
    }

    public function about(Request $request): Response
    {
        return $this->view('home/about', [
            'title' => 'Acerca de'
        ]);
    }

    public function health(Request $request): JsonResponse
    {
        return $this->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'OK',
            'timestamp' => date('Y-m-d H:i:s'),
        ], 200);
    }

}