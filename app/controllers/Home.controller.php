<?php

class Home {

    public function index() {
        require_once 'app/views/partials/header.view.php';
        require_once 'app/views/home.view.php';
        require_once 'app/views/partials/footer.view.php';
    }

}