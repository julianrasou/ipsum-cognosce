<?php

/**
 * Clase Database que ofrece una cceso a la base de datos
 * Sigue un patrón singleton para evitar el uso de múltiples puntos de acceso
 */

class Database
{
    // Instancia de la base de datos
    private static $instance = null;

    // Funciones construct y clone privadas
    private function __construct()
    {
    }

    private function __clone()
    {
    }

    // Función estática pública connect que devuelve la instancia de la base de datos o si no hay crea una y la devuelve
    public static function connect()
    {
        if (self::$instance === null) {
            try {
                $db = new PDO("mysql:host=localhost;dbname=ipsum_cognosce", 'ipsum_user', '1234');
                self::$instance = $db;
            } catch (PDOException $e) {
                print $e->getMessage();
            }
        }

        return self::$instance;
    }

    // Función disconnect para desconectarse de la base de datos
    public static function disconnect()
    {
        self::$instance = null;
    }
}
