<?php

class Database {
    public static function connect() {
        $host = getenv('DB_HOST');
        $port = getenv('DB_PORT') ?: '3306';
        $dbname = getenv('DB_NAME');
        $user = getenv('DB_USER');
        $pass = getenv('DB_PASS');

        $ssl_ca = __DIR__ . '/ca.pem'; // Comente se não usar SSL

        $options = [
            PDO::MYSQL_ATTR_SSL_CA => $ssl_ca,
            PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ];

        $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8";

        try {
            return new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            throw new Exception("Erro ao conectar ao banco de dados: " . $e->getMessage());
        }
    }
}
