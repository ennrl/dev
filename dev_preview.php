<?php
// Безопасный просмотр: только временные папки, только чтение файлов
$tmp = sys_get_temp_dir();
$dir = basename($_GET['dir'] ?? '');
$path = $tmp . '/dev_preview_' . $dir;
$file = $path . '/' . ($_GET['file'] ?? 'index.html');
if (!is_file($file) || strpos(realpath($file), realpath($path)) !== 0) {
    http_response_code(404);
    exit('Not found');
}
$ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
$mime = [
    'html' => 'text/html',
    'htm'  => 'text/html',
    'css'  => 'text/css',
    'js'   => 'application/javascript',
    'png'  => 'image/png',
    'jpg'  => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'gif'  => 'image/gif',
    'svg'  => 'image/svg+xml',
    'ico'  => 'image/x-icon',
    'json' => 'application/json',
    'txt'  => 'text/plain',
][$ext] ?? 'application/octet-stream';
header('Content-Type: ' . $mime);
readfile($file);
