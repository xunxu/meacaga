<?php
/**
 * Created by PhpStorm.
 * User: Jesus
 * Date: 28/10/2015
 * Time: 11:30
 * Class to handle all db operations
 * This class will have CRUD methods for database tables
 */


class db_handler {

    private $conn;

    function __construct() {
        require_once dirname(__FILE__) . './db_connect.php';
        // opening db connection
        $db = new db_connect();
        $this->conn = $db->connect();
    }

    /**
     * Creating new user
     * @param String $name User full name
     * @param String $email User login email id
     * @param String $password User login password
     */
    public function createScore($placeId) {
        $response = array();

        // insert query
        $stmt = $this->conn->prepare("INSERT INTO score(PlaceId, Paper, Size, WaitTime, Cleanliness, Smell) values($placeId, )");

        $result = $stmt->execute();

        $stmt->close();
    }

    /* ------------- `users` table method ------------------ */

    /**
     * Fetching user by email
     * @param String $placeId place ID
     * @return null
     */
    public function getScore($placeId) {
        $stmt = $this->conn->prepare("SELECT Paper FROM score WHERE ScoreId = ?");
        $stmt->bind_param("s", $placeId);
        if ($stmt->execute()) {
            $score = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            return $score;
        } else {
            return NULL;
        }
    }


    /* ------------- `tasks` table method ------------------ */

    /**
     * Creating new task
     * @param String $user_id user id to whom task belongs to
     * @param String $task task text
     */
    public function createTask($user_id, $task) {
        $stmt = $this->conn->prepare("INSERT INTO tasks(task) VALUES(?)");
        $stmt->bind_param("s", $task);
        $result = $stmt->execute();
        $stmt->close();

        if ($result) {
            // task row created
            // now assign the task to user
            $new_task_id = $this->conn->insert_id;
            $res = $this->createUserTask($user_id, $new_task_id);
            if ($res) {
                // task created successfully
                return $new_task_id;
            } else {
                // task failed to create
                return NULL;
            }
        } else {
            // task failed to create
            return NULL;
        }
    }

    /**
     * Fetching single task
     * @param String $task_id id of the task
     */
    public function getTask($task_id, $user_id) {
        $stmt = $this->conn->prepare("SELECT t.id, t.task, t.status, t.created_at from tasks t, user_tasks ut WHERE t.id = ? AND ut.task_id = t.id AND ut.user_id = ?");
        $stmt->bind_param("ii", $task_id, $user_id);
        if ($stmt->execute()) {
            $task = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            return $task;
        } else {
            return NULL;
        }
    }

    /**
     * Fetching all user tasks
     * @param String $user_id id of the user
     */
    public function getAllUserTasks($user_id) {
        $stmt = $this->conn->prepare("SELECT t.* FROM tasks t, user_tasks ut WHERE t.id = ut.task_id AND ut.user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $tasks = $stmt->get_result();
        $stmt->close();
        return $tasks;
    }

}

?>