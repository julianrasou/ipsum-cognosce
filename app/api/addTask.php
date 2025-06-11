<?php

/**
 * API endpoint personalizado
 * Para tener una experiencia de usuario fluida y dinámica se ha decidido por acceder a la base de datos
 * mediante llamadas a APIs que no recargan la página cada vez que se hace una operación
 *
 * Función:
 * Añade una tarea a la base de datos ligada al usuario creador
 */

// Inicia la sesión dado que no se encuentra en index.php
session_start();

// Especifica el tipo de contenido que devuelve la API
header('Content-Type: application/json');

// Si el usuario no está autenticado no continúa con la ejecución
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Not authenticated']);
    exit;
}

// Requiere la base de datos
require_once "../models/Database.php";

// Recupera los datos pasados con la llamada a la API
$input = json_decode(file_get_contents('php://input'), true);

// Si no se ha pasado título o el título está vacío, devuelve un error y no continúa con la ejecución
if (!isset($input['title']) || trim($input['title']) === '') {
    echo json_encode(['error' => 'Task title is required']);
    exit;
}

// Inicializa dos variables para los datos necesarios
$title = trim($input['title']);
$description = trim($input['description']);
$category = null;
if ($input['category'] != 0) {
    $category = $input['category'];
}
$userId = $_SESSION['user_id'];

// Ejecuta la operación de inserción la nueva tarea, si todo va bien
// devuelve un mensaje de success y los datos de la tarea
// si no devuelve un error
try {
    $db = Database::connect();

    // Dependiendo de si se ha especificado una categoría se inserta de una forma u otra
    if ($category) {
        $stmt = $db->prepare("INSERT INTO tasks (user_id, title, description, category_id) VALUES (?, ?, ?, ?)");
        $stmt->execute([$userId, $title, $description, $category]);
    } else {
        $stmt = $db->prepare("INSERT INTO tasks (user_id, title, description) VALUES (?, ?, ?)");
        $stmt->execute([$userId, $title, $description]);
    }

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
