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

if (!isset($input['title']) || trim($input['title']) === '') {
    echo json_encode(['error' => 'Task title is required']);
    exit;
}

$title = trim($input['title']);
$description = trim($input['description']);
$category = $input['category'];
$userId = $_SESSION['user_id'];

try {
    $db = Database::connect();
    $stmt = $db->prepare("INSERT INTO tasks (user_id, title, description, category_id) VALUES (?, ?, ?, ?)");
    $stmt->execute([$userId, $title, $description, $category]);

    echo json_encode([
        'success' => true,
        'task' => [
            'id' => $db->lastInsertId(),
            'user_id' => $userId,
            'title' => $title,
            'description' => $description,
            'category' => $category
        ]
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error', 'details' => $e->getMessage()]);
}
