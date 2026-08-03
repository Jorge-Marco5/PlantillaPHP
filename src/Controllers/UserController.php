<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\User;
use App\Services\UserServices;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    private UserServices $userServices;

    public function __construct()
    {
        $this->userServices = new UserServices();
    }

    public function index(Request $request): Response
    {
        return $this->json([
            'status' => 'success',
            'message' => 'Usuarios obtenidos',
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
                'message' => 'Usuario encontrado',
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
            $data = json_decode($request->getContent(), true);

            $name = $data['name'] ?? '';
            $email = $data['email'] ?? '';
            $password = $data['password'] ?? '';
            $password_verify = $data['password_verify'] ?? '';

            if (empty($name) || empty($email) || empty($password) || empty($password_verify)) {
                return $this->json(['status' => 'error', 'message' => 'Todos los campos son obligatorios'], 400);
            }

            if ($password !== $password_verify) {
                return $this->json(['status' => 'error', 'message' => 'Las contraseñas no coinciden'], 400);
            }

            $userData = $this->userServices->create($name, $email, $password);
            $createdUser = User::create($userData);

            if (!$createdUser) {
                return $this->json([
                    'status' => 'error',
                    'message' => 'Error al crear el usuario'
                ], 500);
            }

            return $this->json([
                'status' => 'success',
                'message' => 'Usuario creado correctamente',
                'user' => $createdUser
            ], 201);
        } catch (\Throwable $e) {
            return $this->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'data' => $request->query->all()
            ], 500);
        }
    }

    public function update(Request $request): Response
    {
        try {
            $id = (int) $request->query->get('id');
            $data = json_decode($request->getContent(), true);
            $name = (string) $data['name'] ?? '';
            $email = (string) $data['email'] ?? '';
            $role = (string) $data['role'] ?? '';
            $is_blocked = (int) ($data['is_blocked'] ?? 0);


            if (empty($id)) {
                return $this->json(['status' => 'error', 'message' => 'El ID es requerido'], 400);
            }

            if (empty($name) || empty($email) || empty($role)) {
                return $this->json(['status' => 'error', 'message' => 'Todos los campos son obligatorios, faltan:' . implode(', ', array_filter([empty($name) ? 'name' : '', empty($email) ? 'email' : '', empty($role) ? 'role' : '']))], 400);
            }

            if ($id <= 0) {
                return $this->json(['status' => 'error', 'message' => 'El ID debe ser un número positivo'], 400);
            }

            $user = User::update($id, $name, $email, $role, $is_blocked);

            return $this->json([
                'status' => 'success',
                'message' => 'Usuario actualizado correctamente',
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
            $id = (int) $request->query->get('id');

            if (empty($id)) {
                return $this->json(['status' => 'error', 'message' => 'El ID es requerido'], 400);
            }

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

    public function comparePassword(Request $request): Response
    {
        try {
            $data = json_decode($request->getContent(), true);
            $email = (string) $data['email'] ?? '';
            $password = (string) $data['password'] ?? '';

            if (empty($email) || empty($password)) {
                return $this->json(['status' => 'error', 'message' => 'Todos los campos son obligatorios'], 400);
            }

            $user = User::getOneByEmail($email);
            if (!password_verify($password, $user['password'])) {
                return $this->json([
                    'status' => 'error',
                    'message' => 'La contraseña es incorrecta'
                ], 401);
            }

            return $this->json([
                'status' => 'success',
                'message' => 'La contraseña es correcta'
            ]);
        } catch (\Throwable $e) {
            return $this->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
