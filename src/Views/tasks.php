<!DOCTYPE html>
<html lang="uk">
<head>
  <meta charset="UTF-8">
  <title>Мої завдання</title>
</head>
<body>
<h1>Ваші завдання</h1>
<a href="/tasks/create">Створити нове завдання</a>

<?php if (isset($tasks) && !empty($tasks)): ?>
    <?php foreach ($tasks as $task): ?>
    <div>
      <h2><?= htmlspecialchars($task['title']) ?></h2>
      <p><?= htmlspecialchars($task['description']) ?></p>
      <p>Статус: <?= htmlspecialchars($task['status']) ?></p>
      <a href="/tasks/update/<?= $task['id'] ?>">Редагувати</a>
      <a href="/tasks/delete/<?= $task['id'] ?>">Видалити</a>
    </div>
    <?php endforeach; ?>
<?php else: ?>
  <p>У вас немає завдань.</p>
<?php endif; ?>
</body>
</html>
