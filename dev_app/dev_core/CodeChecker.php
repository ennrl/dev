<?php
namespace DevApp\Core;

class CodeChecker {
    public function checkProject($projectId) {
        $projectPath = ROOT_PATH . '/dev_data/projects/' . $projectId;
        $results = [
            'html' => $this->checkHtml($projectPath . '/index.html'),
            'css' => $this->checkCss($projectPath),
            'js' => $this->checkJs($projectPath)
        ];
        return $results;
    }

    private function checkHtml($file) {
        if (!file_exists($file)) {
            return ['status' => 'error', 'message' => 'index.html not found'];
        }

        $content = file_get_contents($file);
        $results = [
            'h1' => strpos($content, '<h1') !== false,
            'img' => strpos($content, '<img') !== false,
            'script' => strpos($content, '<script') !== false
        ];

        return [
            'status' => 'success',
            'checks' => $results
        ];
    }

    private function checkCss($path) {
        $cssFiles = glob($path . '/*.css');
        if (empty($cssFiles)) {
            return ['status' => 'error', 'message' => 'No CSS files found'];
        }

        $content = '';
        foreach ($cssFiles as $file) {
            $content .= file_get_contents($file);
        }

        $results = [
            'body_background' => preg_match('/body\s*{[^}]*background/', $content),
            'has_class' => preg_match('/\.[a-zA-Z][a-zA-Z0-9_-]*\s*{/', $content)
        ];

        return [
            'status' => 'success',
            'checks' => $results
        ];
    }

    private function checkJs($path) {
        $jsFiles = glob($path . '/*.js');
        if (empty($jsFiles)) {
            return ['status' => 'error', 'message' => 'No JS files found'];
        }

        $content = '';
        foreach ($jsFiles as $file) {
            $content .= file_get_contents($file);
        }

        $results = [
            'has_function' => preg_match('/function\s+[a-zA-Z]/', $content),
            'has_alert' => strpos($content, 'alert(') !== false
        ];

        return [
            'status' => 'success',
            'checks' => $results
        ];
    }
}
