<?php

class Database {

    private static $instance = null;

    private function __construct() {

    }

    private function __clone() {

    }

    public static function connect() {
        if( self::$instance === null ) {

            try {
                $db = new PDO( "mysql:host=localhost;dbname=ipsum-cognosce" , 'ipsum-user' , '1234' );
                self::$instance = $db;
            } catch (PDOException $e) {
                print $e->getMessage();
            }
            
        }

        return self::$instance;
    }

    public static function disconnect() {
        self::$instance = null;
    }
}