<?php

/**
 * Controlador para la página de tareas
 * Requiere las vistas correspondientes
 * Si no se ha iniciado sesión se carga una vista de contenido restringido
 */

class Tasks
{
    public function index()
    {
        if (!isset($_SESSION["user_id"])) {
            require_once 'app/views/partials/header.view.php';
            require_once 'app/views/unverified.view.php';
            require_once 'app/views/partials/footer.view.php';
        } else {
            require_once 'app/views/partials/header.view.php';
            require_once 'app/views/tasks/tasks.view.php';
            require_once 'app/views/partials/footer.view.php';
        }
    }
}
