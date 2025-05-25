<?php

class Login {

    public function index() {
        require_once 'app/views/partials/header.view.php';
        require_once 'app/views/login/login.view.php';
        require_once 'app/views/partials/footer.view.php';
    }

    public function signup() {
        require_once 'app/views/partials/header.view.php';
        require_once 'app/views/login/signup.view.php';
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

        if ($user) {
            echo 'error'; 
        } else {
            echo 'hola';
        }
    }

    public function loginUser() {
        
    }
}