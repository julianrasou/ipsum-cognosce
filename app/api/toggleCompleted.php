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

if (!isset($input['id']) || trim($input['id']) === '') {
    echo json_encode(['error' => 'Task id is required']);
    exit;
}

$taskId = trim($input['id']);
$newStatus = trim($input['newStatus']);
$userId = $_SESSION['user_id'];

try {
    $db = Database::connect();
    $stmt = $db->prepare("UPDATE tasks SET status=? WHERE id=? AND user_id=?");
    $stmt->execute([$newStatus, $taskId, $userId]);

    echo json_encode([
        'success' => true
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error', 'details' => $e->getMessage()]);
}
