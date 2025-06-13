<?php
namespace DevApp\Core;

class Auth {
    private $user = null;
    private $usersFile;

    public function __construct() {
        $this->usersFile = ROOT_PATH . '/dev_data/users.json';
        $this->checkSession();
    }

    public function login($username, $password) {
        $users = $this->getUsers();
        
        foreach ($users as $user) {
            if ($user['username'] === $username && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $this->user = $user;
                return true;
            }
        }
        return false;
    }

    public function logout() {
        unset($_SESSION['user_id']);
        $this->user = null;
    }

    public function isAdmin() {
        return $this->user && $this->user['role'] === 'admin';
    }

    public function getUser() {
        return $this->user;
    }

    private function checkSession() {
        if (isset($_SESSION['user_id'])) {
            $users = $this->getUsers();
            foreach ($users as $user) {
                if ($user['id'] === $_SESSION['user_id']) {
                    $this->user = $user;
                    break;
                }
            }
        }
    }

    private function getUsers() {
        if (!file_exists($this->usersFile)) {
            return [];
        }
        return json_decode(file_get_contents($this->usersFile), true) ?? [];
    }
}
