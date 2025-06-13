<?php
namespace DevApp\Core;

class DependencyManager {
    private $cdnLibraries = [
        'bootstrap' => [
            'css' => 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css',
            'js' => 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js'
        ],
        'jquery' => [
            'js' => 'https://code.jquery.com/jquery-3.6.0.min.js'
        ],
        'fontawesome' => [
            'css' => 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css'
        ]
    ];

    public function getAvailableLibraries() {
        return array_keys($this->cdnLibraries);
    }

    public function getLibraryUrls($library) {
        return $this->cdnLibraries[$library] ?? null;
    }

    public function addToProject($projectId, $libraries) {
        $projectPath = ROOT_PATH . '/dev_data/projects/' . $projectId;
        $indexFile = $projectPath . '/index.html';
        
        if (!file_exists($indexFile)) {
            return false;
        }

        $content = file_get_contents($indexFile);
        $headPos = strpos($content, '</head>');
        $bodyPos = strpos($content, '</body>');

        if ($headPos === false || $bodyPos === false) {
            return false;
        }

        $cssLinks = '';
        $jsScripts = '';

        foreach ($libraries as $library) {
            if (isset($this->cdnLibraries[$library])) {
                if (isset($this->cdnLibraries[$library]['css'])) {
                    $cssLinks .= sprintf(
                        "\n\t<link rel=\"stylesheet\" href=\"%s\">",
                        $this->cdnLibraries[$library]['css']
                    );
                }
                if (isset($this->cdnLibraries[$library]['js'])) {
                    $jsScripts .= sprintf(
                        "\n\t<script src=\"%s\"></script>",
                        $this->cdnLibraries[$library]['js']
                    );
                }
            }
        }

        $content = substr_replace($content, $cssLinks . "\n", $headPos, 0);
        $content = substr_replace($content, $jsScripts . "\n", $bodyPos, 0);

        return file_put_contents($indexFile, $content);
    }
}
