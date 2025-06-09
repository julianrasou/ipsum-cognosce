<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Not authenticated']);
    exit;
}

require_once "../models/Database.php";
$db = Database::connect();
$stm = $db->prepare('SELECT * FROM task_categories where user_id = ?');
$stm->bindParam(1, $_SESSION['user_id']);
$stm->execute();
$categories = $stm->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($categories);