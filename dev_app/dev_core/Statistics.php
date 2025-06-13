<?php
namespace DevApp\Core;

class Statistics {
    private $statsFile;

    public function __construct() {
        $this->statsFile = ROOT_PATH . '/dev_data/statistics.json';
    }

    public function logAction($userId, $type, $data = []) {
        $stats = $this->getStats();
        
        $action = [
            'timestamp' => time(),
            'type' => $type,
            'data' => $data
        ];

        $stats['users'][$userId]['actions'][] = $action;
        $stats['global'][$type] = ($stats['global'][$type] ?? 0) + 1;

        return $this->saveStats($stats);
    }

    public function getUserStats($userId) {
        $stats = $this->getStats();
        $userStats = $stats['users'][$userId] ?? ['actions' => []];

        return [
            'projects_created' => $this->countUserActions($userStats, 'project_create'),
            'files_edited' => $this->countUserActions($userStats, 'file_edit'),
            'comments_added' => $this->countUserActions($userStats, 'comment_add'),
            'ratings_given' => $this->countUserActions($userStats, 'rating_add'),
            'last_activity' => $this->getLastActivity($userStats['actions'])
        ];
    }

    public function getGlobalStats() {
        $stats = $this->getStats();
        return $stats['global'] ?? [];
    }

    public function exportStats() {
        return json_encode($this->getStats(), JSON_PRETTY_PRINT);
    }

    private function countUserActions($userStats, $type) {
        return count(array_filter($userStats['actions'], function($action) use ($type) {
            return $action['type'] === $type;
        }));
    }

    private function getLastActivity($actions) {
        if (empty($actions)) {
            return null;
        }
        $lastAction = end($actions);
        return $lastAction['timestamp'];
    }

    private function getStats() {
        if (!file_exists($this->statsFile)) {
            return ['users' => [], 'global' => []];
        }
        return json_decode(file_get_contents($this->statsFile), true) ?? ['users' => [], 'global' => []];
    }

    private function saveStats($stats) {
        return file_put_contents($this->statsFile, json_encode($stats, JSON_PRETTY_PRINT));
    }
}
