<?php

/**
 * API endpoint personalizado
 * Para tener una experiencia de usuario fluida y dinámica se ha decidido por acceder a la base de datos
 * mediante llamadas a APIs que no recargan la página cada vez que se hace una operación
 *
 * Función:
 * Añade una categoría a la base de datos ligada al usuario creador
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
require_once "../../models/Database.php";

// Recupera los datos pasados con la llamada a la API
$input = json_decode(file_get_contents('php://input'), true);

// Si no se ha pasado nombre o el nombre está vacío, devuelve un error y no continúa con la ejecución
if (!isset($input['name']) || trim($input['name']) === '') {
    echo json_encode(['error' => 'Category name is required']);
    exit;
}

// Inicializa dos variables para los datos necesarios
$name = trim($input['name']);
$userId = $_SESSION['user_id'];

// Ejecuta la operación de inserción la nueva categoría, si todo va bien
// devuelve un mensaje de success y los datos de la categoría
// si no devuelve un error
try {
    $db = Database::connect();
    $stmt = $db->prepare("INSERT INTO note_categories (user_id, name) VALUES (?, ?)");
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
