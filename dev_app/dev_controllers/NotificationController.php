<?php
namespace DevApp\Controllers;

use DevApp\Core\Controller;
use DevApp\Core\Notification;
use DevApp\Core\Auth;

class NotificationController extends Controller {
    private $notification;
    private $auth;

    public function __construct() {
        parent::__construct();
        $this->notification = new Notification();
        $this->auth = new Auth();
    }

    public function indexAction() {
        $user = $this->auth->getUser();
        $notifications = $this->notification->getUserNotifications($user['id']);
        $this->render('notification/index', ['notifications' => $notifications]);
    }

    public function markReadAction() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = $this->auth->getUser();
            $notificationId = $_POST['notification_id'] ?? '';
            
            if ($this->notification->markAsRead($notificationId, $user['id'])) {
                echo json_encode(['success' => true]);
                exit;
            }
            
            echo json_encode(['success' => false]);
            exit;
        }
    }
}
