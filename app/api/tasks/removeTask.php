<?php

/**
 * API endpoint personalizado
 * Para tener una experiencia de usuario fluida y dinámica se ha decidido por acceder a la base de datos
 * mediante llamadas a APIs que no recargan la página cada vez que se hace una operación
 *
 * Función:
 * Elimina una tarea de la base de datos especificada por el usuario
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

// Inicializa dos variables para los datos necesarios
$id = trim($input['taskId']);
$userId = $_SESSION['user_id'];

// Ejecuta la operación de eliminación de la tarea, si todo va bien
// devuelve un mensaje de success
// si no devuelve un error
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
