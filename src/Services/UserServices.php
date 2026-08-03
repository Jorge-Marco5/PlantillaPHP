<?php

declare(strict_types=1);

namespace App\Services;

class UserServices
{
    public function create(string $name, string $email, string $password): array
    {
        $password_hash = password_hash($password, PASSWORD_ARGON2I);

        $data = [
            'name' => $name,
            'email' => $email,
            'password' => $password_hash
        ];

        return $data;
    }

    public function update(int $id, string $name, string $email, string $role, string $password, bool $is_blocked): array
    {
        $password_hash = password_hash($password, PASSWORD_ARGON2I);

        $data = [
            'id' => $id,
            'name' => $name,
            'email' => $email,
            'role' => $role,
            'password' => $password_hash,
            'is_blocked' => $is_blocked
        ];

        return $data;
    }
}
