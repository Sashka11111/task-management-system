<?php

declare(strict_types=1);

namespace Liamtseva\TaskManagementSystem\Controllers;

use Liamtseva\TaskManagementSystem\Models\User;

class UserController
{
    public static function register(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            if (User::register($username, $email, $password)) {
                echo 'Реєстрація успішна!';
            } else {
                echo 'Помилка реєстрації!';
            }
        } else {
            include __DIR__ . '/../Views/register.php';
        }
    }

    public static function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $user = User::login($email, $password);
            if ($user) {
                session_start();
                $_SESSION['user'] = $user->id;
                echo 'Успішний вхід!';
            } else {
                echo 'Невірний логін або пароль!';
            }
        } else {
            include __DIR__ . '/../Views/login.php';
        }
    }
    public function logout(): void
    {
        // Очистити сесію користувача
        session_start();
        session_unset();
        session_destroy();

        // Після цього можна перенаправити користувача на головну сторінку або сторінку логіну
        header("Location: /login");
        exit();
    }

    public function changePassword(): void
    {
        session_start(); // Ініціалізація сесії

        $userId = $_SESSION['user'] ?? null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newPassword = $_POST['new_password'] ?? null;

            if ($userId && !empty($newPassword)) {
                // Збереження нового пароля
                User::changePassword($userId, password_hash($newPassword, PASSWORD_BCRYPT));
                echo 'Пароль успішно змінено!';
            } else {
                if (!$userId) {
                    echo 'Помилка: користувач не авторизований.';
                }
            }
        } else {
            // Відображення форми
            include __DIR__ . '/../Views/change_password.php';
        }
    }

}
