<?php
namespace DevApp\Controllers;

use DevApp\Core\Controller;
use DevApp\Core\Auth;

class AuthController extends Controller {
    private $auth;

    public function __construct() {
        parent::__construct();
        $this->auth = new Auth();
    }

    public function loginAction() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            if ($this->auth->login($username, $password)) {
                $this->redirect('/dashboard');
            }
            $this->data['error'] = t('Invalid username or password');
        }
        
        $this->render('auth/login');
    }

    public function logoutAction() {
        $this->auth->logout();
        $this->redirect('/login');
    }
}
