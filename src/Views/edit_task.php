<?php if (isset($task)): ?>
  <h1>Редагувати завдання</h1>
  <form method="POST">
    <label for="title">Назва завдання</label>
    <input type="text" name="title" value="<?= htmlspecialchars($task['title'] ?? '') ?>" required>

    <label for="description">Опис завдання</label>
    <textarea name="description"><?= htmlspecialchars($task['description'] ?? '') ?></textarea>

    <label for="status">Статус</label>
    <select name="status" required>
      <option value="Pending" <?= $task['status'] == 'Pending' ? 'selected' : '' ?>>Нове</option>
      <option value="In Progress" <?= $task['status'] == 'In Progress' ? 'selected' : '' ?>>В процесі</option>
      <option value="Completed" <?= $task['status'] == 'Completed' ? 'selected' : '' ?>>Завершено</option>
    </select>

    <button type="submit">Оновити завдання</button>
  </form>

<?php else: ?>
  <p>Завдання не знайдено.</p>
<?php endif; ?>
