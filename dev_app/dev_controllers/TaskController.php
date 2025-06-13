<?php
namespace DevApp\Controllers;

use DevApp\Core\Controller;
use DevApp\Core\Task;
use DevApp\Core\Auth;
use DevApp\Core\Project;

class TaskController extends Controller {
    private $task;
    private $auth;
    private $project;

    public function __construct() {
        parent::__construct();
        $this->task = new Task();
        $this->auth = new Auth();
        $this->project = new Project();
    }

    public function indexAction() {
        $tasks = $this->task->getAll();
        $this->render('task/index', ['tasks' => $tasks]);
    }

    public function createAction() {
        if (!$this->auth->isAdmin()) {
            $this->redirect('/tasks');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->task->create($_POST)) {
                $this->redirect('/tasks');
            }
        }

        $this->render('task/create');
    }

    public function attachAction() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $taskId = $_POST['task_id'] ?? null;
            $projectId = $_POST['project_id'] ?? null;

            if ($taskId && $projectId) {
                if ($this->task->attachProject($taskId, $projectId)) {
                    echo json_encode(['success' => true]);
                    exit;
                }
            }
            
            echo json_encode(['success' => false]);
            exit;
        }
    }
}
