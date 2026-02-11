<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ErrorException;

class UserController extends Controller
{
    public function index(Request $request): Response
    {
        // Simulamos obtener todos los usuarios
        return $this->json([
            'status' => 'success',
            'users' => User::getAll()
        ]);
    }

    public function show(int $id): Response
    {
        try {
            if ($id <= 0 || !is_int($id)) {
                return $this->json(['status' => 'error', 'message' => 'El ID debe ser un número positivo y entero', 'data' => $id], 400);
            }

            $userData = User::getOne($id);

            return $this->json([
                'status' => 'success',
                'user' => $userData
            ]);
        } catch (\Throwable $e) {
            return $this->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request): Response
    {
        try {
            $name = $request->get('name');
            $email = $request->get('email');
            $role = $request->get('role'); // Changed from role_id to role
            $password = $request->get('password');

            $user = User::create($name, $email, $role, $password);

            return $this->json([
                'status' => 'success',
                'user' => $user
            ], 201);
        } catch (\Throwable $e) {
            return $this->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request): Response
    {
        try {
            $id = (int) $request->get('id');
            $name = $request->get('name');
            $email = $request->get('email');
            $role = $request->get('role'); // Changed from role_id to role
            $password = $request->get('password');

            if ($id <= 0) {
                return $this->json(['status' => 'error', 'message' => 'El ID debe ser un número positivo'], 400);
            }

            $user = User::update($id, $name, $email, $role, $password);

            return $this->json([
                'status' => 'success',
                'user' => $user
            ]);
        } catch (\Throwable $e) {
            return $this->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Request $request): Response
    {
        try {
            $id = (int) $request->get('id');

            if ($id <= 0) {
                return $this->json(['status' => 'error', 'message' => 'El ID debe ser un número positivo'], 400);
            }

            User::delete($id);

            return $this->json([
                'status' => 'success',
                'message' => 'Usuario eliminado correctamente'
            ]);
        } catch (\Throwable $e) {
            return $this->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
