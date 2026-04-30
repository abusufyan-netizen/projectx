<?php

namespace Hub\Models;

use Hub\Core\Database;
use PDO;

class Comment {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getByResource($resourceId) {
        $sql = "SELECT c.*, u.name as author 
                FROM comments c 
                JOIN users u ON c.user_id = u.id 
                WHERE c.resource_id = :resource_id 
                ORDER BY c.created_at ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':resource_id' => $resourceId]);
        $allComments = $stmt->fetchAll();

        // Nest the comments
        return $this->nestComments($allComments);
    }

    private function nestComments($comments, $parentId = null) {
        $branch = [];
        foreach ($comments as $comment) {
            if ($comment['parent_id'] == $parentId) {
                $children = $this->nestComments($comments, $comment['id']);
                if ($children) {
                    $comment['children'] = $children;
                }
                $branch[] = $comment;
            }
        }
        return $branch;
    }

    public function create($data) {
        $sql = "INSERT INTO comments (resource_id, user_id, parent_id, content) VALUES (:resource_id, :user_id, :parent_id, :content)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':resource_id' => $data['resource_id'],
            ':user_id' => $data['user_id'],
            ':parent_id' => $data['parent_id'] ?? null,
            ':content' => $data['content']
        ]);
    }
}
