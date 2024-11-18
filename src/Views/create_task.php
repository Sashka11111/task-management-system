<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Створення завдання</title>
</head>
<body>
<h1>Створити нове завдання</h1>
<form method="POST">
    <label for="title">Назва:</label><br>
    <input type="text" id="title" name="title"><br><br>

    <label for="description">Опис:</label><br>
    <textarea id="description" name="description"></textarea><br><br>

    <label for="status">Статус:</label><br>
  <select id="status" name="status">
    <option value="Pending">Нове</option>
    <option value="In Progress">В процесі</option>
    <option value="Completed">Завершено</option>
  </select>
><br><br>

    <input type="submit" value="Створити">
</form>
</body>
</html>
