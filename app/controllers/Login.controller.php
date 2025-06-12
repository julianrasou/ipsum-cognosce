<?php

/**
 * Controlador para la página de inicio de sesión
 * Contiene una función index que carga la vista principal y diferentes funcines que cumplen diferentes propósitos
 */

class Login
{
    /**
     * Función index
     * Comprueba si hay errores en las cookies y los inicializa en una variable $error
     * que es usada por la vista login.view.php
     * Carga las vistas necesarias una vez comprobados los errores
     */
    public function index()
    {

        $controller = "login";

        $error = '';
        if (isset($_COOKIE[ 'error' ])) {
            $error = $_COOKIE[ 'error' ] ;
            setcookie('error', '', time() - 3600);
        }

        require_once 'app/views/partials/header.view.php';
        require_once 'app/views/login/login.view.php';
        require_once 'app/views/partials/footer.view.php';
    }

    /**
     * Función saveUser
     * Acción que se llama cuando se registra un nuevo usuario
     * Utiliza el modelo DAO del usuario para las operaciones con la BD
     */
    public function saveUser()
    {

        //Requiere el modelo DAO del usuario
        require_once 'app/models/UserDAO.php';

        // Inicializa las variables
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $username = $_POST['username'];

        // Comprueba si ya existe un usuario con el email o el nombre de usuario proporcionados
        // Recupera mediante el modelo los usuarios que coincidan
        $emailExists = UserDAO::getUser($email, 'email');
        $usernameExists = UserDAO::getUser($email, 'username');

        // Si coincide se guarda una cookie con el error pertinente y no se crea el usuario
        if ($emailExists[ 'success' ] && $usernameExists[ 'success' ]) {
            if ($emailExists[ 'user' ]) {
                setcookie('error', 'Ya existe una cuenta con ese correo.', time() + 3600);
            } elseif ($usernameExists[ 'user' ]) {
                setcookie('error', 'Ya existe una cuenta con ese nombre de usuario.', time() + 3600);
            } else {
                $result = UserDAO::createUser($name, $username, $email, $password_hash);

                if ($result[ 'success' ]) {
                    // Si se crea el usuario modifica la cookie para autocompletar el formulario de inicio de sesión
                    setcookie("lastUsedEmail", $result[ 'user' ][ 'email' ], time() + (3600 * 24 * 30));
                } else {
                    setcookie('error', 'No se pudo crear el usuario.', time() + 3600);
                }
            }
        } else {
            setcookie('error', 'No se pudo crear el usuario.', time() + 3600);
        }

        // Una vez se crea el usuario o ocurre un error se vuelve a la página de login
        header('Location: ?c=login');
        exit();
    }

    /**
     * Función loginUser
     * Acción que se llama cuando se inicia sesión
     * Utiliza el modelo DAO del usuario para las operaciones con la BD
     */
    public function loginUser()
    {
        // Requiere el modelo DAO del usuario
        require_once 'app/models/UserDAO.php';

        // Inicializa las variables
        $email_username = $_POST['email'];
        $password = $_POST[ 'password' ];

        // Comprueba si ya existe un usuario con el email o el nombre de usuario proporcionados
        // Recupera mediante el modelo los usuarios que coincidan
        $emailExists = UserDAO::getUser($email_username, 'email');
        $usernameExists = UserDAO::getUser($email_username, 'username');

        // Si no coincide se guarda una cookie con el error pertinente y no se inicia sesión
        if ($emailExists[ 'success' ] && $usernameExists[ 'success' ]) {
            if ($emailExists[ 'user' ]) {
                // Si el usuario introduce el correo
                if (password_verify($password, $emailExists[ 'user' ][ 'password_hash' ])) {
                    $_SESSION[ 'username' ] = $emailExists[ 'user' ][ 'username' ];
                    $_SESSION[ 'email' ] = $emailExists[ 'user' ][ 'email' ];
                    $_SESSION[ 'user_id' ] = $emailExists[ 'user' ][ 'id' ];

                    // Si se inicia sesión se modifica la cookie para autocompletar el formulario de inicio de sesión
                    setcookie("lastUsedEmail", $email_username, time() + (3600 * 24 * 30));
                    // Se va a la página principal
                    header('Location: ?c=home');
                    exit();
                }
            } elseif ($usernameExists[ 'user' ]) {
                // Si el usuario introduce el nombre de usuario
                if (password_verify($password, $usernameExists[ 'user' ][ 'password_hash' ])) {
                    $_SESSION[ 'username' ] = $usernameExists[ 'user' ][ 'username' ];
                    $_SESSION[ 'email' ] = $usernameExists[ 'user' ][ 'email' ];
                    $_SESSION[ 'user_id' ] = $usernameExists[ 'user' ][ 'id' ];

                    // Si se inicia sesión se modifica la cookie para autocompletar el formulario de inicio de sesión
                    setcookie("lastUsedEmail", $email_username, time() + (3600 * 24 * 30));
                    // Se va a la página principal
                    header('Location: ?c=home');
                    exit();
                }
            } else {
                setcookie('error', 'Correo o contraseña incorrectos.', time() + 3600);
            }
        } else {
            setcookie('error', 'No se pudo iniciar sesión.', time() + 3600);
        }

        // Si hay un error se vuelve a la página de login
        header('Location: ?c=login');
        exit();
    }

    /**
     * Función logOut
     * Para cerrar sesión se vacían los datos de la sesión y se destruye la sesión
     * Acto seguido se vuelve a la página principal
     */
    public function logOut()
    {
        $_SESSION = [];

        session_unset();
        session_destroy();

        header('Location: ?c=home');
        exit();
    }
}
