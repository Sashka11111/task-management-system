<?php

namespace Liamtseva\TaskManagementSystem\Models;

use Liamtseva\TaskManagementSystem\Config\Database;
use PDO;

class User
{
    public int $id;
    public string $username;
    public string $email;
    public string $password;

    public static function register(string $username, string $email, string $password): bool
    {
        $db = Database::connect();
        $stmt = $db->prepare('INSERT INTO users (username, email, password) VALUES (:username, :email, :password)');
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT); // Хешування паролю
        return $stmt->execute([':username' => $username, ':email' => $email, ':password' => $hashedPassword]);
    }

    public static function login(string $email, string $password): ?self
    {
        $db = Database::connect();
        $stmt = $db->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) { // Перевірка паролю
            $instance = new self();
            $instance->id = $user['id'];
            $instance->username = $user['username'];
            $instance->email = $user['email'];
            return $instance;
        }
        return null;
    }
    public static function changePassword(int $userId, string $newPassword): bool
    {
        $db = Database::connect();
        $stmt = $db->prepare('UPDATE users SET password = :password WHERE id = :id');
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT); // Хешуємо новий пароль
        return $stmt->execute([':password' => $hashedPassword, ':id' => $userId]);
    }
}
