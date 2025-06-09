<?php
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Not authenticated']);
    exit;
}

require_once "../models/Database.php";

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['name']) || trim($input['name']) === '') {
    echo json_encode(['error' => 'Category name is required']);
    exit;
}

$name = trim($input['name']);
$userId = $_SESSION['user_id'];

try {
    $db = Database::connect();
    $stmt = $db->prepare("INSERT INTO task_categories (user_id, name) VALUES (?, ?)");
    $stmt->execute([$userId, $name]);

    echo json_encode([
        'success' => true,
        'category' => [
            'id' => $db->lastInsertId(),
            'user_id' => $userId,
            'name' => $name
        ]
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error', 'details' => $e->getMessage()]);
}
