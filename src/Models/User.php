<?php

namespace Hub\Models;

use Hub\Core\Database;
use PDO;

class User {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create($data) {
        $sql = "INSERT INTO users (name, email, password, role, department, semester, status) 
                VALUES (:name, :email, :password, :role, :dept, :sem, :status)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':name' => $data['name'],
            ':email' => $data['email'],
            ':password' => password_hash($data['password'], PASSWORD_DEFAULT),
            ':role' => $data['role'] ?? 'Student',
            ':dept' => $data['department'] ?? 'Computer Science',
            ':sem' => $data['semester'] ?? 1,
            ':status' => ($data['role'] == 'Faculty') ? 'Pending' : 'Active'
        ]);
    }

    public function findByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }

    public function findById($id) {
        $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
}
