<?php

/**
 * Modelo DAO para acceder a la base de datos
 * Contiene todas las funciones necesarias para manejar los usuarios
 */

class UserDAO
{
    /**
     * Función createUser
     * Crea un usuario y lo añade a la base de datos
     * Parámetros:
     * Nombre, Nombre de usuario, email, hash de la contraseña
     */
    public static function createUser($name, $username, $email, $password_hash)
    {
        // Requiere la base de datos
        require_once 'app/models/Database.php';

        // Instancia y sanea los datos necesarios
        $name_filtered = htmlspecialchars(trim($name));
        $username_filtered = htmlspecialchars(trim($username));
        $email_filtered = htmlspecialchars(trim($email));

        // Ejecuta la operación en la base de datos en un bloque try catch
        // Si todo va bien, devuelve los datos del nuevo usuario y un mensaje de success
        // Si no, devuelve un mensaje de error
        try {
            $db = Database::connect();
            $stm = $db->prepare('INSERT INTO users (name, username, email, password_hash ) VALUES (?, ?, ?, ?)');
            $stm->bindParam(1, $name_filtered);
            $stm->bindParam(2, $username_filtered);
            $stm->bindParam(3, $email_filtered);
            $stm->bindParam(4, $password_hash);
            $stm->execute();

            return [
                'success' => true,
                'user' => [
                    'name' => $name_filtered,
                    'username' => $username_filtered,
                    'email' => $email_filtered
                ]
            ];
        } catch (PDOException $e) {
            return [
                'error' => 'Error en la base de datos, no se pudo crear el usuario.',
                'details' => $e
            ];
        }
    }

    /**
     * Función getUser
     * Devuelve una tabla con los usuarios que coincidan con el email o el username pasados por parámetro
     * También se pasa por parámetro la columna en cuestión
     */
    public static function getUser($data, $column)
    {
        // Requiere la base de datos
        require_once 'app/models/Database.php';

        // Recupera y sanea los datso necesarios
        $data_filtered = htmlspecialchars(trim($data));

        // Ejecuta la operación en la base de datos en un bloque try catch
        // Si todo va bien, devuelve los datos del nuevo usuario y un mensaje de success
        // Si no, devuelve un mensaje de error
        try {
            $db = Database::connect();
            $query = "SELECT * FROM users where $column = ?";
            $stm = $db->prepare($query);
            $stm->bindParam(1, $data_filtered);
            $stm->execute();
            $user = $stm->fetch(PDO::FETCH_ASSOC);

            return [
                'success' => true,
                'user' => $user
            ];
        } catch (PDOException $e) {
            return [
                'error' => 'Error en la base de datos.',
                'details' => $e
            ];
        }
    }
}
