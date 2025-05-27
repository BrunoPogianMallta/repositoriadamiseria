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

    // VERIFICA SE O USUÁRIO JÁ CURTIU O POST
    public function hasUserLiked($postId, $userId) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM post_likes WHERE post_id = :post_id AND user_id = :user_id");
        $stmt->execute([':post_id' => $postId, ':user_id' => $userId]);
        return $stmt->fetchColumn() > 0;
    }

    // ADICIONA CURTIDA (SE NÃO TIVER)
    public function addLike($postId, $userId) {
        if ($this->hasUserLiked($postId, $userId)) {
            return false; // Já curtiu
        }

        // Insere no post_likes
        $stmt = $this->conn->prepare("INSERT INTO post_likes (post_id, user_id) VALUES (:post_id, :user_id)");
        $result = $stmt->execute([':post_id' => $postId, ':user_id' => $userId]);

        if ($result) {
            // Atualiza contador de likes na tabela posts
            $stmt = $this->conn->prepare("UPDATE posts SET likes = likes + 1 WHERE id = :post_id");
            $stmt->execute([':post_id' => $postId]);
        }

        return $result;
    }

    // REMOVE CURTIDA (SE TIVER)
    public function removeLike($postId, $userId) {
        if (!$this->hasUserLiked($postId, $userId)) {
            return false; // Não tinha curtido
        }

        // Remove do post_likes
        $stmt = $this->conn->prepare("DELETE FROM post_likes WHERE post_id = :post_id AND user_id = :user_id");
        $result = $stmt->execute([':post_id' => $postId, ':user_id' => $userId]);

        if ($result) {
            // Atualiza contador de likes na tabela posts (sem deixar negativo)
            $stmt = $this->conn->prepare("UPDATE posts SET likes = GREATEST(likes - 1, 0) WHERE id = :post_id");
            $stmt->execute([':post_id' => $postId]);
        }

        return $result;
    }

    // RETORNA A QUANTIDADE DE LIKES DO POST
    public function getLikes($postId) {
        $stmt = $this->conn->prepare("SELECT likes FROM posts WHERE id = :id");
        $stmt->execute([':id' => $postId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? (int)$row['likes'] : 0;
    }
}

