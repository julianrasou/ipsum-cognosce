<?php

/**
 * Archivo principal en el model vista controlador, toda la aplicación web se mostrará en este archivo
 * Contiene un router que carga el controllador requerido según los parámetros pasados en la URL
 * y ejecuta la acción deseada.
 */

// Se inicia la sesión desde aquí para tener los datos accesibles en cualquier momento
session_start();

// Controlador y acción predeterminadas si no se especifican
$controller = 'Home';
$action = 'index';


// FrontController, obtiene el controlador si está especificado
// Si el controlador no existe, utiliza de forma predeterminada Home
if (isset($_REQUEST['c'])) {
    $controller = ucwords(strtolower($_REQUEST['c']));
    $action = isset($_REQUEST['a']) ? $_REQUEST['a'] : 'index';
    if (!file_exists("app/controllers/$controller.controller.php")) {
        $controller = 'Home';
    }
}

// Se instancia el controlador
require_once "app/controllers/$controller.controller.php";
$controller = new $controller();

// Llamada a la accion a realizar
call_user_func(array( $controller, $action ));
