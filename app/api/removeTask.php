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

$id = trim($input['taskId']);
$userId = $_SESSION['user_id'];

try {
    $db = Database::connect();
    $stmt = $db->prepare("DELETE FROM tasks WHERE user_id=? AND id=?");
    $stmt->execute([$userId, $id]);

    echo json_encode([
        'success' => true
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error', 'details' => $e->getMessage()]);
}
