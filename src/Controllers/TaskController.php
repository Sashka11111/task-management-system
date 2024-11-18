<?php

declare(strict_types=1);

namespace Liamtseva\TaskManagementSystem\Controllers;

use Exception;
use Liamtseva\TaskManagementSystem\Models\Task;

class TaskController
{
    // Отримуємо список завдань користувача
    public static function list(): void
    {
        // Отримуємо ID поточного користувача (передбачається, що користувач авторизований)
        session_start();
        $userId = $_SESSION['user'] ?? null;

        if ($userId) {
            // Отримуємо всі завдання поточного користувача
            $tasks = Task::getByUserId($userId);

            // Перевіряємо чи є завдання
            if (empty($tasks)) {
                echo 'Немає завдань для цього користувача.';
                return;
            }

            // Підключаємо шаблон для відображення завдань
            include __DIR__ . '/../Views/tasks.php';
        } else {
            echo 'Ви не авторизовані!';
        }
    }

    // Створюємо нове завдання
    public static function create(): void
    {
        session_start();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'] ?? '';
            $description = $_POST['description'] ?? '';
            $status = $_POST['status'] ?? '';
            $creator_id = $_SESSION['user'] ?? null;

            if (empty($title) || empty($status) || empty($creator_id)) {
                echo 'Будь ласка, заповніть всі поля!';
                return;
            }

            // Викликаємо метод створення завдання в моделі
            $taskData = compact('title', 'description', 'status', 'creator_id');
            if (Task::create($taskData)) {
                // Перенаправляємо на список завдань після успішного створення
                header('Location: /tasks');
                exit;
            } else {
                echo 'Помилка створення завдання!';
            }
        } else {
            // Якщо запит не POST, виводимо форму для створення завдання
            include __DIR__ . '/../Views/create_task.php';
        }
    }

    public static function delete(int $id): void
    {
        session_start();
        $userId = $_SESSION['user'] ?? null;  // Перевіряємо, чи є користувач в сесії

        if ($userId) {
            // Викликаємо метод моделі для видалення завдання за ID
            if (Task::delete($id)) {
                // Якщо завдання успішно видалене, перенаправляємо на список завдань
                header('Location: /tasks');
                exit;
            } else {
                echo 'Помилка видалення завдання!';
            }
        } else {
            echo 'Ви не авторизовані!';
        }
    }
    public static function update(int $id): void
    {
        session_start();
        $userId = $_SESSION['user'] ?? null;  // Перевіряємо, чи є користувач в сесії

        if ($userId) {
            // Перевіряємо, чи це завдання належить поточному користувачеві
            $task = Task::getById($id);
            if ($task['creator_id'] !== $userId) {
                echo 'Ви не маєте доступу до цього завдання!';
                return;
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Отримуємо дані з форми
                $title = $_POST['title'] ?? '';
                $description = $_POST['description'] ?? '';
                $status = $_POST['status'] ?? '';

                if (empty($title) || empty($status)) {
                    echo 'Будь ласка, заповніть всі поля!';
                    return;
                }

                $data = [
                    'title' => $title,
                    'description' => $description,
                    'status' => $status,
                ];

                try {
                    if (Task::update($id, $data)) {
                        // Перенаправляємо на список завдань після успішного оновлення
                        header('Location: /tasks');
                        exit;
                    } else {
                        echo 'Помилка оновлення завдання!';
                    }
                } catch (Exception $e) {
                    echo 'Помилка: ' . $e->getMessage();
                }
            } else {
                // Якщо запит не POST, виводимо форму для оновлення завдання
                include __DIR__ . '/../Views/edit_task.php';
            }
        } else {
            echo 'Ви не авторизовані!';
        }
    }

    public function assignTo(int $taskId, int $userId): void
    {
        if (Task::assignTo($taskId, $userId)) {
            echo "Завдання призначено користувачу.";
        } else {
            echo "Помилка при призначенні завдання.";
        }
    }

    public function changeStatus(int $taskId, string $status): void
    {
        // Перевірка допустимості статусу
        $validStatuses = ['Pending', 'In Progress', 'Completed'];

        if (in_array($status, $validStatuses)) {
            if (Task::changeStatus($taskId, $status)) {
                echo "Статус завдання успішно оновлено.";
            } else {
                echo "Помилка при зміні статусу.";
            }
        } else {
            echo "Невірний статус завдання.";
        }
    }
}
