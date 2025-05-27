<?php

class Post {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($title, $content, $userId) {
        $stmt = $this->conn->prepare("INSERT INTO posts (title, content, user_id) VALUES (:title, :content, :user_id)");
        return $stmt->execute([
            ':title' => $title,
            ':content' => $content,
            ':user_id' => $userId
        ]);
    }

    public function getAll() {
    $stmt = $this->conn->prepare("
        SELECT p.id, p.title, p.content, p.user_id, u.name AS user_name, COALESCE(p.likes, 0) AS likes 
        FROM posts p 
        JOIN users u ON p.user_id = u.id 
        ORDER BY p.id DESC
    ");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getById($id) {
    $stmt = $this->conn->prepare("
        SELECT p.id, p.title, p.content, p.user_id, u.name AS user_name, COALESCE(p.likes, 0) AS likes 
        FROM posts p 
        JOIN users u ON p.user_id = u.id 
        WHERE p.id = :id
    ");
    $stmt->execute([':id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

    public function update($id, $title, $content) {
        $stmt = $this->conn->prepare("UPDATE posts SET title = :title, content = :content WHERE id = :id");
        return $stmt->execute([
            ':title' => $title,
            ':content' => $content,
            ':id' => $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM posts WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function addLike($postId) {
    $stmt = $this->conn->prepare("UPDATE posts SET likes = likes + 1 WHERE id = :id");
    return $stmt->execute([':id' => $postId]);
}

public function getLikes($postId) {
    $stmt = $this->conn->prepare("SELECT likes FROM posts WHERE id = :id");
    $stmt->execute([':id' => $postId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? (int)$row['likes'] : 0;
}
}
