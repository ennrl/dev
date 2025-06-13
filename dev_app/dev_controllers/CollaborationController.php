<?php
namespace DevApp\Controllers;

use DevApp\Core\Controller;
use DevApp\Core\Project;
use DevApp\Core\Auth;
use DevApp\Core\Notification;

class CollaborationController extends Controller {
    private $project;
    private $auth;
    private $notification;

    public function __construct() {
        parent::__construct();
        $this->project = new Project();
        $this->auth = new Auth();
        $this->notification = new Notification();
    }

    public function inviteAction() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $projectId = $_POST['project_id'] ?? '';
            $username = $_POST['username'] ?? '';
            
            if ($this->project->inviteCollaborator($projectId, $username)) {
                $project = $this->project->getProject($projectId);
                $this->notification->create(
                    $project['collaborators'][count($project['collaborators']) - 1],
                    'project_invitation',
                    ['project_id' => $projectId, 'project_name' => $project['name']]
                );
                echo json_encode(['success' => true]);
                exit;
            }
            
            echo json_encode(['success' => false]);
            exit;
        }
    }

    public function leaveAction() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $projectId = $_POST['project_id'] ?? '';
            $userId = $this->auth->getUser()['id'];
            
            if ($this->project->removeCollaborator($projectId, $userId)) {
                echo json_encode(['success' => true]);
                exit;
            }
            
            echo json_encode(['success' => false]);
            exit;
        }
    }
}
