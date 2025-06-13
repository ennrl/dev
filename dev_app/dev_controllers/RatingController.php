<?php
namespace DevApp\Controllers;

use DevApp\Core\Controller;
use DevApp\Core\Rating;
use DevApp\Core\Auth;
use DevApp\Core\Project;

class RatingController extends Controller {
    private $rating;
    private $auth;
    private $project;

    public function __construct() {
        parent::__construct();
        $this->rating = new Rating();
        $this->auth = new Auth();
        $this->project = new Project();
    }

    public function rateAction() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $projectId = $_POST['project_id'] ?? '';
            $rating = (int)($_POST['rating'] ?? 0);
            $userId = $this->auth->getUser()['id'];
            
            // Проверяем, может ли пользователь оценивать этот проект
            $project = $this->project->getProject($projectId);
            if ($project['user_id'] === $userId || in_array($userId, $project['collaborators'])) {
                echo json_encode(['success' => false, 'error' => 'Cannot rate own project']);
                exit;
            }

            if ($this->rating->addRating($projectId, $userId, $rating)) {
                echo json_encode(['success' => true]);
                exit;
            }
            
            echo json_encode(['success' => false]);
            exit;
        }
    }

    public function removeRatingAction() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $projectId = $_POST['project_id'] ?? '';
            $userId = $this->auth->getUser()['id'];
            
            if ($this->rating->removeRating($projectId, $userId)) {
                echo json_encode(['success' => true]);
                exit;
            }
            
            echo json_encode(['success' => false]);
            exit;
        }
    }
}
