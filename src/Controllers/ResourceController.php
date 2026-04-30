<?php

namespace Hub\Controllers;

use Hub\Models\Resource;
use Hub\Models\Comment;
use Hub\Helpers\FileValidator;
use Hub\Helpers\UuidHelper;
use Hub\Core\Config;

class ResourceController {
    private $resourceModel;
    private $commentModel;

    public function __construct() {
        $this->resourceModel = new Resource();
        $this->commentModel = new Comment();
    }

    public function index() {
        $isSearching = isset($_GET['category']) || isset($_GET['search']);
        $isWorkspace = isset($_GET['my_work']);
        
        $filters = [
            'category' => $_GET['category'] ?? '',
            'search' => $_GET['search'] ?? '',
            'user_id' => $isWorkspace ? $_SESSION['user_id'] : null,
            'dept' => (!$isSearching && !$isWorkspace && isset($_SESSION['user_dept'])) ? $_SESSION['user_dept'] : null,
            'sem' => (!$isSearching && !$isWorkspace && isset($_SESSION['user_sem'])) ? $_SESSION['user_sem'] : null
        ];
        
        $resources = $this->resourceModel->getAll($filters);
        $categories = $this->resourceModel->getCategories();
        
        $this->render('resources/index', [
            'resources' => $resources,
            'categories' => $categories,
            'title' => 'Academic Repository'
        ]);
    }

    public function showUpload() {
        $this->checkAuth();
        $categories = $this->resourceModel->getCategories();
        $this->render('resources/upload', ['categories' => $categories, 'title' => 'Upload Resource']);
    }

    public function upload() {
        $this->checkAuth();
        
        $errors = FileValidator::validateResource($_FILES['resource_file']);
        
        if (empty($errors)) {
            $ext = pathinfo($_FILES['resource_file']['name'], PATHINFO_EXTENSION);
            $filename = UuidHelper::generate() . '.' . $ext; // SRS Requirement: Secure Filenames
            $targetPath = Config::UPLOAD_DIR . $filename;
            
            if (move_uploaded_file($_FILES['resource_file']['tmp_name'], $targetPath)) {
                $data = [
                    'title' => $_POST['title'],
                    'description' => $_POST['description'],
                    'file_path' => $filename,
                    'user_id' => $_SESSION['user_id'],
                    'category_id' => $_POST['category_id'],
                    'year' => $_POST['year'],
                    'difficulty' => $_POST['difficulty'],
                    'department' => $_POST['department'],
                    'semester' => $_POST['semester'],
                    'subject_name' => $_POST['subject_name']
                ];
                
                if ($this->resourceModel->create($data)) {
                    $_SESSION['success'] = "Resource uploaded successfully!";
                    header('Location: ' . \Hub\Core\Config::BASE_URL . '/');
                    exit;
                }
            }
            $errors[] = "Failed to move uploaded file.";
        }
        
        $_SESSION['error'] = implode('<br>', $errors);
        header('Location: ' . \Hub\Core\Config::BASE_URL . '/upload');
        exit;
    }

    public function showDetail($id) {
        $resource = $this->resourceModel->findById($id);
        if (!$resource) {
            $_SESSION['error'] = "Resource not found.";
            header('Location: ' . \Hub\Core\Config::BASE_URL . '/');
            exit;
        }

        $comments = $this->commentModel->getByResource($id);
        
        $this->render('resources/detail', [
            'resource' => $resource,
            'comments' => $comments,
            'title' => $resource['title']
        ]);
    }

    public function addComment($id) {
        $this->checkAuth();
        $data = [
            'resource_id' => $id,
            'user_id' => $_SESSION['user_id'],
            'parent_id' => $_POST['parent_id'] ?: null,
            'content' => $_POST['content']
        ];

        if ($this->commentModel->create($data)) {
            $_SESSION['success'] = "Comment posted.";
        } else {
            $_SESSION['error'] = "Failed to post comment.";
        }
        header('Location: ' . \Hub\Core\Config::BASE_URL . '/view/$id');
        exit;
    }

    public function fork($id) {
        $this->checkAuth();
        if ($this->resourceModel->fork($id, $_SESSION['user_id'])) {
            $_SESSION['success'] = "Resource forked to your collection!";
        } else {
            $_SESSION['error'] = "Forking failed.";
        }
        header('Location: ' . \Hub\Core\Config::BASE_URL . '/');
        exit;
    }

    public function delete($id) {
        $this->checkAuth();
        if ($this->resourceModel->softDelete($id, $_SESSION['user_id'])) {
            $_SESSION['success'] = "Resource moved to trash.";
        } else {
            $_SESSION['error'] = "Action failed or unauthorized.";
        }
        header('Location: ' . \Hub\Core\Config::BASE_URL . '/');
        exit;
    }

    public function showEdit($id) {
        $this->checkAuth();
        $resource = $this->resourceModel->findById($id);
        if (!$resource || $resource['user_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = "Unauthorized or invalid resource.";
            header('Location: ' . \Hub\Core\Config::BASE_URL . '/');
            exit;
        }

        $this->render('resources/edit', ['resource' => $resource, 'title' => 'Edit Fork']);
    }

    public function update($id) {
        $this->checkAuth();
        $data = [
            'title' => $_POST['title'],
            'description' => $_POST['description']
        ];

        $sql = "UPDATE resources SET title = :title, description = :desc WHERE id = :id AND user_id = :user_id";
        $stmt = \Hub\Core\Database::getInstance()->getConnection()->prepare($sql);
        
        if ($stmt->execute([
            ':title' => $data['title'],
            ':desc' => $data['description'],
            ':id' => $id,
            ':user_id' => $_SESSION['user_id']
        ])) {
            $_SESSION['success'] = "Changes saved to your fork.";
        } else {
            $_SESSION['error'] = "Failed to update.";
        }
        header('Location: ' . \Hub\Core\Config::BASE_URL . '/?my_work=1');
        exit;
    }

    public function showTrash() {
        $this->checkAuth();
        $resources = $this->resourceModel->getTrash($_SESSION['user_id']);
        $this->render('resources/trash', [
            'resources' => $resources,
            'title' => 'Recycle Bin'
        ]);
    }

    public function restore($binId) {
        $this->checkAuth();
        if ($this->resourceModel->restore($binId, $_SESSION['user_id'])) {
            $_SESSION['success'] = "Resource restored successfully!";
        } else {
            $_SESSION['error'] = "Restoration failed.";
        }
        header('Location: ' . \Hub\Core\Config::BASE_URL . '/trash');
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
