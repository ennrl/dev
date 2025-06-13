<?php
namespace DevApp\Core;

class Task {
    private $tasksFile;

    public function __construct() {
        $this->tasksFile = ROOT_PATH . '/dev_data/tasks.json';
    }

    public function create($data) {
        $tasks = $this->getTasks();
        
        $newTask = [
            'id' => uniqid(),
            'title' => $data['title'],
            'description' => $data['description'],
            'created_at' => time(),
            'projects' => []
        ];

        $tasks[] = $newTask;
        return $this->saveTasks($tasks) ? $newTask : false;
    }

    public function attachProject($taskId, $projectId) {
        $tasks = $this->getTasks();
        foreach ($tasks as &$task) {
            if ($task['id'] === $taskId) {
                if (!in_array($projectId, $task['projects'])) {
                    $task['projects'][] = $projectId;
                    return $this->saveTasks($tasks);
                }
            }
        }
        return false;
    }

    public function getAll() {
        return $this->getTasks();
    }

    private function getTasks() {
        if (!file_exists($this->tasksFile)) {
            return [];
        }
        return json_decode(file_get_contents($this->tasksFile), true) ?? [];
    }

    private function saveTasks($tasks) {
        return file_put_contents($this->tasksFile, json_encode($tasks, JSON_PRETTY_PRINT));
    }
}
