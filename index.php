<?php

use Liamtseva\TaskManagementSystem\Controllers\TaskController;
use Liamtseva\TaskManagementSystem\Controllers\UserController;
use Liamtseva\TaskManagementSystem\Router\Router;

require_once __DIR__ . '/vendor/autoload.php';

// Створюємо екземпляр роутера
$router = new Router();

// Додаємо маршрути
$router->add('/', function () {
    echo 'Головна сторінка';
});

$router->add('/login', [UserController::class, 'login']);
$router->add('/register', [UserController::class, 'register']);
$router->add('/tasks', [TaskController::class, 'list']);
$router->add('/tasks/create', [TaskController::class, 'create']);

$router->add('/tasks/update/{id}', function ($id) {
    // Підключаємо контролер та викликаємо метод оновлення завдання
    $controller = new TaskController();
    $controller->update((int)$id); // Викликаємо метод оновлення завдання
});

// Додавання маршруту для видалення
$router->add('/tasks/delete/{id}', function ($id) {
    // Підключаємо контролер та викликаємо метод видалення завдання
    $controller = new TaskController();
    $controller->delete((int)$id); // Викликаємо метод видалення завдання
});

$router->add('/tasks/{taskId}/assign/{userId}', function ($taskId, $userId) {
    (new TaskController())->assignTo((int)$taskId, (int)$userId);
});

$router->add('/tasks/{taskId}/status/{status}', function ($taskId, $status) {
    (new TaskController())->changeStatus((int)$taskId, $status);
});
$router->add('/logout', function () {
    (new UserController())->logout();
});
$router->add('/change-password', function () {
    (new UserController())->changePassword();
});

// Отримуємо шлях із запиту
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$router->dispatch($path);
