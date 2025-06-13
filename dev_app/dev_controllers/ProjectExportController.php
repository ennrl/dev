<?php
namespace DevApp\Controllers;

use DevApp\Core\Controller;
use DevApp\Core\Project;
use DevApp\Core\Auth;

class ProjectExportController extends Controller {
    private $project;
    private $auth;

    public function __construct() {
        parent::__construct();
        $this->project = new Project();
        $this->auth = new Auth();
    }

    public function exportAction() {
        $projectId = $_GET['id'] ?? '';
        $user = $this->auth->getUser();
        
        $project = $this->project->getProject($projectId);
        if (!$project || ($project['user_id'] !== $user['id'] && !in_array($user['id'], $project['collaborators']))) {
            $this->redirect('/projects');
        }

        $zipFile = $this->project->exportProject($projectId);
        if ($zipFile) {
            header('Content-Type: application/zip');
            header('Content-Disposition: attachment; filename="' . basename($zipFile) . '"');
            readfile($zipFile);
            unlink($zipFile);
            exit;
        }
        
        $this->redirect('/projects');
    }

    public function importAction() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->render('project/import');
            return;
        }

        if (!isset($_FILES['project_zip']) || $_FILES['project_zip']['error'] !== UPLOAD_ERR_OK) {
            $this->data['error'] = t('Upload failed');
            $this->render('project/import');
            return;
        }

        $user = $this->auth->getUser();
        $result = $this->project->importProject($_FILES['project_zip']['tmp_name'], $user['id']);
        
        if ($result) {
            $this->redirect('/project/editor?id=' . $result['id']);
        } else {
            $this->data['error'] = t('Invalid project archive');
            $this->render('project/import');
        }
    }
}
