<?php

// Enable CORS headers first
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

try {
    // Load dependencies
    require_once __DIR__ . '/../vendor/autoload.php';
    require_once __DIR__ . '/config/database.php';
    
    // Initialize database connection
    $db = Database::connect();
    
    // Test database connection (remove in production)
    if ($db) {
        error_log("Database connection successful");
    } else {
        throw new Exception("Failed to connect to database");
    }
    
    // Load routes
    require_once __DIR__ . '/routes.php';
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Initialization error',
        'error' => $e->getMessage()
    ]);
    error_log("Initialization error: " . $e->getMessage());
    exit;
}