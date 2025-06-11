<?php

class UserDAO
{
    public static function createUser($name, $username, $email, $password_hash)
    {
        require_once 'app/models/Database.php';

        $name_filtered = htmlspecialchars(trim($name));
        $username_filtered = htmlspecialchars(trim($username));
        $email_filtered = htmlspecialchars(trim($email));

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

    public static function getUser($data, $column)
    {
        require_once 'app/models/Database.php';

        $data_filtered = htmlspecialchars(trim($data));

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
                'error' => 'Error en la base de datos, no se pudo crear el usuario.',
                'details' => $e
            ];
        }
    }
}
