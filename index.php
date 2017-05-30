<?php

use models\Auth\Auth;

require_once __DIR__ . '/src/core.php';

var_dump($_SESSION);

$auth = new Auth;
if (!$auth->isAuth()) {
    header('Location: auth.php');
}

$task = new models\Tasks\Task;
$allTasks = $task->getAllTasks();

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="web/style/index.css">
    <title>Task manager</title>
</head>
<body>
<div id="wrapper">
    <a class="logout" href=".">&larr; Выйти</a>
    <div class="tasks">
        <?php if ($allTasks->rowCount() === 0): ?>
            <p class="smile">&#9785;</p>
            <p class="tasksNotExist"><?php echo $_SESSION['user']; ?>, вы пока не добавили ни одной задачи</p>
        <?php else: ?>
            <form method="POST" class="sortForm">
                <h2 class="nickname"><?php echo $_SESSION['user']; ?>, это задачи, созданные вами</h2>
                <div>
                    <label>
                        Сортировать по:
                        <select name="sortBy" id="sortBy">
                            <option value="date">Дате добавления</option>
                            <option value="status">Статусу</option>
                            <option value="description">Описанию</option>
                        </select>
                    </label>
                    <input type="submit" name="sort" id="sort" value="Сортировка">
                </div>
            </form>

            <table>
                <tr>
                    <td>Задача</td>
                    <td>Автор</td>
                    <td>Исполнитель</td>
                    <td>Статус</td>
                    <td>Дата добавления</td>
                    <td>Действия</td>
                </tr>
                <?php foreach ($allTasks as $task): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($task['description']) ?></td>
                        <td><?php echo $task['user_id'] ?></td>
                        <td><?php echo $task['assigned_user_id'] ?></td>
                        <?php echo htmlspecialchars($task['is_done']) ? '<td style="color: green">Выполнено</td>' : '<td style="color: orange">В процессе</td>' ?>
                        <td><?php echo htmlspecialchars($task['date_added']) ?></td>
                        <td>
                            <p class='edit link'>Изменить &#9998;</p>
                            <?php if (!$task['is_done']): ?>
                                <p class='done link'>Выполнить &#10004;</p>
                            <?php endif; ?>
                            <p class='delete link'>Удалить &cross;</p>
                            <input type="hidden" value="<?php echo $task['id'] ?>">
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>

    </div>

    <div class="tasksForYou">
        <h2>А это задачи, созданные другими пользователями для вас:</h2>
    </div>

    <div class="forms">
        <form method="POST" class="addTaskForm">
            <textarea name="task" placeholder="Задача" id="task" cols="50" rows="3" required></textarea>
            <input type="submit" name="addTask" value="Добавить задачу" class="button">
        </form>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="http://code.jquery.com/color/jquery.color-2.1.2.min.js"></script>
<script src="web/js/index.js"></script>

</body>
</html>
