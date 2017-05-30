<?php

require_once __DIR__ . '/../autoload.php';

$task = new models\Tasks\Task;

if (!empty($_POST['addTask'])) {
    $task->addTask();
    echo $task->getLastTask();
}

if (!empty($_POST['done'])) {
    echo $task->setTaskIsDone() ? 'Выполнено' : 'В процессе';
}

if (!empty($_POST['delete'])) {
    $task->deleteTask();
}

if (!empty($_POST['editDescription'])) {
    echo $task->editTask();
}

if (!empty($_POST['sortBy'])) {
    $table = new models\Tasks\TaskTable;
    echo $table->sortTable($_POST['sortBy']);
}