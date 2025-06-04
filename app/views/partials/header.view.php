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
    <link rel="icon" href="#" type="image/x-icon" />
    <link rel="stylesheet" href="public/css/main.css">
    <title>IPSUM COGNOSCE</title>
</head>
<body>

    <header class="page-header" >
        <img src="public/images/image.jpg" alt="" class="header-logo">
        <nav>
            <ul class="nav-links" >
                <li><a href="?c=home">Home</a></li>
            </ul>
        </nav>
        <?php
        if( isset( $_SESSION[ 'username' ] ) ) {
        ?>
        <a class="login-button" href="?c=login&a=logOut"><Button>Log Out</Button></a>
        <?php
        } else {
        ?>
        <a class="login-button" href="?c=login"><Button>Log In</Button></a>
        <?php
        }
        ?>
    </header>