<?php

/**
 * API endpoint personalizado
 * Para tener una experiencia de usuario fluida y dinámica se ha decidido por acceder a la base de datos
 * mediante llamadas a APIs que no recargan la página cada vez que se hace una operación
 *
 * Función:
 * Recupera las tareas de la base de datos ligadas al usuario
 */

// Inicia la sesión dado que no se encuentra en index.php
session_start();

// Especifiva el tipo de contenido que devuelve la API
header('Content-Type: application/json');

// Si el usuario no está autenticado no continúa con la ejecución
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Not authenticated']);
    exit;
}

// Requiere la base de datos
require_once "../../models/Database.php";

// Ejecuta la operación de selección de las tareas del usuario, si todo va bien
// devuelve un array asociativo con las tareas
// si no devuelve un error
try {
    $db = Database::connect();
    $stm = $db->prepare('SELECT * FROM tasks where user_id = ?');
    $stm->bindParam(1, $_SESSION['user_id']);
    $stm->execute();

    $tasks = $stm->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($tasks);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error', 'details' => $e->getMessage()]);
}
