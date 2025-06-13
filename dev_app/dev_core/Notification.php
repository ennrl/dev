<?php
namespace DevApp\Core;

class Notification {
    private $notificationsFile;

    public function __construct() {
        $this->notificationsFile = ROOT_PATH . '/dev_data/notifications.json';
    }

    public function create($userId, $type, $data) {
        $notifications = $this->getNotifications();
        
        $newNotification = [
            'id' => uniqid(),
            'user_id' => $userId,
            'type' => $type,
            'data' => $data,
            'created_at' => time(),
            'read' => false
        ];

        array_unshift($notifications, $newNotification);
        return $this->saveNotifications($notifications);
    }

    public function getUserNotifications($userId) {
        $notifications = $this->getNotifications();
        return array_filter($notifications, function($notification) use ($userId) {
            return $notification['user_id'] === $userId;
        });
    }

    public function markAsRead($id, $userId) {
        $notifications = $this->getNotifications();
        foreach ($notifications as &$notification) {
            if ($notification['id'] === $id && $notification['user_id'] === $userId) {
                $notification['read'] = true;
                return $this->saveNotifications($notifications);
            }
        }
        return false;
    }

    private function getNotifications() {
        if (!file_exists($this->notificationsFile)) {
            return [];
        }
        return json_decode(file_get_contents($this->notificationsFile), true) ?? [];
    }

    private function saveNotifications($notifications) {
        return file_put_contents($this->notificationsFile, json_encode($notifications, JSON_PRETTY_PRINT));
    }
}
