<?php
namespace DevApp\Core;

class Comment {
    private $commentsFile;
    private $statistics;

    public function __construct() {
        $this->commentsFile = ROOT_PATH . '/dev_data/comments.json';
        $this->statistics = new Statistics();
    }

    public function addComment($projectId, $userId, $text, $filename = null) {
        $comments = $this->getComments();
        
        $newComment = [
            'id' => uniqid(),
            'project_id' => $projectId,
            'user_id' => $userId,
            'text' => $text,
            'filename' => $filename,
            'created_at' => time()
        ];

        $comments[] = $newComment;
        
        if ($this->saveComments($comments)) {
            $this->statistics->logAction($userId, 'comment_add', [
                'project_id' => $projectId,
                'comment_id' => $newComment['id']
            ]);
            return $newComment;
        }
        return false;
    }

    public function getProjectComments($projectId) {
        $comments = $this->getComments();
        return array_filter($comments, function($comment) use ($projectId) {
            return $comment['project_id'] === $projectId;
        });
    }

    public function deleteComment($commentId, $userId) {
        $comments = $this->getComments();
        foreach ($comments as $key => $comment) {
            if ($comment['id'] === $commentId && $comment['user_id'] === $userId) {
                unset($comments[$key]);
                return $this->saveComments(array_values($comments));
            }
        }
        return false;
    }

    private function getComments() {
        if (!file_exists($this->commentsFile)) {
            return [];
        }
        return json_decode(file_get_contents($this->commentsFile), true) ?? [];
    }

    private function saveComments($comments) {
        return file_put_contents($this->commentsFile, json_encode($comments, JSON_PRETTY_PRINT));
    }
}
