<?php

require_once __DIR__ . '/../models/Post.php';
require_once __DIR__ . '/../core/AuthMiddleware.php';

class PostController {
    private $db;
    private $auth;

    public function __construct($db) {
        $this->db = $db;
        $this->auth = new AuthMiddleware();
    }

    public function create() {
        $user = $this->auth->verifyToken();

        $input = json_decode(file_get_contents('php://input'), true);
        $title = $input['title'] ?? '';
        $content = $input['content'] ?? '';

        if (!$title || !$content) {
            http_response_code(400);
            echo json_encode(['error' => 'Título e conteúdo são obrigatórios.']);
            return;
        }

        $postModel = new Post($this->db);
        if ($postModel->create($title, $content, $user->id)) {
            echo json_encode(['success' => 'Post criado com sucesso!']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Erro ao criar post.']);
        }
    }

    // get all posts
    public function listAll() {
        $postModel = new Post($this->db);
        $posts = $postModel->getAll();
        echo json_encode(['posts' => $posts]);
    }

    // post by id
    public function getById($id) {
        $postModel = new Post($this->db);
        $post = $postModel->getById($id);

        if (!$post) {
            http_response_code(404);
            echo json_encode(['error' => 'Post não encontrado.']);
            return;
        }

        echo json_encode(['post' => $post]);
    }

    // Edit post
    public function update($id) {
        $user = $this->auth->verifyToken();

        $postModel = new Post($this->db);
        $post = $postModel->getById($id);

        if (!$post) {
            http_response_code(404);
            echo json_encode(['error' => 'Post não encontrado.']);
            return;
        }

        if ($post['user_id'] != $user->id) {
            http_response_code(403);
            echo json_encode(['error' => 'Acesso negado: só o dono pode editar o post.']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $title = $input['title'] ?? '';
        $content = $input['content'] ?? '';

        if (!$title || !$content) {
            http_response_code(400);
            echo json_encode(['error' => 'Título e conteúdo são obrigatórios.']);
            return;
        }

        if ($postModel->update($id, $title, $content)) {
            echo json_encode(['success' => 'Post atualizado com sucesso!']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Erro ao atualizar post.']);
        }
    }

    // Delete post 
    public function delete($id) {
        $user = $this->auth->verifyToken();

        $postModel = new Post($this->db);
        $post = $postModel->getById($id);

        if (!$post) {
            http_response_code(404);
            echo json_encode(['error' => 'Post não encontrado.']);
            return;
        }

        if ($post['user_id'] != $user->id) {
            http_response_code(403);
            echo json_encode(['error' => 'Acesso negado: só o dono pode deletar o post.']);
            return;
        }

        if ($postModel->delete($id)) {
            echo json_encode(['success' => 'Post deletado com sucesso!']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Erro ao deletar post.']);
        }
    }
}
