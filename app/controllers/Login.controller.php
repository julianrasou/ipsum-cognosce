<?php

class Login {

    public function index() {

        $error = '';
        if( isset( $_COOKIE[ 'error' ] ) ) {
            $error = $_COOKIE[ 'error' ] ;
            setcookie( 'error', '', time() + 3600 );
        }

        require_once 'app/views/partials/header.view.php';
        require_once 'app/views/login/login.view.php';
        require_once 'app/views/partials/footer.view.php';
    }

    public function saveUser() {
        $db = Database::connect();
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $username = $_POST['username'];

        $stm = $db->prepare('SELECT email FROM users where email = ?');
        $stm->bindParam(1, $email);
        $stm->execute();
        $user = $stm->fetch(PDO::FETCH_ASSOC);

        if ( $user ) {
            setcookie('error', 'Ya existe una cuenta con ese correo.', time() + 3600);

            header( 'Location: ?c=login' );
            exit();
        } else {
            $stm = $db->prepare('INSERT INTO users (name, email, password, username) VALUES (?, ?, ?, ?)');
            $stm->bindParam(1, $name);
            $stm->bindParam(2, $email);
            $stm->bindParam(3, $password);
            $stm->bindParam(4, $username);
            $stm->execute();
            
            header( 'Location: ?c=login' );
            exit();
        }
    }

    public function loginUser() {
        $db = Database::connect();
        $email = $_POST['email'];
        $password = $_POST[ 'password' ];
        
        $stm = $db->prepare('SELECT * FROM users where email = ?');
        $stm->bindParam(1, $email);
        $stm->execute();
        $user = $stm->fetch(PDO::FETCH_ASSOC);

        if ( $user ) {

            if( password_verify( $password, $user[ 'password' ] ) ) {
                $_SESSION[ 'username' ] = $user[ 'username' ];
                $_SESSION[ 'email' ] = $user[ 'email' ];
                $_SESSION[ 'user-id' ] = $user[ 'id' ];

                header( 'Location: ?c=home' ); 
                exit();
            }

        }
        
        setcookie('error', 'Correo o contraseña incorrectos.', time() + 3600);

        header( 'Location: ?c=login' );
        exit();
        
    }

    public function logOut() {
        $_SESSION = [];

        session_unset();
        session_destroy();

        header( 'Location: ?c=home' );
        exit();
    }
}