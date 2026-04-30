<?php

namespace Hub\Models;

use Hub\Core\Database;
use Hub\Core\Config;
use PDO;

class Resource {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll($filters = []) {
        $sql = "SELECT r.*, u.name as author, c.name as category 
                FROM resources r 
                JOIN users u ON r.user_id = u.id 
                JOIN categories c ON r.category_id = c.id 
                WHERE r.is_deleted = 0";
        
        $params = [];
        if (!empty($filters['category'])) {
            $sql .= " AND c.name = :category";
            $params['category'] = $filters['category'];
        }
        if (!empty($filters['search'])) {
            $sql .= " AND r.title LIKE :search";
            $params['search'] = "%" . $filters['search'] . "%";
        }
        if (!empty($filters['user_id'])) {
            $sql .= " AND r.user_id = :user_id";
            $params['user_id'] = $filters['user_id'];
        }
        if (!empty($filters['dept'])) {
            $sql .= " AND r.department = :dept";
            $params['dept'] = $filters['dept'];
        }
        if (!empty($filters['sem'])) {
            $sql .= " AND r.semester = :sem";
            $params['sem'] = $filters['sem'];
        }

        $sql .= " ORDER BY r.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function create($data) {
        $sql = "INSERT INTO resources (title, description, file_path, user_id, category_id, year, difficulty, version, parent_id, forked_from_author, department, semester, subject_name) 
                VALUES (:title, :description, :file_path, :user_id, :category_id, :year, :difficulty, :version, :parent_id, :forked_from_author, :dept, :sem, :subject)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':title' => $data['title'],
            ':description' => $data['description'] ?? '',
            ':file_path' => $data['file_path'],
            ':user_id' => $data['user_id'],
            ':category_id' => $data['category_id'],
            ':year' => $data['year'],
            ':difficulty' => $data['difficulty'] ?? 'Intermediate',
            ':version' => $data['version'] ?? 1,
            ':parent_id' => $data['parent_id'] ?? null,
            ':forked_from_author' => $data['forked_from_author'] ?? null,
            ':dept' => $data['department'] ?? 'Computer Science',
            ':sem' => $data['semester'] ?? 1,
            ':subject' => $data['subject_name'] ?? ''
        ]);
    }

    public function findById($id) {
        $sql = "SELECT r.*, u.name as author, c.name as category 
                FROM resources r 
                JOIN users u ON r.user_id = u.id 
                JOIN categories c ON r.category_id = c.id 
                WHERE r.id = :id AND r.is_deleted = 0";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function softDelete($id, $userId) {
        $resource = $this->findById($id);
        if (!$resource) return false;

        $this->db->beginTransaction();
        try {
            // Move to recycle_bin
            $sqlBin = "INSERT INTO recycle_bin (original_id, file_path_backup, deleted_by, restore_key) 
                       VALUES (:id, :path, :user, :key)";
            $stmtBin = $this->db->prepare($sqlBin);
            $stmtBin->execute([
                ':id' => $id,
                ':path' => $resource['file_path'],
                ':user' => $userId,
                ':key' => bin2hex(random_bytes(16))
            ]);

            // Hide from main resources
            $sqlRes = "UPDATE resources SET is_deleted = 1, deleted_at = NOW() WHERE id = :id";
            $stmtRes = $this->db->prepare($sqlRes);
            $stmtRes->execute([':id' => $id]);

            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function getTrash($userId) {
        $sql = "SELECT r.*, rb.id as bin_id, rb.deleted_at, c.name as category 
                FROM resources r 
                JOIN recycle_bin rb ON r.id = rb.original_id 
                JOIN categories c ON r.category_id = c.id 
                WHERE rb.deleted_by = :user_id AND r.is_deleted = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll();
    }

    public function restore($binId, $userId) {
        $sqlCheck = "SELECT original_id FROM recycle_bin WHERE id = :bin_id AND deleted_by = :user_id";
        $stmtCheck = $this->db->prepare($sqlCheck);
        $stmtCheck->execute([':bin_id' => $binId, ':user_id' => $userId]);
        $bin = $stmtCheck->fetch();

        if (!$bin) return false;

        $this->db->beginTransaction();
        try {
            $sqlRes = "UPDATE resources SET is_deleted = 0, deleted_at = NULL WHERE id = :id";
            $this->db->prepare($sqlRes)->execute([':id' => $bin['original_id']]);

            $sqlDel = "DELETE FROM recycle_bin WHERE id = :bin_id";
            $this->db->prepare($sqlDel)->execute([':bin_id' => $binId]);

            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function getCategories() {
        $stmt = $this->db->query("SELECT * FROM categories");
        return $stmt->fetchAll();
    }

    public function fork($resourceId, $userId) {
        $original = $this->findById($resourceId);
        if (!$original) return false;

        $data = $original;
        $data['user_id'] = $userId;
        $data['parent_id'] = $resourceId;
        $data['version'] = $original['version'] + 1;
        $data['title'] = $original['title'] . " (Fork)";
        $data['forked_from_author'] = $original['author']; // Explicit attribution
        
        return $this->create($data);
    }

    public function createPR($data) {
        $sql = "INSERT INTO pull_requests (sender_id, original_resource_id, fork_resource_id, message) 
                VALUES (:sender, :original, :fork, :message)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':sender' => $data['sender_id'],
            ':original' => $data['original_id'],
            ':fork' => $data['fork_id'],
            ':message' => $data['message']
        ]);
    }

    public function getPendingPRs($facultyId) {
        $sql = "SELECT pr.*, u.name as sender_name, r_orig.title as paper_title 
                FROM pull_requests pr 
                JOIN users u ON pr.sender_id = u.id 
                JOIN resources r_orig ON pr.original_resource_id = r_orig.id 
                WHERE r_orig.user_id = :faculty_id AND pr.status = 'Pending'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':faculty_id' => $facultyId]);
        return $stmt->fetchAll();
    }

    public function getPR($prId) {
        $sql = "SELECT pr.*, u.name as sender_name, r_fork.title as fork_title, r_fork.description as fork_desc, r_orig.title as orig_title, r_orig.description as orig_desc 
                FROM pull_requests pr 
                JOIN users u ON pr.sender_id = u.id 
                JOIN resources r_fork ON pr.fork_resource_id = r_fork.id 
                JOIN resources r_orig ON pr.original_resource_id = r_orig.id 
                WHERE pr.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $prId]);
        return $stmt->fetch();
    }

    public function mergePR($prId) {
        $pr = $this->getPR($prId);
        if (!$pr) return false;

        $this->db->beginTransaction();
        try {
            $sqlGetOrig = "SELECT contributors, version FROM resources WHERE id = :id";
            $stmtGetOrig = $this->db->prepare($sqlGetOrig);
            $stmtGetOrig->execute([':id' => $pr['original_resource_id']]);
            $orig = $stmtGetOrig->fetch();

            $newVersion = $orig['version'] + 1;
            $newContributors = $orig['contributors'] ? $orig['contributors'] . ", " . $pr['sender_name'] : $pr['sender_name'];

            $sqlUpdate = "UPDATE resources SET version = :v, contributors = :c, description = :desc WHERE id = :id";
            $this->db->prepare($sqlUpdate)->execute([
                ':v' => $newVersion,
                ':c' => $newContributors,
                ':desc' => $pr['fork_desc'],
                ':id' => $pr['original_resource_id']
            ]);

            $this->db->prepare("UPDATE pull_requests SET status = 'Merged' WHERE id = :id")->execute([':id' => $prId]);

            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
}
