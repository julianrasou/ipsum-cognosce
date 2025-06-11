<?php

class Login
{
    public function index()
    {

        $error = '';
        if (isset($_COOKIE[ 'error' ])) {
            $error = $_COOKIE[ 'error' ] ;
            setcookie('error', '', time() - 3600);
        }

        require_once 'app/views/partials/header.view.php';
        require_once 'app/views/login/login.view.php';
        require_once 'app/views/partials/footer.view.php';
    }

    public function saveUser()
    {

        require_once 'app/models/UserDAO.php';

        $name = $_POST['name'];
        $email = $_POST['email'];
        $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $username = $_POST['username'];

        $emailExists = UserDAO::getUser($email, 'email');
        $usernameExists = UserDAO::getUser($email, 'username');

        if ($emailExists[ 'success' ] && $usernameExists[ 'success' ]) {
            if (!$emailExists[ 'user' ]) {
                setcookie('error', 'Ya existe una cuenta con ese correo.', time() + 3600);
            } elseif (!$usernameExists[ 'user' ]) {
                setcookie('error', 'Ya existe una cuenta con ese nombre de usuario.', time() + 3600);
            } else {
                $result = UserDAO::createUser($name, $username, $email, $password_hash);

                if ($result[ 'success' ]) {
                    setcookie("lastUsedEmail", $result[ 'user' ][ 'email' ], time() + (3600 * 24 * 30));
                } else {
                    setcookie('error', 'No se pudo crear el usuario.', time() + 3600);
                }
            }
        } else {
            setcookie('error', 'No se pudo crear el usuario.', time() + 3600);
        }

        header('Location: ?c=login');
        exit();
    }

    public function loginUser()
    {
        require_once 'app/models/UserDAO.php';

        $db = Database::connect();
        $email_username = $_POST['email'];
        $password = $_POST[ 'password' ];

        $emailExists = UserDAO::getUser($email_username, 'email');
        $usernameExists = UserDAO::getUser($email_username, 'username');

        if ($emailExists[ 'success' ] && $usernameExists[ 'success' ]) {
            if ($emailExists[ 'user' ]) {
                if (password_verify($password, $emailExists[ 'user' ][ 'password_hash' ])) {
                    $_SESSION[ 'username' ] = $emailExists[ 'user' ][ 'username' ];
                    $_SESSION[ 'email' ] = $emailExists[ 'user' ][ 'email' ];
                    $_SESSION[ 'user_id' ] = $emailExists[ 'user' ][ 'id' ];

                    setcookie("lastUsedEmail", $email_username, time() + (3600 * 24 * 30));
                    header('Location: ?c=home');
                    exit();
                }
            } elseif ($usernameExists[ 'user' ]) {
                if (password_verify($password, $usernameExists[ 'user' ][ 'password_hash' ])) {
                    $_SESSION[ 'username' ] = $usernameExists[ 'user' ][ 'username' ];
                    $_SESSION[ 'email' ] = $usernameExists[ 'user' ][ 'email' ];
                    $_SESSION[ 'user_id' ] = $usernameExists[ 'user' ][ 'id' ];

                    setcookie("lastUsedEmail", $email_username, time() + (3600 * 24 * 30));
                    header('Location: ?c=home');
                    exit();
                }
            } else {
                setcookie('error', 'Correo o contraseña incorrectos.', time() + 3600);
            }
        } else {
            setcookie('error', 'No se pudo iniciar sesión.', time() + 3600);
        }

        header('Location: ?c=login');
        exit();
    }

    public function logOut()
    {
        $_SESSION = [];

        session_unset();
        session_destroy();

        header('Location: ?c=home');
        exit();
    }
}
