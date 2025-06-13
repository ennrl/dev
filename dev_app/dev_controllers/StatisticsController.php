<?php
namespace DevApp\Controllers;

use DevApp\Core\Controller;
use DevApp\Core\Statistics;
use DevApp\Core\Auth;

class StatisticsController extends Controller {
    private $statistics;
    private $auth;

    public function __construct() {
        parent::__construct();
        $this->statistics = new Statistics();
        $this->auth = new Auth();
    }

    public function indexAction() {
        $user = $this->auth->getUser();
        
        if ($this->auth->isAdmin()) {
            $globalStats = $this->statistics->getGlobalStats();
            $this->render('statistics/admin', ['stats' => $globalStats]);
        } else {
            $userStats = $this->statistics->getUserStats($user['id']);
            $this->render('statistics/user', ['stats' => $userStats]);
        }
    }

    public function exportAction() {
        if (!$this->auth->isAdmin()) {
            $this->redirect('/statistics');
        }

        header('Content-Type: application/json');
        header('Content-Disposition: attachment; filename="statistics.json"');
        echo $this->statistics->exportStats();
        exit;
    }
}
