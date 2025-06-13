<?php
namespace DevApp\Controllers;

use DevApp\Core\Controller;
use DevApp\Core\Comment;
use DevApp\Core\Auth;
use DevApp\Core\Project;
use DevApp\Core\Notification;

class CommentController extends Controller {
    private $comment;
    private $auth;
    private $project;
    private $notification;

    public function __construct() {
        parent::__construct();
        $this->comment = new Comment();
        $this->auth = new Auth();
        $this->project = new Project();
        $this->notification = new Notification();
    }

    public function addAction() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $projectId = $_POST['project_id'] ?? '';
            $text = $_POST['text'] ?? '';
            $filename = $_POST['filename'] ?? null;
            $user = $this->auth->getUser();

            $comment = $this->comment->addComment($projectId, $user['id'], $text, $filename);
            
            if ($comment) {
                // Уведомляем владельца проекта
                $project = $this->project->getProject($projectId);
                if ($project['user_id'] !== $user['id']) {
                    $this->notification->create($project['user_id'], 'new_comment', [
                        'project_id' => $projectId,
                        'comment_id' => $comment['id']
                    ]);
                }
                
                echo json_encode(['success' => true, 'comment' => $comment]);
                exit;
            }
        }
        
        echo json_encode(['success' => false]);
        exit;
    }

    public function deleteAction() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $commentId = $_POST['comment_id'] ?? '';
            $user = $this->auth->getUser();
            
            if ($this->comment->deleteComment($commentId, $user['id'])) {
                echo json_encode(['success' => true]);
                exit;
            }
        }
        
        echo json_encode(['success' => false]);
        exit;
    }
}
