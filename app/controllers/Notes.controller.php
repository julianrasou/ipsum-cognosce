<?php

/**
 * Controlador para la página de notas
 * Requiere las vistas correspondientes
 * Si no se ha iniciado sesión se carga una vista de contenido restringido
 */

class Notes
{
    public function index()
    {
        if (!isset($_SESSION["user_id"])) {
            require_once 'app/views/partials/header.view.php';
            require_once 'app/views/unverified.view.php';
            require_once 'app/views/partials/footer.view.php';
        } else {
            require_once 'app/views/partials/header.view.php';
            require_once 'app/views/notes/notes.view.php';
            require_once 'app/views/partials/footer.view.php';
        }
    }
}
