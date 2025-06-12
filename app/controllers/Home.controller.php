<?php

/**
 * Controlador para la página principal
 * Una única función que carga la vista principal
 */

class Home
{
    public function index()
    {
        $controller = "home";
        require_once 'app/views/partials/header.view.php';
        require_once 'app/views/home.view.php';
        require_once 'app/views/partials/footer.view.php';
    }
}
