<?php

declare(strict_types=1);

namespace Liamtseva\TaskManagementSystem\Router;

class Router
{
    private array $routes = [];

    public function add(string $path, callable $handler): void
    {
        $this->routes[] = [
            'path' => $path,
            'handler' => $handler
        ];
    }

    public function dispatch(string $path): void
    {
        foreach ($this->routes as $route) {
            // Якщо в маршруті є динамічні параметри, вони мають відповідати регулярному виразу
            $pattern = preg_replace('/\{[a-zA-Z0-9_]+}/', '([a-zA-Z0-9_]+)', $route['path']);
            $pattern = "#^$pattern$#";  // Перетворюємо на регулярний вираз

            if (preg_match($pattern, $path, $matches)) {
                // Якщо маршрут знайдений, передаємо параметри в обробник
                array_shift($matches);  // Видаляємо перший елемент, це сам маршрут
                call_user_func_array($route['handler'], $matches);
                return;
            }
        }

        // Якщо маршрут не знайдений, повертаємо 404
        http_response_code(404);
        echo 'Сторінку не знайдено!';
    }
}
