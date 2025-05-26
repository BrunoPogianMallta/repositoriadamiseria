<?php

require_once __DIR__ . '/../models/User.php';


use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

class AuthController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    //registera
    public function register() {
        $input = json_decode(file_get_contents('php://input'), true);

        $name = trim($input['name'] ?? '');
        $email = trim($input['email'] ?? '');
        $password = $input['password'] ?? '';

        if (empty($name) || empty($email) || empty($password)) {
            return $this->jsonResponse(['error' => 'Todos os campos são obrigatórios.'], 400);
        }

        $userModel = new User($this->db);

        if ($userModel->findByEmail($email)) {
            return $this->jsonResponse(['error' => 'Email já cadastrado.'], 409);
        }

        if ($userModel->create($name, $email, $password)) {
            return $this->jsonResponse(['success' => 'Usuário cadastrado com sucesso.']);
        }

        return $this->jsonResponse(['error' => 'Erro ao cadastrar usuário.'], 500);
    }

   //login 
    public function login() {
        $input = json_decode(file_get_contents('php://input'), true);

        $email = trim($input['email'] ?? '');
        $password = $input['password'] ?? '';

        if (empty($email) || empty($password)) {
            return $this->jsonResponse(['error' => 'Email e senha são obrigatórios.'], 400);
        }

        $userModel = new User($this->db);
        $user = $userModel->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            return $this->jsonResponse(['error' => 'Credenciais inválidas.'], 401);
        }

        // JWT
        $jwtConfig = require __DIR__ . '/../config/jwt.php';

        $payload = [
            'iss' => 'localhost',
            'aud' => 'localhost',
            'iat' => time(),
            'exp' => time() + 3600, 
            'data' => [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email']
            ]
        ];

        $token = JWT::encode($payload, $jwtConfig['secret_key'], 'HS256');

        return $this->jsonResponse([
            'success' => 'Login realizado com sucesso.',
            'token' => $token,
            'user' => [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email']
            ]
        ]);
    }

   
    private function jsonResponse(array $data, int $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}
