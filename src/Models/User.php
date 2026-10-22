<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use ErrorException;

class User
{
    private ?int $id = null;
    private ?string $name = null;
    private ?string $email = null;
    private ?string $role = null;
    private bool $is_blocked = false;
    private ?string $password = null;
    private ?string $created_at = null;
    private ?string $updated_at = null;

    // Getters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function getIsBlocked(): bool
    {
        return $this->is_blocked;
    }


    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getCreatedAt(): ?string
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): ?string
    {
        return $this->updated_at;
    }

    // Setters
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function setRole(string $role): void
    {
        $this->role = $role;
    }

    public function setIsBlocked(bool $is_blocked): void
    {
        $this->is_blocked = $is_blocked;
    }

    public function setCreatedAt(string $created_at): void
    {
        $this->created_at = $created_at;
    }

    public function setUpdatedAt(string $updated_at): void
    {
        $this->updated_at = $updated_at;
    }

    // Métodos

    /**
     * Summary of getAll
     * @return array<int, array<string, mixed>>
     */
    public static function getAll(): array
    {
        $db = Database::getInstance();
        $results = $db->query('SELECT id, name, email, role, is_blocked, created_at, updated_at FROM users ORDER BY created_at DESC');

        $users = [];
        foreach ($results as $data) {
            $user = new self();
            $user->hydrate($data);
            $users[] = $user->toArray();
        }
        return $users;
    }

    /**
     * Summary of getOne
     * @param int $id
     * @return array<string, mixed>
     */
    public static function getOne(int $id): array
    {
        $db = Database::getInstance();
        $result = $db->query('SELECT id, name, email, role, is_blocked, created_at, updated_at FROM users WHERE id = ?', [$id]);

        if (empty($result)) {
            throw new ErrorException("Usuario no encontrado con ID: {$id}");
        }

        $user = new self();
        $user->hydrate($result[0]);
        return $user->toArray();
    }

    /**
     * Summary of getOneByEmail
     * @param string $email
     * @return array<string, mixed>
     */
    public static function getOneByEmail(string $email): array
    {
        $db = Database::getInstance();
        $result = $db->query('SELECT id, name, email, role, is_blocked, password, created_at, updated_at FROM users WHERE email = ?', [$email]);

        if (empty($result)) {
            throw new ErrorException("Usuario no encontrado con email: {$email}");
        }

        $user = new self();
        $user->hydrateWithPassword($result[0]);
        return $user->toArray();
    }

    /**
     * Summary of create
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    public static function create(array $data): array
    {
        $db = Database::getInstance();
        $sql = 'INSERT INTO users (name, email, role, password) VALUES (?, ?, ?, ?)';
        $params = [
            $data['name'],
            $data['email'],
            'user',
            $data['password']
        ];

        $db->execute($sql, $params);

        $id = $db->lastInsertId();

        if (!$id) {
            throw new ErrorException("Error al obtener el ID del usuario creado");
        }

        return self::getOne((int)$id);
    }

    /**
     * Summary of update
     * @param int $id
     * @param string $name
     * @param string $email
     * @param string $role
     * @param bool $is_blocked
     * @return array<string, mixed>
     */
    public static function update(int $id, string $name, string $email, string $role, bool $is_blocked): array
    {
        $db = Database::getInstance();
        $now = date('Y-m-d H:i:s');
        $sql = 'UPDATE users SET name = ?, email = ?, role = ?, is_blocked = ?, updated_at = ? WHERE id = ?';
        $params = [
            $name,
            $email,
            $role,
            (int)$is_blocked,
            $now,
            $id
        ];

        $result = $db->execute($sql, $params);

        if (!$result) {
            throw new ErrorException("Error al actualizar el usuario");
        }

        return self::getOne($id);
    }

    /**
     * Summary of delete
     * @param int $id
     * @return bool
     */
    public static function delete(int $id): bool
    {
        $db = Database::getInstance();
        return $db->execute('DELETE FROM users WHERE id = ?', [$id]);
    }

    /**
     * Convierte el objeto User en un array asociativo.
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'is_blocked' => $this->is_blocked,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

        if ($this->password !== null) {
            $data['password'] = $this->password;
        }

        return $data;
    }

    /**
     * Hidrata el objeto User con datos de un array asociativo.
     * sirve para crear un objeto User a partir de un array de datos.
     * @param array<string, mixed> $data
     * @return User
     */
    protected function hydrate(array $data): User
    {
        $this->id = isset($data['id']) ? (int)$data['id'] : null;
        $this->name = isset($data['name']) ? (string)$data['name'] : null;
        $this->email = isset($data['email']) ? (string)$data['email'] : null;
        $this->role = isset($data['role']) ? (string)$data['role'] : null;
        $this->is_blocked = !empty($data['is_blocked']);
        $this->created_at = isset($data['created_at']) ? (string)$data['created_at'] : null;
        $this->updated_at = isset($data['updated_at']) ? (string)$data['updated_at'] : null;
        return $this;
    }

    /**
     * Hidrata el objeto User incluyendo el hash de la contraseña.
     * @param array<string, mixed> $data
     * @return User
     */
    protected function hydrateWithPassword(array $data): User
    {
        $this->hydrate($data);
        $this->password = isset($data['password']) ? (string)$data['password'] : null;
        return $this;
    }
}
