<?php

namespace Hub\Controllers;

use Hub\Core\Database;
use Hub\Models\User;
use Hub\Models\Resource;
use Hub\Models\Department;
use Hub\Models\Semester;

class AdminController {
    private $db;
    private $userModel;
    private $departmentModel;
    private $semesterModel;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->userModel = new User();
        $this->departmentModel = new Department();
        $this->semesterModel = new Semester();
        $this->checkAdmin();
    }

    public function index() {
        // Fetch Stats
        $stats = [
            'total_users' => $this->db->query("SELECT COUNT(*) FROM users")->fetchColumn(),
            'total_resources' => $this->db->query("SELECT COUNT(*) FROM resources WHERE is_deleted = 0")->fetchColumn(),
            'pending_faculty' => $this->db->query("SELECT COUNT(*) FROM users WHERE role = 'Faculty' AND status = 'Pending'")->fetchColumn(),
            'total_comments' => $this->db->query("SELECT COUNT(*) FROM comments")->fetchColumn()
        ];

        $recent_users = $this->db->query("
            SELECT u.*, d.name as department_name, s.name as semester_name 
            FROM users u 
            LEFT JOIN departments d ON u.department_id = d.id 
            LEFT JOIN semesters s ON u.semester_id = s.id 
            ORDER BY u.created_at DESC LIMIT 5
        ")->fetchAll();

        $this->render('admin/dashboard', [
            'stats' => $stats,
            'recent_users' => $recent_users,
            'title' => 'Admin Dashboard'
        ]);
    }

    public function manageUsers() {
        $users = $this->db->query("
            SELECT u.*, d.name as department_name, s.name as semester_name 
            FROM users u 
            LEFT JOIN departments d ON u.department_id = d.id 
            LEFT JOIN semesters s ON u.semester_id = s.id 
            ORDER BY u.role ASC, u.name ASC
        ")->fetchAll();
        
        $departments = $this->departmentModel->all();
        $semesters = $this->semesterModel->all();

        $this->render('admin/users', [
            'users' => $users,
            'departments' => $departments,
            'semesters' => $semesters,
            'title' => 'User Management'
        ]);
    }

    public function assignUser($id) {
        $role = $_POST['role'] ?? 'Student';
        $department_id = !empty($_POST['department_id']) ? $_POST['department_id'] : null;
        $semester_id = !empty($_POST['semester_id']) ? $_POST['semester_id'] : null;

        $stmt = $this->db->prepare("UPDATE users SET role = :role, department_id = :dept, semester_id = :sem WHERE id = :id");
        $stmt->execute([
            ':role' => $role,
            ':dept' => $department_id,
            ':sem' => $semester_id,
            ':id' => $id
        ]);

        $_SESSION['success'] = "User assignments updated successfully.";
        header('Location: ' . \Hub\Core\Config::BASE_URL . '/admin/users');
        exit;
    }

    public function updateStatus($id) {
        $status = $_GET['status'] ?? 'Active';
        $sql = "UPDATE users SET status = :status WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':status' => $status, ':id' => $id]);
        
        $_SESSION['success'] = "User status updated to $status.";
        header('Location: ' . \Hub\Core\Config::BASE_URL . '/admin/users');
        exit;
    }

    public function deleteUser($id) {
        if ($id == $_SESSION['user_id']) {
            $_SESSION['error'] = "You cannot delete yourself.";
        } else {
            $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $_SESSION['success'] = "User removed from portal.";
        }
        header('Location: ' . \Hub\Core\Config::BASE_URL . '/admin/users');
        exit;
    }

    // --- Departments ---
    public function manageDepartments() {
        $departments = $this->departmentModel->all();
        $this->render('admin/departments', [
            'departments' => $departments,
            'title' => 'Manage Departments'
        ]);
    }

    public function storeDepartment() {
        if (!empty($_POST['name'])) {
            $this->departmentModel->create($_POST['name']);
            $_SESSION['success'] = "Department created.";
        }
        header('Location: ' . \Hub\Core\Config::BASE_URL . '/admin/departments');
        exit;
    }

    public function deleteDepartment($id) {
        $this->departmentModel->delete($id);
        $_SESSION['success'] = "Department deleted.";
        header('Location: ' . \Hub\Core\Config::BASE_URL . '/admin/departments');
        exit;
    }

    // --- Semesters ---
    public function manageSemesters() {
        $semesters = $this->semesterModel->all();
        $this->render('admin/semesters', [
            'semesters' => $semesters,
            'title' => 'Manage Semesters'
        ]);
    }

    public function storeSemester() {
        if (!empty($_POST['name'])) {
            $this->semesterModel->create($_POST['name']);
            $_SESSION['success'] = "Semester created.";
        }
        header('Location: ' . \Hub\Core\Config::BASE_URL . '/admin/semesters');
        exit;
    }

    public function deleteSemester($id) {
        $this->semesterModel->delete($id);
        $_SESSION['success'] = "Semester deleted.";
        header('Location: ' . \Hub\Core\Config::BASE_URL . '/admin/semesters');
        exit;
    }

    private function checkAdmin() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Admin') {
            $_SESSION['error'] = "Access denied. Admins only.";
            header('Location: ' . \Hub\Core\Config::BASE_URL . '/');
            exit;
        }
    }

    private function render($view, $data = []) {
        extract($data);
        require_once __DIR__ . "/../../views/layouts/main.php";
    }
}
