<?php
namespace DevApp\Core;

class User {
    private $usersFile;

    public function __construct() {
        $this->usersFile = ROOT_PATH . '/dev_data/users.json';
    }

    public function create($data) {
        if (!$this->validateUserData($data)) {
            return false;
        }

        $users = $this->getUsers();
        
        // Проверка существования пользователя
        foreach ($users as $user) {
            if ($user['username'] === $data['username']) {
                return false;
            }
        }

        $newUser = [
            'id' => uniqid(),
            'username' => $data['username'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            'role' => $data['role'] ?? 'student',
            'created_at' => time()
        ];

        $users[] = $newUser;
        return $this->saveUsers($users);
    }

    public function update($id, $data) {
        $users = $this->getUsers();
        foreach ($users as &$user) {
            if ($user['id'] === $id) {
                if (isset($data['password'])) {
                    $user['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                }
                if (isset($data['username'])) {
                    $user['username'] = $data['username'];
                }
                return $this->saveUsers($users);
            }
        }
        return false;
    }

    public function delete($id) {
        $users = $this->getUsers();
        foreach ($users as $key => $user) {
            if ($user['id'] === $id) {
                unset($users[$key]);
                return $this->saveUsers(array_values($users));
            }
        }
        return false;
    }

    public function get($id) {
        $users = $this->getUsers();
        foreach ($users as $user) {
            if ($user['id'] === $id) {
                return $user;
            }
        }
        return null;
    }

    private function getUsers() {
        if (!file_exists($this->usersFile)) {
            return [];
        }
        return json_decode(file_get_contents($this->usersFile), true) ?? [];
    }

    private function saveUsers($users) {
        return file_put_contents($this->usersFile, json_encode($users, JSON_PRETTY_PRINT));
    }

    private function validateUserData($data) {
        return !empty($data['username']) && !empty($data['password']);
    }
}
