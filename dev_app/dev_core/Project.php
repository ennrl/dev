<?php
namespace DevApp\Core;

class Project {
    private $projectsFile;
    private $projectsPath;

    public function __construct() {
        $this->projectsFile = ROOT_PATH . '/dev_data/projects.json';
        $this->projectsPath = ROOT_PATH . '/dev_data/projects/';
    }

    public function create($data, $userId) {
        $projects = $this->getProjects();
        
        $newProject = [
            'id' => uniqid(),
            'name' => $data['name'],
            'user_id' => $userId,
            'created_at' => time(),
            'participants' => []
        ];

        $projects[] = $newProject;
        
        if ($this->saveProjects($projects)) {
            mkdir($this->projectsPath . $newProject['id'], 0777, true);
            file_put_contents(
                $this->projectsPath . $newProject['id'] . '/index.html',
                '<!DOCTYPE html><html><head><title>New Project</title></head><body></body></html>'
            );
            return $newProject;
        }
        return false;
    }

    public function getProject($id) {
        $projects = $this->getProjects();
        foreach ($projects as $project) {
            if ($project['id'] === $id) {
                return $project;
            }
        }
        return null;
    }

    public function getUserProjects($userId) {
        $projects = $this->getProjects();
        return array_filter($projects, function($project) use ($userId) {
            return $project['user_id'] === $userId || in_array($userId, $project['participants']);
        });
    }

    public function saveFile($projectId, $filename, $content) {
        $path = $this->projectsPath . $projectId . '/' . $filename;
        if ($this->validateFilename($filename)) {
            if (file_put_contents($path, $content)) {
                $this->createBackup($projectId, $filename, $content);
                return true;
            }
        }
        return false;
    }

    public function exportProject($projectId) {
        $project = $this->getProject($projectId);
        if (!$project) {
            return false;
        }

        $zip = new \ZipArchive();
        $zipName = sys_get_temp_dir() . '/' . $project['name'] . '_' . time() . '.zip';

        if ($zip->open($zipName, \ZipArchive::CREATE) !== true) {
            return false;
        }

        $projectPath = $this->projectsPath . $projectId;
        $this->addDirToZip($zip, $projectPath, '');
        $zip->close();

        return $zipName;
    }

    public function importProject($zipFile, $userId) {
        $zip = new \ZipArchive();
        if ($zip->open($zipFile) !== true) {
            return false;
        }

        // Проверяем наличие index.html
        if ($zip->locateName('index.html') === false) {
            $zip->close();
            return false;
        }

        // Создаем новый проект
        $projectId = uniqid();
        $projectPath = $this->projectsPath . $projectId;
        mkdir($projectPath);

        // Проверяем файлы при распаковке
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $filename = $zip->getNameIndex($i);
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            
            if (!in_array($ext, ['html', 'css', 'js', 'php', 'txt'])) {
                $zip->close();
                $this->removeDirectory($projectPath);
                return false;
            }
        }

        $zip->extractTo($projectPath);
        $zip->close();

        // Создаем запись о проекте
        $project = [
            'id' => $projectId,
            'name' => pathinfo($zipFile, PATHINFO_FILENAME),
            'user_id' => $userId,
            'created_at' => time(),
            'collaborators' => []
        ];

        $projects = $this->getProjects();
        $projects[] = $project;
        
        return $this->saveProjects($projects) ? $project : false;
    }

    private function createBackup($projectId, $filename, $content) {
        $backupDir = $this->projectsPath . $projectId . '/.history/' . $filename;
        if (!is_dir($backupDir)) {
            mkdir($backupDir, 0777, true);
        }
        file_put_contents(
            $backupDir . '/' . time() . '.bak',
            $content
        );
    }

    private function validateFilename($filename) {
        $allowedExtensions = ['html', 'css', 'js', 'php', 'txt'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        return in_array($ext, $allowedExtensions);
    }

    private function getProjects() {
        if (!file_exists($this->projectsFile)) {
            return [];
        }
        return json_decode(file_get_contents($this->projectsFile), true) ?? [];
    }

    private function saveProjects($projects) {
        return file_put_contents($this->projectsFile, json_encode($projects, JSON_PRETTY_PRINT));
    }

    private function addDirToZip($zip, $path, $relativePath) {
        $dir = opendir($path);
        while ($entry = readdir($dir)) {
            if ($entry != '.' && $entry != '..' && $entry != '.history') {
                $filePath = $path . '/' . $entry;
                $zipPath = $relativePath . ($relativePath ? '/' : '') . $entry;
                
                if (is_file($filePath)) {
                    $zip->addFile($filePath, $zipPath);
                } elseif (is_dir($filePath)) {
                    $zip->addEmptyDir($zipPath);
                    $this->addDirToZip($zip, $filePath, $zipPath);
                }
            }
        }
        closedir($dir);
    }

    private function removeDirectory($path) {
        if (is_dir($path)) {
            $files = array_diff(scandir($path), ['.', '..']);
            foreach ($files as $file) {
                $filePath = $path . '/' . $file;
                is_dir($filePath) ? $this->removeDirectory($filePath) : unlink($filePath);
            }
            return rmdir($path);
        }
    }
}
