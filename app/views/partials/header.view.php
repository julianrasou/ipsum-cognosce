<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Autor de la aplicación web -->
    <meta name="author" content="Julian Ramos Souza" />

    <!-- Descripción de la aplicación web -->
    <meta
        name="description"
        content="The MDN Web Docs Learning Area aims to provide complete beginners to the Web with
        all they need to know to get started with developing websites and applications."
    />

    <!-- Icono de la App, estilos y título -->
    <link rel="icon" href="public/images/logo.png" type="image/x-icon" />
    <link rel="stylesheet" href="public/css/main.css">
    <title>IPSUM COGNOSCE</title>
</head>
<body>

    <!--
    Header de la aplicación web
    Contiene logo, área de navegación y botón de inicio de sesión
    -->
    <header class="page-header" >
        <img src="public/images/logo.png" alt="" class="header-logo">
        <nav>
            <ul class="nav-links" >
                <li class="<?php echo $controller === "home" ? "activePage" : "inactivePage" ?>" >
                    <a href="?c=home">Home</a>
                </li>
                <li class="<?php echo $controller === "tasks" ? "activePage" : "inactivePage" ?>" >
                    <a href="?c=tasks">Tareas</a>
                </li>
                <li class="<?php echo $controller === "notes" ? "activePage" : "inactivePage" ?>" >
                    <a href="?c=notes">Notas</a>
                </li>
            </ul>
        </nav>
        <?php
        // Si la sesión está iniciada muestra un botón de cerrar sesión con la acción logOut
        // si no, se muestra un botón de login que mueve al usuario a la página de inicio de sesión
        if (isset($_SESSION[ 'username' ])) {
            ?>
        <a class="login-button" href="?c=login&a=logOut"><Button>Cerrar sesión</Button></a>
            <?php
        } else {
            ?>
        <a class="login-button" href="?c=login"><Button>Iniciar sesión</Button></a>
            <?php
        }
        ?>
    </header>