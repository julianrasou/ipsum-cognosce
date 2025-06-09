<?php

class Tasks {

    public function index() {
        if ( !isset( $_SESSION["user_id"] ) ) {
            require_once 'app/views/partials/header.view.php';
            require_once 'app/views/unverified.view.php';
            require_once 'app/views/partials/footer.view.php';
        } else {
            require_once 'app/views/partials/header.view.php';
            require_once 'app/views/tasks/tasks.view.php';
            require_once 'app/views/partials/footer.view.php';
        }
        
    }

}