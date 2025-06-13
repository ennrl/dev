<?php
// Ядро приложения DevManager
session_start();
require_once __DIR__ . '/../../dev_lang/lang.php';
// Функция для инициализации и запуска приложения
function dev_run() {
    require_once __DIR__ . '/../dev_controllers/AuthController.php';
    require_once __DIR__ . '/../dev_controllers/TasksController.php';
    require_once __DIR__ . '/../dev_controllers/ProjectsController.php';
    require_once __DIR__ . '/../dev_controllers/ProfileController.php';
    require_once __DIR__ . '/../dev_controllers/NotificationsController.php';
    require_once __DIR__ . '/../dev_controllers/ProjectAttachController.php';
    require_once __DIR__ . '/../dev_controllers/ProjectCheckController.php';
    require_once __DIR__ . '/../dev_controllers/UserController.php';
    if (isset($_GET['logout'])) {
        session_destroy();
        header('Location: /');
        exit;
    }
    if (isset($_GET['preview_project']) && isset($_GET['project_id'])) {
        dev_preview_project();
    } elseif (isset($_GET['export_project']) && isset($_GET['project_id'])) {
        dev_export_project();
    } elseif (isset($_GET['project_import'])) {
        dev_import_project();
    } elseif (isset($_GET['file_create']) && isset($_GET['project_id'])) {
        dev_create_file();
    } elseif (isset($_GET['file_delete']) && isset($_GET['project_id']) && isset($_GET['file'])) {
        dev_delete_file();
    } elseif (isset($_GET['file_rename']) && isset($_GET['project_id']) && isset($_GET['file'])) {
        dev_rename_file();
    } elseif (isset($_GET['file_history']) && isset($_GET['project_id']) && isset($_GET['file'])) {
        dev_file_history();
    } elseif (isset($_GET['file_edit']) && isset($_GET['project_id']) && isset($_GET['file'])) {
        dev_edit_file();
    } elseif (isset($_GET['project_rename']) && isset($_GET['project_id'])) {
        dev_rename_project();
    } elseif (isset($_GET['project_delete']) && isset($_GET['project_id'])) {
        dev_delete_project();
    } elseif (isset($_GET['project_create'])) {
        dev_create_project();
    } elseif (isset($_GET['check_project']) && isset($_GET['project_id'])) {
        dev_check_project();
    } elseif (isset($_GET['attach_project'])) {
        dev_attach_project();
    } elseif (isset($_GET['tasks_create'])) {
        dev_create_task();
    } elseif (isset($_GET['tasks'])) {
        dev_show_tasks();
    } elseif (isset($_GET['projects'])) {
        dev_show_projects();
    } elseif (isset($_GET['profile'])) {
        dev_show_profile();
    } elseif (isset($_GET['notifications'])) {
        dev_show_notifications();
    } elseif (isset($_GET['stats']) && ($_SESSION['role'] ?? '') === 'admin') {
        dev_show_stats();
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'], $_POST['password'])) {
        dev_handle_login($_POST['login'], $_POST['password']);
    } elseif (isset($_GET['rate_project']) && isset($_GET['project_id']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
        dev_rate_project();
    } elseif (isset($_GET['add_member']) && isset($_GET['project_id']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
        dev_add_member();
    } elseif (isset($_GET['users']) && ($_SESSION['role'] ?? '') === 'admin') {
        dev_users_list();
    } elseif (isset($_GET['user_create']) && ($_SESSION['role'] ?? '') === 'admin') {
        dev_user_create();
    } elseif (isset($_GET['user_edit']) && ($_SESSION['role'] ?? '') === 'admin') {
        dev_user_edit();
    } elseif (isset($_GET['user_delete']) && ($_SESSION['role'] ?? '') === 'admin') {
        dev_user_delete();
    } elseif (isset($_GET['user_reset_password']) && ($_SESSION['role'] ?? '') === 'admin') {
        dev_user_reset_password();
    } elseif (isset($_GET['restore_file']) && isset($_GET['project_id']) && isset($_GET['file']) && isset($_GET['version'])) {
        dev_restore_file_version();
    } elseif (isset($_GET['leave_project']) && isset($_GET['project_id'])) {
        dev_leave_project();
    } elseif (isset($_GET['remove_rating']) && isset($_GET['project_id'])) {
        dev_remove_rating();
    } elseif (isset($_GET['export_stats']) && ($_SESSION['role'] ?? '') === 'admin') {
        dev_export_stats();
    } else {
        dev_show_main();
    }
}

// Функция отображения главной страницы (заглушка)
function dev_show_main() {
    echo "<h1>Добро пожаловать в DevManager!</h1>";
    echo "<p>Система для обучения детей web-программированию.</p>";
}