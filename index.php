<?php

require_once 'app/models/Database.php';
session_start();
$controller = 'Home';
$action = 'index';


// FrontController
if (isset($_REQUEST['c'])) {
    // Obtenemos el controlador que queremos cargar
    $controller = ucwords(strtolower($_REQUEST['c']));
    $action = isset($_REQUEST['a']) ? $_REQUEST['a'] : 'index';
    if (!file_exists("app/controllers/$controller.controller.php")) {
        $controller = 'Home';
    }
}

// Instanciamos el controlador
require_once "app/controllers/$controller.controller.php";
$controller = new $controller();

// Llamada a la accion a realizar
call_user_func(array( $controller, $action ));
