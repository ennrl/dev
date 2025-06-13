<?php
namespace DevApp\Controllers;

use DevApp\Core\Controller;
use DevApp\Core\Project;
use DevApp\Core\Auth;

class ProjectController extends Controller {
    private $project;
    private $auth;

    public function __construct() {
        parent::__construct();
        $this->project = new Project();
        $this->auth = new Auth();
    }

    public function indexAction() {
        $user = $this->auth->getUser();
        $projects = $this->project->getUserProjects($user['id']);
        $this->render('project/index', ['projects' => $projects]);
    }

    public function editorAction() {
        $projectId = $_GET['id'] ?? null;
        $project = $this->project->getProject($projectId);
        
        if (!$project) {
            $this->redirect('/projects');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $filename = $_POST['filename'] ?? '';
            $content = $_POST['content'] ?? '';
            
            if ($this->project->saveFile($projectId, $filename, $content)) {
                echo json_encode(['success' => true]);
                exit;
            }
            
            echo json_encode(['success' => false]);
            exit;
        }

        $this->render('project/editor', ['project' => $project]);
    }
}
