<?php
namespace DevApp\Core;

class Rating {
    private $ratingsFile;

    public function __construct() {
        $this->ratingsFile = ROOT_PATH . '/dev_data/ratings.json';
    }

    public function addRating($projectId, $userId, $rating) {
        if ($rating < 1 || $rating > 5) {
            return false;
        }

        $ratings = $this->getRatings();
        $ratings[$projectId] = $ratings[$projectId] ?? [];
        $ratings[$projectId][$userId] = [
            'rating' => $rating,
            'timestamp' => time()
        ];

        return $this->saveRatings($ratings);
    }

    public function removeRating($projectId, $userId) {
        $ratings = $this->getRatings();
        if (isset($ratings[$projectId][$userId])) {
            unset($ratings[$projectId][$userId]);
            return $this->saveRatings($ratings);
        }
        return false;
    }

    public function getProjectRating($projectId) {
        $ratings = $this->getRatings();
        if (!isset($ratings[$projectId])) {
            return [
                'average' => 0,
                'count' => 0
            ];
        }

        $projectRatings = array_column($ratings[$projectId], 'rating');
        return [
            'average' => count($projectRatings) > 0 ? array_sum($projectRatings) / count($projectRatings) : 0,
            'count' => count($projectRatings)
        ];
    }

    public function getUserRating($projectId, $userId) {
        $ratings = $this->getRatings();
        return $ratings[$projectId][$userId]['rating'] ?? null;
    }

    private function getRatings() {
        if (!file_exists($this->ratingsFile)) {
            return [];
        }
        return json_decode(file_get_contents($this->ratingsFile), true) ?? [];
    }

    private function saveRatings($ratings) {
        return file_put_contents($this->ratingsFile, json_encode($ratings, JSON_PRETTY_PRINT));
    }
}
