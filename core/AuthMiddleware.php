<?php


use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

class AuthMiddleware {
    private $jwtConfig;

    public function __construct() {
        $this->jwtConfig = require __DIR__ . '/../config/jwt.php';
    }

    public function verifyToken() {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Token não fornecido']);
            exit;
        }

        $authHeader = $headers['Authorization'];
        list($type, $token) = explode(" ", $authHeader);

        if ($type !== 'Bearer' || !$token) {
            http_response_code(401);
            echo json_encode(['error' => 'Token inválido']);
            exit;
        }

        try {
            $decoded = JWT::decode($token, new Key($this->jwtConfig['secret_key'], 'HS256'));
            return $decoded->data;
        } catch (Exception $e) {
            http_response_code(401);
            echo json_encode(['error' => 'Token inválido ou expirado']);
            exit;
        }
    }
}
