<?php
class NotificationController {
    public function handle() {
        if (!is_logged_in()) redirect('/?route=login');
        $lang = $_GET['lang'] ?? 'ru';
        $t = load_lang($lang);
        $username = $_SESSION['user']['username'];
        if (isset($_GET['mark_read'])) {
            mark_notifications_read($username);
            redirect('/?route=notifications');
        }
        $notifications = get_notifications($username);
        $content = __DIR__ . '/../../views/notifications.php';
        include __DIR__ . '/../../views/layout.php';
    }
}
