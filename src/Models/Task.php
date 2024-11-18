<?php

namespace Liamtseva\TaskManagementSystem\Models;

use Exception;
use Liamtseva\TaskManagementSystem\Config\Database;
use PDO;

class Task
{
    public int $id;
    public string $title;
    public ?string $description;
    public string $status;
    public int $creator_id;
    public ?int $assigned_to_id;

    public static function create(array $data): bool
    {
        $db = Database::connect();

        // Допустимі значення для статусу
        $validStatuses = ['Pending', 'In Progress', 'Completed'];

        // Перевірка, чи статус є допустимим
        if (!in_array($data['status'], $validStatuses)) {
            throw new Exception("Invalid status value: " . $data['status']);
        }

        $sql = "INSERT INTO tasks (title, description, status, creator_id, assigned_to_id, created_at) 
        VALUES (:title, :description, :status, :creator_id, :assigned_to_id, CURRENT_TIMESTAMP)";

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':status', $data['status']);
        $stmt->bindParam(':creator_id', $data['creator_id']);
        $stmt->bindParam(':assigned_to_id', $data['assigned_to_id']);

        return $stmt->execute();
    }

    public static function delete(int $taskId): bool
    {
        error_log("Отримано запит на видалення ID: $taskId");
        $db = Database::connect();
        $stmt = $db->prepare('DELETE FROM tasks WHERE id = :id');
        $stmt->bindParam(':id', $taskId, PDO::PARAM_INT);

        if ($stmt->execute()) {
            error_log("Завдання $taskId успішно видалено");
            return true;
        } else {
            error_log(print_r($stmt->errorInfo(), true));
            return false;
        }
    }

    public static function assignTo(int $taskId, int $userId): bool
    {
        $db = Database::connect();
        $stmt = $db->prepare('UPDATE tasks SET assigned_to_id = :assigned_to_id WHERE id = :task_id');
        return $stmt->execute([':task_id' => $taskId, ':assigned_to_id' => $userId]);
    }

    public static function changeStatus(int $taskId, string $status): bool
    {
        $db = Database::connect();
        $stmt = $db->prepare('UPDATE tasks SET status = :status WHERE id = :task_id');
        return $stmt->execute([':task_id' => $taskId, ':status' => $status]);
    }

    public static function getByUserId(int $userId): array
    {
        $db = Database::connect();
        $stmt = $db->prepare('SELECT * FROM tasks WHERE creator_id = :user_id OR assigned_to_id = :user_id');
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);  // Повертає масив завдань
    }
    // Отримання завдань за користувачем
    public static function getById(int $taskId): ?array
    {
        $db = Database::connect();
        $stmt = $db->prepare('SELECT * FROM tasks WHERE id = :id');
        $stmt->execute([':id' => $taskId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);  // Повертає одне завдання або null
    }
    public static function update(int $id, array $data): bool
    {
        // Перевірка на обов'язкові поля
        if (empty($data['title']) || empty($data['status'])) {
            throw new Exception("Не всі обов'язкові дані передані для оновлення завдання.");
        }

        // Допустимі значення для статусу
        $validStatuses = ['Pending', 'In Progress', 'Completed'];

        // Перевірка, чи статус є допустимим
        if (!in_array($data['status'], $validStatuses)) {
            throw new Exception("Невірне значення статусу: " . $data['status']);
        }

        $db = Database::connect();

        // Формуємо частину SQL-запиту для оновлення лише тих полів, які передані в масиві $data
        $fields = [];
        foreach ($data as $key => $value) {
            // Перевірка, чи передано значення для цього поля
            if ($value !== null) {
                $fields[] = "$key = :$key";  // Додаємо поле та параметр для запиту
            }
        }

        // Якщо немає полів для оновлення, повертаємо false
        if (empty($fields)) {
            return false;
        }

        // Формуємо SQL-запит для оновлення
        $sql = "UPDATE tasks SET " . implode(', ', $fields) . " WHERE id = :id";

        // Додаємо ID завдання до параметрів запиту
        $data['id'] = $id;

        // Виконання запиту з параметрами
        $stmt = $db->prepare($sql);
        return $stmt->execute($data);
    }

}
