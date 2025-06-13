<?php
namespace DevApp\Controllers;

use DevApp\Core\Controller;
use DevApp\Core\Backup;
use DevApp\Core\Auth;
use DevApp\Core\Project;

class BackupController extends Controller {
    private $backup;
    private $auth;
    private $project;

    public function __construct() {
        parent::__construct();
        $this->backup = new Backup();
        $this->auth = new Auth();
        $this->project = new Project();
    }

    public function createAction() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $projectId = $_POST['project_id'] ?? '';
            $project = $this->project->getProject($projectId);
            $user = $this->auth->getUser();

            if (!$project || ($project['user_id'] !== $user['id'] && 
                !in_array($user['id'], $project['collaborators']))) {
                echo json_encode(['success' => false]);
                exit;
            }

            $backup = $this->backup->createBackup($projectId);
            if ($backup) {
                echo json_encode(['success' => true, 'backup' => $backup]);
                exit;
            }
        }
        
        echo json_encode(['success' => false]);
        exit;
    }

    public function restoreAction() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $projectId = $_POST['project_id'] ?? '';
            $backupName = $_POST['backup_name'] ?? '';
            
            $project = $this->project->getProject($projectId);
            $user = $this->auth->getUser();

            if (!$project || ($project['user_id'] !== $user['id'] && 
                !in_array($user['id'], $project['collaborators']))) {
                echo json_encode(['success' => false]);
                exit;
            }

            if ($this->backup->restoreBackup($projectId, $backupName)) {
                echo json_encode(['success' => true]);
                exit;
            }
        }
        
        echo json_encode(['success' => false]);
        exit;
    }

    public function listAction() {
        $projectId = $_GET['project_id'] ?? '';
        $backups = $this->backup->getBackups($projectId);
        $this->render('backup/list', ['backups' => $backups, 'projectId' => $projectId]);
    }
}
