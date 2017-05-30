<?php

namespace models\Tasks;

use models\ConnectDB;

class Task extends ConnectDB
{

    public function getAllTasks()
    {
        $pdo = $this->connectToDb();

        $query = "SELECT * FROM task";

        return $this->sendQueryToDb($pdo, $query);
    }

    public function getIdByUser()
    {
        $pdo = $this->connectToDb();

        $query = "SELECT id FROM `user` WHERE login = ?";

        $nickname = $_SESSION['user'];

        return $this->sendQueryToDb($pdo, $query, [$nickname])->fetch(\PDO::FETCH_ASSOC)['id'];
    }

    public function addTask()
    {
        $pdo = $this->connectToDb();

        $query = "INSERT INTO task (description, date_added, user_id, assigned_user_id) VALUES (?, NOW(), ?, ?)";

        $id = $this->getIdByUser();
        $description = (string)(isset($_POST['task']) ? $_POST['task'] : "");
        $description = trim($description);
        if (!strlen($description)) {
            die('зачем же вам задача из одних пробелов?');
        }

        return $this->sendQueryToDb($pdo, $query, [$description, $id, $id]);
    }

    public function getLastTask()
    {
        $pdo = $this->connectToDb();

        $query = "SELECT * FROM task ORDER BY id DESC LIMIT 1";

        $result = $this->sendQueryToDb($pdo, $query)->fetch(\PDO::FETCH_ASSOC);

        $result = json_encode($result);
        return $result;
    }

    public function setTaskIsDone()
    {
        $pdo = $this->connectToDb();

        $query = "UPDATE task SET is_done = 1 WHERE id = ?";
        $id = (int)!empty($_POST['id']) ? $_POST['id'] : 0;

        $this->sendQueryToDb($pdo, $query, [$id]);
        return true;
    }

    public function deleteTask()
    {
        $pdo = $this->connectToDb();

        $query = "DELETE FROM task WHERE id = ?";
        $id = (int)!empty($_POST['id']) ? $_POST['id'] : 0;


        $this->sendQueryToDb($pdo, $query, [$id]);
    }

    public function editTask()
    {
        $pdo = $this->connectToDb();

        $query = "UPDATE task SET description = ? WHERE id = ?";
        $description = (string)!empty($_POST['editDescription']) ? $_POST['editDescription'] : 0;
        if (!trim($description) || strlen(trim($description)) === 0) {
            die('задача из пробелов? интересно...'); // Jquery не видит этот die и выдает success, исправить!
        }
        $id = (int)!empty($_POST['id']) ? $_POST['id'] : 0;

        $this->sendQueryToDb($pdo, $query, [$description, $id]);
        return $description;
    }
}