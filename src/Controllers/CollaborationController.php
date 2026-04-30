<?php

namespace Hub\Controllers;

use Hub\Models\Resource;

class CollaborationController {
    private $resourceModel;

    public function __construct() {
        $this->resourceModel = new Resource();
    }

    public function submitPR($forkId) {
        $this->checkAuth();
        $fork = $this->resourceModel->findById($forkId);
        if (!$fork || !$fork['parent_id']) {
            $_SESSION['error'] = "Invalid fork for submission.";
            header('Location: ' . \Hub\Core\Config::BASE_URL . '/');
            exit;
        }

        $data = [
            'sender_id' => $_SESSION['user_id'],
            'original_id' => $fork['parent_id'],
            'fork_id' => $forkId,
            'message' => $_POST['message'] ?? 'Proposed research contributions.'
        ];

        if ($this->resourceModel->createPR($data)) {
            $_SESSION['success'] = "Pull Request submitted to the original author!";
        } else {
            $_SESSION['error'] = "Failed to submit Pull Request.";
        }
        header('Location: ' . \Hub\Core\Config::BASE_URL . '/view/$forkId');
        exit;
    }

    public function index() {
        $this->checkAuth();
        $requests = $this->resourceModel->getPendingPRs($_SESSION['user_id']);
        
        $this->render('resources/collaboration', [
            'requests' => $requests,
            'title' => 'Collaboration Hub'
        ]);
    }

    public function review($prId) {
        $this->checkAuth();
        $pr = $this->resourceModel->getPR($prId);
        if (!$pr) {
            $_SESSION['error'] = "Request not found.";
            header('Location: ' . \Hub\Core\Config::BASE_URL . '/collaboration');
            exit;
        }

        $this->render('resources/review', [
            'pr' => $pr,
            'title' => 'Review Contribution'
        ]);
    }

    public function merge($prId) {
        $this->checkAuth();
        if ($this->resourceModel->mergePR($prId)) {
            $_SESSION['success'] = "Contribution merged! The paper has been updated and a co-author credited.";
        } else {
            $_SESSION['error'] = "Merge failed.";
        }
        header('Location: ' . \Hub\Core\Config::BASE_URL . '/collaboration');
        exit;
    }

    private function checkAuth() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . \Hub\Core\Config::BASE_URL . '/login');
            exit;
        }
    }

    private function render($view, $data = []) {
        extract($data);
        require_once __DIR__ . "/../../views/layouts/main.php";
    }
}
