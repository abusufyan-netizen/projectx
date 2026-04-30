<?php
namespace Hub\Models;

use Hub\Core\Database;

class Department {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function all() {
        return $this->db->query("SELECT * FROM departments ORDER BY name ASC")->fetchAll();
    }

    public function create($name) {
        $stmt = $this->db->prepare("INSERT INTO departments (name) VALUES (:name)");
        return $stmt->execute([':name' => $name]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM departments WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
