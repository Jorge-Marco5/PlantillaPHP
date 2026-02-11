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
     * @return array[]
     */
    public static function getAll(): array
    {
        try{
            $db = Database::getInstance();
            $results = $db->query('SELECT id, name, email, role, created_at, updated_at FROM users ORDER BY created_at DESC');

            $users = [];
            foreach ($results as $data) {
                $user = new self();
                $user->hydrate($data);
                $users[] = $user->toArray();
            }
            return $users;
        }catch(ErrorException $e){
            throw $e;
        }
    }

    /**
     * Summary of getOne
     * @param int $id
     * @return array
     */
    public static function getOne(int $id): array
    {
        try{
            $db = Database::getInstance();
            $result = $db->query('SELECT id, name, email, role, created_at, updated_at FROM users WHERE id = ?', [$id]);

            if (empty($result)) {
                throw new ErrorException("Resultado vacio: ".print_r($result, true));
            }

            $user = new self();
            $user->hydrate($result[0]);
            return $user->toArray();
        }catch(ErrorException $e){
            throw $e;
        }
    }

    /**
     * Summary of create
     * @param string $name
     * @param string $email
     * @param string $role
     * @param string $password
     * @return array
     */
    public static function create($name, $email, $role, $password): array
    {
        try{
            $db = Database::getInstance();
            $now = date('Y-m-d H:i:s');
            $sql = 'INSERT INTO users (name, email, role, password, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?)';
            $params = [
                $name,
                $email,
                $role,
                $password,
                $now,
                $now
            ];
            
            $db->execute($sql, $params);
            
            $id = $db->lastInsertId();
            
            if (!$id) {
                throw new ErrorException("Error al obtener el ID del usuario creado");
            }

            return self::getOne((int)$id);
        }catch(ErrorException $e){
            throw $e;
        }
    }

    /**
     * Summary of update
     * @param int $id
     * @param array $data
     * @return array
     */
    public static function update(int $id, $name, $email, $role, $password): array
    {
        try{
            $db = Database::getInstance();
            $now = date('Y-m-d H:i:s');
            $sql = 'UPDATE users SET name = ?, email = ?, role = ?, password = ?, updated_at = ? WHERE id = ?';
            $params = [
                $name,
                $email,
                $role,
                $password,
                $now,
                $id
            ];

            $result = $db->execute($sql, $params);

            if(!$result){
                 throw new ErrorException("Error al actualizar el usuario");
            }

            return self::getOne($id);
        }catch(ErrorException $e){
            throw $e;
        }
    }

    /**
     * Summary of delete
     * @param int $id
     * @return bool
     */
    public static function delete(int $id): bool
    {
        try{
            $db = Database::getInstance();
            $result = $db->execute('DELETE FROM users WHERE id = ?', [$id]);
            return $result;
        }catch(ErrorException $e){
            throw $e;
        }
    }

    /**
     * Convierte el objeto User en un array asociativo.
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    /**
     * Hidrata el objeto User con datos de un array asociativo.
     * sirve para crear un objeto User a partir de un array de datos.
     * @param array<string, mixed> $data
     * @return User
     */    protected function hydrate(array $data): User
    {
        $this->id = $data['id'] ?? null;
        $this->name = $data['name'] ?? null;
        $this->email = $data['email'] ?? null;
        $this->role = $data['role'] ?? null;
        $this->password = $data['password'] ?? null;
        $this->created_at = $data['created_at'] ?? null;
        $this->updated_at = $data['updated_at'] ?? null;
        return $this;
    }
}
