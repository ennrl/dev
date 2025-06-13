<?php
namespace DevApp\Core;

class Backup {
    private $backupsPath;
    
    public function __construct() {
        $this->backupsPath = ROOT_PATH . '/dev_data/backups/';
        if (!is_dir($this->backupsPath)) {
            mkdir($this->backupsPath, 0777, true);
        }
    }

    public function createBackup($projectId) {
        $project = (new Project())->getProject($projectId);
        if (!$project) {
            return false;
        }

        $backupName = $projectId . '_' . time() . '.zip';
        $backupPath = $this->backupsPath . $backupName;

        $zip = new \ZipArchive();
        if ($zip->open($backupPath, \ZipArchive::CREATE) !== true) {
            return false;
        }

        $projectPath = ROOT_PATH . '/dev_data/projects/' . $projectId;
        $this->addDirToZip($zip, $projectPath, '');
        $zip->close();

        $this->saveBackupInfo($projectId, $backupName);
        return $backupName;
    }

    public function restoreBackup($projectId, $backupName) {
        $backupPath = $this->backupsPath . $backupName;
        if (!file_exists($backupPath)) {
            return false;
        }

        $projectPath = ROOT_PATH . '/dev_data/projects/' . $projectId;
        $this->clearDirectory($projectPath);

        $zip = new \ZipArchive();
        if ($zip->open($backupPath) !== true) {
            return false;
        }

        $zip->extractTo($projectPath);
        $zip->close();

        return true;
    }

    public function getBackups($projectId) {
        $backupsFile = $this->backupsPath . 'backups.json';
        if (!file_exists($backupsFile)) {
            return [];
        }
        $backups = json_decode(file_get_contents($backupsFile), true) ?? [];
        return $backups[$projectId] ?? [];
    }

    private function saveBackupInfo($projectId, $backupName) {
        $backupsFile = $this->backupsPath . 'backups.json';
        $backups = file_exists($backupsFile) ? 
            json_decode(file_get_contents($backupsFile), true) : [];
        
        $backups[$projectId][] = [
            'name' => $backupName,
            'created_at' => time()
        ];

        file_put_contents($backupsFile, json_encode($backups, JSON_PRETTY_PRINT));
    }

    private function addDirToZip($zip, $path, $relativePath) {
        $dir = opendir($path);
        while ($entry = readdir($dir)) {
            if ($entry != '.' && $entry != '..') {
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

    private function clearDirectory($path) {
        if (is_dir($path)) {
            $files = array_diff(scandir($path), ['.', '..']);
            foreach ($files as $file) {
                $filePath = $path . '/' . $file;
                is_dir($filePath) ? $this->clearDirectory($filePath) : unlink($filePath);
            }
        }
    }
}
